#!/usr/bin/env python3
"""
MySQL Database Synchronization Script for ALL Tables
===================================================

This script synchronizes ALL tables from local MySQL database to online MySQL database.
Designed for production use with comprehensive error handling and duplicate prevention.

Features:
- Syncs ALL 155+ tables from your database schema
- Incremental sync based on timestamps (only new/updated records)
- Duplicate prevention using unique constraints and ON DUPLICATE KEY UPDATE
- Priority-based sync order (masters first, then transactions, then relationships)
- Parallel table processing for faster sync
- Comprehensive logging and error handling
- Connection pooling and retry mechanisms
- Email notifications for failures/success
- Transaction rollback on failures
- Configurable chunk sizes per table type

Author: Database Sync Tool
Version: 2.0 (Complete Schema Support)
"""

import mysql.connector
from mysql.connector import pooling, Error
import logging
import json
import sys
import os
from datetime import datetime, timedelta
from typing import Dict, List, Tuple, Optional, Union
import smtplib
from email.mime.text import MimeText
from email.mime.multipart import MimeMultipart
import time
import hashlib
import threading
from concurrent.futures import ThreadPoolExecutor, as_completed
import traceback

class DatabaseSyncManager:
    def __init__(self, config_path: str = 'sync_config_complete.json'):
        """Initialize the database sync manager with configuration."""
        self.config = self._load_config(config_path)
        self.setup_logging()
        self.local_pool = None
        self.online_pool = None
        self.sync_stats = {
            'total_processed': 0,
            'successful_inserts': 0,
            'duplicates_skipped': 0,
            'errors': 0,
            'tables_synced': 0,
            'tables_failed': 0,
            'start_time': None,
            'end_time': None,
            'table_stats': {}
        }
        self.sync_lock = threading.Lock()
        
    def _load_config(self, config_path: str) -> dict:
        """Load configuration from JSON file."""
        default_config = {
            "local_db": {
                "host": "localhost",
    "port": 3306,
    "database": "malabarr_sku_new",
    "user": "root",
    "password": "",
    "charset": "utf8mb4"
            },
            "online_db": {
                "host": "adler.matsolutions.in",
                "port": 3306,
                "database": "matsolut_malabarr_sku_new",
                "user": "matsolut_adler",
                "password": "-ODS,R4anC]d",
                "charset": "utf8mb4",
                "ssl_disabled": False
            },
            "sync_settings": {
                "incremental_hours": 24,
                "max_retries": 3,
                "retry_delay": 5,
                "connection_timeout": 30,
                "pool_size": 1,
                "batch_commit": True,
                "parallel_sync": False,
                "max_parallel_tables": 1,
                "sync_by_priority": True
            },
            "logging": {
                "level": "INFO",
                "file": "db_sync.log",
                "max_size": 20971520,
                "backup_count": 10
            },
            "email_notifications": {
                "enabled": False,
                "smtp_server": "smtp.gmail.com",
                "smtp_port": 587,
                "username": "your_email@gmail.com",
                "password": "your_app_password",
                "from_email": "your_email@gmail.com",
                "to_emails": ["admin@yourcompany.com"],
                "send_on_error": True,
                "send_summary": True
            }
        }
        
        try:
            with open(config_path, 'r') as f:
                config = json.load(f)
                # Merge with defaults for any missing keys
                for key, value in default_config.items():
                    if key not in config:
                        config[key] = value
                    elif isinstance(value, dict):
                        for subkey, subvalue in value.items():
                            if subkey not in config[key]:
                                config[key][subkey] = subvalue
                return config
        except FileNotFoundError:
            print(f"Config file {config_path} not found, creating default config...")
            with open(config_path, 'w') as f:
                json.dump(default_config, f, indent=2)
            return default_config
    
    def setup_logging(self):
        """Setup logging configuration with rotation."""
        from logging.handlers import RotatingFileHandler
        
        # Create logs directory if it doesn't exist
        os.makedirs('logs', exist_ok=True)
        
        # Setup rotating file handler
        file_handler = RotatingFileHandler(
            filename=f"logs/{self.config['logging']['file']}",
            maxBytes=self.config['logging']['max_size'],
            backupCount=self.config['logging']['backup_count']
        )
        
        # Setup console handler
        console_handler = logging.StreamHandler(sys.stdout)
        
        # Create formatter
        formatter = logging.Formatter(
            '%(asctime)s - %(name)s - %(levelname)s - [%(threadName)s] - %(message)s'
        )
        
        file_handler.setFormatter(formatter)
        console_handler.setFormatter(formatter)
        
        # Setup logger
        self.logger = logging.getLogger(__name__)
        self.logger.setLevel(getattr(logging, self.config['logging']['level']))
        self.logger.addHandler(file_handler)
        self.logger.addHandler(console_handler)
    
    def create_connection_pools(self):
        """Create connection pools for both databases."""
        try:
            # determine requested pool size and use safer online pool size
            requested_pool_size = int(self.config['sync_settings'].get('pool_size', 8))
            # keep online pool small to avoid remote server limits causing refusal
            online_pool_size = min(requested_pool_size, 2)

            # Local database pool configuration
            local_config = {
                'pool_name': 'local_pool',
                'pool_size': requested_pool_size,
                'host': self.config['local_db']['host'],
                'port': int(self.config['local_db'].get('port', 3306)),
                'database': self.config['local_db']['database'],
                'user': self.config['local_db']['user'],
                'password': self.config['local_db']['password'],
                'charset': self.config['local_db'].get('charset', 'utf8mb4'),
                'connection_timeout': int(self.config['sync_settings'].get('connection_timeout', 30)),
                'pool_reset_session': True
            }

            # Online database pool configuration (safer smaller pool)
            online_config = {
                'pool_name': 'online_pool',
                'pool_size': online_pool_size,
                'host': self.config['online_db']['host'],
                'port': int(self.config['online_db'].get('port', 3306)),
                'database': self.config['online_db']['database'],
                'user': self.config['online_db']['user'],
                'password': self.config['online_db']['password'],
                'charset': self.config['online_db'].get('charset', 'utf8mb4'),
                'connection_timeout': int(self.config['sync_settings'].get('connection_timeout', 30)),
                'pool_reset_session': True
            }

            # Allow explicit SSL disabling flag to be passed (avoid unknown kw args)
            if self.config['online_db'].get('ssl_disabled', False):
                online_config['ssl_disabled'] = True

            # Create pools
            self.local_pool = pooling.MySQLConnectionPool(**local_config)
            self.online_pool = pooling.MySQLConnectionPool(**online_config)

            # Verify both pools by obtaining and pinging a connection
            for pool_name, pool in (('local', self.local_pool), ('online', self.online_pool)):
                try:
                    conn = pool.get_connection()
                    # If connection isn't flagged as connected, try ping/reconnect
                    if not getattr(conn, 'is_connected', lambda: False)():
                        try:
                            conn.ping(reconnect=True, attempts=2, delay=1)
                        except Exception:
                            try:
                                conn.reconnect(attempts=2, delay=1)
                            except Exception:
                                pass
                    if not conn.is_connected():
                        raise Error(f"{pool_name.capitalize()} pool connection not established")
                    conn.close()
                except Exception as e:
                    self.logger.error(f"Failed to obtain healthy connection from {pool_name} pool: {e}")
                    raise

            self.logger.info("Connection pools created and verified successfully")

        except Exception as e:
            # Log full traceback and try a conservative retry with pool_size=1
            self.logger.error(f"Error creating connection pools: {e}")
            self.logger.error(traceback.format_exc())
            self.logger.warning("Retrying pool creation with pool_size=1 for both pools...")
            try:
                retry_local = {
                    'pool_name': 'local_pool',
                    'pool_size': 1,
                    'host': self.config['local_db']['host'],
                    'port': int(self.config['local_db'].get('port', 3306)),
                    'database': self.config['local_db']['database'],
                    'user': self.config['local_db']['user'],
                    'password': self.config['local_db']['password'],
                    'charset': self.config['local_db'].get('charset', 'utf8mb4'),
                    'connection_timeout': int(self.config['sync_settings'].get('connection_timeout', 30)),
                    'pool_reset_session': True
                }
                retry_online = {
                    'pool_name': 'online_pool',
                    'pool_size': 1,
                    'host': self.config['online_db']['host'],
                    'port': int(self.config['online_db'].get('port', 3306)),
                    'database': self.config['online_db']['database'],
                    'user': self.config['online_db']['user'],
                    'password': self.config['online_db']['password'],
                    'charset': self.config['online_db'].get('charset', 'utf8mb4'),
                    'connection_timeout': int(self.config['sync_settings'].get('connection_timeout', 30)),
                    'pool_reset_session': True
                }
                if self.config['online_db'].get('ssl_disabled', False):
                    retry_online['ssl_disabled'] = True

                self.local_pool = pooling.MySQLConnectionPool(**retry_local)
                self.online_pool = pooling.MySQLConnectionPool(**retry_online)

                # quick test-get to force early failure if pool can't hand out connections
                conn = self.online_pool.get_connection()
                if not getattr(conn, 'is_connected', lambda: False)():
                    try:
                        conn.ping(reconnect=True)
                    except Exception:
                        pass
                conn.close()
                self.logger.info("Connection pools created on retry with pool_size=1")
            except Exception as e2:
                self.logger.error(f"Retry pool creation failed: {e2}")
                self.logger.error(traceback.format_exc())
                raise
    
    def get_all_tables_from_database(self) -> List[dict]:
        """Get all tables from local database if not configured."""
        try:
            conn = self.local_pool.get_connection()
            cursor = conn.cursor()
            
            cursor.execute("SHOW TABLES")
            tables = [row[0] for row in cursor.fetchall()]
            
            # Generate basic config for each table
            table_configs = []
            for table_name in tables:
                # Skip system tables
                if table_name in ['migrations', 'failed_jobs', 'jobs']:
                    continue
                
                # Get table schema to determine primary key and timestamp column
                cursor.execute(f"DESCRIBE `{table_name}`")
                schema = cursor.fetchall()
                
                primary_key = "id"
                timestamp_column = None
                
                for column_info in schema:
                    column_name = column_info[0]
                    column_type = column_info[1]
                    column_key = column_info[3]
                    
                    # Find primary key
                    if column_key == 'PRI':
                        primary_key = column_name
                    
                    # Find timestamp column
                    if not timestamp_column:
                        if 'timestamp' in column_type.lower() or 'datetime' in column_type.lower():
                            if 'update' in column_name.lower():
                                timestamp_column = column_name
                            elif 'create' in column_name.lower():
                                timestamp_column = column_name
                
                # Default timestamp column if none found
                if not timestamp_column:
                    timestamp_column = "created_at"
                
                table_config = {
                    "name": table_name,
                    "primary_key": primary_key,
                    "timestamp_column": timestamp_column,
                    "unique_columns": [primary_key],
                    "chunk_size": 1000,
                    "priority": 2
                }
                
                table_configs.append(table_config)
            
            return table_configs
            
        except Error as e:
            self.logger.error(f"Error getting tables from database: {e}")
            return []
        finally:
            if conn and conn.is_connected():
                cursor.close()
                conn.close()
    
    def get_last_sync_time(self, table_name: str) -> Optional[datetime]:
        """Get the last sync timestamp for a table."""
        sync_log_table = 'sync_log'
        
        try:
            conn = self.online_pool.get_connection()
            cursor = conn.cursor()
            
            # Create sync log table if it doesn't exist
            create_sync_log = f"""
            CREATE TABLE IF NOT EXISTS {sync_log_table} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                table_name VARCHAR(255) NOT NULL,
                last_sync_time TIMESTAMP NOT NULL,
                records_synced INT DEFAULT 0,
                sync_status ENUM('SUCCESS', 'FAILED', 'PARTIAL') DEFAULT 'SUCCESS',
                error_message TEXT,
                sync_duration_seconds INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY uk_table_name (table_name)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            """
            
            cursor.execute(create_sync_log)
            conn.commit()
            
            # Get last successful sync time
            query = f"""
            SELECT last_sync_time FROM {sync_log_table} 
            WHERE table_name = %s AND sync_status = 'SUCCESS'
            """
            cursor.execute(query, (table_name,))
            result = cursor.fetchone()
            
            if result:
                return result[0]
            else:
                # If no record exists, sync data from configured hours back
                return datetime.now() - timedelta(hours=self.config['sync_settings']['incremental_hours'])
                
        except Error as e:
            self.logger.error(f"Error getting last sync time for {table_name}: {e}")
            return datetime.now() - timedelta(hours=self.config['sync_settings']['incremental_hours'])
        finally:
            if conn and conn.is_connected():
                cursor.close()
                conn.close()
    
    def update_sync_log(self, table_name: str, records_synced: int, status: str = 'SUCCESS', 
                       error_message: str = None, duration: int = 0):
        """Update the sync log with latest sync information."""
        sync_log_table = 'sync_log'
        
        try:
            conn = self.online_pool.get_connection()
            cursor = conn.cursor()
            
            query = f"""
            INSERT INTO {sync_log_table} 
            (table_name, last_sync_time, records_synced, sync_status, error_message, sync_duration_seconds)
            VALUES (%s, %s, %s, %s, %s, %s)
            ON DUPLICATE KEY UPDATE
                last_sync_time = VALUES(last_sync_time),
                records_synced = VALUES(records_synced),
                sync_status = VALUES(sync_status),
                error_message = VALUES(error_message),
                sync_duration_seconds = VALUES(sync_duration_seconds),
                updated_at = CURRENT_TIMESTAMP
            """
            
            cursor.execute(query, (table_name, datetime.now(), records_synced, status, error_message, duration))
            conn.commit()
            
        except Error as e:
            self.logger.error(f"Error updating sync log for {table_name}: {e}")
        finally:
            if conn and conn.is_connected():
                cursor.close()
                conn.close()
    
    def get_table_schema(self, table_name: str, connection_pool) -> Tuple[List[str], List[str]]:
        """Get table schema (column names and types) for building dynamic queries."""
        try:
            conn = connection_pool.get_connection()
            cursor = conn.cursor()
            
            cursor.execute(f"DESCRIBE `{table_name}`")
            schema_info = cursor.fetchall()
            
            columns = [row[0] for row in schema_info]
            column_types = [row[1] for row in schema_info]
            
            return columns, column_types
            
        except Error as e:
            self.logger.error(f"Error getting schema for {table_name}: {e}")
            return [], []
        finally:
            if conn and conn.is_connected():
                cursor.close()
                conn.close()
    
    def table_exists(self, table_name: str, connection_pool) -> bool:
        """Check if table exists in database."""
        try:
            conn = connection_pool.get_connection()
            cursor = conn.cursor()
            
            cursor.execute(f"SHOW TABLES LIKE '{table_name}'")
            result = cursor.fetchone()
            
            return result is not None
            
        except Error as e:
            self.logger.error(f"Error checking if table {table_name} exists: {e}")
            return False
        finally:
            if conn and conn.is_connected():
                cursor.close()
                conn.close()
    
    def fetch_incremental_data(self, table_config: dict, last_sync_time: datetime) -> Tuple[List[Tuple], int]:
        """Fetch incremental data from local database."""
        table_name = table_config['name']
        timestamp_column = table_config.get('timestamp_column', 'created_at')
        chunk_size = table_config.get('chunk_size', 1000)
        
        try:
            conn = self.local_pool.get_connection()
            cursor = conn.cursor()
            
            # Check if timestamp column exists
            columns, _ = self.get_table_schema(table_name, self.local_pool)
            if timestamp_column not in columns:
                # If timestamp column doesn't exist, sync all records (be careful with large tables)
                self.logger.warning(f"Timestamp column '{timestamp_column}' not found in {table_name}, syncing all records")
                query = f"SELECT COUNT(*) FROM `{table_name}`"
                cursor.execute(query)
            else:
                # Get total count of records to sync
                query = f"SELECT COUNT(*) FROM `{table_name}` WHERE `{timestamp_column}` >= %s"
                cursor.execute(query, (last_sync_time,))
            
            total_records = cursor.fetchone()[0]
            
            self.logger.info(f"Found {total_records} records to sync for {table_name}")
            
            if total_records == 0:
                return [], 0
            
            # Fetch data in chunks
            all_records = []
            offset = 0
            
            while offset < total_records:
                if timestamp_column in columns:
                    data_query = f"""
                    SELECT * FROM `{table_name}` 
                    WHERE `{timestamp_column}` >= %s 
                    ORDER BY `{table_config.get('primary_key', 'id')}` 
                    LIMIT %s OFFSET %s
                    """
                    cursor.execute(data_query, (last_sync_time, chunk_size, offset))
                else:
                    data_query = f"""
                    SELECT * FROM `{table_name}` 
                    ORDER BY `{table_config.get('primary_key', 'id')}` 
                    LIMIT %s OFFSET %s
                    """
                    cursor.execute(data_query, (chunk_size, offset))
                
                chunk_records = cursor.fetchall()
                all_records.extend(chunk_records)
                offset += chunk_size
                
                self.logger.debug(f"Fetched {len(chunk_records)} records from {table_name} (offset: {offset})")
            
            return all_records, total_records
            
        except Error as e:
            self.logger.error(f"Error fetching data from {table_name}: {e}")
            return [], 0
        finally:
            if conn and conn.is_connected():
                cursor.close()
                conn.close()
    
    def insert_records_batch(self, table_name: str, records: List[Tuple], columns: List[str]) -> Tuple[int, int]:
        """Insert multiple records using batch insert with duplicate handling."""
        if not records:
            return 0, 0
        
        successful_inserts = 0
        errors = 0
        
        try:
            conn = self.online_pool.get_connection()
            cursor = conn.cursor()
            
            # Build INSERT query with ON DUPLICATE KEY UPDATE
            placeholders = ', '.join(['%s'] * len(columns))
            column_names = ', '.join([f"`{col}`" for col in columns])
            
            # Create update clause for duplicate key handling (exclude primary key from updates)
            update_columns = [col for col in columns if col not in ['id']]
            if update_columns:
                update_clause = ', '.join([f"`{col}` = VALUES(`{col}`)" for col in update_columns])
                query = f"""
                INSERT INTO `{table_name}` ({column_names})
                VALUES ({placeholders})
                ON DUPLICATE KEY UPDATE {update_clause}
                """
            else:
                query = f"""
                INSERT IGNORE INTO `{table_name}` ({column_names})
                VALUES ({placeholders})
                """
            
            # Execute batch insert
            cursor.executemany(query, records)
            successful_inserts = cursor.rowcount
            
            if self.config['sync_settings']['batch_commit']:
                conn.commit()
            
            self.logger.debug(f"Batch inserted {successful_inserts} records into {table_name}")
            
        except Error as e:
            self.logger.error(f"Error batch inserting records into {table_name}: {e}")
            if conn and conn.is_connected():
                conn.rollback()
            errors = len(records)
            successful_inserts = 0
        finally:
            if conn and conn.is_connected():
                cursor.close()
                conn.close()
        
        return successful_inserts, errors
    
    def sync_table_data(self, table_config: dict) -> dict:
        """Sync data for a specific table."""
        table_name = table_config['name']
        start_time = time.time()
        
        self.logger.info(f"Starting sync for table: {table_name}")
        
        table_stats = {
            'processed': 0,
            'inserted': 0,
            'skipped': 0,
            'errors': 0,
            'duration': 0
        }
        
        try:
            # Check if table exists in both databases
            if not self.table_exists(table_name, self.local_pool):
                self.logger.warning(f"Table {table_name} does not exist in local database, skipping")
                return table_stats
            
            if not self.table_exists(table_name, self.online_pool):
                self.logger.warning(f"Table {table_name} does not exist in online database, skipping")
                return table_stats
            
            # Get last sync time
            last_sync_time = self.get_last_sync_time(table_name)
            self.logger.info(f"Last sync time for {table_name}: {last_sync_time}")
            
            # Get table schema from local database
            columns, column_types = self.get_table_schema(table_name, self.local_pool)
            if not columns:
                self.logger.error(f"Could not get schema for {table_name}")
                return table_stats
            
            # Fetch incremental data
            records, total_count = self.fetch_incremental_data(table_config, last_sync_time)
            
            if not records:
                self.logger.info(f"No new records to sync for {table_name}")
                table_stats['duration'] = time.time() - start_time
                return table_stats
            
            table_stats['processed'] = len(records)
            
            # Insert records one-by-one (no batching)
            for idx, rec in enumerate(records, start=1):
                inserted, batch_errors = self.insert_records_batch(table_name, [rec], columns)
                table_stats['inserted'] += inserted
                table_stats['errors'] += batch_errors
                if idx % 100 == 0 or idx == len(records):
                    self.logger.debug(f"Processed {idx}/{len(records)} records for {table_name}: total_inserted={table_stats['inserted']}, total_errors={table_stats['errors']}")
            
            # Update sync log
            table_stats['duration'] = time.time() - start_time
            
            if table_stats['errors'] == 0:
                self.update_sync_log(table_name, table_stats['inserted'], 'SUCCESS', 
                                   duration=int(table_stats['duration']))
                self.logger.info(f" Sync completed successfully for {table_name}: "
                               f"{table_stats['inserted']} records in {table_stats['duration']:.2f}s")
            else:
                self.update_sync_log(table_name, table_stats['inserted'], 'PARTIAL', 
                                   f"Completed with {table_stats['errors']} errors", 
                                   duration=int(table_stats['duration']))
                self.logger.warning(f" Sync completed with errors for {table_name}: "
                                  f"{table_stats['inserted']} inserted, {table_stats['errors']} errors")
            
        except Exception as e:
            table_stats['duration'] = time.time() - start_time
            error_msg = f"Critical error syncing {table_name}: {str(e)}"
            self.logger.error(error_msg)
            self.logger.error(traceback.format_exc())
            table_stats['errors'] = table_stats.get('processed', 0)
            self.update_sync_log(table_name, 0, 'FAILED', error_msg, 
                               duration=int(table_stats['duration']))
        
        return table_stats
    
    def sync_tables_parallel(self, table_configs: List[dict]) -> Dict[str, dict]:
        """Parallel sync disabled — run sequentially instead."""
        self.logger.info("Parallel sync is disabled or not required; running sequential sync for provided tables")
        return self.sync_tables_sequential(table_configs)
    
    def sync_tables_sequential(self, table_configs: List[dict]) -> Dict[str, dict]:
        """Sync tables sequentially (fallback method)."""
        all_table_stats = {}
        
        for table_config in table_configs:
            table_name = table_config['name']
            table_stats = self.sync_table_data(table_config)
            all_table_stats[table_name] = table_stats
            
            # Update overall stats
            self.sync_stats['total_processed'] += table_stats['processed']
            self.sync_stats['successful_inserts'] += table_stats['inserted']
            self.sync_stats['errors'] += table_stats['errors']
            
            if table_stats['errors'] == 0 and table_stats['processed'] > 0:
                self.sync_stats['tables_synced'] += 1
            elif table_stats['errors'] > 0:
                self.sync_stats['tables_failed'] += 1
        
        return all_table_stats
    
    def send_notification(self, subject: str, message: str, is_error: bool = False):
        """Send email notification."""
        if not self.config['email_notifications']['enabled']:
            return
        
        if is_error and not self.config['email_notifications']['send_on_error']:
            return
        
        if not is_error and not self.config['email_notifications']['send_summary']:
            return
        
        try:
            smtp_server = self.config['email_notifications']['smtp_server']
            smtp_port = self.config['email_notifications']['smtp_port']
            username = self.config['email_notifications']['username']
            password = self.config['email_notifications']['password']
            from_email = self.config['email_notifications']['from_email']
            to_emails = self.config['email_notifications']['to_emails']
            
            msg = MimeMultipart()
            msg['From'] = from_email
            msg['To'] = ', '.join(to_emails)
            msg['Subject'] = subject
            
            msg.attach(MimeText(message, 'plain'))
            
            server = smtplib.SMTP(smtp_server, smtp_port)
            server.starttls()
            server.login(username, password)
            
            text = msg.as_string()
            server.sendmail(from_email, to_emails, text)
            server.quit()
            
            self.logger.info("Email notification sent successfully")
            
        except Exception as e:
            self.logger.error(f"Error sending email notification: {e}")
    
    def run_sync(self):
        """Main sync process."""
        self.sync_stats['start_time'] = datetime.now()
        self.logger.info(" Starting comprehensive database synchronization process")
        
        try:
            # Create connection pools
            self.create_connection_pools()
            
            # Test connections
            self.test_connections()
            
            # Get tables to sync
            if 'tables_to_sync' in self.config and self.config['tables_to_sync']:
                tables_to_sync = self.config['tables_to_sync']
                self.logger.info(f"Using configured tables: {len(tables_to_sync)} tables")
            else:
                tables_to_sync = self.get_all_tables_from_database()
                self.logger.info(f"Auto-discovered tables: {len(tables_to_sync)} tables")
            
            if not tables_to_sync:
                self.logger.error("No tables to sync found!")
                return
            
            # Sort tables by priority if enabled
            if self.config['sync_settings'].get('sync_by_priority', True):
                tables_to_sync.sort(key=lambda x: x.get('priority', 2))
                self.logger.info("Tables sorted by priority for optimal sync order")
            
            # Group tables by priority for parallel processing
            priority_groups = {}
            for table_config in tables_to_sync:
                priority = table_config.get('priority', 2)
                if priority not in priority_groups:
                    priority_groups[priority] = []
                priority_groups[priority].append(table_config)
            
            # Sync each priority group
            for priority in sorted(priority_groups.keys()):
                group_tables = priority_groups[priority]
                self.logger.info(f" Syncing priority {priority} tables: {len(group_tables)} tables")
                
                if (self.config['sync_settings'].get('parallel_sync', True) and 
                    len(group_tables) > 1):
                    self.sync_tables_parallel(group_tables)
                else:
                    self.sync_tables_sequential(group_tables)
            
            self.sync_stats['end_time'] = datetime.now()
            
            # Generate summary report
            self.generate_summary_report()
            
        except Exception as e:
            self.logger.error(f"Critical error during sync process: {e}")
            self.logger.error(traceback.format_exc())
            error_msg = f"Database sync failed with critical error: {str(e)}"
            self.send_notification(" Database Sync Failed", error_msg, is_error=True)
            raise
    
    def test_connections(self):
        """Test both database connections with retries, ping/reconnect and direct-connect fallback.

        This method first attempts to get a healthy connection from the pools (with retries,
        ping and reconnect). If pooling fails it will attempt a direct, non-pooled
        `mysql.connector.connect` to help distinguish pool issues from server/network issues.
        """
        max_retries = int(self.config['sync_settings'].get('max_retries', 3))
        retry_delay = int(self.config['sync_settings'].get('retry_delay', 5))

        # Prepare connection param dicts for logging and fallback
        local_params = {
            'host': self.config['local_db']['host'],
            'port': int(self.config['local_db'].get('port', 3306)),
            'user': self.config['local_db']['user'],
            'password': self.config['local_db']['password'],
            'database': self.config['local_db']['database'],
            'connection_timeout': int(self.config['sync_settings'].get('connection_timeout', 30)),
            'charset': self.config['local_db'].get('charset', 'utf8mb4')
        }
        online_params = {
            'host': self.config['online_db']['host'],
            'port': int(self.config['online_db'].get('port', 3306)),
            'user': self.config['online_db']['user'],
            'password': self.config['online_db']['password'],
            'database': self.config['online_db']['database'],
            'connection_timeout': int(self.config['sync_settings'].get('connection_timeout', 30)),
            'charset': self.config['online_db'].get('charset', 'utf8mb4'),
            'ssl_disabled': bool(self.config['online_db'].get('ssl_disabled', False))
        }

        def mask_password(pw: str) -> str:
            if not pw:
                return ''
            if len(pw) <= 2:
                return '*' * len(pw)
            return pw[0] + '***' + pw[-1]

        # Log connection params (mask passwords)
        try:
            self.logger.debug("Local connect params: host=%s port=%s user=%s pool_size=%s timeout=%s",
                              local_params['host'], local_params['port'], local_params['user'],
                              self.config['sync_settings'].get('pool_size'), local_params['connection_timeout'])
            self.logger.debug("Online connect params: host=%s port=%s user=%s pool_size=%s timeout=%s ssl_disabled=%s",
                              online_params['host'], online_params['port'], online_params['user'],
                              min(int(self.config['sync_settings'].get('pool_size', 1)), 2),
                              online_params['connection_timeout'], online_params['ssl_disabled'])
        except Exception:
            # Safe to continue even if logging fails
            pass

        def get_verified_connection(pool, desc: str):
            last_exc = None
            for attempt in range(1, max_retries + 1):
                try:
                    conn = pool.get_connection()
                    # Ensure connection is live
                    if not getattr(conn, 'is_connected', lambda: False)():
                        try:
                            conn.ping(reconnect=True)
                        except Exception:
                            try:
                                conn.reconnect()
                            except Exception:
                                pass
                    if not conn.is_connected():
                        raise Error(f"{desc} connection not active after ping/reconnect")
                    return conn
                except Exception as e:
                    last_exc = e
                    self.logger.warning(f"{desc} pool connection attempt {attempt} failed: {e} - retrying in {retry_delay}s")
                    time.sleep(retry_delay)
            raise last_exc or Error(f"{desc} pool connection failed after {max_retries} attempts")

        # Test local connection via pool and fallback to direct connect if needed
        try:
            local_conn = get_verified_connection(self.local_pool, 'Local database')
            local_cursor = local_conn.cursor()
            local_cursor.execute("SELECT 1 AS test, NOW() AS `current_time`")
            res = local_cursor.fetchone()
            local_cursor.close()
            local_conn.close()
            self.logger.info(f"Local database connection successful - {res[1] if res else ''}")
        except Exception as e:
            self.logger.error(f"Local database pool connection failed: {e}")
            # Try direct non-pooled connection as fallback for diagnosis
            try:
                self.logger.debug("Attempting direct local connect for diagnosis")
                dc = mysql.connector.connect(host=local_params['host'],
                                             port=local_params['port'],
                                             user=local_params['user'],
                                             password=local_params['password'],
                                             database=local_params['database'],
                                             connection_timeout=local_params['connection_timeout'])
                cur = dc.cursor()
                cur.execute("SELECT 1")
                cur.close()
                dc.close()
                self.logger.info("Direct local connect succeeded (fallback)")
            except Exception as de:
                self.logger.error(f"Direct local connect also failed: {de}")
                self.logger.error(traceback.format_exc())
            raise

        # Test online connection via pool and fallback to direct connect if needed
        try:
            online_conn = get_verified_connection(self.online_pool, 'Online database')
            online_cursor = online_conn.cursor()
            online_cursor.execute("SELECT 1 as test, NOW() as current_time")
            res = online_cursor.fetchone()
            online_cursor.close()
            online_conn.close()
            self.logger.info(f"Online database connection successful - {res[1] if res else ''}")
        except Exception as e:
            self.logger.error(f"Online database pool connection failed: {e}")
            # Direct connect fallback to isolate pool vs server/network issue
            try:
                self.logger.debug("Attempting direct online connect for diagnosis")
                direct_kwargs = {
                    'host': online_params['host'],
                    'port': online_params['port'],
                    'user': online_params['user'],
                    'password': online_params['password'],
                    'database': online_params['database'],
                    'connection_timeout': online_params['connection_timeout']
                }
                if online_params.get('ssl_disabled'):
                    direct_kwargs['ssl_disabled'] = True

                self.logger.debug("Direct online connect params: host=%s port=%s user=%s pwd=%s timeout=%s ssl_disabled=%s",
                                  direct_kwargs['host'], direct_kwargs['port'], direct_kwargs['user'],
                                  mask_password(direct_kwargs.get('password')), direct_kwargs.get('connection_timeout'),
                                  direct_kwargs.get('ssl_disabled', False))

                direct_conn = mysql.connector.connect(**direct_kwargs)
                cur = direct_conn.cursor()
                cur.execute("SELECT 1 as test, NOW() as current_time")
                r = cur.fetchone()
                cur.close()
                direct_conn.close()
                self.logger.info(f"Direct online connection successful - {r[1] if r else ''}")
            except Exception as de:
                self.logger.error(f"Direct online connect also failed: {de}")
                self.logger.error(traceback.format_exc())
            # Re-raise original pool exception so run_sync handles it
            raise
    
    def generate_summary_report(self):
        """Generate and log comprehensive summary report."""
        duration = self.sync_stats['end_time'] - self.sync_stats['start_time']
        
        # Calculate success rate
        total_tables = self.sync_stats['tables_synced'] + self.sync_stats['tables_failed']
        if total_tables > 0:
            success_rate = (self.sync_stats['tables_synced'] / total_tables) * 100
        else:
            success_rate = 0
        
        # Calculate record success rate
        if self.sync_stats['total_processed'] > 0:
            record_success_rate = ((self.sync_stats['successful_inserts']) / 
                                 self.sync_stats['total_processed']) * 100
        else:
            record_success_rate = 0
        
        summary = f"""
        
        ═══════════════════════════════════════════════════════════════
         COMPREHENSIVE DATABASE SYNC SUMMARY REPORT
        ═══════════════════════════════════════════════════════════════
        
         TIMING INFORMATION:
        ├─ Start Time: {self.sync_stats['start_time']}
        ├─ End Time: {self.sync_stats['end_time']}
        └─ Total Duration: {duration}
        
         TABLE STATISTICS:
        ├─ Tables Successfully Synced: {self.sync_stats['tables_synced']}
        ├─ Tables with Errors: {self.sync_stats['tables_failed']}
        ├─ Total Tables Processed: {total_tables}
        └─ Table Success Rate: {success_rate:.2f}%
        
         RECORD STATISTICS:
        ├─ Total Records Processed: {self.sync_stats['total_processed']:,}
        ├─ Successfully Inserted/Updated: {self.sync_stats['successful_inserts']:,}
        ├─ Records with Errors: {self.sync_stats['errors']:,}
        └─ Record Success Rate: {record_success_rate:.2f}%
        
         PERFORMANCE METRICS:
        ├─ Average Records/Second: {(self.sync_stats['successful_inserts'] / max(duration.total_seconds(), 1)):.2f}
        ├─ Average Tables/Minute: {(total_tables / max(duration.total_seconds()/60, 1)):.2f}
        └─ Parallel Processing: {'Enabled' if self.config['sync_settings'].get('parallel_sync') else 'Disabled'}
        
        ═══════════════════════════════════════════════════════════════
        """
        
        self.logger.info(summary)
        
        # Send appropriate notification
        if self.sync_stats['errors'] > 0 or self.sync_stats['tables_failed'] > 0:
            subject = f" Database Sync Completed with Issues - {self.sync_stats['tables_failed']} tables failed"
            self.send_notification(subject, summary, is_error=True)
        else:
            subject = f" Database Sync Completed Successfully - {self.sync_stats['tables_synced']} tables synced"
            self.send_notification(subject, summary, is_error=False)


def main():
    """Main function to run the database sync."""
    sync_manager = None
    
    try:
        # Check for config file argument
        config_file = 'sync_config_complete.json'
        if len(sys.argv) > 1:
            config_file = sys.argv[1]
        
        print(f" Using configuration file: {config_file}")
        
        sync_manager = DatabaseSyncManager(config_file)
        sync_manager.run_sync()
        
        print("\n Database synchronization completed successfully!")
        sys.exit(0)
        
    except KeyboardInterrupt:
        if sync_manager:
            sync_manager.logger.info(" Sync process interrupted by user")
        print("\n Sync process interrupted by user")
        sys.exit(1)
        
    except Exception as e:
        if sync_manager:
            sync_manager.logger.error(f" Sync failed with critical error: {e}")
        print(f"\n Sync failed: {e}")
        sys.exit(1)


if __name__ == "__main__":
    main()

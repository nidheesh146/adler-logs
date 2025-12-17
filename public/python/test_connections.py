#!/usr/bin/env python3
"""
Quick test script to verify database connections
"""

import json
import mysql.connector
from mysql.connector import Error

def test_connections():
    """Test both database connections using the config file."""
    
    # Load configuration
    try:
        with open('sync_config_complete.json', 'r') as f:
            config = json.load(f)
    except FileNotFoundError:
        print(" Configuration file 'sync_config_complete.json' not found!")
        print(" Please make sure the configuration file exists and update the credentials.")
        return False
    
    # Test local database
    print(" Testing local database connection...")
    try:
        local_conn = mysql.connector.connect(**config['local_db'])
        cursor = local_conn.cursor()
        cursor.execute("SELECT DATABASE(), NOW(), VERSION()")
        result = cursor.fetchone()
        print(f" Local DB connected: {result[0]} | Time: {result[1]} | Version: {result[2]}")
        cursor.close()
        local_conn.close()
    except Error as e:
        print(f" Local database connection failed: {e}")
        return False
    
    # Test online database
    print(" Testing online database connection...")
    try:
        online_conn = mysql.connector.connect(**config['online_db'])
        cursor = online_conn.cursor()
        cursor.execute("SELECT DATABASE(), NOW(), VERSION()")
        result = cursor.fetchone()
        print(f" Online DB connected: {result[0]} | Time: {result[1]} | Version: {result[2]}")
        cursor.close()
        online_conn.close()
    except Error as e:
        print(f" Online database connection failed: {e}")
        return False
    
    print("\n All database connections successful!")
    print(" You can now run the full sync: python mysql_sync_complete.py")
    return True

if __name__ == "__main__":
    test_connections()

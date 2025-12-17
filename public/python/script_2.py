# Create a simple setup and run script
setup_run_script = '''#!/bin/bash
# Complete MySQL Database Sync - Setup and Run Script

echo " MySQL Database Synchronization Setup"
echo "======================================="

# Create virtual environment if it doesn't exist
if [ ! -d "sync_env" ]; then
    echo " Creating virtual environment..."
    python3 -m venv sync_env
fi

# Activate virtual environment
echo " Activating virtual environment..."
source sync_env/bin/activate

# Install/upgrade dependencies
echo " Installing dependencies..."
pip install --upgrade pip
pip install mysql-connector-python PyMySQL python-dotenv

# Create logs directory
mkdir -p logs

# Set permissions
chmod +x mysql_sync_complete.py

echo " Setup completed successfully!"
echo ""
echo " Configuration Steps:"
echo "1. Edit sync_config_complete.json with your database credentials"
echo "2. Test the connection: python mysql_sync_complete.py"
echo "3. Add to crontab for daily sync"
echo ""
echo " Recommended Cron Job (Daily at 2 AM):"
echo "0 2 * * * cd $(pwd) && $(pwd)/sync_env/bin/python $(pwd)/mysql_sync_complete.py"
echo ""
echo " To add to crontab:"
echo "crontab -e"
echo "Then add the above line"
echo ""
echo " Monitor logs at: logs/db_sync.log"
'''

with open('setup_and_run.sh', 'w') as f:
    f.write(setup_run_script)

# Create requirements file
requirements = """mysql-connector-python>=8.0.0
PyMySQL>=1.0.0
python-dotenv>=0.19.0
"""

with open('requirements.txt', 'w') as f:
    f.write(requirements)

# Create a simple test script
test_script = '''#!/usr/bin/env python3
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
    
    print("\\n All database connections successful!")
    print(" You can now run the full sync: python mysql_sync_complete.py")
    return True

if __name__ == "__main__":
    test_connections()
'''

with open('test_connections.py', 'w') as f:
    f.write(test_script)

print(" Additional files created:")
print("   - setup_and_run.sh (setup script)")
print("   - requirements.txt (Python dependencies)")
print("   - test_connections.py (connection test script)")
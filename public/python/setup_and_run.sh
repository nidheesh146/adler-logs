#!/bin/bash
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

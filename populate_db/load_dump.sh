# Get script working directory
mydir="${0%/*}"
# First argument is file name of desired sql dump in sql_dumps directory
dump_file_name=$1
# Re-create comp0178db as empty database
mysql -u root -e 'DROP DATABASE IF EXISTS comp0178db; CREATE DATABASE IF NOT EXISTS comp0178db;'
# use mysql to load specified sql dump
# NOTE - change start of below line to wherever path to mysql is on your system
/applications/MAMP/library/bin/mysql -u root comp0178db < "$mydir"/../sql_dumps/"$dump_file_name" || printf "\nError while loading dump, is your file name correct?\nUSAGE: ./load_dump.sh <dump_file_name>"
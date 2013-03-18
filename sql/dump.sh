#!/bin/sh

mysqldump -u root -p --no-data kurkuma | sed 's/AUTO_INCREMENT\=[0-9]\+ //g' > kurkuma.sql

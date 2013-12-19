#!/bin/bash

suffix=$(date +%d-%m-%Y)
number="_bgs"

mysqldump -u root -pbitnami fitbit | gzip > /root/backups/$suffix$number.sql.gz
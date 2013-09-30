#!/bin/bash

HOSTNAME="localhost:8051"
BASEDIR=`pwd -P`
PUBLIC_DIR=$BASEDIR/../public
LOG_DIR=$BASEDIR/../log
LOG_FILE=$LOG_DIR/service_double.log
PID_FILE=$LOG_DIR/service_double.pid

if [ -f $PID_FILE ]
then
    echo "Selenium is already running."
    exit 1
fi;

php -S $HOSTNAME -t $PUBLIC_DIR >> $LOG_FILE 2>&1 & echo $! > $PID_FILE

ERROR=$?
if [ $ERROR -eq 0 ]
then
    echo "Service Double started on $HOSTNAME"
else
    echo "Error during starting Service Double server."
fi

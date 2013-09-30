#!/bin/bash

BASEDIR=`pwd -P`
LOG_DIR=$BASEDIR/../log
PID_FILE=$LOG_DIR/service_double.pid

if [ -f $PID_FILE ]
then
    echo "Stopping Selenium..."
    PID=`cat $PID_FILE`
    if kill -9 $PID ;
    then
        sleep 2
        test -f $PID_FILE && rm -f $PID_FILE
    else
        echo "Service Double could not be stopped..."
        exit 1
    fi
else
    echo "Service Double is not running."
    exit 2
fi

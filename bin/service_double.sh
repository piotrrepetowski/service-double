#!/bin/bash

HOSTNAME="localhost:8051"
SELF=`realpath $0`
BASEDIR=`dirname $SELF`
PUBLIC_DIR=`realpath $BASEDIR/../public`
LOG_DIR=`realpath $BASEDIR/../log`
LOG_FILE=$LOG_DIR/service_double.log
PID_FILE=$LOG_DIR/service_double.pid

case "${1:-''}" in
    'start')
        if [ -f $PID_FILE ]
        then
            echo "Service Double is already running."
            exit 1
        fi;

        php -S $HOSTNAME -t $PUBLIC_DIR >> $LOG_FILE 2>&1 & echo $! > $PID_FILE

        error=$?
        if [ $error -eq 0 ]
        then
            echo "Service Double started on $HOSTNAME"
        else
            echo "Error during starting Service Double server."
            exit 2
        fi
    ;;
    'stop')
        if [ -f $PID_FILE ]
        then
            echo "Stopping Service Double..."
            pid=`cat $PID_FILE`
            if kill -9 $pid ;
            then
                sleep 2
                test -f $PID_FILE && rm -f $PID_FILE
            else
                echo "Service Double could not be stopped..."
                exit 2
            fi
        else
            echo "Service Double is not running."
            exit 1
        fi
    ;;
    'status')
        if [ -f $PID_FILE ]
        then
            echo "Service Double is running."
        else
            echo "Service Double is not running."
        fi;
    ;;
esac

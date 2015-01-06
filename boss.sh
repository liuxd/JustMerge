#!/usr/bin/env bash

# Function - Get current absoulute path.
function _current_path {
    SOURCE=${BASH_SOURCE[0]}
    DIR=$( dirname "$SOURCE" )

    while [ -h "$SOURCE" ]
    do
        SOURCE=$(readlink "$SOURCE")
        [[ $SOURCE != /* ]] && SOURCE="$DIR/$SOURCE"
        DIR=$( cd -P "$( dirname "$SOURCE"  )" && pwd )
    done

    DIR=$( cd -P "$( dirname "$SOURCE" )" && pwd )
    echo $DIR
}

# Function - Print colorful message.
function _cecho {
    case $2 in
        info )
            color=33;;
        error )
            color=31;;
        success )
            color=32;;
        *)
            color=1;;
    esac

    echo -e "\033["$color"m$1\033[0m"
}

# Function - Get pid of the server daemon.
function _getpid {
    PID_FILE='/tmp/jm.pid'

    if [ ! -e "$PID_FILE" ];then
        echo 0 > $PID_FILE
    fi

    PID=`cat $PID_FILE`
    echo $PID
}

# Help information.
if [ $# -eq 0 ];then
echo -e "\033[1m"
cat << EOT
What you want?

Usage:
    boss.sh restart

Options:
    restart    Restart the server.
    start      Start the server.
    stop       Stop the server.
EOT
echo -e "\033[0m"
fi

CUR=$(_current_path)

# boss.sh restart
if [ "$1" = "restart" ];then
    _cecho "Stoping..." error
    PID=$(_getpid)

    if [ $PID -gt 0 ];then
        kill $PID
    fi

    _cecho "Starting..." success
    cd $CUR/server
    nohup ./server.js > /dev/null &
fi

if [ "$1" = "start" ];then
    _cecho "Starting..." success
    cd $CUR/server
    nohup ./server.js > /dev/null &
fi

if [ "$1" = "stop" ];then
    _cecho "Stoping..." error
    PID=$(_getpid)

    if [ $PID -gt 0 ];then
        kill $PID
    fi
fi

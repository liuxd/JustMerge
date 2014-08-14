#!/bin/bash
#
# Merge develop to master for one repository.
# Demo:
#     merge-one.sh app-render

# Get this script real path.
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

# Colorful printing.
# Demo:
#     _cecho 'hello' error
function _cecho {
    if [ "$is_cmd" = "log" ];then
        echo $1
        return
    fi

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

# Echo and run.
function _ear {
    _cecho "$@" success
    $@
}

# Merge develop to master.
# @param string the path of repository.
# @param string the path of git.
# Demo:
#     _merge /home/www/code/hf-index/ /bin/git
function _merge {
    cd $1
    _ear "$2 clean -df"
    _ear "$2 reset --hard"
    _ear "$2 checkout master"
    _ear "$2 pull origin master"
    _ear "$2 checkout develop"
    _ear "$2 pull origin develop"
    _ear "$2 checkout master"
    _ear "$2 merge develop --no-ff -m 'merge develop'"

    result=$?

    if [ $result -eq 0 ];then
        $2 push origin master
    fi
}

# Add lock.
function _lock {
    echo 1 > $lock_file
}

# Clear lock.
function _unlock {
    rm -f $lock_file
}

# Check lock.
function _check_lock {
    if [ -f "$lock_file" ];then
        _cecho 'Now locking!' error
        exit $errcode_locked
    fi
}

errcode_need_repo=1
errcode_no_such_folder=2
errcode_locked=3

current_path=$(_current_path)
. $current_path/config.sh

_check_lock

if [ ! $1 ];then
    _cecho 'Repository name is needed.' error
    exit $errcode_need_repo
fi

if [ ! $2 ];then
    is_cmd="cmd"
else
    is_cmd=$2
fi

repo=$1
repo_path=$code_path$repo

if [ ! -d $repo_path ];then
    _cecho "No such folder : $repo_path" error
    exit $errcode_no_such_folder
fi

_cecho "Handle Repository: " $repo
_lock
_merge $repo_path $git
_unlock

# end of this file

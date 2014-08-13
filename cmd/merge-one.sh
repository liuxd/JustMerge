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

    if [ $result == 0 ];then
        $2 push origin master
    fi   
}

current_path=$(_current_path)
. $current_path/config.sh
repo=$1

if [ ! -n $2 ];then
    is_cmd="cmd"
else
    is_cmd=$2
fi

repo_path=$code_path$repo

if [ ! -d $repo_path ];then
    _cecho "No such folder : $repo_path" error
    exit
fi

_merge $repo_path $git

# end of this file

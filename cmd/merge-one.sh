#!/bin/bash
#
# Merge develop to master for one repository.
# Demo:
#     merge-one.sh app-render

# Colorful printing.
# Demo:
#     _cecho 'hello' error
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

# Merge develop to master.
# @param string the path of repository.
# @param string the path of git.
# Demo:
#     _merge /home/www/code/hf-index/ /bin/git
function _merge {
    cd $1
    _cecho "$2 clean -df" success
    $2 clean -df

    _cecho "$2 reset --hard" success
    $2 reset --hard

    _cecho "$2 checkout master" success
    $2 checkout master

    _cecho "$2 pull origin master" success
    $2 pull origin master

    _cecho "$2 checkout develop" success
    $2 checkout develop

    _cecho "$2 pull origin develop" success
    $2 pull origin develop

    _cecho "$2 checkout master" success
    $2 checkout master

    _cecho "$2 merge develop --no-ff -m 'merge develop'" success
    $2 merge develop --no-ff -m 'merge develop'

    result=$?

    if [ $result == 0 ];then
        $2 push origin master
    fi   
}

#code_path=/Users/liuxd/Documents/git.ipo.com/hf-code/
code_path=/Users/liuxd/Documents/git.ipo.com/
repo=$1
git=/usr/local/git/bin/git

repo_path=$code_path$repo

if [ ! -d $repo_path ];then
    echo No such folder : $repo_path
    exit
fi

_merge $repo_path $git

# end of this file

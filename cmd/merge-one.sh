#!/bin/bash
#
# Merge develop to master for one repository.
# Demo:
#     merge-one.sh app-render

code_path=/Users/liuxd/Documents/git.ipo.com/hf-code/
repo=$1
git=/usr/local/git/bin/git

cd $code_path$repo
$git checkout master
$git pull origin master
$git checkout develop
$git pull origin develop
$git checkout master
$git merge develop --no-ff -m 'merge develop'
result=$?

if [ $result == 0 ];then
    echo 1
fi

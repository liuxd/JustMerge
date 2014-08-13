#!/bin/bash
#
# Merge develop to master for one repository.
# Demo:
#     merge-one.sh app-render

code_path=/Users/liuxd/Documents/git.ipo.com/hf-code/
repo=$1
git=/usr/local/git/bin/git

cd $code_path$repo
$git clean -df
$git reset --hard
$git checkout master
$git pull origin master
$git checkout develop
$git pull origin develop
$git checkout master
$git merge develop --no-ff -m 'merge develop'
result=$?

if [ $result == 0 ];then
    $git push origin master
fi

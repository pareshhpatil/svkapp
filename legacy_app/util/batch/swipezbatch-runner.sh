#!/bin/bash

PWD="/opt/app/swipezbatch"

#plumbing, get all command line parameters required to run the script

function usage
{
    echo "usage: runner.sh -d dir -f file | [-h]"
}

while [ "$1" != "" ]; do
    case $1 in
        -f | --file )           shift
                                filename=$1
                                ;;
        -d | --dir  )           shift
                                dirname=$1
                                ;;
        -h | --help )           usage
                                exit
                                ;;
        * )                     usage
                                exit 1
    esac
    shift
done

#filepath

DIRLOC="$PWD""/""$dirname";

cd $DIRLOC
php $filename

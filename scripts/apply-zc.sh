#!/bin/bash
# 
# Purpose: apply a new version of zen-cart
# 
# INPUTS:
#   expects a zen-cart version in zen-cart/zen-cart-v1.3.7.1-full-patched-07052007
#   which gets 'applied' to       zen-cart/htdocs
#   so take the zen-cart official zip file and extract it to zen-cart directory.
#
# Usage:
#   cd zen-cart
#   script/apply.sh zen-cart-v1.3.7.1-full-patched-07052007
# 
# Eric Winter, updated 11/22/2010
#

start=`date`
host=`hostname`
wd="/c/work/php/zen-cart"
delay=8
function warning {
    echo " "
    echo "This program will delete old htdocs content and apply the"
    echo "full new version to the repository"
    echo "        DELETE: $wd/htdocs"
    echo "        APPLY:  $wd/$inDir"
    echo " "
    echo "sleeping $delay..."
    sleep $delay
}

function nukeOld {
    echo " "
    echo "Delting contents of:"
    echo "        $wd/htdocs"
    echo " "
    echo "sleeping $delay..."
    sleep $delay
    rm -fR $wd/htdocs/*
}
function moveNew {
    echo " "
    echo "Moving contents of:"
    echo "        mv $wd/$inDir to $wd/htdocs"
    echo " "
    echo "sleeping $delay..."
    sleep $delay
    mv $wd/$inDir/* $wd/htdocs

}

# Validate input
if [[ $host != "k2" && $host != "lhotse" ]]; then
  echo "this should only be used in development "
  exit 0
fi

if [[ $# < 1 ]]; then
    echo " "
    echo "Usage: apply.sh zencart-version-name-and-directory"
    echo "ARGC: $#"

    exit 1
fi
inDir=$1

# get the input filename
cd $wd/htdocs
warning
nukeOld
moveNew

echo " done"
date


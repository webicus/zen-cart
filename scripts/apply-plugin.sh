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
baseDir="/c/work/php/zen-cart"
delay=8
function warning {
    echo " "
    echo "Replace existing $pluginName plugin"
    echo "        FROM: $piFromDir "
    echo "        TO  : $piToDir "
    echo " "
    echo "sleeping $delay..."
    sleep $delay
}

function nukeOld {
    echo " "
    echo "Delting old plugin:"
    echo "        $piToDir"
    echo " "
    echo "sleeping $delay..."
    sleep $delay
    rm -fR $piToDir/*
}
function moveNew {
    echo " "
    echo "Moving contents of:"
    echo "        FROM: $piFromDir "
    echo "        TO  : $piToDir "
    echo " "
    echo "sleeping $delay..."
    sleep $delay
    mv $piFromDir/* $piToDir
    rmdir $piFromDir
}

# Validate input
if [[ $host != "k2" && $host != "lhotse" ]]; then
  echo "this should only be used in development "
  exit 0
fi

if [[ $# < 2 ]]; then
    echo " "
    echo "Usage: apply-plugin.sh plugin-name unzipped-plugin-directory-name"
    echo "ARGC: $#"

    exit 1
fi

piToDir=$baseDir/plugins/$1
piFromDir=$baseDir/$2



if [ ! -e $piToDir ]
then
echo $piToDir does not exist. Invalid plugin name.
exit 1
fi
if [ ! -e $piFromDir ]
then
echo $piFromDir does not exist. Invalid plugin copy from directory name.
exit 1
fi





# get the input filename
cd $baseDir
warning
nukeOld
moveNew

echo " done"
date


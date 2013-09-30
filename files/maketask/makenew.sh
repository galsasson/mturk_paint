#!/bin/bash

export JAVA_HOME="/usr/lib/jvm/java-1.7.0-openjdk-amd64"

token=$1

if [ -z $token ]
then
	echo "USAGE: $0 <token>"
	exit 1
fi

TEMPLATE_DIR=/var/www/maketask
TASK_DIR=/opt/paint-tasks/${token}
MTURK_BIN=/home/ubuntu/mturk-tools/bin

# make the task dir
if [ -d $TASK_DIR ]
then
	echo "task: $token already exists."
	exit 1
fi

mkdir $TASK_DIR

sleep 60

# create input file with the token
echo "token" > $TASK_DIR/paint.input
echo $token >> $TASK_DIR/paint.input

pushd $MTURK_BIN
./loadHITs.sh -label $TASK_DIR/paint -input $TASK_DIR/paint.input -question $TEMPLATE_DIR/paint.question -properties $TEMPLATE_DIR/paint.properties 
popd
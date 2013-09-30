#!/bin/bash

export JAVA_HOME="/usr/lib/jvm/java-1.7.0-openjdk-amd64"

token=$1

if [ -z $token ]
then
	echo "USAGE: $0 <token>"
	exit 1
fi

TASK_DIR=/opt/paint-tasks/${token}
MTURK_BIN=/home/ubuntu/mturk-tools/bin

pushd $MTURK_BIN
./getResults.sh -successfile $TASK_DIR/paint.success -outputfile $TASK_DIR/paint.results
popd

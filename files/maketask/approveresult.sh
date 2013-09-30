#!/bin/bash

export JAVA_HOME="/usr/lib/jvm/java-1.7.0-openjdk-amd64"

token=$1

if [ -z $token ]
then
	echo "USAGE: $0 <token>"
	exit 1
fi

TASK_DIR=/opt/paint-tasks/${token}
COMPLETED_TASKS=/opt/completed
MTURK_BIN=/home/ubuntu/mturk-tools/bin

pushd $MTURK_BIN
./deleteHITs.sh -successfile $TASK_DIR/paint.success -approve -expire && mv $TASK_DIR $COMPLETED_TASKS/
popd

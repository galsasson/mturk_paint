#!/bin/bash

TEMPLATE_DIR=/var/www/maketask
TASKS_DIR=/opt/paint-tasks

tasks=`ls $TASKS_DIR`

for task in $tasks
do
	echo "getting result of task $task"
	$TEMPLATE_DIR/approveresult.sh $task
done

echo "all done"
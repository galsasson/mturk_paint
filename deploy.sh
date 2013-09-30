#!/bin/sh

if [ ! -f ../private/server.name ]
then
	echo "could not find server.name"
	exit 1
fi

PUB_DNS=`cat ../private/server.name`
USERNAME="ubuntu"

DIR=$1

if [ -z $DIR ]
then
	echo "USAGE: $0 <deploy_dir>"
	exit 1
fi

if [ ! -d $DIR ]
then
	echo "could not find $DIR"
	exit 1
fi

basename=`basename $DIR`
package="$basename.tar.gz"
pushd $DIR

tar -czf ../out/$package *

popd

echo "sending $package to $PUB_DNS"
scp -i ../private/mturk_keypair.pem out/files.tar.gz ${USERNAME}@${PUB_DNS}:/home/${USERNAME}




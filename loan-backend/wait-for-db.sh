#!/usr/bin/env bash
# Usage: ./wait-for-it.sh host:port -- command args

set -e

hostport=$1
shift

host=$(echo $hostport | cut -d: -f1)
port=$(echo $hostport | cut -d: -f2)

echo "Waiting for $host:$port..."

while ! nc -z $host $port; do
  sleep 1
done

echo "$host:$port is up!"

exec "$@"

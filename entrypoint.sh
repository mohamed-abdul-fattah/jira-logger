#!/bin/sh
set -e

composer install

exec "$@"

#!/bin/sh
set -e

composer install
overcommit --install
overcommit --sign

exec "$@"

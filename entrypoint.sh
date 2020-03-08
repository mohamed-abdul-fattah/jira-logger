#!/bin/sh
set -e

composer install

# Setup overcommit only for git repo
if git rev-parse --git-dir > /dev/null 2>&1; then
    overcommit --install
    overcommit --sign
fi


exec "$@"

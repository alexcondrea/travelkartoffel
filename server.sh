#!/bin/sh
git pull
backend/bin/console cache:clear
chmod -R 777 backend/var/cache
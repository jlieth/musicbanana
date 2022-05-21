#!/bin/sh

# start db container
docker-compose -p musicbanana-test -f docker-compose.test.yml up -d test-db

# run tests
docker-compose -p musicbanana-test -f docker-compose.test.yml run --rm testrunner bin/phpunit --testdox

# stop db
yes | docker-compose -p musicbanana-test -f docker-compose.test.yml rm -s test-db

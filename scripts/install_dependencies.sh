#!/bin/sh

docker run --rm --interactive --tty \
    --volume "$PWD/app:/app" \
    --user "$(id -u):$(id -g)" \
    composer:2 install

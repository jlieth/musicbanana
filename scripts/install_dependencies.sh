#!/bin/sh

docker run --rm --interactive --tty \
    --volume "$PWD/app:/app" \
    --user "$(id -u):$(id -g)" \
    composer:2 install

docker-compose run --rm --user "$(id -u):$(id -g)" musicbanana \
    sh -c "npm_config_cache=$(mktemp -d) npm install"

docker-compose run --rm --user "$(id -u):$(id -g)" musicbanana \
    php bin/console assets:install public --symlink --relative

docker-compose run --rm --user "$(id -u):$(id -g)" musicbanana \
    php bin/console fos:js-routing:dump --format=json --target=assets/js/routes.json

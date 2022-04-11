# musicbanana

## Development

### Running the dev containers

Execute from the root of the repository:

```console
./scripts/install_dependencies.sh
cp docker/postgres.env.example docker/postgres.env
cp docker/symfony.env.example docker/symfony.env
docker-compose build
docker-compose up
```

A musicbanana test instance should now be reachable at <http://127.0.0.1:8080>.

### Loading a dataset

Enter the container (there's a script that sets the correct user id):

```console
./scripts/enter_container.sh
```

Grab the [Last.fm](https://www.last.fm/) dataset from my
[repository](https://github.com/jlieth/lastfm-dataset-1K-extended/):

```console
wget https://github.com/jlieth/lastfm-dataset-1K-extended/releases/download/v2.1/lastfm-dataset-50-extended-normalized.db -P src/DataFixtures
```

Run the fixture import:

```console
php -d memory_limit=256M bin/console doctrine:fixtures:load --no-debug
```

Afterwards, behold the data (optional):

```console
$ php bin/console doctrine:query:sql "select count(*) from listen;"

array(1) {
  [0]=>
  array(1) {
    ["count"]=>
    int(775718)
  }
}
```

### Contributing

Please use the included [pre-commit](https://pre-commit.com) hooks to make sure
your contributions can be merged seamlessly.

You need to have python installed and a working docker setup.

First time setup:

```console
pip install --user pre-commit
pre-commit install
```
Make sure `~/.local/bin` is in your `$PATH` or else install the package globally.

Run manually (optional):

```console
pre-commit run --all-files
```

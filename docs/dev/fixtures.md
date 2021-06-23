# Fixtures

We are using [wp-cli-fixtures](https://github.com/nlemoine/wp-cli-fixtures) for fixtures.

## Install

`./bin/docker/wp package install git@github.com:nlemoine/wp-cli-fixtures.git`

## Usage

Fixtures can be added to `./fixtures.yml` see readme of [wp-cli-fixtures](https://github.com/nlemoine/wp-cli-fixtures) for more info.

## Load fixtures

Fixtures can be loaded with the following command:

`./bin/docker/wp fixtures load`

## Delete fixtures

If you wish to delete fixtures you've previously loaded, you can do so via:

`./bin/docker/wp fixtures delete`

# DO NOT PUT YOUR TESTS IN HERE

This is used for bootstrapping tests, actually writing of tests should be done within individual `mu-plugins`.

## Running the tests

### Using docker

* `bin/docker/phpunit`

### Not using docker

* Ensure `tests/.env` exists and is configured correctly.
* `tests/create-test-db.sh` (if you need to create the database
* `bin/phpunit`

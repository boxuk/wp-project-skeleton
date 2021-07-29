# Usage

Any plugins and themes should be installed via composer. Any custom development that doesn't fit should be done as part of `mu-plugins`. Any custom work done on the visual element of the site should be done within `themes`.

## Memcache caching (optional)

This one is optional but highly recommended, if you want to use memcache for object caching, you need to run the following (will have been done during install if you used the install script):

`cp wp-content/plugins/memcached/object-cache.php wp-content/object-cache.php`

## Running the tests

Tests are designed to be added within each `mu-plugin` you create within a `tests` directory. The test runner sits outside though in the root of this repo. It works by looping over each `mu-plugin` and running it's test.

> It's important each `mu-plugin` follows the format `plugin-name/plugin-name.php`

`bin/docker/phpunit`

(or `bin/phpunit` if not using docker)

## Running the code sniffs

We use the [WordPress VIP coding standards](https://github.com/Automattic/VIP-Coding-Standards) to ensure the code is adhering to the best possible performance and security practices. We also use the core [WordPress standards](https://github.com/WordPress/WordPress-Coding-Standards) and the [Neutron standards](https://github.com/Automattic/phpcs-neutron-standard) to ensure for consistency for a modern WordPress code base. You can check your code against all of this by running the following command:

`tools/php_codesniffer/vendor/bin/phpcs`


## Working with Feature Flags

We encourage working with feature flags. Simply add your flags to `000-boxuk/flags.yaml`. Format example below:

```yaml
feature_flags:
    log_sample_text:
        title: Log sample text
        description: Log sample text to query monitor
        enforced: false
        stable: true
        label: Samples
```

This will register with the feature flagging library you are using, as long as we support it. Current supported feature flagging libraries:

* [Flagpole](https://github.com/jamesrwilliams/flagpole)

## WPScan

We encourage running [WPScan](https://github.com/wpscanteam/wpscan) at regular intervals (at least weekly). This can be done via the following command:

`bin/docker/wpscan`

> An API token can be added via `./docker/wpscan/.env`

The URL of the local site (the value of `WP_HOME`) will be added automatically, as will `disable-tls-checks` (this is needed due to the self-signed certificate).

You can pass any other options as you wish.

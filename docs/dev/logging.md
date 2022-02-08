# Logging

Logging capabilities are provided by [Wonolog](https://github.com/inpsyde/Wonolog).

To log a message you simply need to fire an action hook, e.g.:

```php
    do_action(
        'wonolog.log',
        [
            'message' => 'Some message.',
            'channel' => Channels::DEBUG,
            'level' => LogLevel::INFO,
            'context' => [],
        ]
    );
```

There are several ways and variations on the above, more of which you can read about on the [Wonolog documentation](https://inpsyde.github.io/Wonolog/).

## Logging Handlers

Wonolog supports several logging handlers, which are used to output the log messages to a specific destination. Most of which are inherited from
the library it extends upon, [Monolog](https://github.com/Seldaek/monolog).

By default Wonolog will use a file based streaming handler, which will output the log messages to a file. We use this when working locally and by default the logs
are written to `wp-content/wonolog`.

In non-local environments (except production) we default to a custom Query Monitor handler which will log to [Query Monitor](https://querymonitor.com/) using its [logging capabilities](https://querymonitor.com/docs/logging-variables/).

In production we default to a New Relic handler which will log to New Relic using its [logging capabilities](https://docs.newrelic.com/docs/agents/php-agent/logging/log-custom-events).

All of these defaults can be changed through configuration. For example, if you wanted to change your local environment to log to Query Monitor you can update
`wp-content/config/config_local.yaml` to include the following:

```yaml
# config_local.yaml
imports:
    - { resource: config.yaml }

parameters:
    wonolog_default_handler_service_id: 'BoxUk\Plugins\Base\Wonolog\Handler\QueryMonitorHandler'
```

> In actual fact for local you can simply remove the parameter and it will pick up the default from `config.yaml` which is the QueryMonitorHandler however it's a good example.

### Adding a brand new custom handler

You can supply a custom handler or use any of the [wide array of supported Monolog handlers](https://github.com/Seldaek/monolog/blob/main/doc/02-handlers-formatters-processors.md#handlers). To do so you will need to set up the handler as a service
in `wp-content/config/services.yaml`, e.g.:

```yaml
services:
    _defaults:
        autowire: true
        autoconfigure: true

    Monolog\Handler\BrowserConsoleHandler:
        public: true
```

You can then point the `wonolog_default_handler_service_id` parameter to the service name in the appropriate config file.

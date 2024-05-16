![Box UK Logo](https://www.boxuk.com/wp-content/themes/BoxUK/assets/images/boxuk-logo.png)

# BoxUK WordPress Base Plugin

A WordPress plugin that includes a number of things to help us work on WordPress projects.

[![GPLv2 License](https://img.shields.io/github/license/boxuk/boxuk-base-wp-plugin)](https://github.com/boxuk/boxuk-base-wp-plugin/blob/6675942f8babaa9447c1224225eae153981af660/LICENSE)

## WP-CLI

We provide the following useful WP-CLI commands:

* `wp scaffold boxuk-mu-plugin` - which is an extension of `wp scaffold` to add support for scaffolding an opinionated `mu-plugin`

## DependencyInjection

The plugin is registered with the container from the [Box UK WP Project Skeleton](https://github.com/boxuk/wp-project-skeleton) via an extension within `src/DependencyInjection` this works just like Symfony bundles, for information in the [Symfony docs](https://symfony.com/doc/master/components/dependency_injection/compilation.html#managing-configuration-with-extensions).

## Hooks

Added support for tagging services in order to decouple hooks from the service, e.g.

```yaml
services:
    _defaults:
        autowire: true
        autoconfigure: true

    BoxUk\Mu\Plugins\MyPlugin\MyService:
        tags:
            - { name: 'wp_hook', action: 'init', method: 'something_to_do_on_init' }
```

`action` can be replaced with `filter` when using a filter. `priority` and `accepted_args` also supported.

## DB Repositories

We wrap database query access in repository classes for an easier abstraction dealing with querying the database. This follows a loose [repository pattern](https://shawnmc.cool/2015-01-08_the-repository-pattern).

## Feature Flags

There is a helper you can use when developing with feature flags which will allow you to configure flags in yaml. Usage is as follows:

```php
boxuk_container()->get('BoxUk\Plugins\Base\FeatureFlag\FeatureFlagManager')->register_from_yaml(
__DIR__ . '/flags.yaml' );
```

## Gutenberg

It's useful to allow a phased approach to enabling gutenberg across a site. This helper will allow you to use tags in order to 'turn on' gutenberg for
select posts easily.

## Security

Provides a number of security enhancements by default:

* Remove users endpoints from the REST API
* 404s author queries to protect against author enumeration
* Sets X-Frame-Options header to SAMEORIGIN to protect against clickjacking

## License

[GPLv2](https://choosealicense.com/licenses/gpl-2.0/)

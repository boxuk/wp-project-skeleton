# Custom Code

## Should my work go within the theme?

Generally only code that is directly related to the theme, i.e. appearance should live in the theme. This can be quite hard distinction to make. The one question to ask yourself is:

> If I removed the theme should this piece of functionality still be available?

If the answer to the above is yes, then you should not put this in the theme. Instead, it should live inside a plugin within mu-plugins (on VIP projects, this will be client-mu-plugins).

## Creating an mu-plugin

> tl;dr
> `bin/create-mu-plugin my-plugin`

`mu-plugin` is short for `must-use plugin` it means the plugin will be installed and activated by default. It's typically where we would put any custom development given the distinction above.

When creating an `mu-plugin` we should have the mindset that it is a plugin and that it could be useful as a stand-alone plugin if possible. Of course, there will always be
client specific custom development that is so esoteric that it wouldn't make sense to be a stand-alone plugin, but still we should aim to make it as re-usable as possible if we can.

Generally we should aim to follow the [plugin naming conventions](https://developer.wordpress.org/plugins/plugin-basics/best-practices/) set out by WordPress, however we should favour [PSR-4 autoloading](https://www.php-fig.org/psr/psr-4/) which means the following things are different:

* Namespaces > 'Prefix Everything'
* `src` > `includes`
* PSR-4 naming > `class-` prefixed [hyphenated naming](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/#naming-conventions)

### Autoloading

Each mu-plugin should have it's autoloading defined within its own `composer.json` this will allow us to _merge_ it with the root `composer.json` to ensure autoloading of all mu-plugins works correctly.

## Dependency Injection

Each mu-plugin can and should take advantage of Dependency Injection. The `boxuk_container()` function can be used to retrieve things from the container, example:

```php
$sample = boxuk_container()->get( 'BoxUk\Mu\Plugins\MyPlugin\MyService' );
```

In order to set things in the container, we can use a `servives.yaml` file in the `config` directory, example:

```yaml
# my-plugin/config/services.yaml
services:
    _defaults:
        autowire: true
        autoconfigure: true

    BoxUk\Mu\Plugins\MyPlugin\MyService:
        public: true # If a service is being used directly (i.e. not being injected) you have to mark it as public.
```

We support and recommend [autowiring](https://symfony.com/doc/current/service_container/autowiring.html) but it is entirely optional if you don't wish to use it.

## Using Hooks

WordPress has two types of hooks. Actions and Filters. These work much in the same way as the [observer pattern](https://en.wikipedia.org/wiki/Observer_pattern) and we have added support for tagging services in our DI container for ease of use, much in the same way [Symfony does for event listeners](https://symfony.com/doc/current/event_dispatcher.html).

Example:
```yaml
services:
    _defaults:
        autowire: true
        autoconfigure: true

    BoxUk\Mu\Plugins\MyPlugin\MyService: ~

    BoxUk\Mu\Plugins\MyPlugin\HookListener:
        tags:
            - { name: 'wp_hook', action: 'init' } # Will call the on_init method on HookListener
            - { name: 'wp_hook', filter: 'the_content' } # Will call the on_the_content method on HookListener
            - { name: 'wp_hook', action: 'plugins_loaded', method: 'plugins_have_now_loaded' } # Will call the plugins_have_now_loaded method on HookListener
```

You don't necessarily need an explicit listener and will work on the service class directly if required. However, a listener can be useful for applying additional logic before the service class is required. For example, if you only want a service class to fire under a certain feature flag or similar, you could do so in the listener:

```php
class HookListener {
	private $my_service;
	private $feature_flag_manager;

	public function __construct( MyService $my_service, FeatureFlagManager $feature_flag_manager ) {
		$this->my_service = $my_service;
		$this->feature_flag_manager = $feature_flag_manager;
	}

	public function on_init(): void {
		if ( $this->feature_flag_manager->is_enabled( 'my_feature' ) ) {
			$this->my_service->log_sample();
		}
	}
}
```

This allows us to keep the service class nice and clean and will help with unit testing and adherence to single responsibility.

### Hook Attributes

Alternatively, you can use the [wp-hook-attributes](https://github.com/boxuk/wp-hook-attributes) library (installed by default) to use attributes (or annotations) as a way of defining hooks.

Example:

```php
use BoxUk\WpHookAttributes\Hook\Annotations\Action;
use BoxUk\WpHookAttributes\Hook\Annotations\Filter;

class MyClass {
    // Example of using an action hook
    /**
     * @Action("init")
     */
    public static function basic_action(): string {
        return 'something...';
    }

    // Example of using a filter hook
    /**
     * @Filter("the_content")
     */
    public static function basic_filter(): string {
        return 'something...';
    }
}
```

There is some setup required, check the `sample-with-hook-attributes` example plugin within `mu-plugins` for an example.

## Tests

Each `mu-plugin` should have tests in the `tests` directory and an `autoload-dev` section should be set up in `composer.json`. Each test should an appropriate namespace.

## Directory layout

```
my-plugin/
├─ config/
│  ├─ services.yaml
├─ src/
│  ├─ MyService.php
├─ tests/
│  ├─ MyServiceTest.php
├─ composer.json
├─ my-plugin.php
```

## Scaffolding

The easiest way to get up and running with a new `mu-plugin` is to use the scaffolding tool, you can do this by running the following command:

`bin/create-mu-plugin my-plugin`

Under the hood this runs the following commands:

`bin/docker/wp scaffold boxuk-mu-plugin my-plugin` to scaffold the plugin

`tools/monorepo-builder/vendor/bin/monorepo-builder merge` to merge composer settings

`bin/docker/composer dump-autoload` to update the composer autoloader

This will give you everything you need to adhere to info above.

## ACF field naming conventions

It's best practice to ensure ACF fields have unique names. This helps avoid collisions in field names. For consistency and
uniqueness the following naming convention is recommended:

- snake_case to be used when naming fields
- ACF field names to consist of block names followed by the name of the field, separated by a double underscore e.g. `block_name__field_name`
- Nested field names do not need to follow this convention. This is because nested repeater fields (for example) are only ever used in context of the parent, which has a unique name, so ensuring nested field names are unique is unnecessarily verbose.

This naming convention is similar to the one described in [this article](https://kamilgrzegorczyk.com/2017/10/12/best-practices-naming-convention-for-wordpress-custom-fields/). The main difference is that we don't use hyphens. This is primarily because at some point ACF creates objects, the property names of which are the field names. Properties with hyphens are not valid in PHP. Therefore, in order to access properties created by ACF which contain hyphens we would have to use something like `$theobject->{'the-prop'}`, which isn't ideal.

### Example

If you have a block called accordion, which has a "drawer" repeater field, which consists of multiple accordion items, each of which has a title field, the field list would be:
- Accordion drawer: `accordion_block__drawer`
- Accordion item summary: `summary`

Retrieving the values looks like this:

Twig:

```twig
{% set drawer = [] %}

{% for item in fields['accordion_block__drawer'] %}
    {% set drawer = drawer | merge([{
        summary: item['summary']
    }]) %}
{% endfor %}
```

PHP:

```php
$drawer = [];

foreach ( get_field( 'accordion_block__drawer' ) as $item ) {
    $drawer[] = [ 'summary' => $item['summary'] ];
}
```

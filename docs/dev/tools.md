# Tools

Sometimes you need to install some tools via composer but you don't want to include them in the global composer file as there could be a dependency mismatch.

The way we get around this is to install composer based tools in a separate tools directory. This is the same [approach suggested by php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer/#installation).

## Adding a new tool

* `mkdir -p tools/tool-name`
* `composer require --working-dir=tools/tool-name toolvendor/tool-name`
* Update the `install-tools` script in the root `composer.json`

You would then use the tool as thus:

`tools/tool-name/vendor/bin/tool-name`

## Installing tools

As we use dependabot for keeping on top of updates for our tools, we likely need to ensure we're running the latest version locally, the easiest way to do this is by running:

`composer install-tools`

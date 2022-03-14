# PHP Versions

The skeleton supports the following PHP versions:

* 7.4 (default)
* 8.0
* 8.1*

> *Please note with 8.1 you will likely see a bunch of deprecation notices, [please read this post for more information](https://make.wordpress.org/core/2022/01/10/wordpress-5-9-and-php-8-0-8-1/).
> It's also worth setting `WP_DEBUG_DISPLAY` to false as to not flood the page with notices.

## Using docker

When using docker you can easily decide which PHP version you want to use by setting the `PHP_VERSION` environment variable in the `.env` file.

If you're using the `bin/install` script (recommended) then you can specify which version you want to run by passing a 4th option.
For example, if you want to use PHP 8.0 you might run something like the following:

`bin/install boxuk-wp-skeleton '' boxuk-docker 8.0`

## Not using docker

If you're not using docker it will use whichever version of PHP is installed on your system.

## composer.lock issues

We need to use `composer.lock` in the main because we are using composer for plugins. We need
to ensure plugins are locked to specific versions. We can't trust that always updating plugins will work, we
generally rely on dependabot to update our plugins, so we can review the changes as part of a PR and
make a decision whether we want to upgrade or not.

This presents a challenge if we need to change our version of PHP as our `composer.lock` file will be locked
to the original PHP version we were using. This should be a rare occurrence but if you come across it you can solve
by either updating each library individually which is causing issues or by doing a blanket `composer update` or by removing the
lock file and running `composer install`.

# PHP Versions

The skeleton supports the following PHP versions:

* 8.2 (default)
* 8.3

## Using docker

When using docker you can easily decide which PHP version you want to use by setting the `PHP_VERSION` environment variable in the `.env` file.

If you're using the `bin/install` script (recommended) then you can specify which version you want to run by passing a 4th option.
For example, if you want to use PHP 8.3 you might run something like the following:

`bin/install boxuk-wp-skeleton '' boxuk-docker 8.3`

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

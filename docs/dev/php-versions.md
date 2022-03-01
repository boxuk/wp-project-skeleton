# PHP Versions

The skeleton supports the following PHP versions:

* 7.4 (default)
* 8.0
* 8.1*

> *Please note with 8.1 you will likely see a bunch of deprecation notices, [please read this post for more information](https://make.wordpress.org/core/2022/01/10/wordpress-5-9-and-php-8-0-8-1/).

## Using docker

When using docker you can easily decide which PHP version you want to use by setting the `PHP_VERSION` environment variable in the `.env` file.

If you're using the `bin/install` script (recommended) then you can specify which version you want to run by passing a 4th option.
For example, if you want to use PHP 8.0 you might run something like the following:

`bin/install boxuk-wp-skeleton '' boxuk-docker 8.0`

## Not using docker

If you're not using docker it will use whichever version of PHP is installed on your system.

# Troubleshooting

## WP install fails

Sometimes the WordPress install in the `bin/install` script can fail. This is usually fixed by increasing the sleep time:

```bash
# ...
echo 'Please hold...';

sleep 10; # Increase this and retry, it may need to go as high as 30.
# ...
```

## Docker errors

> ERROR: readlink /var/lib/docker/overlay2: invalid argument

Often this is due to a corrupted image that needs to be rebuilt sans cache.

`docker-compose build --no-cache`

Or you can amend the install script to include the `--no-cache` option, e.g.

```diff
 if [ ! -z "${STYLEGUIDE_DIR}" ]; then
-    docker-compose -f docker-compose.yml -f docker-compose-styleguide.yml build;
+    docker-compose -f docker-compose.yml -f docker-compose-styleguide.yml build --no-cache;
     docker-compose -f docker-compose.yml -f docker-compose-styleguide.yml up -d;
 else
-    docker-compose build;
+    docker-compose build --no-cache;
     docker-compose up -d;
 fi
```

## Permission errors

If you are on a Linux machine, you will need to uncomment the following within the root `.env` file:

```
#USER_ID=1000
#GROUP_ID=1000
```

> Note: You will need to stop and rebuild the containers when changing env vars, e.g. `docker-compose stop; docker-compose up --build`

## Certificate warnings

On chrome you will need to type `thisisunsafe` to bypass the certificate warning (make sure your cursor isn't in the url bar). Othe browsers usually give you a button you can use to bypass.

## Slow local env

## Composer autoloader optimisations

Composer can often be quite slow if you have lots of classes and it falls back to filesystem lookups.

They have addressed this with a series of [autoloader optimisations](https://getcomposer.org/doc/articles/autoloader-optimization.md).

Typically, Level 1 optimisations `composer install -o` / `composer dump-autoload -o` are enough, however with WordPress you are likely to get a large number of autoloader misses due to the fact many plugins and things are not installed via composer.

Therefore, we can have more success with the Level 2 optimisations. Using an authoritative classmap should be ok, because everything we want to autoload through composer should be defined in the classmap and we are unlikely to be declaring classes dynamically.

`composer install -a`

or

`composer dump-autoload -a`

If we do come across class not found errors though then we may need to look into APCu caching.

### Query Monitor

Sometimes query monitor can slow down your local env, particularly if you have xdebug enabled. You can de-activate through the admin or on CLI with:

`./bin/docker/wp plugin deactivate query-monitor`

To re-activate:

`./bin/docker/wp plugin activate query-monitor`

### Object Cache

Sometimes using wp-memcached can cause slowdown locally due to limited memory on a developers machine (or assigned to the container). To stop using memcached, simply remove the `object-cache.php` file within `wp-content`

`rm -f wp-content/object-cache.php`

To re-enable:

`cp wp-content/plugins/memcached/object-cache.php wp-content/object-cache.php`

### Profiling

If you still find it's slow, it could be to do with a particular plugin or something else, the best way to identify this is either to disable all plugins and enable one by one (shotgun debugging) or you can use blackfire to profile and find out where the slowness is.

To enable blackfire:

`./bin/docker/enable_blackfire`

> Make sure you filled out `./docker/blackfire/.env` with your credentials (see `docker/blackfire/.env.dist for examples).

Then when you're done, to disable:

`./bin/docker/disable_blackfire`

## Other

If you're still having trouble, sometimes it can be resolved by re-fetching and re-building fresh images:

`docker-compose stop; docker-compose build --no-cache --pull`


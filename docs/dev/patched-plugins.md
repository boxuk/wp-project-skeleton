# Patched Plugins

Sometimes a plugin you rely on doesn't work quite right and they either aren't on github or are just ignoring your submitted PR. In this scenario it's useful to 'patch' the plugin, i.e. make direct changes to the source of the plugin.

Doing this directly in `wp-content/plugins` isn't safe because it can get reverted to the source easily with a composer install or update.

Therefore, we offer a way of safely patching plugins by using a similar method to how we deal with premium plugins. By patching and thus installing from the `patched-plugins` directly we are controlling the patch in a confined way, with a neat install and upgrade path.

> **IMPORTANT**: When we patch the plugin in this way, we are diverging from the original source and thus aren't able to easily install updates. Therefore, patching a plugin should be considered a last resort or a temporary measure until you're able to get back to the source.

To patch a plugin, take the following steps:

## Composer dependency

* Move the plugin to `patched-plugins`

`mv wp-content/plugins/plugin patched-plugins/plugin`

* Edit `patched-plugins/plugin/composer.json` (or add one if it doesn't exist, see [further down for an example](#adding-a-composer-json)) to include a patched version number, either add the branch alias or update it if one already exists.

```json
  "extra": {
    "branch-alias": {
        "dev-master": "1.0.0-patched"
    }
  }
```

* Remove the original package

`composer remove vendor/plugin`

* Require the package (note `@dev` is important)

`composer require wp-patched-plugins/plugin-name:@dev`

> If a composer.json already existed then the vendor name will remain intact so use that instead of `wp-patched-plugins`

### Adding a composer.json

* Add a `composer.json` into the directory, that should look like the following (note, if the plugin already has a `composer.json` you can just use that, just make sure you use the name from within their `composer.json`):

```json
{
    "name": "wp-patched-plugins/plugin-name",
    "type": "wordpress-plugin",
    "require": {
        "composer/installers": "~1.0"
    }
}

```

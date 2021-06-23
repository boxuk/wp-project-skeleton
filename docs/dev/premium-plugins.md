# Premium Plugins

## Plugin repository

The desired approach is the plugin provider offers a private repository we can auth with. See how delicious brains handle it for a good example: https://deliciousbrains.com/wp-migrate-db-pro/doc/installing-via-composer/

In this scenario we can add the credentials to `auth.json` and it should work ok.

> IMPORTANT: `auth.json` must not be committed to source control, instead add to `auth.json.dist` with details on where to obtain the credentials.

> VERY IMPORTANT: Check the license terms of the plugin before using `auth.json` if the license only covers a single dev and that dev is not you, do not do this. If in any doubt check with the lead developer.

### Adding a new plugin repository

Follow the instructions from the plugin provider, but usually it will be the following steps:

* Add repository to `composer.json`

```json
"repositories": [
    {
        "type":"composer",
        "url":"https://path/to/repository"
    }
]
```

* Add creds to `auth.json` (and documented in `auth.json.dist`)

```json
{
    "http-basic": {
        "path/to/repository": {
            "username": "{COMPOSER_API_USERNAME}",
            "password": "{COMPOSER_API_PASSWORD}"
        }
    }
}
```

* Require the package

`composer require vendor/plugin`

* Update travis encryption of the `auth.json` file

`travis encrypt-file auth.json`

* Move encrypted `auth.json`

`mv auth.json.enc .travis/auth.json.enc`

* Update `.travis.yml` by replacing any instance of

`- openssl aes-256-cbc -K $encrypted_xxxx_key -iv $encrypted_xxxx_iv -in .travis/auth.json.enc -out auth.json -d`

With the output from `travis encrypt-file...`

## Plugin zip file

Sometimes all we have for a premium plugin is a zip file (or we can't install via the above method for reasons such as dependency clash). In these cases we need to take the following steps:

### Adding a new plugin zip file

* Unzip to `premium-plugins`

> Pro tip: If you download and unzip to `~/Downloads` you can just do `cp -fR ~/Downloads/plugin-name ./premium-plugins`

* Add a `composer.json` into the directory, that should look like the following (note, if the plugin already has a `composer.json` you can just use that, just make sure you use the name from within their `composer.json`):

```json
{
    "name": "wp-premium-plugins/plugin-name",
    "type": "wordpress-plugin",
    "require": {
        "composer/installers": "~1.0"
    }
}

```

* Require the package (note `@dev` is important)

`composer require wp-premium-plugins/plugin-name:@dev`

Although this is shorter than the above method it's not as desired because it puts the emphasis on us to keep the plugins up to date where as with a private repository we can get updates automatically with composer. It also means we have to commit the files to our VCS as well.

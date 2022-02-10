
![Box UK Logo](https://www.boxuk.com/wp-content/themes/BoxUK/assets/images/boxuk-logo.png)


# BoxUK WordPress Project Skeleton

A base WordPress project from Box UK to get you up and running quickly.



| [![Build Status](https://travis-ci.com/boxuk/wp-project-skeleton.svg?token=3rRfYiN6sMupp1z6RpzN&branch=main)](https://travis-ci.com/boxuk/wp-project-skeleton) | [![GPLv2 License](https://img.shields.io/github/license/boxuk/wp-project-skeleton)](https://github.com/boxuk/wp-project-skeleton/blob/528933ef462e00b36fbd6a6f3371da62c1227eac/LICENSE) |
|-----|-----|

## Installation

Create a new project with composer

```bash
composer create-project boxuk/wp-project-skeleton my-project --stability=dev
```

Install with the simple install script

```bash
bin/install my-project
```

> Note: This is an interactive command.

## Usage

Frontend:

[https://my-project.local](https://my-project.local)

> You will be faced with a security warning.
>
> On Chrome, type `thisisunsafe` to bypass. On other browsers follow the prompts.
>
> To find out more about this and what options you have, [read the dedicated section on HTTPS](docs/dev/https.md).

Admin:

[https://my-project.local/wp-admin](https://my-project.local/wp-admin)

> Username is: `admin` An admin password would have been generated during install, but unless you were beady eyed you probably missed it. You can easily regenerate an admin password using the following command:
>
> `bin/docker/wp user update admin --user_pass=your_password_here`

## Features

🐳 - Fully dockerized setup

📦 - Composer based plugin management

🧠 - [Genius xdebug setup](https://strayobject.medium.com/php-docker-and-xdebug-with-no-performance-loss-261ad89efd6e)

📧 - Email testing with [mailhog](https://github.com/mailhog/MailHog)

🚀 - Memcached support

🔥 - [Blackfire](https://blackfire.io) support

🧐 - Monorepo support

📋 - [Dictator](https://github.com/boxuk/dictator/) support for dictating state across environments

🪜 - Fixtures support using [wp-cli-fixtures](https://github.com/nlemoine/wp-cli-fixtures)

💉 - Baked in Dependency Injection support with [Symfony](https://symfony.com/doc/current/components/dependency_injection.html)

🪝 - DI based hook solution using tags

🏋️‍♀️ - Optimised mu-plugin setup, including scaffold tool

🚩 - First class support for feature flags using [flagpole](https://github.com/jamesrwilliams/flagpole)

✅ - Unit, Integration and Visual Regression testing support

💻 - Logging support through [Wonolog](https://github.com/inpsyde/Wonolog)

> You can read more about all of these features in [this post on the Box UK blog](https://www.boxuk.com/insight/how-we-develop-wordpress-sites/).


## Documentation

* Getting Started
    * [Quickstart](docs/dev/quickstart.md)
    * [Docker Setup](docs/dev/docker-setup.md)
    * [Non Docker Setup](docs/dev/non-docker-setup.md)
    * [Fixtures](docs/dev/fixtures.md)
* [Usage](docs/dev/usage.md)
* [Keeping up to date](docs/dev/keeping-up-to-date.md)
* [Troubleshooting](docs/dev/troubleshooting.md)
* [Premium plugins](docs/dev/premium-plugins.md)
* [Patched plugins](docs/dev/patched-plugins.md)
* [VIP project?](docs/dev/vip.md)
* [Monorepo](docs/dev/monorepo.md)
* [Custom code](docs/dev/custom-code.md)
* [Working with a styleguide](docs/dev/styleguide.md)
* [i18n](docs/dev/i18n.md)
* [Tools](docs/dev/tools.md)
* [Testing](docs/dev/testing.md)
* [Logging](docs/dev/logging.md)



## License

[GPLv2](https://choosealicense.com/licenses/gpl-2.0/)


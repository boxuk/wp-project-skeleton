# BoxUK WordPress Project Skeleton

A base WordPress project from Box UK to get you up and running quickly.


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

Admin:

[https://my-project.local/wp-admin](https://my-project.local/wp-admin)


If you need to update the admin password, you can run the following WP-CLI command:

```sh
bin/docker/wp user update admin --user_pass=your_password_here
```

## Features

📕 - Fully [documented](https://github.com/boxuk/wp-packages/blob/main/docs/index.md).

🐳 - Fully dockerized setup

📦 - Composer based plugin management

🧠 - [Genius xDebug setup](https://strayobject.medium.com/php-docker-and-xdebug-with-no-performance-loss-261ad89efd6e)

📋 - [Dictator](https://github.com/boxuk/dictator/) support for dictating state across environments

🪜 - Fixtures support using [wp-cli-fixtures](https://github.com/nlemoine/wp-cli-fixtures)

🚩 - First class support for feature flags using [wp-feature-flags](https://github.com/boxuk/wp-feature-flags)

✅ - Unit, Integration and Visual Regression testing support

> You can read more about all of these features in [this post on the Box UK blog](https://www.boxuk.com/insight/how-we-develop-wordpress-sites/).

## License

[GPLv2](https://choosealicense.com/licenses/gpl-2.0/)


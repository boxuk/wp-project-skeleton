# BoxUK WordPress Project Skeleton

A base WordPress project from Box UK to get you up and running quickly.

## [📚 Read the docs](https://boxuk.github.io/wp-packages/)

## Installation

Create a new project with composer

```bash
composer create-project boxuk/wp-project-skeleton my-project --stability=dev
```

Install with the simple install script

```bash
bin/install --project=[project_name] --network=[docker_network_name] --php=[php_version]
```
All arguments are optional.

Defaults: 
- Project Name = boxuk-wp-skeleton 
- Network Name = boxuk-docker
- PHP Version = 8.2

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

📕 - Fully [documented](https://boxuk.github.io/wp-project-skeleton/).

🐳 - Fully dockerized setup

📦 - Composer based plugin management

🧠 - Xdebug installed by default

📋 - Configurable state across environments (WIP)

🪜 - Customisable fixtures support using the WP CLI (WIP)

🚩 - First class support for feature flags using [wp-feature-flags](https://github.com/boxuk/wp-feature-flags)

✅ - Unit, Integration and Visual Regression testing support

> You can read more about all of these features in [this post on the Box UK blog](https://www.boxuk.com/insight/how-we-develop-wordpress-sites/).

## License

[GPLv2](https://choosealicense.com/licenses/gpl-2.0/)


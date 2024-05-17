# Quickstart

#### Pre-requisites
##### Pre-requisites for Windows systems
* [WSL](https://docs.microsoft.com/en-us/windows/wsl/install)
* [Linux Kernel Update Package](https://docs.microsoft.com/en-gb/windows/wsl/install-manual#step-4---download-the-linux-kernel-update-package)
* [Cygwin](https://cygwin.com/install.html) (be sure to include the gettext package on install)
##### Pre-requisites for all systems
* [Docker](https://www.docker.com/)
* [Docker compose](https://docs.docker.com/compose/install/)

## TL;DR

If you just want a ready to go environment you can just use the following commands, if you're after more detail read
the [docker setup](docker-setup.md) or [non docker setup](non-docker-setup.md) docs instead


```
bin/install [project_name] [docker_network_name] [php_version]

# project_name will default to boxuk-wp-skeleton if not provided.
# docker_network_name will default to boxuk-docker
# php_version allows you set which PHP version you wish to run, e.g. 8.2, 8.3.
```

> The docker network is required to ensure the loopback works with the expected IP address.

> Note: This will start the containers in detached mode, use `docker-compose stop` if you wish to stop them.

<details>
<summary>Install details</summary>

```
cp .env.dist .env; cp ./docker/database/.env.dist ./docker/database/.env; cp ./docker/app/.env.dist ./docker/app/.env;
docker network create --subnet=192.168.35.0/24 boxuk-docker;
docker-compose stop;
docker-compose build;
docker-compose up -d;
bin/docker/composer install;
cp wp-content/plugins/memcached/object-cache.php wp-content/object-cache.php;
bin/docker/wp core install --url="https://$PROJECT_NAME.local" --title="Box UK WordPress Project" --admin_user=admin --admin_email=boxuk@example.com --skip-email;
bin/docker/wp site empty;
bin/docker/wp dictator impose site-state.yml;
bin/docker/wp package install git@github.com:nlemoine/wp-cli-fixtures.git;
bin/docker/wp fixtures load;
bin/docker/wp cache flush;
echo '127.0.0.1 $PROJECT_NAME.local | sudo tee -a /etc/hosts;
```
</details>

# Non Docker Setup

### Pre-requisites
* [Composer](https://getcomposer.org/)

You will need to create the database you are going to be using.

`CREATE DATABASE IF NOT EXISTS wordpress;`

### Env Vars

#### .env

Create a `.env` file based on `.env.dist`.

### Hosts file entries

`127.0.0.1 $PROJECT_NAME.local`

### Create environment

`php -S 127.0.0.1`

This will run using the built-in PHP server under port 80. If you wanted to run a different port you can.

That's it! You should now be able to browse to `https://$PROJECT_NAME.local` (or whatever you set) and view the site. Note, at time of writing you will need to go through the install script to have WordPress set up.

## What Next?

Once you've got an environment setup you can [setup the initial state](setup-initial-state.md)

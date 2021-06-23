# Set up initial state

Run composer (if you haven't already):

`bin/docker/composer install`

> or simply `composer install` if not using docker

So you have WordPress set up and you're ready to rock. You can go through the WordPress installer and set everything up manually *or* you can automate this using [dictator](https://github.com/boxuk/dictator)

Before using dictator though you will need to do install, so run the following first:

`bin/docker/wp core install --url="https://$PROJECT_NAME.local" --title="Box UK WordPress Project" --admin_user=admin --admin_email=boxuk@example.com`

> Be sure to take a note of the generated password!

To do this, take a look at `site-state.yml` and edit to your needs, then run:

`bin/docker/wp dictator impose site-state.yml`

(or `bin/wp dictator impose site-state.yml` if not using docker)

This should give you an installed WordPress with some basic settings already set.

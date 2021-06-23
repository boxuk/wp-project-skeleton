# Monorepo

The WordPress Skeleton and thus any project created from it is set up loosely as a monorepo. I say loosely because it's only certain aspects that act as a monorepo (mu-plugins & themes) and we may not always publish the plugins and themes we are using as sub packages.

## monorepo-builder

We use https://github.com/symplify/monorepo-builder to manage our mono-repo and in particular the `merge` command in order to merge the contents of our mu-plugins and themes `composer.json` with our root `composer.json`. The main reason we do this is an easy way to copy across autoloading configuration from our mu-plugins, however it's also beneficial if we wish to split out at a later date any theme or mu-plugin to a stand-alone package.

## Using Monorepo for your project

You too can use a monorepo for your project! Uncomment the `project_packages_split` job in `.github/workflows/split_monorepo.yaml` and fill in the blanks.

Some uses cases for this:

* If your organisation has a theme within a separate repository which you want to work on within the project.
* You use a shared plugin within a separate repository whcih you want to work on within the project.

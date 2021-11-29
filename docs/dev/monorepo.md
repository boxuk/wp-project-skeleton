# Monorepo

The WordPress Skeleton and thus any project created from it is set up loosely as a monorepo. I say loosely because it's only certain aspects that act as a monorepo (mu-plugins & themes) and we may not always publish the plugins and themes we are using as sub packages.

## monorepo-builder

We use https://github.com/symplify/monorepo-builder to manage our mono-repo and in particular the `merge` command in order to merge the contents of our mu-plugins and themes `composer.json` with our root `composer.json`. The main reason we do this is an easy way to copy across autoloading configuration from our mu-plugins, however it's also beneficial if we wish to split out at a later date any theme or mu-plugin to a stand-alone package.

## Using Monorepo for your project

You too can use a monorepo for your project! Uncomment the `project_packages_split` job in `.github/workflows/split_monorepo.yaml` and fill in the blanks.

Some uses cases for this:

* If your organisation has a theme within a separate repository which you want to work on within the project.
* You use a shared plugin within a separate repository which you want to work on within the project.

## Useful commands

### Merge

`tools/monorepo-builder/vendor/bin/monorepo-builder merge`

> Merge the composer.json of all sub packages into the main composer.json

### Release

`tools/monorepo-builder/vendor/bin/monorepo-builder release v1.0.0`

> Release a new version of the main package and all sub-packages. Change v1.0.0 to the version you wish to release. Append `--dry-run` to do a dry run without actually releasing anything

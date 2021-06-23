# Styleguide

For most, if not all of our projects we use a styleguide. By default, the skeleton doesn't include a styleguide (or indeed a theme) but support is there for one to be added easily.

An example of how to bring in a styleguide is via composer:

`composer create-project boxuk/project-styleguide wp-content/themes/your-theme/styleguide --stability=dev`

## Where should the styleguide live?

In our example here we have declared that the styleguide is part of the theme and therefore lives within our theme. However, feel free to change this and have styleguide live wherever you want (such as the root). Just make sure to update the `STYLEGUIDE_DIR` in `.env` and ensure the symlinks are set up appropriately.

## What if I don't need a styleguide?

A styleguide is not required and simply leaving the `STYLEGUIDE_DIR` variable in the `.env` file blank or removing completely will ensure a styleguide isn't setup.

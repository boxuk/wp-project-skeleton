# Testing

Testing WordPress sites can be a challenge. We should strive to make use of different testing techniques to ensure we're getting good test coverage across our sites. Here are the different types of tests we recommend and how to achieve each.

* [Unit Testing](#unit-testing)
* [Integration Testing](#integration-testing)
* [Visual Regression Testing](#visual-regression-testing)
* [Acceptance Testing](#acceptance-testing)

## Unit Testing

Unit tests should be added for every `(mu-)plugin`/`theme` we create. We should always aim to decouple the core logic from WordPress, for this to be true we should avoid any use of global functions or hidden dependencies in any classes/functions we use for our core logic. We should then be able to unit test these easily.

We use [PHPUnit](https://phpunit.de/) for unit testing.

## Integration Testing

Integration tests should also be added wherever appropriate. For any part of your `(mu-)plugin`/`theme` that interacts with WordPress this should be covered by an integration test.

We also use [PHPUnit](https://phpunit.de/) for integration testing along with [WP PHPUnit](https://github.com/wp-phpunit/wp-phpunit) which will allow you to interact with the WordPress install easily. It also provides a number of helpers to allow for easy generation of synthetic data for testing.

### Running the unit & integration tests

Both the unit tests and integration tests follow the same structure, tests are designed to be added within each `mu-plugin` you create within a `tests` directory. The test runner sits outside though in the root of this repo. It works by looping over each `mu-plugin` and running its test.

> It's important each `mu-plugin` follows the format `plugin-name/plugin-name.php`

`bin/docker/phpunit`

(or `bin/phpunit` if not using docker)

## Visual Regression Testing

Sometimes hard to find bugs can be present even though all our automated tests are passing, this could be due to something that's fallen through the cracks, a JS/CSS issue, an update to a plugin or theme, an update to a browser or something else entirely. It's therefore useful to run Visual Regression (VR) tests to catch anything that may have changed how the site appears to the end user.

VR tests work by taking screenshots of given scenarios (typically defined pages/posts), approving the screenshots to a known state and then comparing future runs against this approved state to see if anything has changed. Tests will then fail if the difference between the screenshots is above a set tolerance.

We use [Playwright](https://playwright.dev/) for VR testing.

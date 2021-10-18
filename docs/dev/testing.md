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

We use [BackstopJS](https://github.com/garris/BackstopJS/) for VR testing.

### Running the tests

Tests (or scenarios) should be added to `tests/visual-regression/backstop.json` within the `scenarios` section. For example:

```json
    "scenarios": [
        {
            "label": "Page or post to test",
            "cookiePath": "config/%env_name%/cookies.json",
            "url": "%base_url%/page-or-post-slug",
            "delay": 500,
            "misMatchThreshold": 0.1,
            "requireSameDimensions": true
        }
    ]
```

You can configure things like cookies, env vars and secrets within the `config` directory. You can do so for each environment you want to test, `local` is set up currently that will allow you to test the local environment.

* Setup backstop

`bin/docker/setup_backstop`

* Run backstop tests

`bin/docker/backstop test [environment]`

* Approve detected changes

`bin/docker/backstop approve [environment]`

> [environment] is the environment you want to test against, falls back to `local`.

* Open report

`bin/backstop_report`

> This will open the test report in your default browser.

## Acceptance testing

As well as the other tests we also want to run Acceptance Tests, so we can test our `(mu-)plugin`/`theme` does what it's supposed to do for the end user. For this we typically want to run some automated browser based testing to make sure clicking links, navigating between sections works the way it should.

We use... [coming soon]

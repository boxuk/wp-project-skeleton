<?php

declare ( strict_types=1 );

$root = dirname( __DIR__ );
$composer = json_decode( file_get_contents( $root . '/composer.json' ), true, 512, JSON_THROW_ON_ERROR );

// Path to the WordPress codebase to test.
define( 'ABSPATH', $root . '/' . $composer['extra']['wordpress-install-dir'] . '/' );

define( 'WP_CONTENT_DIR', $root . '/wp-content' );

// https://make.wordpress.org/core/2020/08/27/wordpress-environment-types/.
define( 'WP_ENVIRONMENT_TYPE', getenv( 'WP_ENVIRONMENT_TYPE' ) ?: 'test' );

// Path to the theme to test with.
define( 'WP_DEFAULT_THEME', 'default' );

// Test with WordPress debug mode (default).
define( 'WP_DEBUG', true );

// WARNING WARNING WARNING!
// These tests will DROP ALL TABLES in the database with the prefix named below.
// DO NOT use a production database or one that is shared with something else.
define( 'DB_NAME', getenv('WP_TESTS_DB_NAME') ?: 'wordpress_test' );
define( 'DB_USER', getenv('WP_TESTS_DB_USER') ?: 'root' );
define( 'DB_PASSWORD', getenv('WP_TESTS_DB_PASS') ?: '' );
define( 'DB_HOST', getenv('WP_TESTS_DB_HOST') ?: 'localhost' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// Test suite configuration.
define( 'PROJECT_NAME', 'boxuk-wp-skeleton' );
define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Box UK WordPress Tests' );
define( 'WP_PHP_BINARY', 'php' );

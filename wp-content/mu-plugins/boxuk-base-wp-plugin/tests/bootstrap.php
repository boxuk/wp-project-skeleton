<?php
/**
 * PHPUnit bootstrap file
 */

declare ( strict_types=1 );

// Composer autoloader must be loaded before WP_PHPUNIT__DIR will be available
use Symfony\Component\Dotenv\Dotenv;

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

if ( is_readable( __DIR__ . '/.env' ) ) {
	$dotenv = new Dotenv( true );
	$dotenv->load( __DIR__ . '/.env' );
}

$tests_dir = getenv('WP_PHPUNIT__DIR');

// Give access to tests_add_filter() function.
require_once $tests_dir . '/includes/functions.php';

tests_add_filter( 'muplugins_loaded', static function() {
	// test set up, plugin activation, etc.
	require dirname( __DIR__ ) . '/boxuk-base-wp-plugin.php';
} );

// Start up the WP testing environment.
require $tests_dir . '/includes/bootstrap.php';

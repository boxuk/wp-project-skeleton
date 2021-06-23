<?php

declare ( strict_types=1 );

use Symfony\Component\Dotenv\Dotenv;

$root_dir = dirname( __DIR__, 1 );
require_once $root_dir . '/wp-content/vendor/autoload.php';

if ( is_readable( __DIR__ . '/.env' ) ) {
	$dotenv = new Dotenv( true );
	$dotenv->load( __DIR__ . '/.env' );
}

$tests_dir = getenv('WP_PHPUNIT__DIR');

require_once $tests_dir . '/includes/functions.php';

// Loop over our mu-plugins and load each one.
tests_add_filter(
	'muplugins_loaded',
	function () use ( $root_dir ) {
		require_once $root_dir . '/wp-content/mu-plugins/zzz-mu-require.php';
	}
);

require $tests_dir . '/includes/bootstrap.php';

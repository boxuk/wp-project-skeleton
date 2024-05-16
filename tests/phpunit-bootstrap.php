<?php

declare ( strict_types=1 );

use Symfony\Component\Dotenv\Dotenv;

$root_dir = dirname( __DIR__, 1 );
require_once $root_dir . '/wp-content/vendor/autoload.php';

if ( is_readable( __DIR__ . '/.env' ) ) {
	$dotenv = new Dotenv();
	$dotenv->usePutenv( true );
	$dotenv->load( __DIR__ . '/.env' );
}

WP_Mock::bootstrap();

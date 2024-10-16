<?php
/**
 * Bootstrap the WordPress tests.
 */

declare ( strict_types=1 );

$root = dirname( __DIR__ );
$composer = json_decode( file_get_contents( $root . '/composer.json' ), true, 512, JSON_THROW_ON_ERROR );
$vendor = $composer['config']['vendor-dir'];
$tests_dir = getenv( 'WP_PHPUNIT__DIR' );

require_once $vendor . '/autoload.php';
require_once $tests_dir . '/includes/functions.php';
require_once $tests_dir . '/includes/bootstrap.php';

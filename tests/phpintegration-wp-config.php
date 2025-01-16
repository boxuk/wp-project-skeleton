<?php

declare ( strict_types=1 );

use Symfony\Component\Dotenv\Dotenv;

$root = dirname( __DIR__ );
$composer = json_decode( (string) file_get_contents( $root . '/composer.json' ), true, 512, JSON_THROW_ON_ERROR );
$vendor = $composer['config']['vendor-dir'];

require_once $vendor . '/autoload.php';

if ( is_readable( $root . '/.env' ) ) {
	$dotenv = new Dotenv();
	$dotenv->usePutenv( true );
	$dotenv->load( $root . '/.env' );
}

/* DB */
define( 'DB_NAME', $_ENV['DB_NAME'] ?? 'wordpress' );
define( 'DB_USER', $_ENV['DB_USER'] ?? 'root' );
define( 'DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? 'root' );
define( 'DB_COLLATE', $_ENV['DB_COLLATE'] ?? 'utf8mb4_unicode_ci' );
define( 'DB_HOST', $_ENV['DB_HOST'] ?? '127.0.0.1' );
$table_prefix = 'wptests_'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

/* WP */
define( 'WP_DEBUG', true );
define( 'SCRIPT_DEBUG', true );
define( 'ABSPATH', $root . '/wp/' );
define( 'WP_CONTENT_DIR', $root . '/wp-content' );
define( 'WP_DEFAULT_THEME', 'default' );
define( 'WP_ENVIRONMENT_TYPE', 'development' );
define( 'WP_TESTS_DOMAIN', 'tests.boxuk.com' );
define( 'WP_TESTS_EMAIL', 'tests@boxuk.com' );
define( 'WP_TESTS_TITLE', 'Test Blog' );
define( 'WP_PHP_BINARY', 'php' );
define( 'PROJECT_NAME', 'boxuk' );

if (file_exists($root . '/wp-content/vip-config/vip-config.php')) {
	require_once($root . '/wp-content/vip-config/vip-config.php');
}

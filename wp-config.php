<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * We use Symfony DotEnv component for loading environment variables and using to popoulate the config constants.
 *
 * @link https://symfony.com/doc/current/components/dotenv.html
 *
 * @package BoxUk
 */

declare ( strict_types=1 );

use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/wp-content/vendor/autoload.php';

/**
 * Load Environment Configuration
 */

$dotenv = new Dotenv();
$dotenv->load( __DIR__ . '/.env' );

/**
 * Site Configs
 */

define( 'PROJECT_NAME', $_ENV['PROJECT_NAME'] );
define( 'WP_HOME', $_ENV['WP_HOME'] );
define( 'WP_SITEURL', $_ENV['WP_HOME'] );

define( 'WP_CONTENT_URL', $_ENV['WP_HOME'] . '/wp-content' );
define( 'WP_CONTENT_DIR', __DIR__ . '/wp-content' );

define( 'WP_ENVIRONMENT_TYPE', $_ENV['WP_ENVIRONMENT_TYPE'] );

// Include core built in themes too.
// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited
if ( empty( $GLOBALS['wp_theme_directories'] ) ) {
	$GLOBALS['wp_theme_directories'] = [];
}

if ( file_exists( WP_CONTENT_DIR . '/themes' ) ) {
	$GLOBALS['wp_theme_directories'][] = WP_CONTENT_DIR . '/themes';
}

// The duplication below is deliberate.
$GLOBALS['wp_theme_directories'][] = __DIR__ . '/wp-content/themes';
$GLOBALS['wp_theme_directories'][] = __DIR__ . '/wp-content/themes';
// phpcs:enable WordPress.WP.GlobalVariablesOverride.Prohibited

/**
 * DB Configs
 */

define( 'DB_NAME', $_ENV['DB_NAME'] );
define( 'DB_USER', $_ENV['DB_USER'] );
define( 'DB_PASSWORD', $_ENV['DB_PASSWORD'] );
define( 'DB_COLLATE', $_ENV['DB_COLLATE'] );

define( 'DB_HOST', ! empty( $_ENV['DB_HOST'] ) ? $_ENV['DB_HOST'] : 'localhost' );
define( 'DB_CHARSET', ! empty( $_ENV['DB_CHARSET'] ) ? $_ENV['DB_CHARSET'] : 'utf8' );

$table_prefix = ! empty( $_ENV['DB_TABLE_PREFIX'] ) ? $_ENV['DB_TABLE_PREFIX'] : 'wp_'; //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 */
define( 'AUTH_KEY', $_ENV['AUTH_KEY'] );
define( 'SECURE_AUTH_KEY', $_ENV['SECURE_AUTH_KEY'] );
define( 'LOGGED_IN_KEY', $_ENV['LOGGED_IN_KEY'] );
define( 'NONCE_KEY', $_ENV['NONCE_KEY'] );
define( 'AUTH_SALT', $_ENV['AUTH_SALT'] );
define( 'SECURE_AUTH_SALT', $_ENV['SECURE_AUTH_SALT'] );
define( 'LOGGED_IN_SALT', $_ENV['LOGGED_IN_SALT'] );
define( 'NONCE_SALT', $_ENV['NONCE_SALT'] );

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', isset( $_ENV['WP_DEBUG'] ) ? filter_var( $_ENV['WP_DEBUG'], FILTER_VALIDATE_BOOLEAN ) : false );
define( 'WP_DEBUG_DISPLAY', isset( $_ENV['WP_DEBUG_DISPLAY'] ) ? filter_var( $_ENV['WP_DEBUG_DISPLAY'], FILTER_VALIDATE_BOOLEAN ) : false );
define( 'WP_DEBUG_LOG', isset( $_ENV['WP_DEBUG_LOG'] ) ? filter_var( $_ENV['WP_DEBUG_LOG'], FILTER_VALIDATE_BOOLEAN ) : false );
define( 'SCRIPT_DEBUG', isset( $_ENV['SCRIPT_DEBUG'] ) ? filter_var( $_ENV['SCRIPT_DEBUG'], FILTER_VALIDATE_BOOLEAN ) : false );

define( 'DISALLOW_FILE_EDIT', ! empty( $_ENV['DISALLOW_FILE_EDIT'] ) ? filter_var( $_ENV['DISALLOW_FILE_EDIT'], FILTER_VALIDATE_BOOLEAN ) : true );

// Enable cache by default.

define( 'WP_CACHE', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/wp/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

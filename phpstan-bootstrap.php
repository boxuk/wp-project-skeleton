<?php
/**
 * Define WordPress constants for PHPStan.
 *
 * @package PHPStan
 */

declare ( strict_types=1 );

/**
 * Load Environment Configuration
*/

define( 'PROJECT_NAME', 'PHPStan test' );
define( 'WP_CONTENT_DIR', __DIR__ . '/wp-content' );
define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
define( 'WP_LANG_DIR', WP_CONTENT_DIR . '/languages' );

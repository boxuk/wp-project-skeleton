<?php
/**
 * Plugin Name: Box UK WordPress Base Plugin
 * Plugin URI: https://boxuk.com
 * Description: A base plugin for WordPress that includes useful functions and classes for WordPress development.
 * Version:     0.1.0
 * Author:      Box UK
 * Author URI:  https://boxuk.com
 * Text Domain: boxuk
 * Domain Path: /languages
 *
 * @package BoxUK\Plugins\Base
 */

declare ( strict_types=1 );

use BoxUk\Plugins\Base\Cli\MakePotTwigCommand;
use BoxUk\Plugins\Base\Cli\ScaffoldCommand;

if ( wp_get_environment_type() === 'local' && class_exists( 'WP_CLI' ) ) {
	\WP_CLI::add_command( 'scaffold', ScaffoldCommand::class );
}

if (
	class_exists( \Twig\Environment::class ) &&
	class_exists( \Twig\Extensions\I18nExtension::class ) &&
	class_exists( \Timber\Loader::class ) &&
	class_exists( 'WP_CLI' )
) {
	\WP_CLI::add_command( 'i18n-twig make-pot', MakePotTwigCommand::class );
}

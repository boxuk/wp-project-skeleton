<?php
/**
 * Define here anything, that needs to be done across all mu-plugins.
 * This should fire before any mu-plugins so we are able to do things early here too.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

namespace BoxUk\Mu\Plugins;

use BoxUk\Plugins\Base\Event\HookDispatcher;
use Symfony\Component\DependencyInjection\Container;

// Load composer autoloader from the wp-content dir (if it isn't already loaded).
if ( file_exists( WP_CONTENT_DIR . '/vendor/autoload.php' ) ) {
	require_once WP_CONTENT_DIR . '/vendor/autoload.php';
}

if ( \defined( 'WP_INSTALLING' ) && true === WP_INSTALLING ) {
	return;
}

require_once __DIR__ . '/000-boxuk/BoxUkContainerFactory.php';
require_once __DIR__ . '/000-boxuk/BoxUkContainer.php';

/**
 * Retrieve or create our container.
 *
 * Example usage:
 * Register service: boxuk_container()->register( 'MyService\Class', MyService::class );
 * Retrieve service: boxuk_container()->get( 'MyService\Class' );
 *
 * @return Container
 */
function boxuk_container(): Container {
	static $container;

	if ( ! $container instanceof Container ) {
		$container = \BoxUkContainerFactory::instance()->get_container();
		do_action( 'boxuk_container_initialised' );
	}
	return $container;
}

/**
 * Things to do by default once the container has been initialised.
 */
add_action(
	'boxuk_container_initialised',
	static function () {
		if ( boxuk_container()->has( HookDispatcher::class ) ) {
			/**
			 * Hook Dispatcher
			 *
			 * @var HookDispatcher $dispatcher
			 */
			$dispatcher = boxuk_container()->get( HookDispatcher::class );
			$dispatcher->dispatch_all();
		}
	}
);

/**
 * Things to do by default once all plugins have been loaded.
 */
add_action(
	'plugins_loaded',
	static function () {
		$locale = get_locale();
		load_textdomain( PROJECT_NAME, WP_LANG_DIR . '/' . PROJECT_NAME . '-' . $locale . '.mo' );
	}
);

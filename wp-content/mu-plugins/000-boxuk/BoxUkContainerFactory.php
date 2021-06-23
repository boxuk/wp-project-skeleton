<?php
/**
 * Factory class that wraps BoxUkContainer. Should use this to retrieve an instance of the container
 * to ensure always using the global version of the container.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

/**
 * BoxUkContainerFactory class.
 */
class BoxUkContainerFactory {
	/**
	 * BoxUkContainer instance.
	 *
	 * @var BoxUkContainer
	 */
	private static $instance;

	/**
	 * Static factory to retrieve instance of the Box UK Container.
	 *
	 * @return BoxUkContainer BoxUkContainer instance.
	 */
	public static function instance(): BoxUkContainer {
		$is_debug = defined( 'WP_DEBUG' ) ? WP_DEBUG : false;

		if ( ! ( static::$instance instanceof BoxUkContainer ) ) {
			static::$instance = new BoxUkContainer( $is_debug );
		}

		return static::$instance;
	}
}

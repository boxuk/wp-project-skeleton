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
final class BoxUkContainerFactory {
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
		$environment_type = defined( 'WP_ENVIRONMENT_TYPE' ) ? WP_ENVIRONMENT_TYPE : 'production';

		if ( ! ( self::$instance instanceof BoxUkContainer ) ) {
			self::$instance = new BoxUkContainer( $is_debug, $environment_type );
		}

		return self::$instance;
	}
}

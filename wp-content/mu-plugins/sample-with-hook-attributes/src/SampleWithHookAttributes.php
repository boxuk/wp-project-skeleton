<?php
/**
 * SampleWithHookAttributes class. Hooks are defined on methods using attributes/annotations.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

namespace BoxUk\Mu\Plugins\SampleWithHookAttributes;

use BoxUk\WpHookAttributes\Hook\Annotations\Action;
use BoxUk\WpHookAttributes\Hook\Annotations\Filter;

/**
 * Example service class.
 */
class SampleWithHookAttributes {

	/**
	 * Sample method.
	 *
	 * @Action("init")
	 */
	public static function log_sample(): void {
		do_action( 'qm/info', 'sample from hook attributes' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
	}

	/**
	 * Append to content.
	 *
	 * @Filter("the_content")
	 *
	 * @param string $content The content to append to.
	 *
	 * @return string
	 */
	public static function on_the_content( string $content ): string {
		return $content . ' <p>sample from hook attributes</p>';
	}
}

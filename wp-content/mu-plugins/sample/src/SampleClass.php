<?php
/**
 * Sample class.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

namespace BoxUk\Mu\Plugins\Sample;

use Inpsyde\Wonolog\Channels;
use Psr\Log\LogLevel;

/**
 * Sample class.
 */
class SampleClass {
	/**
	 * SampleClass constructor.
	 */
	public function __construct() {
	}

	/**
	 * Sample method.
	 *
	 * @return string
	 */
	public function get_sample(): string {
		// An example of logging with wonolog.
		do_action(
			'wonolog.log', // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
			[
				'message' => 'SampleClass::get_sample() called.',
				'channel' => Channels::DEBUG,
				'level' => LogLevel::INFO,
				'context' => [],
			]
		);
		return 'sample';
	}
}

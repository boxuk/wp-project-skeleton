<?php
/**
 * Sample class with hooks.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

namespace BoxUk\Mu\Plugins\SampleWithHooks;

use BoxUk\Mu\Plugins\Sample\SampleClass;

/**
 * Sample class with hooks.
 */
class SampleWithHooksClass {
	/**
	 * Sample class.
	 *
	 * @var SampleClass
	 */
	private $sample;

	/**
	 * SampleWithHooksClass constructor.
	 *
	 * @param SampleClass $sample Instance of SampleClass.
	 */
	public function __construct( SampleClass $sample ) {
		$this->sample = $sample;
	}

	/**
	 * Sample method.
	 */
	public function log_sample(): void {
		do_action( 'qm/info', $this->sample->get_sample() ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
	}

	/**
	 * Return a sample string.
	 *
	 * @return string
	 */
	public function get_sample(): string {
		return $this->sample->get_sample();
	}

	/**
	 * Sample echoing method.
	 */
	public function echo_sample(): void {
		echo esc_html( $this->sample->get_sample() );
	}
}

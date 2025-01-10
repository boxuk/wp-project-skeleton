<?php
/**
 * Plugin Name: Box UK WordPress Project Skeleton Sample Plugin
 *
 * @package BoxUk
 */

declare( strict_types=1 );

use BoxUk\Mu\Plugins\Sample\SampleClass;
use BoxUk\WpFeatureFlags\Flag;
use BoxUk\WpFeatureFlags\FlagRegister;

use function BoxUk\Mu\Plugins\boxuk_container;

add_action(
	'plugins_loaded',
	static function () {

		$flag_register = FlagRegister::instance();

		$flag = new Flag(
			'sample-flag',
			'Example',
			'An example flag to demonstrate the use of feature flags.',
		);

		$flag_register->register_flag( $flag );
	}
);

add_action(
	'init',
	function () {
		$flag_register = FlagRegister::instance();
		$flag = $flag_register->get_flag( 'sample-flag' );

		if ( $flag && $flag->is_enabled() ) {
			/**
			 * IDE Hint.
			 *
			 * @var SampleClass $sample
			 */
			$sample = boxuk_container()->get( 'BoxUk\Mu\Plugins\Sample\SampleClass' );
			$sample_text = $sample->get_sample();
			do_action( 'qm/info', $sample_text . ' if feature enabled.' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}
	}
);

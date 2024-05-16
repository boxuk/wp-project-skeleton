<?php
/**
 * Plugin Name: Box UK WordPress Project Skeleton Sample Plugin
 *
 * @package BoxUk
 */

declare( strict_types=1 );

use BoxUk\Mu\Plugins\Sample\SampleClass;
use function BoxUk\Mu\Plugins\boxuk_container;

/**
 * IDE Hint.
 *
 * @var SampleClass $sample
 */
$sample = boxuk_container()->get( 'BoxUk\Mu\Plugins\Sample\SampleClass' );
$sample_text = $sample->get_sample();

// As we're logging in Query Monitor we need to make sure it's been loaded before we can log.
add_action(
	'plugins_loaded',
	static function () use ( $sample_text ) {
		if ( flagpole_flag_enabled( 'log_sample_text' ) ) {
			do_action( 'qm/info', $sample_text . ' if feature enabled.' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}
	}
);

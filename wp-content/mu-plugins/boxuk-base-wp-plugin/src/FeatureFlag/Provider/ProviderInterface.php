<?php
/**
 * An interface for feature flag providers.
 *
 * @package BoxUk\Plugins\Base
 */

declare ( strict_types=1 );

namespace BoxUk\Plugins\Base\FeatureFlag\Provider;

/**
 * ProviderInterface interface.
 *
 * @package BoxUk\Plugins\Base
 */
interface ProviderInterface {
	/**
	 * Top level key for feature flags, i.e. first level of yaml or array.
	 */
	public const FEATURE_FLAGS_TOP_LEVEL_KEY = 'feature_flags';

	/**
	 * Register flags with provider from an array of flags.
	 *
	 * @param array $feature_flags Array of flags.
	 */
	public function register_flags( array $feature_flags ): void;

	/**
	 * Checker whether a flag is enabled or not.
	 *
	 * @param string $flag_name Name of the flag to check.
	 *
	 * @return bool
	 */
	public function is_enabled( string $flag_name ): bool;
}

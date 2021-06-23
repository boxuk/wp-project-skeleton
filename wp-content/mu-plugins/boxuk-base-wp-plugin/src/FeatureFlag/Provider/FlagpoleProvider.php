<?php
/**
 * A flagpole provider.
 *
 * @package BoxUk\Plugins\Base
 */

declare ( strict_types=1 );

namespace BoxUk\Plugins\Base\FeatureFlag\Provider;

/**
 * FlagpoleProvider class
 *
 * @package BoxUk\Plugins\Base
 */
final class FlagpoleProvider implements ProviderInterface {
	use FeatureFlagProviderNormalisationTrait;

	/**
	 * Register flags with flagpole.
	 *
	 * @param array $feature_flags Array of flags.
	 */
	public function register_flags( array $feature_flags ): void {
		$feature_flags = $this->normalise_feature_flag_array( $feature_flags );

		\flagpole_register_flag( $feature_flags );
	}

	/**
	 * Is a flag enabled or not?
	 *
	 * @param string $flag_name Name of flag to check.
	 *
	 * @return bool
	 */
	public function is_enabled( string $flag_name ): bool {
		return \flagpole_flag_enabled( $flag_name );
	}
}

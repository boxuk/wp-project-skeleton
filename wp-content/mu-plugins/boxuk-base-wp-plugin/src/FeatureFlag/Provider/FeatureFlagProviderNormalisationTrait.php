<?php
/**
 * A trait to normalise feature flag arrays.
 *
 * @package BoxUk\Plugins\Base
 */

declare ( strict_types=1 );

namespace BoxUk\Plugins\Base\FeatureFlag\Provider;

/**
 * Trait FeatureFlagProviderNormalisationTrait
 *
 * @package BoxUk\Plugins\Base
 */
trait FeatureFlagProviderNormalisationTrait {
	/**
	 * Normalise by removing top level key and assigning key to payload of the array.
	 *
	 * @param array $feature_flags Feature flags to normalise.
	 *
	 * @return array
	 */
	public function normalise_feature_flag_array( array $feature_flags ): array {
		if ( isset( $feature_flags[ ProviderInterface::FEATURE_FLAGS_TOP_LEVEL_KEY ] ) ) {
			$feature_flags = (array) $feature_flags[ ProviderInterface::FEATURE_FLAGS_TOP_LEVEL_KEY ];
		}

		// Make the key of the array the key field expected by flagpole.
		$feature_flags = array_map(
			static function ( array $flag, string $key ): array {
				$flag['key'] = $key;

				return $flag;
			},
			$feature_flags,
			array_keys( $feature_flags )
		);

		return $feature_flags;
	}
}

<?php
/**
 * An in memory provider, useful for testing.
 *
 * @package BoxUk\Plugins\Base
 */

declare ( strict_types=1 );

namespace BoxUk\Plugins\Base\FeatureFlag\Provider;

/**
 * InMemoryProvider class.
 *
 * @package BoxUk\Plugins\Base
 */
final class InMemoryProvider implements ProviderInterface {
	use FeatureFlagProviderNormalisationTrait;

	/**
	 * Array of flags.
	 *
	 * @var array|null $flags Array of flags.
	 */
	private $flags;

	/**
	 * Register flags in memory.
	 *
	 * @param array $feature_flags Array of flags.
	 */
	public function register_flags( array $feature_flags ): void {
		$feature_flags = $this->normalise_feature_flag_array( $feature_flags );
		$this->flags = $feature_flags;
	}

	/**
	 * Check whether the flag is enabled.
	 *
	 * @param string $flag_name Name of the flag to check.
	 *
	 * @return bool
	 */
	public function is_enabled( string $flag_name ): bool {
		$flag_index = array_search( $flag_name, array_column( $this->flags, 'key' ), true );

		if ( $flag_index === false ) {
			return false;
		}

		// As we don't have a way of 'publishing' in memory flags, a flag is only enabled if it is enforced.
		return $this->flags[ $flag_index ]['enforced'] ?? false;
	}

	/**
	 * Get the flags from memory.
	 *
	 * @return array
	 */
	public function get_flags(): array {
		return $this->flags;
	}
}

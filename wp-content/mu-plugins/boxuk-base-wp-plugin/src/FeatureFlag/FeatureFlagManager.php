<?php
/**
 * Manager for feature flags.
 *
 * @package BoxUk\Plugins\Base
 */

declare ( strict_types=1 );

namespace BoxUk\Plugins\Base\FeatureFlag;

use BoxUk\Plugins\Base\FeatureFlag\Provider\ProviderInterface;
use Symfony\Component\Yaml\Parser;

/**
 * FeatureFlagManager class.
 *
 * @package BoxUk\Plugins\Base
 */
class FeatureFlagManager {
	/**
	 * Parser for yaml.
	 *
	 * @var Parser
	 */
	private $yaml_parser;

	/**
	 * Provider for registering flags.
	 *
	 * @var ProviderInterface
	 */
	private $provider;

	/**
	 * FeatureFlagManager constructor.
	 *
	 * @param Parser            $parser Parser to use to parse yaml.
	 * @param ProviderInterface $provider Provider for registering flags.
	 */
	public function __construct( Parser $parser, ProviderInterface $provider ) {
		$this->yaml_parser = $parser;
		$this->provider = $provider;
	}

	/**
	 * Register feature flags from an array.
	 *
	 * @param array $feature_flags Array of feature flags.
	 */
	public function register_from_array( array $feature_flags ): void {
		$this->provider->register_flags( $feature_flags );
	}

	/**
	 * Register feature flags from a yaml file.
	 *
	 * @param string $filename Filename of the yaml to use.
	 */
	public function register_from_yaml_file( string $filename ): void {
		$feature_flags = $this->yaml_parser->parseFile( $filename );
		$this->provider->register_flags( $feature_flags );
	}

	/**
	 * Check if a flag is enabled.
	 *
	 * @param string $flag_name Name of the flag to check.
	 *
	 * @return bool
	 */
	public function is_enabled( string $flag_name ): bool {
		return $this->provider->is_enabled( $flag_name );
	}
}

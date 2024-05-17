<?php
/**
 * Class in charge of building, initialising and managing the container.
 * This needs to be loaded early, so deliberately doesn't have a namespace and is not autoloaded
 * by PSR-4.
 *
 * If you want the safety of a singleton for using this, then look at BoxUkContainerFactory.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

use BoxUk\BoxUkCachedContainer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * BoxUk Container class.
 *
 * @package BoxUk
 */
class BoxUkContainer {
	private const MU_PLUGINS = WPMU_PLUGIN_DIR;
	private const PLUGINS = WP_PLUGIN_DIR;
	private const CACHED_CONTAINER_FILE_NAME = __DIR__ . '/cache/container.php';
	private const CACHED_CONTAINER_CLASS_NAME = 'BoxUkCachedContainer';
	private const CACHED_CONTAINER_NAMESPACE = 'BoxUk';

	/**
	 * Our container instance.
	 *
	 * @var Container
	 */
	private $container;

	/**
	 * ContainerBuilder instance.
	 *
	 * @var ?ContainerBuilder
	 */
	private $container_builder;

	/**
	 * Are we in debug mode (i.e. non prod).
	 *
	 * @var bool
	 */
	private $is_debug;

	/**
	 * Type of environment (i.e. production or local).
	 *
	 * @var string
	 */
	private $environment_type;

	/**
	 * BoxUk_Container constructor.
	 *
	 * @param bool   $is_debug Whether debug mode should be enabled or not.
	 * @param string $environment_type Which environment type we are in (i.e. production or local).
	 */
	public function __construct( bool $is_debug = false, string $environment_type = 'production' ) {
		$this->is_debug = $is_debug;
		$this->environment_type = $environment_type;
		$this->init_container();
	}

	/**
	 * Get the container, either from memory or cache.
	 *
	 * @return Container
	 */
	public function get_container(): Container {
		return $this->container;
	}

	/**
	 * Build a container.
	 *
	 * @return ContainerBuilder
	 */
	private function build_container(): ContainerBuilder {
		if ( ! $this->container_builder ) {
			$this->container_builder = new ContainerBuilder();
		}

		return $this->container_builder;
	}

	/**
	 * Initialise container based of service definitions configured per mu-plugin.
	 * Cache the container if in  non debug mode.
	 */
	private function init_container(): void {
		$cache_path = self::CACHED_CONTAINER_FILE_NAME;
		if ( ! $this->is_debug && file_exists( $cache_path ) ) {
			require_once $cache_path; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.NotAbsolutePath, WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			$this->container = new BoxUkCachedContainer();
			return;
		}

		$container_builder = $this->build_container();
		$this->load_config( $container_builder );
		$this->load_mu_plugins( $container_builder );
		$this->load_boxuk_plugins( $container_builder );

		$container_builder->compile( $this->is_debug );
		$this->container = $container_builder;

		if ( ! $this->is_debug ) {
			$this->cache_container( $container_builder );
		}
	}

	/**
	 * Load parameters depending on environment type. This will allow us to use different parameters in production compared to local.
	 *
	 * @see https://make.wordpress.org/core/2020/08/27/wordpress-environment-types/ for list of valid environment types.
	 *
	 * @param ContainerBuilder $container_builder Instance ofo ContainerBuilder.
	 *
	 * @return void
	 * @throws InvalidArgumentException If the environment type is invalid and thus a config file cannot be found.
	 */
	private function load_config( ContainerBuilder $container_builder ): void {
		if ( ! file_exists( __DIR__ . '/config/config_' . $this->environment_type . '.yaml' ) ) {
			throw new InvalidArgumentException( 'Could not find config file for environment type: ' . $this->environment_type ); // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
		}

		$loader = new YamlFileLoader( $container_builder, new FileLocator( __DIR__ . '/config' ) );
		$loader->load( 'config_' . $this->environment_type . '.yaml' );
	}

	/**
	 * Load services from mu-plugins using the services.yaml file within a config directory.
	 *
	 * @param ContainerBuilder $container_builder Instance of ContainerBuilder.
	 */
	private function load_mu_plugins( ContainerBuilder $container_builder ): void {
		// Go through plugins and register any services it may have defined.
		$configs = glob( self::MU_PLUGINS . '/**/config' );
		if ( ! $configs ) {
			return;
		}
		$config_dirs = array_filter( $configs, 'is_dir' );
		$loader = new YamlFileLoader( $container_builder, new FileLocator( $config_dirs ) );

		array_map(
			static function ( string $dir ) use ( $loader ): void {
				$loader->load( $dir . '/services.yaml' );
			},
			$config_dirs
		);
	}

	/**
	 * This will find plugins with a DependencyInjection directory within src. It then goes through this looking
	 * for files with the pattern BoxUk*Extension and then register it with the container if it finds one.
	 *
	 * This means for a plugin to register with the main container it needs to have the following:
	 * - src/DependencyInjection directory
	 * - BoxUkMyPluginExtension.php class within this directory
	 * - The extension must implement the ExtensionInterface interface
	 *
	 * @param ContainerBuilder $container_builder Instance of ContainerBuilder.
	 */
	private function load_boxuk_plugins( ContainerBuilder $container_builder ): void {
		$plugin_di = glob( self::PLUGINS . '/**/src/DependencyInjection' );
		$mu_plugin_di = glob( self::MU_PLUGINS . '/**/src/DependencyInjection' );

		if ( ! $plugin_di ) {
			$plugin_di = [];
		}

		if ( ! $mu_plugin_di ) {
			$mu_plugin_di = [];
		}


		$plugins_extensions_dirs = array_filter( $plugin_di, 'is_dir' );
		$mu_plugins_extensions = array_filter( $mu_plugin_di, 'is_dir' );

		array_map(
			static function ( string $extension_dir ) use ( $container_builder ): void {
				$extension_glob = glob( $extension_dir . '/BoxUk*Extension.php' );
				if ( ! $extension_glob ) {
					return;
				}

				$extension = array_filter( $extension_glob, 'is_file' )[0] ?? '';

				if ( '' === $extension || ! file_exists( $extension ) ) {
					return;
				}

				include_once $extension; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable

				$declared_classes = get_declared_classes();
				$extension_class = end( $declared_classes );

				if ( \in_array( ExtensionInterface::class, class_implements( $extension_class ?: '' ) ?: [], true ) ) { // phpcs:ignore Universal.Operators.DisallowShortTernary.Found
					$extension_instance = new $extension_class(); // phpcs:ignore NeutronStandard.Functions.VariableFunctions.VariableFunction
					$container_builder->registerExtension( $extension_instance );
					$container_builder->loadFromExtension( $extension_instance->getAlias() );
				}

				// Handle compiler passes.
				$compiler_passes = array_filter( glob( $extension_dir . '/Compiler/*CompilerPass.php' ) ?: [], 'is_file' ); // phpcs:ignore Universal.Operators.DisallowShortTernary.Found
				foreach ( $compiler_passes as $compiler_pass ) {
					include_once $compiler_pass; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable

					$declared_classes = get_declared_classes();
					$compiler_class = end( $declared_classes );

					if ( \in_array( CompilerPassInterface::class, class_implements( $compiler_class ?: '' ) ?: [], true ) ) { // phpcs:ignore Universal.Operators.DisallowShortTernary.Found
						$pass_instance = new $compiler_class(); // phpcs:ignore NeutronStandard.Functions.VariableFunctions.VariableFunction
						$container_builder->addCompilerPass( $pass_instance );
					}
				}
			},
			array_merge( $plugins_extensions_dirs, $mu_plugins_extensions )
		);
	}

	/**
	 * Cache container to disk.
	 *
	 * @param ContainerBuilder $container_builder Instance of ContainerBuilder.
	 */
	private function cache_container( ContainerBuilder $container_builder ): void {
		if ( ! file_exists( self::CACHED_CONTAINER_FILE_NAME ) ) {
			$dumper = new PhpDumper( $container_builder );
			file_put_contents(
				self::CACHED_CONTAINER_FILE_NAME,
				$dumper->dump(
					[
						'class' => self::CACHED_CONTAINER_CLASS_NAME,
						'namespace' => self::CACHED_CONTAINER_NAMESPACE,
					]
				)
			);
		}

		if ( file_exists( self::CACHED_CONTAINER_FILE_NAME ) ) {
			// phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.NotAbsolutePath
			require_once self::CACHED_CONTAINER_FILE_NAME;
			$this->container = new BoxUkCachedContainer();
		}
	}
}

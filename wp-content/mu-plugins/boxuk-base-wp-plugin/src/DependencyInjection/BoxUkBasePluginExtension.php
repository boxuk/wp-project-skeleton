<?php
/**
 * Extension that will get registered with the container when using a projects based off the Box UK WordPress Skeleton.
 *
 * @package BoxUk\Plugins\Base
 */

declare ( strict_types=1 );

namespace BoxUk\Plugins\Base\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * BoxUkBasePluginExtension Class.
 *
 * @package BoxUk\Plugins\Base
 */
class BoxUkBasePluginExtension implements ExtensionInterface {
	/**
	 * Load our DI config in our container.
	 *
	 * @param array            $configs Configs.
	 * @param ContainerBuilder $container Copy of the container which will be merged with main container.
	 *
	 * @throws \Exception If contents of the yaml is bad.
	 */
	public function load( array $configs, ContainerBuilder $container ) {
		$loader = new YamlFileLoader(
			$container,
			new FileLocator( __DIR__ . '/../../config' )
		);

		$loader->load( 'services.yaml' );
	}

	/**
	 * Return namespace.
	 *
	 * @return string
	 */
	public function getNamespace(): string {
		return 'http://boxuk.com/schema/dic/' . $this->getAlias();
	}

	/**
	 * Return Xsd validation base path if using xml configuration.
	 *
	 * @return bool
	 */
	public function getXsdValidationBasePath(): bool {
		return false;
	}

	/**
	 * Return basic alias for this plugin.
	 *
	 * @return string
	 */
	public function getAlias(): string {
		return 'boxuk_base_plugin';
	}
}

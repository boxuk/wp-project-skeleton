<?php
/**
 * Plugin Name: Sample With Hook Attributes
 * Description: This plugin acts as an example of how you can use hook attributes to define hooks on a service class.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

use BoxUk\Mu\Plugins\SampleWithHookAttributes\SampleWithHookAttributesClass;
use Cache\Adapter\Memcache\MemcacheCachePool;
use Psr\Cache\CacheItemPoolInterface;

// In a project where you want to use this across mu-plugins you'd probably want to put this in `000-boxuk-init.php`.
add_filter(
	'wp_hook_attributes_registered_namespaces',
	function(): array {
		return [
			'BoxUk\Mu\Plugins',
		];
	}
);

// In a project where you want to use this across mu-plugins you'd probably want to put this in `000-boxuk-init.php`.
if ( wp_get_environment_type() === 'production' ) {
	add_filter(
		'wp_hook_attributes_cache_adapter',
		function ( CacheItemPoolInterface $cache_adapter ): CacheItemPoolInterface {
			global $wp_object_cache;
			if ( ! class_exists( \Memcache::class ) || ! class_exists( MemcacheCachePool::class ) ) {
				return $cache_adapter;
			}

			if ( $wp_object_cache->get_mc( 'default' ) instanceof \Memcache ) {
				$client = $wp_object_cache->get_mc( 'default' );

				return new MemcacheCachePool( $client );
			}

			return $cache_adapter;
		}
	);
}

// As we're not instantiating our service class at any point we need to register it manually.
add_filter(
	'wp_hook_attributes_registered_classes',
	function( array $registered_classes ): array {
		return array_merge(
			$registered_classes,
			[
				SampleWithHookAttributesClass::class,
			]
		);
	}
);

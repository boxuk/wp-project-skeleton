<?php
/**
 * Test services can be instantiated successfully.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Tests;

use Symfony\Component\DependencyInjection\ContainerInterface;
use WP_UnitTestCase;

use function BoxUk\Mu\Plugins\boxuk_container;

/**
 * Test services.
 */
class ServicesTest extends WP_UnitTestCase {

	/**
	 * Test services can be instantiated.
	 * @dataProvider get_service_ids
	 * @dataProvider get_legacy_service_ids
	 *
	 * @param string $service_id_to_check Service ID to test.
	 */
	public function test_services_can_be_instantiated( string $service_id_to_check ): void {
		$container = boxuk_container();
		$service_ids = $container->getServiceIds();
		$services = array_filter( $service_ids, static function ( $service_id ) use( $service_id_to_check ) {
			return 0 === strpos( $service_id, $service_id_to_check );
		});

		self::assertContains( $service_id_to_check, $container->getServiceIds() );

		foreach ($services as $id) {
			self::assertNotNull(
				$container->get( $id, ContainerInterface::NULL_ON_INVALID_REFERENCE ),
				$id . ' could not be instantiated from the container.'
			);
		}
	}

	/**
	 * Return service ids to test against.
	 *
	 * @return \string[][]
	 */
	public function get_service_ids(): array {
		return [
			'QueryRepository' => [ 'BoxUk\Plugins\Base\Database\QueryRepository' ],
			'PostRepository' => [ 'BoxUk\Plugins\Base\Database\PostRepository' ],
			'FeatureFlagManager' => [ 'BoxUk\Plugins\Base\FeatureFlag\FeatureFlagManager' ],
			'EnableGutenberg' => [ 'BoxUk\Plugins\Base\Gutenberg\EnableGutenberg' ],
		];
	}

	/**
	 * Return legacy service ids to test against.
	 *
	 * @return \string[][]
	 */
	public function get_legacy_service_ids(): array {
		return [
			'query_repository' => [ 'base_plugin.query_repository' ],
			'post_repository' => [ 'base_plugin.post_repository' ],
			'feature_flag_manager' => [ 'base_plugin.feature_flag_manager' ],
			'enable_gutenberg' => [ 'base_plugin.enable_gutenberg' ],
		];
	}
}

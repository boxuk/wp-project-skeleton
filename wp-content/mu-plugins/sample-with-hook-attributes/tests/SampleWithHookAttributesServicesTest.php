<?php
/**
 * Test sample with hook attributes services.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

namespace BoxUk\Mu\Plugins\SampleWithHookAttributes\Tests;

use Symfony\Component\DependencyInjection\ContainerInterface;
use WP_UnitTestCase;

use function BoxUk\Mu\Plugins\boxuk_container;
use BoxUk\Mu\Plugins\SampleWithHookAttributes\SampleWithHookAttributes;

/**
 * Test services.
 */
class SampleWithHookAttributesServicesTest extends WP_UnitTestCase {

	private const SERVICE_NAME = SampleWithHookAttributes::class;

	/**
	 * Test services can be instantiated.
	 */
	public function test_services_can_be_instantiated(): void {
		$container = boxuk_container();
		$services = array_filter($container->getServiceIds(), static function ( $service_id ) {
			return str_starts_with( $service_id, self::SERVICE_NAME );
		});

		foreach ($services as $id) {
			self::assertNotNull(
				$container->get($id, ContainerInterface::NULL_ON_INVALID_REFERENCE),
				$id . ' could not be instantiated from the container.'
			);
		}
	}
}

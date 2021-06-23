<?php
/**
 * Test services can be instantiated successfully.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

namespace BoxUk\Mu\Plugins\Sample\Tests;

use Symfony\Component\DependencyInjection\ContainerInterface;
use WP_UnitTestCase;

use function BoxUk\Mu\Plugins\boxuk_container;

/**
 * Test services.
 */
class ServicesTest extends WP_UnitTestCase {

	private const SERVICE_NAME = 'BoxUk\Mu\Plugins\Sample\SampleClass';

	/**
	 * Test services can be instantiated.
	 */
	public function test_services_can_be_instantiated(): void {
		$container = boxuk_container();
		$services = array_filter( $container->getServiceIds(), static function ( $service_id ) {
			return 0 === strpos( $service_id, self::SERVICE_NAME );
		});

		foreach ($services as $id) {
			self::assertNotNull(
				$container->get( $id, ContainerInterface::NULL_ON_INVALID_REFERENCE ),
				$id . ' could not be instantiated from the container.'
			);
		}
	}
}

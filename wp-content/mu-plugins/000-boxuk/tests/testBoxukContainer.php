<?php
/**
 * Class Test_BoxUk_Container
 *
 * @package BoxUK
 */

declare( strict_types=1 );

use Symfony\Component\DependencyInjection\Container;
use WP_Mock\Tools\TestCase;

/**
 * BoxUK Container test case.
 */
class Test_BoxUk_Container extends TestCase {
	public function tearDown(): void {
		parent::tearDown();
		$cached_container = __DIR__ . '/../cache/container.php';
		if ( file_exists( $cached_container ) ) {
			unlink( $cached_container );
		}
	}

	public function test_get_container_returns_cached_container_instance_in_non_debug_mode(): void {
		$container = new BoxUkContainer( false );
		self::assertEquals( 'BoxUk\\BoxUkCachedContainer', get_class( $container->get_container() ) );
	}

	public function test_container_cache_is_not_rewritten_every_load_of_container(): void {
		$cached_container = __DIR__ . '/../cache/container.php';

		// Delete any existing cache before we run our test.
		if ( file_exists( $cached_container ) ) {
			unlink( $cached_container );
		}

		$container = new BoxUkContainer( false );
		$container_modified_time = filemtime( $cached_container );

		$container_two = new BoxUkContainer( false );
		$container_two_modified_time = filemtime( $cached_container );

		self::assertEquals( $container_modified_time, $container_two_modified_time );
	}

	public function test_get_container_returns_non_cached_container_instance_debug_mode(): void {
		$container = new BoxUkContainer( true );
		self::assertNotEquals( 'BoxUk\\BoxUkCachedContainer', get_class( $container->get_container() ) );
		self::assertInstanceOf( Container::class, $container->get_container() );
	}
}

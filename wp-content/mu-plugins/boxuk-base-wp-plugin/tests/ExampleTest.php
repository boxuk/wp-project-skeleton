<?php

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Tests;

use ReflectionClass;
use WP_UnitTestCase;

class ExampleTest extends WP_UnitTestCase {

	public function test_wordpress_and_plugin_are_loaded(): void {
		self::assertTrue( function_exists( 'do_action' ) );
	}
}

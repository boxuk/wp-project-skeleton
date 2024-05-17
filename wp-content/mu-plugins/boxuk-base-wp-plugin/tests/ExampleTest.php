<?php

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Tests;

use WP_Mock\Tools\TestCase;

class ExampleTest extends TestCase {

	public function test_wordpress_and_plugin_are_loaded(): void {
		self::assertTrue( function_exists( 'do_action' ) );
	}
}

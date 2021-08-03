<?php

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Tests\Security;

use BoxUk\Plugins\Base\Security\Security;
use WP_UnitTestCase;

/**
 * Security test case.
 */
class SecurityTest extends WP_UnitTestCase {

	/**
	 * System under test.
	 *
	 * @var Security|null
	 */
	private $sut;

	/**
	 * Test set up
	 */
	public function setUp() {
		parent::setUp();

		$this->sut = new Security();
	}

	/**
	 * Tests user endpoints are filtered out.
	 */
	public function test_filter_out_user_endpoints_removes_user_endpoints_from_default_list(): void {
		$default_endpoints = [
			'/' => [
				'callback' => fn() => null,
				'methods'  => 'GET',
			],
			'/wp/v2/users' => [
				'callback' => fn() => null,
				'methods'  => 'GET',
			],
			'/wp/v2/users/(?P<id>[\d]+)' => [
				'callback' => fn() => null,
				'methods'  => 'GET',
			],
		];

		self::assertNotContains(
			$default_endpoints['/wp/v2/users'],
			$this->sut::filter_out_user_endpoints( $default_endpoints )
		);

		self::assertNotContains(
			$default_endpoints['/wp/v2/users/(?P<id>[\d]+)'],
			$this->sut::filter_out_user_endpoints( $default_endpoints )
		);
	}

	public function test_prevent_author_enum_returns_redirect_if_author_query_not_set(): void {
		$redirect = '/author/admin';

		self::assertEquals( $redirect, $this->sut::prevent_author_enum( $redirect ) );
	}

	public function test_prevent_author_enum_404s_author_redirects(): void {
		$redirect = '/author/admin';

		global $wp_query;
		$wp_query->set( 'author', 1 );

		// Note order matter in the following as the method must have been called for 404 to be set.
		self::assertNull( $this->sut::prevent_author_enum( $redirect ) );
		self::assertTrue( $wp_query->is_404() );
	}

	/**
	 * Tests `prevent_clickjacking` sets X-Frame header if it does not exist.
	 */
	public function test_prevent_clickjacking_sets_header_if_not_set(): void {
		$headers = [
			'TEST_HEADER' => 'not preventing clickjacking',
		];

		self::assertFalse( isset( $headers[ Security::HTTP_X_FRAME_OPTIONS ] ) );

		$headers = $this->sut::prevent_clickjacking( $headers );

		self::assertTrue( isset( $headers[ Security::HTTP_X_FRAME_OPTIONS ] ) );
		self::assertEquals( $headers[ Security::HTTP_X_FRAME_OPTIONS ], Security::HTTP_X_FRAME_OPTIONS_SAMEORIGIN );
		self::assertCount( 2, $headers );
	}

	/**
	 * Tests `prevent_clickjacking` does not override existing X-Frame header.
	 */
	public function test_prevent_clickjacking_does_not_override_existing_value(): void {
		$not_preventing_clickjacking = 'NOT' . Security::HTTP_X_FRAME_OPTIONS_SAMEORIGIN;

		$headers = [
			'TEST_HEADER' => 'not preventing clickjacking',
			Security::HTTP_X_FRAME_OPTIONS => $not_preventing_clickjacking,
		];

		self::assertTrue( isset( $headers[ Security::HTTP_X_FRAME_OPTIONS ] ) );

		$headers = $this->sut::prevent_clickjacking( $headers );

		self::assertTrue( isset( $headers[ Security::HTTP_X_FRAME_OPTIONS ] ) );
		self::assertEquals( $headers[ Security::HTTP_X_FRAME_OPTIONS ], $not_preventing_clickjacking );
		self::assertCount( 2, $headers );
	}
}

<?php

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Tests\Database;

use BoxUk\Plugins\Base\Database\PostRepository;
use WP_UnitTestCase;

class PostRepositoryTest extends WP_UnitTestCase {

	private $post_repository;

	public function setUp(): void {
		parent::setUp();
		$this->post_repository = new PostRepository();
	}

	public function test_find_by_id_returns_expected_post(): void {
		$post_id = $this->factory->post->create();

		$posts = $this->post_repository->find_by_id( $post_id );

		self::assertNotEmpty( $posts->posts );
		self::assertEquals( $post_id, $posts->posts[0]->ID );
	}

	public function test_find_by_type_returns_expected_post(): void {
		$post_id = $this->factory->post->create( [ 'post_type' => 'page' ] );

		$posts = $this->post_repository->find_by_type( 'page' );

		self::assertNotEmpty( $posts->posts );
		self::assertEquals( $post_id, $posts->posts[0]->ID );
		self::assertEquals( 'page', $posts->posts[0]->post_type );
	}
}

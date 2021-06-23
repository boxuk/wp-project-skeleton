<?php

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Tests\Database;

use BoxUk\Plugins\Base\Database\QueryRepository;
use WP_UnitTestCase;

class QueryRepositoryTest extends WP_UnitTestCase {

	private $query_repository;

	public function setUp(): void {
		parent::setUp();
		$this->query_repository = new QueryRepository();
	}

	public function test_find_by_post_ids_for_single_id_returns_expected_post(): void {
		$post_id = $this->factory->post->create();

		$posts = $this->query_repository->find_by_post_ids( [ $post_id ] );

		self::assertNotEmpty( $posts->posts );
		self::assertEquals( $post_id, $posts->posts[0]->ID );
	}

	public function test_find_by_post_ids_for_multiple_ids_returns_expected_posts(): void {
		$post_id_one = $this->factory->post->create();
		$post_id_two = $this->factory->post->create();
		$post_id_three = $this->factory->post->create();

		$posts = $this->query_repository->find_by_post_ids( [ $post_id_one, $post_id_two, $post_id_three ] );

		self::assertNotEmpty( $posts->posts );
		self::assertEquals( $post_id_one, $posts->posts[0]->ID );
		self::assertEquals( $post_id_two, $posts->posts[1]->ID );
		self::assertEquals( $post_id_three, $posts->posts[2]->ID );
	}

	public function test_find_by_tag_returns_expected_post(): void {
		$tag = 'foo';

		$this->factory->tag->create( [ 'name' => $tag ] );
		$post_id = $this->factory->post->create( [ 'tags_input' => [ $tag ] ] );

		$posts = $this->query_repository->find_by_tag( $tag );

		self::assertNotEmpty( $posts->posts );
		self::assertEquals( $post_id, $posts->posts[0]->ID );

		$tags = wp_get_post_terms( $post_id );

		self::assertNotEmpty( $tags );
		self::assertEquals( $tag, $tags[0]->name );
	}

	public function test_find_all_by_tag_returns_expected_posts(): void {
		$tag = 'bar';

		$this->factory->tag->create( [ 'name' => $tag ] );
		$post_id = $this->factory->post->create( [ 'tags_input' => [ $tag ] ] );

		$posts = $this->query_repository->find_all_by_tag( [ $tag ] );

		self::assertNotEmpty( $posts->posts );
		self::assertEquals( $post_id, $posts->posts[0]->ID );

		$tags = wp_get_post_terms( $post_id );

		self::assertNotEmpty( $tags );
		self::assertEquals( $tag, $tags[0]->name );
	}
}

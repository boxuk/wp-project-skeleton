<?php

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Tests\Gutenberg;

use BoxUk\Plugins\Base\Gutenberg\EnableGutenberg;
use WP_UnitTestCase;

/**
 * Enable Gutenberg test case.
 */
class EnableGutenbergTest extends WP_UnitTestCase {

	/**
	 * System under test.
	 *
	 * @var EnableGutenberg
	 */
	private $sut;

	/**
	 * Test set up
	 */
	public function setUp(): void {
		parent::setUp();

		$this->sut = new EnableGutenberg();
	}

	/**
	 * Test that post_should_use_gutenberg returns true for posts with Gutenberg tag(s).
	 *
	 * Ideally these objects would be created separately and fed in to these fucntions with a
	 * data provider. Unfortunately the posts and tags don't seem to persist when created
	 * within a data provider.
	 */
	public function test_post_should_use_gutenberg_returns_true_when_posts_have_gutenberg_tags(): void {
		// Simple post tagged with 'gutenberg' should return true.
		$post_1 = self::factory()->post->create_and_get();
		wp_set_post_terms( $post_1->ID, 'gutenberg', 'post_tag', true );
		static::assertTrue( $this->sut->post_should_use_gutenberg( $post_1 ) );

		// A post with post_type of 'page', and tagged with 'enable-gutenberg' should return true.
		$post_2 = self::factory()->post->create_and_get([
			'post_type' => 'page'
		]);
		wp_set_post_terms( $post_2->ID, 'enable-gutenberg', 'post_tag', true );
		static::assertTrue( $this->sut->post_should_use_gutenberg( $post_2 ) );

		// Simple post tagged with 'gutenberg-enable' and other tags should return true.
		$post_3 = self::factory()->post->create_and_get();
		wp_set_post_terms( $post_3->ID, 'foo', 'post_tag', true );
		wp_set_post_terms( $post_3->ID, 'gutenberg-enable', 'post_tag', true );
		wp_set_post_terms( $post_3->ID, 'bar', 'post_tag', true );
		static::assertTrue( $this->sut->post_should_use_gutenberg( $post_3 ) );
	}

	/**
	 * Test that post_should_use_gutenberg returns false for posts with no Gutenberg tags.
	 */
	public function test_post_should_use_gutenberg_returns_false_when_posts_have_no_gutenberg_tags(): void {
		// Simple post with no valid gutenberg tags should return false.
		$post_1 = self::factory()->post->create_and_get();
		wp_set_post_terms( $post_1->ID, 'no-gutenberg', 'post_tag', true );
		static::assertFalse( $this->sut->post_should_use_gutenberg( $post_1 ) );

		// A post with post_type of 'page', and no valid gutenberg tags should return false.
		$post_2 = self::factory()->post->create_and_get([
			'post_type' => 'page'
		]);
		wp_set_post_terms( $post_2->ID, 'guten', 'post_tag', true );
		wp_set_post_terms( $post_2->ID, 'berg', 'post_tag', true );
		static::assertFalse( $this->sut->post_should_use_gutenberg( $post_2 ) );
	}

	/**
	 * Test that post_should_use_gutenberg returns false for unsupported post types.
	 */
	public function test_post_should_use_gutenberg_returns_false_when_post_type_isnt_supported(): void {
		// A post with post_type of 'attachment' should return false regardless of it having a valid Gutenberg tag.
		$post_1 = self::factory()->post->create_and_get([
			'post_type' => 'attachment'
		]);
		wp_set_post_terms( $post_1->ID, 'gutenberg', 'post_tag', true );
		static::assertFalse( $this->sut->post_should_use_gutenberg( $post_1 ) );

		// A post with post_type of 'revision' should return false regardless of it having a valid Gutenberg tag.
		$post_2 = self::factory()->post->create_and_get([
			'post_type' => 'revision'
		]);
		wp_set_post_terms( $post_2->ID, 'enable-gutenberg', 'post_tag', true );
		static::assertFalse( $this->sut->post_should_use_gutenberg( $post_2 ) );
	}
}

<?php
/**
 * Class for granular enabling of Gutenberg.
 *
 * @package BoxUk\Plugins\Base
 */

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Gutenberg;

use WP_Post;
use WP_Term;

/**
 * Class EnableGutenberg.
 *
 * @package BoxUk\Plugins\Base
 */
class EnableGutenberg {
	/**
	 * Set the post types that can be used with Gutenberg. This won't enable the Gutenberg editor
	 * for all posts with this post type. Only those that also have the required tag.
	 *
	 * NOTE FOR ADDING NEW POST TYPES: If you add a new post type here, and it doesn't use the
	 * 'post_tag' taxonomy, you will need to extend the post_should_use_gutenberg function so
	 * that it reads the correct taxonomy using `get_the_terms`, instead of using `get_the_tags`
	 * which will not return tags from other taxonomies.
	 *
	 * @var array
	 */
	private const ENABLED_POST_TYPES = [
		'post',
		'page',
	];

	/**
	 * Attempt to use result of hook use_block_editor_for_post, but in case that's not available for whatever reason,
	 * we will fallback to this.
	 */
	private const GUTENBERG_ENABLED_BY_DEFAULT = false;

	/**
	 * Set the tags that can be used to enable Gutenberg.
	 *
	 * @var array
	 */
	private const TAG_NAMES = [
		'gutenberg',
		'enable-gutenberg',
		'enable_gutenberg',
		'gutenberg-enable',
		'gutenberg_enable',
	];

	/**
	 * Init all hooks.
	 */
	public function init(): void {
		add_filter( 'use_block_editor_for_post', [ $this, 'maybe_load_gutenberg_for_post' ], 10, 2 );
	}

	/**
	 * Load gutenberg for posts tagged with one of the Gutenberg tags in TAG_NAMES.
	 *
	 * @param  bool    $use_block_editor Whether to block editor.
	 * @param  WP_Post $post Post.
	 * @return bool
	 */
	public function maybe_load_gutenberg_for_post( bool $use_block_editor, WP_Post $post ): bool {
		return $this->post_should_use_gutenberg( $post, $use_block_editor );
	}

	/**
	 * Should we load a given post using the Gutenberg editor?
	 *
	 * Returns true if we think the post should use the Gutenberg editor, false if we think it shouldn't,
	 * or the the $default argument if we can't make a decision.
	 *
	 * @param WP_Post $post The post to check.
	 * @param bool    $default Default value to fall back on if we can't make a decision.
	 * @return bool
	 */
	public function post_should_use_gutenberg( WP_Post $post, bool $default = self::GUTENBERG_ENABLED_BY_DEFAULT ): bool {
		if ( ! \in_array( $post->post_type, self::ENABLED_POST_TYPES, true ) ) {
			return false;
		}

		$post_tags = array_map(
			static function( WP_Term $tag ): string {
				return $tag->name;
			},
			get_the_tags( $post->ID ) ?: []
		);

		if ( 0 === count( $post_tags ) ) {
			return false;
		}

		foreach ( self::TAG_NAMES as $enable_gutenberg_tag ) {
			if ( \in_array( $enable_gutenberg_tag, $post_tags, true ) ) {
				return true;
			}
		}

		return $default;
	}
}

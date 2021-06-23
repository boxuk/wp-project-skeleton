<?php
/**
 * Repository for the retrieval of post items from the database.
 *
 * @package BoxUk\Plugins\Base
 */

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Database;

use WP_Query;

/**
 * Class Post_Repository.
 *
 * @package BoxUk\Plugins\Base
 */
class PostRepository extends QueryRepository {

	/**
	 * Instance of class
	 * See parent class for info on this property.
	 *
	 * @var self;
	 */
	protected static $instance;

	/**
	 * Get a Post by ID.
	 *
	 * @param int    $post_id   The Post ID.
	 * @param string $post_type The Post Type.
	 *
	 * @return WP_Query
	 */
	public function find_by_id( int $post_id, string $post_type = self::DEFAULT_POST_TYPE ): WP_Query {
		$args = [
			'p' => $post_id,
			'post_type' => $post_type,
			'post_status' => 'publish',
		];

		return $this->find_by_args( $args );
	}

	/**
	 * Get posts by type.
	 *
	 * @param string $post_type The Post Type.
	 *
	 * @return WP_Query
	 */
	public function find_by_type( string $post_type = self::DEFAULT_POST_TYPE ): WP_Query {
		$args = [
			'post_type' => $post_type,
			'post_status' => 'publish',
		];

		return $this->find_by_args( $args );
	}
}

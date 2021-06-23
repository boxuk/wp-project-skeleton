<?php
/**
 * Parent class for querying WordPress.
 *
 * @package BoxUk\Plugins\Base
 */

declare ( strict_types=1 );

namespace BoxUk\Plugins\Base\Database;

use WP_Query;

/**
 * Class QueryRepository
 */
class QueryRepository {
	protected const DEFAULT_POST_TYPE = 'post';

	/**
	 * Instance of class
	 * Please note that child classes MUST redefine this property, otherwise ::instance() will only ever return the
	 * very first defined instance irrespective of the fact that it may not be the child class.
	 *
	 * @var static;
	 */
	protected static $instance;

	/**
	 * Creates new instance and adds fields
	 *
	 * @return static
	 */
	public static function instance(): self {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Wrapper for WP_Query that allows us to query by an array of arguments. This is the based method that will most
	 * likely be used by all sub class repositories.
	 *
	 * @param array $args arguments.
	 *
	 * @return WP_Query
	 */
	protected function find_by_args( array $args ): WP_Query {
		return new WP_Query( $args );
	}

	/**
	 * Find posts by an array of ids.
	 *
	 * @param array    $post_ids Post IDs.
	 * @param string   $post_type Post type to return results for, defaults to self::DEFAULT_POST_TYPE.
	 * @param int|null $posts_per_page Posts per page or null to use WP global setting.
	 *
	 * @return WP_Query
	 */
	public function find_by_post_ids(
		array $post_ids,
		string $post_type = self::DEFAULT_POST_TYPE,
		int $posts_per_page = null
	): WP_Query {
		$args = [
			'post__in' => $post_ids,
			'post_type' => $post_type,
			'orderby' => 'post__in',
		];

		if ( null !== $posts_per_page ) {
			$args['posts_per_page'] = $posts_per_page;
		}

		return $this->find_by_args( $args );
	}

	/**
	 * Find posts by a tag.
	 *
	 * @param string   $tag Name of tag to retrieve posts for.
	 * @param string   $post_type Post type to return results for, defaults to self::DEFAULT_POST_TYPE.
	 * @param int|null $posts_per_page Posts per page or null to use WP global setting.
	 *
	 * @return WP_Query
	 */
	public function find_by_tag(
		string $tag,
		string $post_type = self::DEFAULT_POST_TYPE,
		int $posts_per_page = null
	): WP_Query {
		$args = [
			'tag' => $tag,
			'post_type' => $post_type,
			'orderby' => 'post__in',
		];

		if ( null !== $posts_per_page ) {
			$args['posts_per_page'] = $posts_per_page;
		}

		return $this->find_by_args( $args );
	}

	/**
	 * Find posts across all content types by tag.
	 *
	 * @param array       $tag Array of tag names to retrieve posts for.
	 * @param string|null $orderby What field to order by [default: date].
	 * @param string|null $order Which way do we order [default: DESC].
	 * @param int|null    $posts_per_page Number of posts to display [default: all].
	 *
	 * @return WP_Query
	 */
	public function find_all_by_tag(
		array $tag,
		?string $orderby = 'date',
		?string $order = 'DESC',
		?int $posts_per_page = null
	): WP_Query {
		$tag_taxonomies = \array_filter(
			get_taxonomies(),
			function( string $name ): bool {
				return \substr( $name, -4 ) === '_tag';
			}
		);

		$post_types = [ self::DEFAULT_POST_TYPE ];
		$taxonomy_query_args = [];
		$taxonomy_query_args['relation'] = 'or';

		foreach ( $tag_taxonomies as $taxonomy ) {
			if ( $taxonomy instanceof \WP_Taxonomy ) {
				$taxonomy = $taxonomy->name;
			}
			$post_types[] = \substr( $taxonomy, 0, -4 );
			$taxonomy_query_args[] = [
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => $tag,
				'include_children' => false,
			];
		}

		/**
		 * Disable slow query check. Added recommended flag from:
		 * https://wpvip.com/documentation/vip-go/code-review-blockers-warnings-notices/#taxonomy-queries-that-do-not-specify-include_children-false
		 *
		 * phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		 */
		$args = [
			'tax_query' => $taxonomy_query_args,
			'post_type' => $post_types,
			'orderby' => $orderby,
			'order' => $order,
		];

		if ( null !== $posts_per_page ) {
			$args['posts_per_page'] = $posts_per_page;
		}

		/**
		 * Enable the check back.
		 *
		 * phpcs:enable WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		 */

		return $this->find_by_args( $args );
	}

	/**
	 * Find posts across all content types by tag.
	 *
	 * @param array       $tag Array of tag names to retrieve posts for.
	 * @param string|null $orderby What field to order by [default: date].
	 * @param string|null $order Which way do we order [default: DESC].
	 * @param int|null    $posts_per_page Number of posts to display [default: all].
	 *
	 * @return WP_Query
	 */
	public function find_all_by_tag_arr(
		array $tag,
		?string $orderby = 'date',
		?string $order = 'DESC',
		?int $posts_per_page = null
	): WP_Query {
		$tag_taxonomies = \array_filter(
			get_taxonomies(),
			function( string $name ): bool {
				return \substr( $name, -4 ) === '_tag';
			}
		);

		$post_types = [ 'page' ];
		$taxonomy_query_args = [];
		$taxonomy_query_args['relation'] = 'or';

		foreach ( $tag_taxonomies as $taxonomy ) {
			if ( $taxonomy instanceof \WP_Taxonomy ) {
				$taxonomy = $taxonomy->name;
			}
			$post_types[] = \substr( $taxonomy, 0, -4 );
			$taxonomy_query_args[] = [
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => $tag,
				'include_children' => false,
			];
		}

		/**
		 * Disable slow query check. Added recommended flag from:
		 * https://wpvip.com/documentation/vip-go/code-review-blockers-warnings-notices/#taxonomy-queries-that-do-not-specify-include_children-false
		 *
		 * phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		 */
		$args = [
			'tax_query' => $taxonomy_query_args,
			'post_type' => $post_types,
			'orderby' => $orderby,
			'order' => $order,
		];

		if ( null !== $posts_per_page ) {
			$args['posts_per_page'] = $posts_per_page;
		}

		/**
		 * Enable the check back.
		 *
		 * phpcs:enable WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		 */

		return $this->find_by_args( $args );
	}
}

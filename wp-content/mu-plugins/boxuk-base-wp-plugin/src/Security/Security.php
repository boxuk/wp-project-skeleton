<?php
/**
 * Suite of security enhancements.
 *
 * @package BoxUk\Plugins\Base
 */

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Security;

/**
 * Class Security.
 *
 * @package BoxUk\Plugins\Base
 */
class Security {
	public const HTTP_X_FRAME_OPTIONS = 'X-Frame-Options';
	public const HTTP_X_FRAME_OPTIONS_SAMEORIGIN = 'SAMEORIGIN';

	/**
	 * Filters out the 'user' endpoints from the default list of endpoints.
	 *
	 * @param array $endpoints WordPress provided variable of all available endpoints.
	 * @return array
	 */
	public static function filter_out_user_endpoints( array $endpoints ): array {
		unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );

		return $endpoints;
	}

	/**
	 * Returns a 404 instead of redirecting an author query (?author=1) to the pretty printed URL (/author/admin).
	 *
	 * @param string $redirect The pretty permalink URL.
	 *
	 * @return string|null
	 */
	public static function prevent_author_enum( string $redirect ): ?string {
		if ( get_query_var( 'author', false ) ) {
			global $wp_query;
			$wp_query->set_404();

			add_action(
				'wp_title',
				fn() => '404: Not Found',
				9999
			);

			status_header( 404 );
			nocache_headers();

			return null;
		}

		return $redirect;
	}

	/**
	 * Ensure Click Jacking is not exploitable.
	 *
	 * @see https://www.owasp.org/index.php/Clickjacking_Defense_Cheat_Sheet
	 *
	 * @param array $headers Http Headers.
	 * @return array
	 */
	public static function prevent_clickjacking( array $headers ): array {
		if ( false === isset( $headers[ self::HTTP_X_FRAME_OPTIONS ] ) ) {
			$headers[ self::HTTP_X_FRAME_OPTIONS ] = self::HTTP_X_FRAME_OPTIONS_SAMEORIGIN;
		}

		return $headers;
	}
}

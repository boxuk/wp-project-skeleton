<?php
/**
 * Plugin Name: BoxUK SSO
 * Description: A Single Sign-On solution for BoxUK
 * Version: 1.0
 * Author: BoxUK <developers@boxuk.com>
 * Author URI: https://boxuk.com
 *
 * @package BoxUK\User_Authentication
 */

declare( strict_types = 1 );

namespace BoxUK\User_Authentication;

use WP_Error;
use WP_User;
use function HumanMade\SimpleSaml\map_user_roles;

add_filter(
	'wpsimplesaml_idp_metadata_xml_path',
	fn () => WP_CONTENT_DIR . '/private/sso/sso.boxuk.com-idp-metadata.xml'
);

add_filter(
	'option_sso_sp_base',
	fn () =>  'https://sso.boxuk.com'
);
add_filter(
	'option_sso_enabled',
	fn () => 'force'
);

add_filter(
	'option_sso_role_management',
	fn () => 'forced'
);

add_filter( 'wpcom_vip_enable_two_factor', '__return_false' );
add_filter( 'wpcom_vip_is_two_factor_forced', '__return_false' );
add_filter( 'wpcom_vip_two_factor_prep_hide_admin_notice', '__return_true' );

add_filter( 'wpsimplesaml_config', __NAMESPACE__ . '\change_saml_config', 12 );
add_filter( 'wpsimplesaml_map_role', __NAMESPACE__ . '\map_user_group_to_wp_role', 10, 2 );
add_filter( 'wpsimplesaml_match_user', __NAMESPACE__ . '\maybe_update_role', 10, 3 );

add_action(
	'admin_head',
	function () {
		global $wp_settings_sections;
		// Hide the SSO settings section.
		unset( $wp_settings_sections['general']['sso_settings'] );
		// This is the best we can do because no other filters are available for this...
		// We're forcing all the settings above to our configuration so having the settings section is redundant.
	}
);


add_filter(
	'wpsimplesaml_attribute_mapping',
	fn () =>  [
		'first_name' => 'user-first_name',
		'last_name'  => 'user-last_name',
		'user_login' => 'user-email',
	]
);

/**
 * Hook user matching to check whether a user is found and their role has changed.
 * Match by email, then remap their role from the SAML attributes
 *
 * @param WP_User|null $user       Found user.
 * @param string       $email      Email from SAMLResponse.
 * @param array        $attributes SAML Attributes parsed from SAMLResponse.
 *
 * @return false|WP_User User object or false if not found
 */
function maybe_update_role( $user, string $email, array $attributes ): false|\WP_User {

	if ( null === $user ) {
		$user = get_user_by( 'email', $email );
	}

	if ( ! empty( $user ) ) {
		map_user_roles( $user, $attributes );
	}

	return $user;
}

/**
 * Map Active Directory user group membership to WordPress roles.
 *
 * @param string|array $default_role Default user role.
 * @param array        $attributes   SAML attributes.
 *
 * @return string|array Updated user role.
 */
function map_user_group_to_wp_role( string|array $default_role, array $attributes ): string|array {

	$role_key = 'user_role';

	if ( empty( $attributes[ $role_key ] ) ) {
		return $default_role;
	}

	switch ( $attributes[ $role_key ][0] ) {
		case 'BoxUK-WP_Admin':
			return 'administrator';
		case 'BoxUK-WP_Editor':
			return 'editor';
		case 'BoxUK-WP_Author':
			return 'author';
		case 'BoxUK-WP_Contributor':
			return 'contributor';
		case 'BoxUK-WP_Subscriber':
			return 'subscriber';
		default:
			return $default_role;
	}
}

/**
 * Change SAML configuration settings.
 *
 * @param array|WP_Error $settings SAML configuration settings, or an error object.
 *
 * @return array|WP_Error SAML configuration settings, or an error object.
 */
function change_saml_config( $settings ) {
	if ( is_wp_error( $settings ) ) {
		return $settings;
	}

	// modify any necessary settings....
	return $settings;
}

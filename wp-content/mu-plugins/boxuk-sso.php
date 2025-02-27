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

/**
 * BoxUK SSO Class
 */
class BoxUK_SSO {

	/**
	 * Setup Hooks
	 *
	 * @return void
	 */
	public function setup(): void {

		/* Force SSO options and hide admin-options screen */
		add_filter( 'option_sso_sp_base', [ $this, 'get_sso_url' ] );
		add_filter( 'option_sso_enabled', [ $this, 'get_sso_enabled_state' ] );
		add_filter( 'option_sso_role_management', [ $this, 'get_role_management_state' ] );
		add_action( 'admin_head', [ $this, 'hide_sso_settings' ] );

		/* Disable VIP Login Options */
		add_filter( 'wpcom_vip_enable_two_factor', '__return_false' );
		add_filter( 'wpcom_vip_is_two_factor_forced', '__return_false' );
		add_filter( 'wpcom_vip_two_factor_prep_hide_admin_notice', '__return_true' );

		/* Set SAML Attributes */
		add_filter( 'wpsimplesaml_attribute_mapping', [ $this, 'attribute_mapping' ] );
		add_filter( 'wpsimplesaml_idp_metadata_xml_path', [ $this, 'get_metadata_path' ] );
		add_filter( 'wpsimplesaml_config', [ $this, 'change_saml_config' ], 12 );
		add_filter( 'wpsimplesaml_map_role', [ $this, 'map_user_group_to_wp_role' ], 10, 2 );
		add_filter( 'wpsimplesaml_match_user', [ $this, 'maybe_update_role' ], 10, 3 );
	}

	/**
	 * Get Metadata Path
	 *
	 * @param string $path The path to the metadata file (or empty string).
	 *
	 * @return string
	 */
	public function get_metadata_path( string $path ): string {
		$new_path = WP_CONTENT_DIR . '/private/sso/mock-saml.xml';
		return file_exists( $new_path ) ? $new_path : $path;
	}

	/**
	 * Get SSO Enabled State
	 *
	 * @return string
	 */
	public function get_sso_enabled_state(): string {

		/**
		 * Filter to force SSO or not
		 *
		 * @return boolean
		 */
		$force = apply_filters( 'boxuk_sso_force_redirect', false );
		return $force ? 'force' : 'link';
	}

	/**
	 * Get Role Management State
	 *
	 * @return string
	 */
	public function get_role_management_state(): string {
		return 'enabled';
	}

	/**
	 * Get SSO URL
	 *
	 * @return string
	 */
	public function get_sso_url(): string {
		return home_url();
	}

	/**
	 * Attribute Mapping
	 *
	 * @param array<string,string> $map Attributes from WP to SAML.
	 *
	 * @return array<string,string>
	 */
	public function attribute_mapping( array $map ): array {
		return array_merge(
			$map,
			[
				'first_name' => 'firstName',
				'last_name'  => 'lastName',
			] // @todo: Map attributes.
		);
	}

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
	public function maybe_update_role( $user, string $email, array $attributes ): false|\WP_User {
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
	public function map_user_group_to_wp_role( string|array $default_role, array $attributes ): string|array {

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
	public function change_saml_config( $settings ) {
		if ( is_wp_error( $settings ) ) {
			return $settings;
		}

		return $settings;
	}

	/**
	 * Hide the SSO settings section.
	 *
	 * @return void
	 */
	public function hide_sso_settings() {
		global $wp_settings_sections;
		// Hide the SSO settings section.
		unset( $wp_settings_sections['general']['sso_settings'] );
		// We're forcing all the settings above so having the settings section is redundant since it won't work.
	}
}

$class = new BoxUK_SSO();
$class->setup();

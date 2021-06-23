<?php
/**
 * Scaffold command that extends the core scaffold command with our own.
 *
 * @package BoxUk\Plugins\Base
 */

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Cli;

use Scaffold_Command;
use WP_CLI;
use WP_CLI\Utils;

/**
 * Custom ScaffoldCommand for scaffolding Box UK flavoured files.
 */
final class ScaffoldCommand extends Scaffold_Command {
	private const DEFAULT_PACKAGE_NAME = 'BoxUk';

	/**
	 * Generates starter code for a Box UK flavoured mu-plugin.
	 *
	 * The following files are always generated:
	 *
	 * * `plugin-slug.php` is the main PHP plugin file.
	 * * `composer.json` used for documenting autoload and for monorepo support.
	 * * `.gitignore` tells which files (or patterns) git should ignore.
	 * * `config/services.yaml` used for configuration of DI services.
	 * * `src/ExampleService.php` is an example service class.
	 *
	 * The following files are also included unless the `--skip-tests` is used:
	 *
	 * * `tests/ExampleTest.php` is an example file containing test cases.
	 *
	 * ## OPTIONS
	 *
	 * <slug>
	 * : The internal name of the plugin.
	 *
	 * [--dir=<dirname>]
	 * : Put the new plugin in some arbitrary directory path. Plugin directory will be path plus supplied slug.
	 *
	 * [--plugin_name=<title>]
	 * : What to put in the 'Plugin Name:' header.
	 *
	 * [--plugin_description=<description>]
	 * : What to put in the 'Description:' header.
	 *
	 * [--plugin_author=<author>]
	 * : What to put in the 'Author:' header.
	 *
	 * [--plugin_author_uri=<url>]
	 * : What to put in the 'Author URI:' header.
	 *
	 * [--plugin_uri=<url>]
	 * : What to put in the 'Plugin URI:' header.
	 *
	 * [--skip-tests]
	 * : Don't generate files for unit testing.
	 *
	 * [--skip-post-warnings]
	 * : Don't show warnings at the end of the run reminding user to run some extra commands.
	 *
	 *
	 * [--force]
	 * : Overwrite files that already exist.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp scaffold boxuk-mu-plugin sample-plugin
	 *     Success: Created plugin files.
	 *     Success: Created test files.
	 *
	 * @subcommand boxuk-mu-plugin
	 *
	 * @param array $args Args for the command, see above.
	 * @param array $assoc_args Assoc args.
	 *
	 * @throws WP_CLI\ExitException If CLI errors are set to capture.
	 */
	public function boxuk_mu_plugin( array $args, array $assoc_args ): void {
		$plugin_slug = $args[0];
		$plugin_name = ucwords( str_replace( '-', ' ', $plugin_slug ) );
		$plugin_canonical_name = str_replace( ' ', '', $plugin_name );
		$plugin_autoload_name = ucfirst( $plugin_canonical_name );
		$plugin_package = self::DEFAULT_PACKAGE_NAME;

		if ( \in_array( $plugin_slug, [ '.', '..' ], true ) ) {
			WP_CLI::error( "Invalid plugin slug specified. The slug cannot be '.' or '..'." );
		}

		$defaults = [
			'plugin_slug' => $plugin_slug,
			'plugin_name' => $plugin_name,
			'plugin_canonical_name' => $plugin_canonical_name,
			'plugin_autoload_name' => $plugin_autoload_name,
			'plugin_package' => $plugin_package,
			'plugin_description' => 'PLUGIN DESCRIPTION HERE',
		];

		$data = wp_parse_args( $assoc_args, $defaults );

		$data['textdomain'] = $plugin_slug;

		if ( ! empty( $assoc_args['dir'] ) ) {
			if ( ! is_dir( $assoc_args['dir'] ) ) {
				WP_CLI::error( "Cannot create plugin in directory that doesn't exist." );
			}
			$plugin_dir = "{$assoc_args['dir']}/{$plugin_slug}";
		} else {
			$plugin_dir = WPMU_PLUGIN_DIR . "/{$plugin_slug}";
			$this->maybe_create_mu_plugins_dir();

			$error_msg = $this->check_target_directory( 'mu-plugin', $plugin_dir );
			if ( ! empty( $error_msg ) ) {
				WP_CLI::error( "Invalid mu-plugin slug specified. {$error_msg}" );
			}
		}

		$plugin_path = "{$plugin_dir}/{$plugin_slug}.php";

		$src_dir = "{$plugin_dir}/src";
		$config_dir = "{$plugin_dir}/config";

		$wp_filesystem = $this->init_wp_filesystem();

		$wp_filesystem->mkdir( $src_dir );
		$wp_filesystem->mkdir( $config_dir );

		$files_to_create = [
			$plugin_path => self::mustache_render( 'mu-plugin.mustache', $data ),
			"{$plugin_dir}/composer.json" => self::mustache_render( 'mu-plugin-composer.mustache', $data ),
			"{$plugin_dir}/.gitignore" => self::mustache_render( 'mu-plugin-gitignore.mustache', $data ),
			"{$plugin_dir}/config/services.yaml" => self::mustache_render( 'mu-plugin-services.mustache', $data ),
			"{$plugin_dir}/src/ExampleService.php" => self::mustache_render( 'mu-plugin-ExampleService.mustache', $data ),
		];

		$force = Utils\get_flag_value( $assoc_args, 'force' );
		$files_written = $this->create_files( $files_to_create, $force );

		$skip_message = 'All mu-plugin files were skipped.';
		$success_message = 'Created mu-plugin files.';
		$this->log_whether_files_written( $files_written, $skip_message, $success_message );

		if ( ! Utils\get_flag_value( $assoc_args, 'skip-tests' ) ) {
			$command_args = [
				'dir' => $plugin_dir,
				'force' => $force,
			];
			WP_CLI::run_command( [ 'scaffold', 'boxuk-mu-plugin-tests', $plugin_slug ], $command_args );
		}

		if ( ! Utils\get_flag_value( $assoc_args, 'skip-post-warnings' ) ) {
			WP_CLI::warning( 'Remember to run `bin/monorepo-builder merge` from the root of the project to sync composer settings.' );
			WP_CLI::warning( 'Remember to run `composer install` from the root of the project to update the autoloader.' );
		}
	}

	/**
	 * Generates files needed for running PHPUnit tests in a Box UK flavoured  mu-plugin.
	 *
	 * The following files are generated by default:
	 *
	 * * `tests/ExampleTest.php` is an example file containing the actual tests.
	 *
	 * ## OPTIONS
	 *
	 * [<plugin>]
	 * : The name of the plugin to generate test files for.
	 *
	 * [--dir=<dirname>]
	 * : Generate test files for a non-standard plugin path. If no plugin slug is specified, the directory name is used.
	 *
	 * [--force]
	 * : Overwrite files that already exist.
	 *
	 * ## EXAMPLES
	 *
	 *     # Generate unit test files for mu-plugin 'sample-plugin'.
	 *     $ wp scaffold boxuk-mu-plugin-tests sample-plugin
	 *     Success: Created test files.
	 *
	 * @subcommand boxuk-mu-plugin-tests
	 *
	 * @param array $args Args for the command, see above.
	 * @param array $assoc_args Assoc args.
	 *
	 * @throws WP_CLI\ExitException If CLI errors are set to capture.
	 */
	public function boxuk_mu_plugin_tests( array $args, array $assoc_args ): void {
		$this->scaffold_mu_plugin_theme_tests( $args, $assoc_args, 'mu-plugin' );
	}

	/**
	 * Create the test files.
	 *
	 * @param array  $args Args used for creating the files, see self::mu_plugin_tests for more info.
	 * @param array  $assoc_args Associated args.
	 * @param string $type Type of files to generate, one of: theme, mu-plugin.
	 *
	 * @throws WP_CLI\ExitException If CLI errors are set to capture.
	 */
	private function scaffold_mu_plugin_theme_tests( array $args, array $assoc_args, string $type ): void {
		$wp_filesystem = $this->init_wp_filesystem();

		if ( ! empty( $args[0] ) ) {
			$slug = $args[0];
			if ( \in_array( $slug, [ '.', '..' ], true ) ) {
				WP_CLI::error( "Invalid {$type} slug specified. The slug cannot be '.' or '..'." );
			}

			$target_dir = '';
			if ( 'theme' === $type ) {
				$theme = wp_get_theme( $slug );
				if ( $theme->exists() ) {
					$target_dir = $theme->get_stylesheet_directory();
				} else {
					WP_CLI::error( "Invalid {$type} slug specified. The theme '{$slug}' does not exist." );
				}
			} else {
				$target_dir = WPMU_PLUGIN_DIR . "/{$slug}";
			}
			if ( empty( $assoc_args['dir'] ) && ! is_dir( $target_dir ) ) {
				WP_CLI::error( "Invalid {$type} slug specified. No such target directory '{$target_dir}'." );
			}

			$error_msg = $this->check_target_directory( $type, $target_dir );
			if ( ! empty( $error_msg ) ) {
				WP_CLI::error( "Invalid {$type} slug specified. {$error_msg}" );
			}
		}

		if ( ! empty( $assoc_args['dir'] ) ) {
			$target_dir = $assoc_args['dir'];
			if ( ! is_dir( $target_dir ) ) {
				WP_CLI::error( "Invalid {$type} directory specified. No such directory '{$target_dir}'." );
			}
			if ( empty( $slug ) ) {
				$slug = Utils\basename( $target_dir );
			}
		}

		if ( empty( $slug ) || empty( $target_dir ) ) {
			WP_CLI::error( "Invalid {$type} specified." );
		}

		$name = ucwords( str_replace( '-', ' ', $slug ) );
		$canonical_name = strtok( strtolower( $name ), ' ' );
		$autoload_name = ucfirst( $canonical_name );
		$package = str_replace( ' ', '_', $name );

		$tests_dir = "{$target_dir}/tests";

		$wp_filesystem->mkdir( $tests_dir );

		$template_data = [
			"{$type}_slug" => $slug,
			"{$type}_package" => $package,
			"{$type}_autoload_name" => $autoload_name,
		];

		$force = Utils\get_flag_value( $assoc_args, 'force' );
		$files_to_create = [
			"{$tests_dir}/ExampleTest.php" => self::mustache_render( "{$type}-ExampleTest.mustache", $template_data ),
		];

		$files_written = $this->create_files( $files_to_create, $force );

		$skip_message = 'All test files were skipped.';
		$success_message = 'Created test files.';
		$this->log_whether_files_written( $files_written, $skip_message, $success_message );
	}

	/**
	 * Creates the mu-plugins directory if it doesn't already exist.
	 *
	 * Copied from parent class due to it being private, changed the plugin part to check `mu-plugin`.
	 */
	private function maybe_create_mu_plugins_dir(): void {
		if ( ! is_dir( WPMU_PLUGIN_DIR ) ) {
			wp_mkdir_p( WPMU_PLUGIN_DIR );
		}
	}

	/**
	 * Checks that the `$target_dir` is a child directory of the WP themes or plugins directory, depending on `$type`.
	 *
	 * Copied from parent class due to it being private, changed the plugin part to check `mu-plugin`.
	 *
	 * @param string $type One of "theme" or "mu-plugin".
	 * @param string $target_dir The theme/mu-plugin directory to check.
	 *
	 * @return null|string Returns null on success, error message on error.
	 */
	private function check_target_directory( string $type, string $target_dir ): ?string {
		$parent_dir = \dirname( self::canonicalize_path( str_replace( '\\', '/', $target_dir ) ) );

		if ( 'theme' === $type && str_replace( '\\', '/', WP_CONTENT_DIR . '/themes' ) !== $parent_dir ) {
			return sprintf( 'The target directory \'%1$s\' is not in \'%2$s\'.', $target_dir, WP_CONTENT_DIR . '/themes' );
		}

		if ( 'mu-plugin' === $type && str_replace( '\\', '/', WPMU_PLUGIN_DIR ) !== $parent_dir ) {
			return sprintf( 'The target directory \'%1$s\' is not in \'%2$s\'.', $target_dir, WPMU_PLUGIN_DIR );
		}

		// Success.
		return null;
	}

	/**
	 * Returns the canonicalized path, with dot and double dot segments resolved.
	 *
	 * Copied verbatim from parent class due to it being private.
	 *
	 * Copied from Symfony\Component\DomCrawler\AbstractUriElement::canonicalizePath().
	 * Implements RFC 3986, section 5.2.4.
	 *
	 * @param string $path The path to make canonical.
	 *
	 * @return string The canonicalized path.
	 */
	private static function canonicalize_path( string $path ): string {
		if ( '' === $path || '/' === $path ) {
			return $path;
		}

		if ( '.' === substr( $path, -1 ) ) {
			$path .= '/';
		}

		$output = [];

		foreach ( explode( '/', $path ) as $segment ) {
			if ( '..' === $segment ) {
				array_pop( $output );
			} elseif ( '.' !== $segment ) {
				$output[] = $segment;
			}
		}

		return implode( '/', $output );
	}

	/**
	 * Render a mustache template file.
	 *
	 * Copied verbatim from parent class due it being private.
	 *
	 * @param string $template Name of the template file to render.
	 * @param array  $data Data to pass to the template file.
	 *
	 * @return string
	 */
	private static function mustache_render( string $template, array $data = [] ): string {
		return Utils\mustache_render( __DIR__ . "/templates/{$template}", $data );
	}

	/**
	 * Gets the template path based on installation type.
	 *
	 * Copied verbatim from parent class due to it being private.
	 *
	 * @param string $template Template to get path for.
	 *
	 * @return string Path of template.
	 * @throws WP_CLI\ExitException If CLI errors are set to capture.
	 */
	private static function get_template_path( string $template ): string {
		$command_root = Utils\phar_safe_path( __DIR__ );
		$template_path = "{$command_root}/templates/{$template}";

		if ( ! file_exists( $template_path ) ) {
			WP_CLI::error( "Couldn't find {$template}" );
		}

		return $template_path;
	}
}

<?php
/**
 * MakePotTwig command that extends the core MakePot command with our own.
 *
 * @package BoxUk\Plugins\Base
 */

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Cli;

use BoxUk\Plugins\Base\Cli\I18n\TwigExtractor;
use Gettext\Extractors\Po;
use Gettext\Merge;
use Gettext\Translation;
use Gettext\Translations;
use WP_CLI\ExitException;
use WP_CLI\I18n\MakePotCommand as BaseMakePotCommand;
use WP_CLI\I18n\PotGenerator;

/**
 * MakePotTwigCommand class.
 *
 * @package BoxUk\Plugins\Base
 */
class MakePotTwigCommand extends BaseMakePotCommand {
	/**
	 * Create a POT file for a WordPress/Timber project.
	 *
	 * Scans twig files for translatable strings, as well as theme stylesheets and plugin files
	 * if the source directory is detected as either a plugin or theme.
	 *
	 * ## OPTIONS
	 *
	 * <source>
	 * : Directory to scan for string extraction.
	 *
	 * [<destination>]
	 * : Name of the resulting POT file.
	 *
	 * [--slug=<slug>]
	 * : Plugin or theme slug. Defaults to the source directory's basename.
	 *
	 * [--domain=<domain>]
	 * : Text domain to look for in the source code, unless the `--ignore-domain` option is used.
	 * By default, the "Text Domain" header of the plugin or theme is used.
	 * If none is provided, it falls back to the project slug.
	 *
	 * [--ignore-domain]
	 * : Ignore the text domain completely and extract strings with any text domain.
	 *
	 * [--merge[=<paths>]]
	 * : Comma-separated list of POT files whose contents should be merged with the extracted strings.
	 * If left empty, defaults to the destination POT file. POT file headers will be ignored.
	 *
	 * [--subtract=<paths>]
	 * : Comma-separated list of POT files whose contents should act as some sort of blacklist for string extraction.
	 * Any string which is found on that blacklist will not be extracted.
	 * This can be useful when you want to create multiple POT files from the same source directory with slightly
	 * different content and no duplicate strings between them.
	 *
	 * [--include=<paths>]
	 * : Comma-separated list of files and paths that should be used for string extraction.
	 * If provided, only these files and folders will be taken into account for string extraction.
	 * For example, `--include="src,my-file.php` will ignore anything besides `my-file.php` and files in the `src`
	 * directory. Simple glob patterns can be used, i.e. `--include=foo-*.php` includes any PHP file with the `foo-`
	 * prefix. Leading and trailing slashes are ignored, i.e. `/my/directory/` is the same as `my/directory`.
	 *
	 * [--exclude=<paths>]
	 * : Comma-separated list of files and paths that should be skipped for string extraction.
	 * For example, `--exclude=".github,myfile.php` would ignore any strings found within `myfile.php` or the `.github`
	 * folder. Simple glob patterns can be used, i.e. `--exclude=foo-*.php` excludes any PHP file with the `foo-`
	 * prefix. Leading and trailing slashes are ignored, i.e. `/my/directory/` is the same as `my/directory`. The
	 * following files and folders are always excluded: node_modules, .git, .svn, .CVS, .hg, vendor, *.min.js.
	 *
	 * [--headers=<headers>]
	 * : Array in JSON format of custom headers which will be added to the POT file. Defaults to empty array.
	 *
	 * [--skip-audit]
	 * : Skips string audit where it tries to find possible mistakes in translatable strings. Useful when running in an
	 * automated environment.
	 *
	 * [--file-comment=<file-comment>]
	 * : String that should be added as a comment to the top of the resulting POT file.
	 * By default, a copyright comment is added for WordPress plugins and themes in the following manner:
	 *
	 *      ```
	 *      Copyright (C) 2018 Example Plugin Author
	 *      This file is distributed under the same license as the Example Plugin package.
	 *      ```
	 *
	 *      If a plugin or theme specifies a license in their main plugin file or stylesheet, the comment looks like
	 *      this:
	 *
	 *      ```
	 *      Copyright (C) 2018 Example Plugin Author
	 *      This file is distributed under the GPLv2.
	 *      ```
	 *
	 * [--package-name=<name>]
	 * : Name to use for package name in the resulting POT file's `Project-Id-Version` header.
	 * Overrides plugin or theme name, if applicable.
	 *
	 * ## EXAMPLES
	 *
	 *     # Create a POT file for the WordPress plugin/theme in the current directory
	 *     $ wp i18n-twig make-pot . languages/my-plugin.pot
	 *
	 *     # Create a POT file for the continents and cities list in WordPress core.
	 *     $ wp i18n-twig make-pot . continents-and-cities.pot --include="wp-admin/includes/continents-cities.php"
	 *     --ignore-domain
	 *
	 * @when after_wp_load
	 *
	 * @param array $args Args for the command, see above.
	 * @param array $assoc_args Assoc args.
	 *
	 * @throws ExitException If CLI errors are set to capture.
	 */
	public function __invoke( $args, $assoc_args ) { // phpcs:ignore NeutronStandard.Functions.TypeHint, NeutronStandard.MagicMethods.RiskyMagicMethod.RiskyMagicMethod

		// If we have the twig profiler enabled, disable profiling for this.
		if ( has_filter( 'timber/twig', 'NdB\\QM_Twig_Profile\\collect_timber' ) ) {
			remove_filter( 'timber/twig', 'NdB\\QM_Twig_Profile\\collect_timber' );
		}

		$this->handle_arguments( $args, $assoc_args );

		$translations = $this->extract_strings();
		$twig_translations = $this->extract_twig_strings();
		$translations->mergeWith( $twig_translations );

		if ( ! $translations ) {
			\WP_CLI::warning( 'No strings found' );
		}

		$translations_count = count( $translations );

		if ( 1 === $translations_count ) {
			\WP_CLI::debug( sprintf( 'Extracted %d string', $translations_count ), 'make-pot' );
		} else {
			\WP_CLI::debug( sprintf( 'Extracted %d strings', $translations_count ), 'make-pot' );
		}

		if ( ! PotGenerator::toFile( $translations, $this->destination ) ) {
			\WP_CLI::error( 'Could not generate a POT file!' );
		}

		\WP_CLI::success( 'POT file successfully generated!' );
	}

	/**
	 * Creates a POT file and stores it on disk.
	 *
	 * @throws ExitException If CLI errors are set to capture.
	 *
	 * @return Translations A Translation set.
	 */
	protected function extract_twig_strings(): Translations {
		$translations = new Translations();

		// Add existing strings first but don't keep headers.
		if ( ! empty( $this->merge ) ) {
			$existing_translations = new Translations();
			Po::fromFile( $this->merge, $existing_translations );
			$translations->mergeWith( $existing_translations, Merge::ADD | Merge::REMOVE );
		}

		PotGenerator::setCommentBeforeHeaders( $this->get_file_comment() );

		$this->set_default_headers( $translations );

		// POT files have no Language header.
		$translations->deleteHeader( Translations::HEADER_LANGUAGE );

		// Only relevant for PO files, not POT files.
		$translations->setHeader( 'PO-Revision-Date', 'YEAR-MO-DA HO:MI+ZONE' );

		if ( $this->domain ) {
			$translations->setDomain( $this->domain );
		}

		unset( $this->main_file_data['Version'], $this->main_file_data['License'], $this->main_file_data['Domain Path'], $this->main_file_data['Text Domain'] );

		// Set entries from main file data.
		foreach ( $this->main_file_data as $header => $data ) {
			if ( empty( $data ) ) {
				continue;
			}

			$translation = new Translation( '', $data );

			if ( isset( $this->main_file_data['Theme Name'] ) ) {
				$translation->addExtractedComment( sprintf( '%s of the theme', $header ) );
			} else {
				$translation->addExtractedComment( sprintf( '%s of the plugin', $header ) );
			}

			$translations[] = $translation;
		}

		try {
			$options = [
				// Extract 'Template Name' headers in theme files.
				'wpExtractTemplates' => isset( $this->main_file_data['Theme Name'] ),
				'include' => $this->include,
				'exclude' => $this->exclude, // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'extensions' => [ 'twig' ],
			];
			TwigExtractor::fromDirectory( $this->source, $translations, $options );
		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}

		foreach ( $this->exceptions as $translation ) {
			if ( $translations->find( $translation ) ) {
				unset( $translations[ $translation->getId() ] );
			}
		}

		if ( ! $this->skip_audit ) {
			$this->audit_strings( $translations );
		}

		return $translations;
	}
}

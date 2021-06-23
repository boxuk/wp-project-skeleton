<?php
/**
 * Extractor class to extract translations from twig files.
 *
 * @package BoxUk\Plugins\Base
 */

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Cli\I18n;

use Gettext\Extractors\Twig;
use Gettext\Translations;
use Timber\Loader;
use Twig\Environment;
use Twig\Error\SyntaxError;
use Twig\Extensions\I18nExtension;
use Twig\Loader\ArrayLoader;
use Twig\Source;
use WP_CLI\I18n;

/**
 * TwigExtractor class.
 *
 * @package BoxUk\Plugins\Base
 */
class TwigExtractor extends Twig {
	use I18n\IterableCodeExtractor;

	/**
	 * Options for get text.
	 *
	 * @var array
	 */
	public static $options = [
		'twig' => null,
		'extractComments' => [ 'translators', 'Translators' ],
		'constants' => [],
		'functions' => [
			'__' => 'text_domain',
			'_e' => 'text_domain',
			'_x' => 'text_context_domain',
			'_ex' => 'text_context_domain',
			'_n' => 'single_plural_number_domain',
			'_nx' => 'single_plural_number_context_domain',
			'_n_noop' => 'single_plural_domain',
			'_nx_noop' => 'single_plural_context_domain',
		],
	];

	/**
	 * Extract translations from string.
	 *
	 * @param string       $string String to extract from.
	 * @param Translations $translations Translations set to update.
	 * @param array        $options Options.
	 * @throws SyntaxError If string contains a twig syntax error.
	 */
	public static function fromString( $string, Translations $translations, array $options = [] ) { // phpcs:ignore NeutronStandard.Functions.TypeHint
		$options += static::$options;
		$twig = $options['twig'] ?: self::createTwig();
		$source = $twig->compileSource( new Source( $string, '' ) );
		$functions = new I18n\PhpFunctionsScanner( $source );
		$functions->saveGettextFunctions( $translations, $options );
	}

	/**
	 * Create our twig environment.
	 *
	 * @return Environment
	 */
	protected static function createTwig(): Environment {
		$twig = new Environment( new ArrayLoader( [ '' => '' ] ) );
		$twig->addExtension( new I18nExtension() );

		$loader = new Loader();
		$existing_functions = $loader->get_twig()->getFunctions();
		$existing_filters = $loader->get_twig()->getFilters();
		$existing_extensions = $loader->get_twig()->getExtensions();

		foreach ( $existing_functions as $existing_function ) {
			$twig->addFunction( $existing_function );
		}

		foreach ( $existing_filters as $existing_filter ) {
			$twig->addFilter( $existing_filter );
		}

		foreach ( $existing_extensions as $existing_extension ) {
			if ( $twig->hasExtension( get_class( $existing_extension ) ) ) {
				continue;
			}
			$twig->addExtension( $existing_extension );
		}
		static::$options['twig'] = $twig;
		return $twig;
	}
}

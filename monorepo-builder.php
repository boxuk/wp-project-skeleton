<?php
/**
 * Configuration file for monorepo-builder.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

use MonorepoBuilder20210802\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function ( ContainerConfigurator $container_configurator ): void {
	$parameters = $container_configurator->parameters();

	$parameters->set(
		Option::PACKAGE_DIRECTORIES,
		[
			__DIR__ . '/wp-content/mu-plugins',
			__DIR__ . '/wp-content/themes',
		]
	);

	$parameters->set(
		Option::PACKAGE_DIRECTORIES_EXCLUDES,
		[
			'flagpole',
			'styleguide',
		]
	);

	$parameters->set(
		Option::DATA_TO_APPEND,
		[
			// Add anything you wish to append after merge here.
		]
	);

	$parameters->set(
		Option::DATA_TO_REMOVE,
		[
			ComposerJsonSection::REQUIRE_DEV => [
				'roots/wordpress' => '*',
				'symfony/dotenv' => '*',
			],
		]
	);

	$parameters->set( Option::DEFAULT_BRANCH_NAME, 'main' );

	$services = $container_configurator->services();

	// Release workers - in order to execute.
	$services->set( UpdateReplaceReleaseWorker::class );
	$services->set( SetCurrentMutualDependenciesReleaseWorker::class );
	$services->set( TagVersionReleaseWorker::class );
	$services->set( PushTagReleaseWorker::class );
	$services->set( SetNextMutualDependenciesReleaseWorker::class );
	$services->set( UpdateBranchAliasReleaseWorker::class );
	$services->set( PushNextDevReleaseWorker::class );
};

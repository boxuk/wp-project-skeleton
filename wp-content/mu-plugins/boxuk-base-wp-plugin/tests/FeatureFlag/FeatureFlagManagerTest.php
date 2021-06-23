<?php

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Tests\FeatureFlag;

use BoxUk\Plugins\Base\FeatureFlag\FeatureFlagManager;
use BoxUk\Plugins\Base\FeatureFlag\Provider\InMemoryProvider;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;
use WP_UnitTestCase;

class FeatureFlagManagerTest extends WP_UnitTestCase {
	private $provider;
	private $manager;

	public function setUp() {
		parent::setUp();

		$this->provider = new InMemoryProvider();
		$this->manager = new FeatureFlagManager( new Parser(), $this->provider );
	}

	public function test_feature_flag_array_registration(): void {
		$feature_flags = [
			'feature_flags' => [
				'disable_rss' => [
					'title' => 'Disable RSS feeds',
					'description' => 'Disable RSS feeds across the site',
					'enforced' => true,
					'stable' => true,
				]
			]
		];

		$this->manager->register_from_array( $feature_flags );

		$expected = [
			[
				'title' => 'Disable RSS feeds',
				'description' => 'Disable RSS feeds across the site',
				'enforced' => true,
				'stable' => true,
				'key' => 'disable_rss',
			]
		];

		self::assertEquals( $expected, $this->provider->get_flags() );
	}

	public function test_feature_flag_yaml_registration(): void {

		$this->manager->register_from_yaml_file( __DIR__ . '/resources/flags.yml' );

		$expected = [
			[
				'title' => 'Disable RSS feeds',
				'description' => 'Disable RSS feeds across the site',
				'enforced' => false,
				'stable' => true,
				'key' => 'disable_rss',
			]
		];

		self::assertEquals( $expected, $this->provider->get_flags() );
	}

	public function test_feature_flag_yaml_registration_with_invalid_path_to_yaml(): void {
		$this->expectException( ParseException::class );
		$this->manager->register_from_yaml_file( __DIR__ . '/resources/absent.yml' );
	}

	public function test_is_enabled_returns_true_for_enforced_flags(): void {
		$feature_flags = [
			'feature_flags' => [
				'my_enabled_flag' => [
					'title' => 'My Enabled flag',
					'description' => 'Test flag',
					'enforced' => true,
					'stable' => true,
				]
			]
		];

		$this->manager->register_from_array( $feature_flags );

		self::assertTrue( $this->manager->is_enabled( 'my_enabled_flag' ) );
	}

	public function test_is_enabled_returns_false_for_unenforced_flags(): void {
		$feature_flags = [
			'feature_flags' => [
				'my_enabled_flag' => [
					'title' => 'My Enabled flag',
					'description' => 'Test flag',
					'enforced' => false,
					'stable' => true,
				]
			]
		];

		$this->manager->register_from_array( $feature_flags );

		self::assertFalse( $this->manager->is_enabled( 'my_enabled_flag' ) );
	}
}

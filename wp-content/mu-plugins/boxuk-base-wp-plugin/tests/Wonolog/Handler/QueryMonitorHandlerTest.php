<?php

namespace BoxUk\Plugins\Base\Tests\Wonolog\Handler;

use BoxUk\Plugins\Base\Wonolog\Handler\QueryMonitorHandler;
use Monolog\Logger;
use WP_Mock\Tools\TestCase;

class QueryMonitorHandlerTest extends TestCase {
	/**
	 * @dataProvider get_levels
	 */
	public function test_the_handler_raises_the_appropriate_action( int $log_level, string $expected_qm_level ): void {
		$handler = new QueryMonitorHandler();
		$handler->handle( $this->get_record( $log_level ) );

		self::assertTrue( (bool) did_action( $expected_qm_level ) );
	}

	public function test_the_handler_adds_the_message_to_the_log(): void {
		add_action( 'qm/debug', function( string $message, array $context ) {
			self::assertEquals( 'This is a test', $message );
			self::assertEquals( [ 'foo' => 'bar' ], $context );
		}, 10, 2 );

		$handler = new QueryMonitorHandler();
		$handler->handle( $this->get_record( Logger::DEBUG, 'This is a test', [ 'foo' => 'bar' ] ) );
		$this->assertConditionsMet();
	}

	public function test_the_handler_does_not_log_if_message_empty(): void {
		$handler = new QueryMonitorHandler();
		$handler->handle( $this->get_record( Logger::DEBUG, '', [ 'foo' => 'bar' ] ) );

		self::assertFalse( (bool) did_action( 'qm/debug' ) );
	}

	public function test_the_handler_defaults_to_debug_if_no_level_name_supplied_in_record(): void {
		$record = $this->get_record( Logger::DEBUG, 'Test', [ 'foo' => 'bar' ] );
		$handler = new QueryMonitorHandler();

		// Remove level details.
		unset( $record['level_name'] );
		$handler->handle( $record );

		self::assertTrue( (bool) did_action( 'qm/debug' ) );
	}

	public function test_the_handler_defaults_to_debug_if_level_name_is_null(): void {
		$record = $this->get_record( Logger::DEBUG, 'Test', [ 'foo' => 'bar' ] );
		$handler = new QueryMonitorHandler();

		// Set level to null.
		$record['level_name'] = null;
		$handler->handle( $record );

		self::assertTrue( (bool) did_action( 'qm/debug' ) );
	}

	public function test_the_handler_defaults_to_debug_if_level_name_is_invalid(): void {
		$record = $this->get_record( Logger::DEBUG, 'Test', [ 'foo' => 'bar' ] );
		$handler = new QueryMonitorHandler();

		// Set level to invalid value.
		$record['level_name'] = 'invalid';
		$handler->handle( $record );

		self::assertTrue( (bool) did_action( 'qm/debug' ) );
	}

	public function get_levels(): array {
		return [
			'Debug level' => [ Logger::DEBUG, 'qm/debug' ],
			'Info level' => [ Logger::INFO, 'qm/info' ],
			'Notice level' => [ Logger::NOTICE, 'qm/notice' ],
			'Warning level' => [ Logger::WARNING, 'qm/warning' ],
			'Error level' => [ Logger::ERROR, 'qm/error' ],
			'Critical level' => [ Logger::CRITICAL, 'qm/critical' ],
			'Alert level' => [ Logger::ALERT, 'qm/alert' ],
			'Emergency level' => [ Logger::EMERGENCY, 'qm/emergency' ],
		];
	}

	/**
	 * @param int    $level Level to log.
	 * @param string $message Message to log.
	 * @param array   $context Context to log.
	 *
	 * @return array
	 */
	protected function get_record( int $level = Logger::WARNING, string $message = 'test', array $context = [] ): array {
		return [
			'message' => (string) $message,
			'context' => $context,
			'level' => $level,
			'level_name' => Logger::getLevelName( $level ),
			'channel' => 'test',
			'extra' => [],
		];
	}
}

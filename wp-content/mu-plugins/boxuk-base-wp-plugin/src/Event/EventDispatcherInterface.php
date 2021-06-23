<?php
/**
 * Interface for dispatching events.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Event;

interface EventDispatcherInterface {
	/**
	 * Dispatch a single event by it's name.
	 *
	 * @param string $name Name of the event to dispatch.
	 */
	public function dispatch( string $name ): void;

	/**
	 * Dispatch all events currently held in memory.
	 */
	public function dispatch_all(): void;
}

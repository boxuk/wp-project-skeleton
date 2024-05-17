<?php
/**
 * HookDispatcher for dispatching hook events.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\Event;

/**
 * HookDispatcher class.
 */
class HookDispatcher implements EventDispatcherInterface {
	/**
	 * Actions to dispatch.
	 *
	 * @var array
	 */
	private $actions = [];

	/**
	 * Filters to dispatch.
	 *
	 * @var array
	 */
	private $filters = [];

	/**
	 * Add action to the list of actions to dispatch.
	 *
	 * @param string   $hook_name Name of the hook to add.
	 * @param callable $callable Callable to fire when the hook is dispatched.
	 * @param int      $priority Priority of the hook.
	 * @param int      $accepted_args Number of args to pass to the callable.
	 */
	public function add_action( string $hook_name, $callable, int $priority, int $accepted_args ): void { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.callableFound
		$this->add_hook( 'actions', $hook_name, $callable, $priority, $accepted_args );
	}

	/**
	 * Add filter to the list of filters to dispatch.
	 *
	 * @param string   $hook_name Name of the hook to add.
	 * @param callable $callable Callable to fire when the hook is dispatched.
	 * @param int      $priority Priority of the hook.
	 * @param int      $accepted_args Number of args to pass to the callable.
	 */
	public function add_filter( string $hook_name, $callable, int $priority, int $accepted_args ): void { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.callableFound
		$this->add_hook( 'filters', $hook_name, $callable, $priority, $accepted_args );
	}

	/**
	 * Add the hook to either our list of actions or filters.
	 *
	 * @param string   $property_name Which list are we adding to, one of actions or filters.
	 * @param string   $hook_name Name of the hook to add.
	 * @param callable $callable Callable to fire when the hook is dispatched.
	 * @param int      $priority Priority of the hook.
	 * @param int      $accepted_args Number of args to pass to the callable.
	 */
	private function add_hook( string $property_name, string $hook_name, $callable, int $priority, int $accepted_args ): void { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.callableFound
		if ( \is_array( $callable ) && $callable[0] instanceof \Closure ) {
			$class = $callable[0]();
			$method = $callable[1];
			$callable = [ $class, $method ];
		}

		$this->{$property_name}[] = [
			'hook_name' => $hook_name,
			'callable' => $callable,
			'priority' => $priority,
			'accepted_args' => $accepted_args,
		];
	}

	/**
	 * Dispatch a single hook.
	 *
	 * @param string $name Name of the hook to dispatch.
	 */
	public function dispatch( string $name ): void {
		$action_index = array_search( $name, array_column( $this->actions, 'hook_name' ), true );

		if ( $action_index ) {
			$action = $this->actions[ $action_index ];
			add_action( $action['hook_name'], $action['callable'], $action['priority'], $action['accepted_args'] );
			return;
		}

		$filter_index = array_search( $name, array_column( $this->filters, 'hook_name' ), true );

		if ( $filter_index ) {
			$filter = $this->filters[ $filter_index ];
			add_filter( $filter['hook_name'], $filter['callable'], $filter['priority'], $filter['accepted_args'] );
		}
	}

	/**
	 * Dispatch all hook events.
	 */
	public function dispatch_all(): void {
		foreach ( $this->actions as $action ) {
			add_action( $action['hook_name'], $action['callable'], $action['priority'], $action['accepted_args'] );
		}

		foreach ( $this->filters as $filter ) {
			add_filter( $filter['hook_name'], $filter['callable'], $filter['priority'], $filter['accepted_args'] );
		}
	}
}

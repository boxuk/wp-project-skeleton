<?php
/**
 * A compiler pass for registering hook events by tagging.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

namespace BoxUk\Plugins\Base\DependencyInjection\Compiler;

use BoxUk\Plugins\Base\Event\HookDispatcher;
use Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * HookCompilerPass class.
 */
class HookCompilerPass implements CompilerPassInterface {
	private const TAG_NAME = 'wp_hook';
	private const VALID_HOOK_TYPES = [ 'action', 'filter' ];
	public const DEFAULT_HOOK_PRIORITY = 10;
	public const DEFAULT_ACCEPTED_ARGS = 1;

	/**
	 * Process the hook tags and configure the container accordingly.
	 *
	 * @param ContainerBuilder $container Container to configure.
	 *
	 * @throws InvalidHookException If an invalid hook or hook type is used.
	 */
	public function process( ContainerBuilder $container ): void {
		if ( ! $container->has( HookDispatcher::class ) ) {
			return;
		}

		$hook_dispatcher = $container->findDefinition( HookDispatcher::class );
		$tagged_services = $container->findTaggedServiceIds( self::TAG_NAME );

		foreach ( $tagged_services as $id => $tags ) {
			foreach ( $tags as $attributes ) {
				$attributes['priority'] = $attributes['priority'] ?? self::DEFAULT_HOOK_PRIORITY;
				$attributes['accepted_args'] = $attributes['accepted_args'] ?? self::DEFAULT_ACCEPTED_ARGS;

				// Handle action.
				if ( isset( $attributes['action'] ) ) {
					$this->handle_method_call( 'action', $id, $attributes, $hook_dispatcher );
				}

				// Handle filter.
				if ( isset( $attributes['filter'] ) ) {
					$this->handle_method_call( 'filter', $id, $attributes, $hook_dispatcher );
				}
			}
		}
	}

	/**
	 * Handle the actual adding of the method call on to the dispatcher for both actions and filters.
	 *
	 * @param string     $hook_type One of action or filter.
	 * @param string     $service_id ID of the service to use for the callable.
	 * @param array      $attributes Attributes from the tag for use on the method call.
	 * @param Definition $dispatcher The dispatcher to add the method call to.
	 *
	 * @throws InvalidHookException If something other than action or filter as passed as hook type.
	 */
	private function handle_method_call( string $hook_type, string $service_id, array $attributes, Definition $dispatcher ): void {
		if ( ! \in_array( $hook_type, self::VALID_HOOK_TYPES, true ) ) {
			throw new InvalidHookException( 'Hook type must be one of ' . implode( ', ', self::VALID_HOOK_TYPES ) );
		}

		// TODO: Validate hook is valid, using https://github.com/johnbillion/wp-hooks-generator to generate a list of valid hooks used in the application.

		// If no method provided, default to onHookName.
		if ( ! isset( $attributes['method'] ) ) {
			$attributes['method'] = $this->hook_to_method( $attributes[ $hook_type ] );
		}
		$dispatcher->addMethodCall(
			'add_' . $hook_type,
			[
				$attributes[ $hook_type ],
				[ new ServiceClosureArgument( new Reference( $service_id ) ), $attributes['method'] ],
				$attributes['priority'],
				$attributes['accepted_args'],
			]
		);
	}

	/**
	 * Converts hook names into method names, e.g. the_content will become on_the_content.
	 *
	 * @param string $hook Hook to convert to method name.
	 *
	 * @return string
	 */
	private function hook_to_method( string $hook ): string {
		$method = 'on_' . preg_replace_callback(
			[
				'/(?<=\b)[a-z]/i',
				'/[^a-z0-9]/i',
			],
			static function ( array $matches ): string {
				return strtolower( $matches[0] );
			},
			$hook
		);
		$method = preg_replace( '/[^a-z0-9]/i', '_', $method );

		return $method;
	}
}

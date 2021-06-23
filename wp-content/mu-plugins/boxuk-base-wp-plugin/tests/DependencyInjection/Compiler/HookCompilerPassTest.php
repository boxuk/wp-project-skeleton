<?php

namespace BoxUk\Plugins\Base\Tests\DependencyInjection\Compiler;

use BoxUk\Plugins\Base\DependencyInjection\Compiler\HookCompilerPass;
use BoxUk\Plugins\Base\Event\HookDispatcher;
use Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class HookCompilerPassTest extends \WP_UnitTestCase {
	public function test_no_method_calls_if_hook_dispatcher_not_registered(): void {
		$container = new ContainerBuilder();
		$container->register( HookDispatcher::class, 'stdClass');

		$registerHookListenersPass = new HookCompilerPass();
		$registerHookListenersPass->process( $container );

		$definition = $container->getDefinition( HookDispatcher::class );
		self::assertEmpty( $definition->getMethodCalls() );
	}

	public function test_action_makes_expected_method_call(): void {
		$container = new ContainerBuilder();
		$container->register( 'foo', \stdClass::class )->addTag( 'wp_hook', [ 'action' => 'init' ]);
		$container->register( HookDispatcher::class, 'stdClass');

		$registerHookListenersPass = new HookCompilerPass();
		$registerHookListenersPass->process( $container );

		$definition = $container->getDefinition( HookDispatcher::class );
		$expected_calls = [
			[
				'add_action',
				[
					'init',
					[ new ServiceClosureArgument( new Reference('foo') ), 'on_init' ],
					HookCompilerPass::DEFAULT_HOOK_PRIORITY,
					HookCompilerPass::DEFAULT_ACCEPTED_ARGS,
				],
			],
		];

		self::assertEquals( $expected_calls, $definition->getMethodCalls() );
	}

	public function test_filter_makes_expected_method_call(): void {
		$container = new ContainerBuilder();
		$container->register( 'foo', \stdClass::class )->addTag( 'wp_hook', [ 'filter' => 'the_content' ]);
		$container->register( HookDispatcher::class, 'stdClass');

		$registerHookListenersPass = new HookCompilerPass();
		$registerHookListenersPass->process( $container );

		$definition = $container->getDefinition( HookDispatcher::class );
		$expected_calls = [
			[
				'add_filter',
				[
					'the_content',
					[ new ServiceClosureArgument( new Reference('foo') ), 'on_the_content' ],
					HookCompilerPass::DEFAULT_HOOK_PRIORITY,
					HookCompilerPass::DEFAULT_ACCEPTED_ARGS,
				],
			],
		];

		self::assertEquals( $expected_calls, $definition->getMethodCalls() );
	}

	public function test_action_and_filter_can_be_tagged_in_single_service(): void {
		$container = new ContainerBuilder();
		$container->register( 'foo', \stdClass::class )
		          ->addTag( 'wp_hook', [ 'action' => 'init' ])
		          ->addTag( 'wp_hook', [ 'filter' => 'the_content' ]);
		$container->register( HookDispatcher::class, 'stdClass');

		$registerHookListenersPass = new HookCompilerPass();
		$registerHookListenersPass->process( $container );

		$definition = $container->getDefinition( HookDispatcher::class );
		$expected_calls = [
			[
				'add_action',
				[
					'init',
					[ new ServiceClosureArgument( new Reference('foo') ), 'on_init' ],
					HookCompilerPass::DEFAULT_HOOK_PRIORITY,
					HookCompilerPass::DEFAULT_ACCEPTED_ARGS,
				],
			],
			[
				'add_filter',
				[
					'the_content',
					[ new ServiceClosureArgument( new Reference('foo') ), 'on_the_content' ],
					HookCompilerPass::DEFAULT_HOOK_PRIORITY,
					HookCompilerPass::DEFAULT_ACCEPTED_ARGS,
				],
			],
		];

		self::assertEquals( $expected_calls, $definition->getMethodCalls() );
	}

	public function test_priority_can_be_changed(): void {
		$container = new ContainerBuilder();
		$container->register( 'foo', \stdClass::class )->addTag( 'wp_hook', [ 'action' => 'init', 'priority' => 99 ]);
		$container->register( HookDispatcher::class, 'stdClass');

		$registerHookListenersPass = new HookCompilerPass();
		$registerHookListenersPass->process( $container );

		$definition = $container->getDefinition( HookDispatcher::class );
		$expected_calls = [
			[
				'add_action',
				[
					'init',
					[ new ServiceClosureArgument( new Reference('foo') ), 'on_init' ],
					99,
					HookCompilerPass::DEFAULT_ACCEPTED_ARGS,
				],
			],
		];

		self::assertEquals( $expected_calls, $definition->getMethodCalls() );
	}

	public function test_accepted_args_can_be_changed(): void {
		$container = new ContainerBuilder();
		$container->register( 'foo', \stdClass::class )->addTag( 'wp_hook', [ 'action' => 'init', 'accepted_args' => 3 ]);
		$container->register( HookDispatcher::class, 'stdClass');

		$registerHookListenersPass = new HookCompilerPass();
		$registerHookListenersPass->process( $container );

		$definition = $container->getDefinition( HookDispatcher::class );
		$expected_calls = [
			[
				'add_action',
				[
					'init',
					[ new ServiceClosureArgument( new Reference('foo') ), 'on_init' ],
					HookCompilerPass::DEFAULT_HOOK_PRIORITY,
					3,
				],
			],
		];

		self::assertEquals( $expected_calls, $definition->getMethodCalls() );
	}
}

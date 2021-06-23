<?php
/**
 * HookListener an example of listening for hooks.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

namespace BoxUk\Mu\Plugins\SampleWithHooks;

/**
 * HookListener, convention for the method names is onHookName.
 */
class HookListener {
	/**
	 * Sample class to act upon.
	 *
	 * @var SampleWithHooksClass
	 */
	private $sample_with_hooks;

	/**
	 * Constructor.
	 *
	 * @param SampleWithHooksClass $sample_with_hooks Sample class to act upon.
	 */
	public function __construct( SampleWithHooksClass $sample_with_hooks ) {
		$this->sample_with_hooks = $sample_with_hooks;
	}

	/**
	 * Example of an action hook.
	 */
	public function on_init(): void {
		$this->sample_with_hooks->log_sample();
	}

	/**
	 * Example of a filter hook.
	 *
	 * @param string $content Existing content.
	 */
	public function on_the_content( string $content ): string {
		return $content . ' <p>' . $this->sample_with_hooks->get_sample() . '</p>';
	}

	/**
	 * Example of a filter hook.
	 */
	public function plugins_have_now_loaded(): void {
		$this->sample_with_hooks->log_sample();
	}
}

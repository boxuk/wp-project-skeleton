<?php
/**
 * HookListener an example of listening for hooks.
 *
 * @package BoxUk
 */

declare( strict_types=1 );

namespace BoxUk\Mu\Plugins\SampleWithTaggedHooks;

/**
 * HookListener, convention for the method names is onHookName.
 */
class HookListener {
	/**
	 * Sample class to act upon.
	 *
	 * @var SampleWithTaggedHooksClass
	 */
	private SampleWithTaggedHooksClass $sample_with_tagged_hooks;

	/**
	 * Constructor.
	 *
	 * @param SampleWithTaggedHooksClass $sample_with_tagged_hooks Sample class to act upon.
	 */
	public function __construct( SampleWithTaggedHooksClass $sample_with_tagged_hooks ) {
		$this->sample_with_tagged_hooks = $sample_with_tagged_hooks;
	}

	/**
	 * Example of an action hook.
	 */
	public function on_init(): void {
		$this->sample_with_tagged_hooks->log_sample();
	}

	/**
	 * Example of a filter hook.
	 *
	 * @param string $content Existing content.
	 */
	public function on_the_content( string $content ): string {
		return $content . ' <p>' . $this->sample_with_tagged_hooks->get_sample() . ' from tagged hook</p>';
	}

	/**
	 * Example of a filter hook.
	 */
	public function plugins_have_now_loaded(): void {
		$this->sample_with_tagged_hooks->log_sample();
	}
}

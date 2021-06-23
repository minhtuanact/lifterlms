<?php

namespace WebpConverter\Notice;

use WebpConverter\HookableInterface;

/**
 * Adds integration for list of notices.
 */
class NoticeFactory implements HookableInterface {

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		$this->set_integration( new ThanksNotice() );
		$this->set_integration( new WelcomeNotice() );
	}

	/**
	 * Sets integration for notice.
	 *
	 * @param NoticeInterface $notice .
	 *
	 * @return void
	 */
	private function set_integration( NoticeInterface $notice ) {
		( new NoticeIntegration( $notice ) )->init_hooks();
	}
}

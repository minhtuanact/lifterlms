<?php

namespace WebpConverter\Plugin\Activation;

use WebpConverter\Notice\NoticeIntegration;
use WebpConverter\Notice\ThanksNotice;
use WebpConverter\Notice\WelcomeNotice;

/**
 * Adds default options for plugin settings.
 */
class DefaultSettings {

	/**
	 * Sets default value for admin notices.
	 *
	 * @return void
	 */
	public function add_default_options() {
		( new NoticeIntegration( new ThanksNotice() ) )->set_default_value();
		( new NoticeIntegration( new WelcomeNotice() ) )->set_default_value();
	}
}

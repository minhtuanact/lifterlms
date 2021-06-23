<?php

namespace WebpConverter\Plugin\Uninstall;

use WebpConverter\Error\ErrorFactory;
use WebpConverter\Notice\ThanksNotice;
use WebpConverter\Notice\WelcomeNotice;
use WebpConverter\Plugin\Update;
use WebpConverter\Settings\SettingsSave;

/**
 * Removes options saved by plugin.
 */
class PluginSettings {

	/**
	 * Removes options from wp_options table.
	 *
	 * @return void
	 */
	public static function remove_plugin_settings() {
		delete_option( ThanksNotice::NOTICE_OPTION );
		delete_option( WelcomeNotice::NOTICE_OPTION );
		delete_option( ErrorFactory::ERRORS_CACHE_OPTION );
		delete_option( SettingsSave::SETTINGS_OPTION );
		delete_option( Update::VERSION_OPTION );
	}
}

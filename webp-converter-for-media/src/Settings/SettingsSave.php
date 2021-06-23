<?php

namespace WebpConverter\Settings;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\Conversion\Cron\Event;
use WebpConverter\Loader\LoaderAbstract;
use WebpConverter\Settings\Option\OptionFactory;
use WebpConverter\Conversion\Directory\DirectoryFactory;

/**
 * Supports saving plugin settings on plugin settings page.
 */
class SettingsSave extends PluginAccessAbstract implements PluginAccessInterface {

	const SETTINGS_OPTION   = 'webpc_settings';
	const SUBMIT_VALUE      = 'webpc_save';
	const NONCE_PARAM_KEY   = '_wpnonce';
	const NONCE_PARAM_VALUE = 'webpc-save';

	/**
	 * Saves plugin settings after submitting form on plugin settings page.
	 *
	 * @return void
	 */
	public function save_settings() {
		if ( ! isset( $_POST[ self::SUBMIT_VALUE ] )
			|| ! isset( $_REQUEST[ self::NONCE_PARAM_KEY ] )
			|| ! wp_verify_nonce( $_REQUEST[ self::NONCE_PARAM_KEY ], self::NONCE_PARAM_VALUE ) ) { // phpcs:ignore
			return;
		}

		update_option( self::SETTINGS_OPTION, ( new OptionFactory() )->get_values( false, $_POST ) );
		$settings = $this->get_plugin()->get_settings( true );
		$this->get_plugin()->get_settings_debug( true );
		$this->init_actions_after_save( $settings );
	}

	/**
	 * Runs actions needed after saving plugin settings.
	 *
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return void
	 */
	private function init_actions_after_save( array $settings ) {
		do_action( LoaderAbstract::ACTION_NAME, true );
		wp_clear_scheduled_hook( Event::CRON_ACTION );
		( new DirectoryFactory() )->remove_unused_output_directories( $settings['dirs'] );
	}
}

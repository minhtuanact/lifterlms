<?php

namespace WebpConverter\Plugin;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\HookableInterface;
use WebpConverter\Plugin\Deactivation\CronReset;
use WebpConverter\Plugin\Deactivation\Modal;
use WebpConverter\Plugin\Deactivation\RefreshLoader;

/**
 * Runs actions after plugin deactivation.
 */
class Deactivation extends PluginAccessAbstract implements PluginAccessInterface, HookableInterface {

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		register_deactivation_hook( WEBPC_FILE, [ $this, 'load_deactivation_actions' ] );

		$modal = new Modal();
		$modal->set_plugin( $this->get_plugin() );
		$modal->show_deactivation_modal();
	}

	/**
	 * Initializes actions when plugin is deactivated.
	 *
	 * @return void
	 * @internal
	 */
	public function load_deactivation_actions() {
		( new RefreshLoader() )->refresh_image_loader();
		( new CronReset() )->reset_cron_event();
	}
}

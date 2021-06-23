<?php

namespace WebpConverter\Plugin;

use WebpConverter\HookableInterface;
use WebpConverter\Plugin\Activation\DefaultSettings;
use WebpConverter\Plugin\Activation\RefreshLoader;
use WebpConverter\Plugin\Activation\WebpDirectory;

/**
 * Runs actions after plugin activation.
 */
class Activation implements HookableInterface {

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		register_activation_hook( WEBPC_FILE, [ $this, 'load_activation_actions' ] );
	}

	/**
	 * Initializes actions when plugin is activated.
	 *
	 * @return void
	 * @internal
	 */
	public function load_activation_actions() {
		( new WebpDirectory() )->create_directory_for_uploads_webp();
		( new DefaultSettings() )->add_default_options();
		( new RefreshLoader() )->refresh_image_loader();
	}
}

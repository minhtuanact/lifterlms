<?php

namespace WebpConverter\Plugin;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\HookableInterface;
use WebpConverter\Loader\LoaderAbstract;
use WebpConverter\Notice\WelcomeNotice;

/**
 * Runs actions after plugin update to new version.
 */
class Update extends PluginAccessAbstract implements PluginAccessInterface, HookableInterface {

	const VERSION_OPTION = 'webpc_latest_version';

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'admin_init', [ $this, 'run_actions_after_update' ], 0 );
	}

	/**
	 * Initializes actions after updating plugin to different version.
	 *
	 * @return void
	 * @internal
	 */
	public function run_actions_after_update() {
		$version = get_option( self::VERSION_OPTION, null );
		if ( $version === WEBPC_VERSION ) {
			return;
		}

		if ( $version !== null ) {
			WelcomeNotice::disable_notice();
		}

		do_action( LoaderAbstract::ACTION_NAME, true );
		update_option( self::VERSION_OPTION, WEBPC_VERSION );
	}
}

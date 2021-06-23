<?php

namespace WebpConverter\Plugin;

use WebpConverter\HookableInterface;
use WebpConverter\Plugin\Uninstall\DebugFiles;
use WebpConverter\Plugin\Uninstall\HtaccessFile;
use WebpConverter\Plugin\Uninstall\PluginSettings;
use WebpConverter\Plugin\Uninstall\WebpFiles;

/**
 * Runs actions before plugin uninstallation.
 */
class Uninstall implements HookableInterface {

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		register_uninstall_hook( WEBPC_FILE, [ 'WebpConverter\Plugin\Uninstall', 'load_uninstall_actions' ] );
	}

	/**
	 * Initializes actions when plugin is uninstalled.
	 *
	 * @return void
	 * @internal
	 */
	public static function load_uninstall_actions() {
		PluginSettings::remove_plugin_settings();
		HtaccessFile::remove_htaccess_file();
		WebpFiles::remove_webp_files();
		DebugFiles::remove_debug_files();
	}
}

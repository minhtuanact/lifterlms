<?php

namespace WebpConverter;

/**
 * Allows class to access handle to main plugin class.
 */
abstract class PluginAccessAbstract implements PluginAccessInterface {

	/**
	 * Object of main plugin class.
	 *
	 * @var WebpConverter
	 */
	private $plugin;

	/**
	 * Saves handler for object of plugin main class.
	 *
	 * @param WebpConverter $plugin Main class of plugin.
	 *
	 * @return void
	 */
	public function set_plugin( WebpConverter $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Saves handler for object of plugin main class and initializes integration with WordPress hooks.
	 *
	 * @param WebpConverter $plugin Main class of plugin.
	 *
	 * @return void
	 */
	public function set_plugin_hookable( WebpConverter $plugin ) {
		$this->set_plugin( $plugin );
		if ( $this instanceof HookableInterface ) {
			$this->init_hooks();
		}
	}

	/**
	 * Returns handler for object of plugin main class.
	 *
	 * @return WebpConverter Main class of plugin.
	 */
	public function get_plugin(): WebpConverter {
		return $this->plugin;
	}
}

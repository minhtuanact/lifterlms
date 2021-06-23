<?php

namespace WebpConverter;

/**
 * Interface for class having access handle to main plugin class.
 */
interface PluginAccessInterface {

	/**
	 * Saves handler for object of plugin main class.
	 *
	 * @param WebpConverter $plugin Main class of plugin.
	 *
	 * @return void
	 */
	public function set_plugin( WebpConverter $plugin );

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @param WebpConverter $plugin Main class of plugin.
	 *
	 * @return void
	 */
	public function set_plugin_hookable( WebpConverter $plugin );

	/**
	 * Returns handler for object of plugin main class.
	 *
	 * @return WebpConverter Main class of plugin.
	 */
	public function get_plugin(): WebpConverter;
}

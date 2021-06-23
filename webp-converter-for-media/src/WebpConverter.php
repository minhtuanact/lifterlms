<?php

namespace WebpConverter;

use WebpConverter\Action;
use WebpConverter\Conversion;
use WebpConverter\Conversion\Cron;
use WebpConverter\Error;
use WebpConverter\Conversion\Media;
use WebpConverter\Notice;
use WebpConverter\Plugin;
use WebpConverter\Settings;

/**
 * Class initializes all plugin actions.
 */
class WebpConverter {

	/**
	 * Handler of class with plugin settings.
	 *
	 * @var Settings\Option\OptionFactory
	 */
	private $settings_object;

	/**
	 * Cached settings of plugin.
	 *
	 * @var array[]
	 */
	private $plugin_settings = null;

	/**
	 * Cached settings of plugin for debugging.
	 *
	 * @var array[]
	 */
	private $plugin_settings_debug = null;

	/**
	 * WebpConverter constructor.
	 */
	public function __construct() {
		$this->settings_object = new Settings\Option\OptionFactory();

		( new Action\ConvertAttachment() )->set_plugin_hookable( $this );
		( new Action\ConvertDir() )->init_hooks();
		( new Action\ConvertPaths() )->set_plugin_hookable( $this );
		( new Action\DeletePaths() )->init_hooks();
		( new Action\RegenerateAll() )->set_plugin_hookable( $this );
		( new Conversion\Directory\DirectoryFactory() )->init_hooks();
		( new Conversion\DirectoryFiles() )->set_plugin_hookable( $this );
		( new Conversion\Endpoint\EndpointFactory() )->set_plugin_hookable( $this );
		( new Conversion\SkipExists() )->set_plugin_hookable( $this );
		( new Conversion\SkipLarger() )->set_plugin_hookable( $this );
		( new Cron\Event() )->set_plugin_hookable( $this );
		( new Cron\Schedules() )->init_hooks();
		( new Error\ErrorFactory() )->set_plugin_hookable( $this );
		( new Notice\NoticeFactory() )->init_hooks();
		( new Loader\LoaderFactory() )->set_plugin_hookable( $this );
		( new Media\Delete() )->init_hooks();
		( new Media\Upload() )->set_plugin_hookable( $this );
		( new Plugin\Activation() )->init_hooks();
		( new Plugin\Deactivation() )->set_plugin_hookable( $this );
		( new Plugin\Links() )->init_hooks();
		( new Plugin\Uninstall() )->init_hooks();
		( new Plugin\Update() )->set_plugin_hookable( $this );
		( new Settings\Page\PageFactory() )->set_plugin_hookable( $this );
	}

	/**
	 * Returns settings of plugin.
	 *
	 * @param bool $is_force_refresh Force refresh of current settings?
	 *
	 * @return mixed[] Settings of plugin.
	 */
	public function get_settings( bool $is_force_refresh = false ): array {
		if ( ( $this->plugin_settings === null ) || $is_force_refresh ) {
			$this->plugin_settings = $this->settings_object->get_values();
		}
		return $this->plugin_settings;
	}

	/**
	 * Returns settings of plugin using for debugging.
	 *
	 * @param bool $is_force_refresh Force refresh of current settings?
	 *
	 * @return mixed[] Settings of plugin.
	 */
	public function get_settings_debug( bool $is_force_refresh = false ): array {
		if ( ( $this->plugin_settings_debug === null ) || $is_force_refresh ) {
			$this->plugin_settings_debug = $this->settings_object->get_values( true );
		}
		return $this->plugin_settings_debug;
	}
}

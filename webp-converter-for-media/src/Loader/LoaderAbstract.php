<?php

namespace WebpConverter\Loader;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\Conversion\Format\FormatFactory;

/**
 * Abstract class for class that supports method of loading images.
 */
abstract class LoaderAbstract extends PluginAccessAbstract implements PluginAccessInterface, LoaderInterface {

	const ACTION_NAME = 'webpc_refresh_loader';

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
	}

	/**
	 * Returns mime types for loader.
	 *
	 * @return string[] Output formats with mime types.
	 */
	public function get_mime_types(): array {
		$settings = $this->get_plugin()->get_settings();
		return ( new FormatFactory() )->get_mime_types( $settings['output_formats'] );
	}
}

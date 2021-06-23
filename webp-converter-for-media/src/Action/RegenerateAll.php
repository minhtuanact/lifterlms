<?php

namespace WebpConverter\Action;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\HookableInterface;
use WebpConverter\Conversion\Endpoint\PathsEndpoint;

/**
 * Initializes conversion of all image sizes in all directories.
 */
class RegenerateAll extends PluginAccessAbstract implements PluginAccessInterface, HookableInterface {

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'webpc_regenerate_all', [ $this, 'regenerate_all_images' ] );
	}

	/**
	 * Converts all images in directories set in options to output formats.
	 *
	 * @return void
	 * @internal
	 */
	public function regenerate_all_images() {
		$paths_endpoint = new PathsEndpoint();
		$paths_endpoint->set_plugin( $this->get_plugin() );

		do_action( 'webpc_convert_paths', $paths_endpoint->get_paths( true ) );
	}
}

<?php

namespace WebpConverter\Conversion\Endpoint;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\HookableInterface;

/**
 * Initializes integration for all endpoints.
 */
class EndpointFactory extends PluginAccessAbstract implements PluginAccessInterface, HookableInterface {

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		$this->set_integration( new PathsEndpoint() );
		$this->set_integration( new RegenerateEndpoint() );
	}

	/**
	 * Sets integration for endpoint.
	 *
	 * @param EndpointInterface $endpoint .
	 *
	 * @return void
	 */
	private function set_integration( EndpointInterface $endpoint ) {
		$endpoint->set_plugin( $this->get_plugin() );
		( new EndpointIntegration( $endpoint ) )->init_hooks();
	}
}

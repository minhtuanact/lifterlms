<?php

namespace WebpConverter\Conversion\Endpoint;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;

/**
 * Abstract class for class that supports image conversion method.
 */
abstract class EndpointAbstract extends PluginAccessAbstract implements PluginAccessInterface, EndpointInterface {

	/**
	 * Returns list of params for endpoint.
	 *
	 * @return array[] Params of endpoint.
	 */
	public function get_route_args(): array {
		return [];
	}

	/**
	 * Returns URL of endpoint.
	 *
	 * @return string Endpoint URL.
	 */
	public function get_route_url(): string {
		return get_rest_url(
			null,
			sprintf(
				'%1$s/%2$s?_wpnonce=%3$s',
				EndpointIntegration::ROUTE_NAMESPACE,
				$this->get_route_name(),
				wp_create_nonce( 'wp_rest' )
			)
		);
	}
}

<?php

namespace WebpConverter\Conversion\Endpoint;

use WebpConverter\Conversion\Method\MethodIntegrator;

/**
 * Supports endpoint for converting list of paths to images.
 */
class RegenerateEndpoint extends EndpointAbstract implements EndpointInterface {

	/**
	 * Returns route of endpoint.
	 *
	 * @return string Endpoint route.
	 */
	public function get_route_name(): string {
		return 'regenerate';
	}

	/**
	 * Returns list of params for endpoint.
	 *
	 * @return array[] Params of endpoint.
	 */
	public function get_route_args(): array {
		return [
			'paths' => [
				'description'       => 'Array of file paths (server paths)',
				'required'          => true,
				'default'           => [],
				'validate_callback' => function ( $value ) {
					return ( is_array( $value ) && $value );
				},
			],
		];
	}

	/**
	 * Returns response to endpoint.
	 *
	 * @param \WP_REST_Request $request REST request object.
	 *
	 * @return \WP_REST_Response|\WP_Error REST response object or WordPress Error object.
	 * @internal
	 */
	public function get_route_response( \WP_REST_Request $request ) {
		$params = $request->get_params();
		$data   = $this->convert_images( $params['paths'] );
		if ( $data !== false ) {
			return new \WP_REST_Response(
				$data,
				200
			);
		} else {
			return new \WP_Error(
				'webpc_rest_api_error',
				'',
				[
					'status' => 405,
				]
			);
		}
	}

	/**
	 * Initializes image conversion to output formats.
	 *
	 * @param string[] $paths Server paths of source images.
	 *
	 * @return array[]|false Status of conversion.
	 */
	public function convert_images( array $paths ) {
		$method_integrator = new MethodIntegrator();
		$method_integrator->set_plugin( $this->get_plugin() );

		$response = $method_integrator->init_conversion( $paths );
		if ( $response === null ) {
			return false;
		}
		return $response;
	}
}

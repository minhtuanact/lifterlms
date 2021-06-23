<?php

namespace WebpConverter\Conversion\Endpoint;

/**
 * Supports endpoint to get list of image paths to be converted.
 */
class PathsEndpoint extends EndpointAbstract implements EndpointInterface {

	const PATHS_PER_REQUEST = 10;

	/**
	 * Returns route of endpoint.
	 *
	 * @return string Endpoint route.
	 */
	public function get_route_name(): string {
		return 'paths';
	}

	/**
	 * Returns list of params for endpoint.
	 *
	 * @return array[] Params of endpoint.
	 */
	public function get_route_args(): array {
		return [
			'regenerate_force' => [
				'description'       => 'Option to force all images to be converted again (set `1` to enable)',
				'required'          => false,
				'default'           => false,
				'sanitize_callback' => function ( $value ) {
					return ( $value === '1' );
				},
			],
		];
	}

	/**
	 * Returns response to endpoint.
	 *
	 * @param \WP_REST_Request $request REST request object.
	 *
	 * @return \WP_REST_Response REST response object or WordPress Error object.
	 * @internal
	 */
	public function get_route_response( \WP_REST_Request $request ) {
		$params      = $request->get_params();
		$skip_exists = isset( $params['regenerate_force'] ) && ! $params['regenerate_force'];

		$data = $this->get_paths( $skip_exists, self::PATHS_PER_REQUEST );
		return new \WP_REST_Response(
			$data,
			200
		);
	}

	/**
	 * Returns list of server paths of source images to be converted.
	 *
	 * @param bool $skip_exists Skip converted images?
	 * @param int  $chunk_size  Number of files per one conversion request.
	 *
	 * @return array[] Server paths of source images.
	 */
	public function get_paths( bool $skip_exists = false, int $chunk_size = 0 ): array {
		$settings = $this->get_plugin()->get_settings();
		$dirs     = array_filter(
			array_map(
				function ( $dir_name ) {
					return apply_filters( 'webpc_dir_path', '', $dir_name );
				},
				$settings['dirs']
			)
		);

		$list = [];
		foreach ( $dirs as $dir_path ) {
			$paths = apply_filters( 'webpc_dir_files', [], $dir_path, $skip_exists );
			$list  = array_merge( $list, $paths );
		}

		if ( $chunk_size === 0 ) {
			return $list;
		}
		return array_chunk( $list, $chunk_size );
	}
}

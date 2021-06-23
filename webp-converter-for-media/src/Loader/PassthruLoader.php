<?php

namespace WebpConverter\Loader;

/**
 * Supports method of loading images using .php file as Pass Thru.
 */
class PassthruLoader extends LoaderAbstract implements LoaderInterface {

	const LOADER_TYPE   = 'passthru';
	const PATH_LOADER   = '/webpc-passthru.php';
	const LOADER_SOURCE = '/includes/passthru.php';

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'get_header', [ $this, 'start_buffer' ] );
	}

	/**
	 * Returns status if loader is active.
	 *
	 * @return bool Is loader active?
	 */
	public function is_active_loader(): bool {
		$settings = $this->get_plugin()->get_settings();
		return ( isset( $settings['loader_type'] ) && ( $settings['loader_type'] === self::LOADER_TYPE ) );
	}

	/**
	 * Initializes actions for activating loader.
	 *
	 * @param bool $is_debug Is debugging?
	 *
	 * @return void
	 */
	public function activate_loader( bool $is_debug = false ) {
		$path_source = WEBPC_PATH . self::LOADER_SOURCE;
		$source_code = ( is_readable( $path_source ) ) ? file_get_contents( $path_source ) ?: '' : '';
		if ( ! $source_code ) {
			return;
		}

		$path_dir_uploads = apply_filters( 'webpc_dir_name', '', 'uploads' );
		$path_dir_webp    = apply_filters( 'webpc_dir_name', '', 'webp' );
		$upload_suffix    = implode( '/', array_diff( explode( '/', $path_dir_uploads ), explode( '/', $path_dir_webp ) ) );

		$source_code = preg_replace(
			'/(PATH_UPLOADS(?:\s+)= \')(\')/',
			'$1' . $path_dir_uploads . '$2',
			$source_code
		);
		$source_code = preg_replace(
			'/(PATH_UPLOADS_WEBP(?:\s+)= \')(\')/',
			'$1' . $path_dir_webp . '/' . $upload_suffix . '$2',
			$source_code ?: ''
		);
		$source_code = preg_replace(
			'/(MIME_TYPES(?:\s+)= \')(\')/',
			'$1' . json_encode( $this->get_mime_types() ) . '$2',
			$source_code ?: ''
		);

		$dir_output = dirname( apply_filters( 'webpc_dir_path', '', 'uploads' ) );
		if ( is_writable( $dir_output ) ) {
			file_put_contents( $dir_output . self::PATH_LOADER, $source_code );
		}
	}

	/**
	 * Initializes actions for deactivating loader.
	 *
	 * @return void
	 */
	public function deactivate_loader() {
		$dir_output = dirname( apply_filters( 'webpc_dir_path', '', 'uploads' ) ) . self::PATH_LOADER;
		if ( is_writable( $dir_output ) ) {
			unlink( $dir_output );
		}
	}

	/**
	 * Opens buffer in which all output is stored.
	 *
	 * @return void
	 * @internal
	 */
	public function start_buffer() {
		ob_start(
			function( $buffer ) {
				return $this->update_image_urls( $buffer );
			}
		);
	}

	/**
	 * Replaces URLs to source images in output buffer.
	 *
	 * @param string $buffer   Contents of output buffer.
	 * @param bool   $is_debug Is debugging?
	 *
	 * @return string Contents of output buffer.
	 * @internal
	 */
	public function update_image_urls( string $buffer, bool $is_debug = false ): string {
		if ( ! $this->is_active_loader() ) {
			return $buffer;
		}

		$settings   = ( ! $is_debug ) ? $this->get_plugin()->get_settings() : $this->get_plugin()->get_settings_debug();
		$extensions = implode( '|', $settings['extensions'] ?? [] );
		if ( ! $extensions || ( ! $source_dir = self::get_loader_url() )
			|| ( ! $allowed_dirs = $this->get_allowed_dirs( $settings ) ) ) {
			return $buffer;
		}

		$dir_paths   = str_replace( '/', '\\/', implode( '|', $allowed_dirs ) );
		$has_nocache = apply_filters( 'webpc_passthru_url_nocache', true );

		return preg_replace(
			'/(https?:\/\/(?:[^\s()"\']+)(?:' . $dir_paths . ')(?:[^\s()"\']+)\.(?:' . $extensions . '))/',
			$source_dir . '?src=$1' . ( ( $has_nocache ) ? '&nocache=1' : '' ),
			$buffer
		) ?: '';
	}

	/**
	 * Returns URL for Passthru loader.
	 *
	 * @return string|null URL of source PHP file.
	 */
	public static function get_loader_url() {
		if ( ! $source_dir = dirname( apply_filters( 'webpc_dir_url', '', 'uploads' ) ) ) {
			return null;
		}
		return $source_dir . self::PATH_LOADER;
	}

	/**
	 * Returns list of directories for which redirection from source images to output images.
	 *
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return string[] List of directories names.
	 */
	private function get_allowed_dirs( array $settings ): array {
		$dirs = [];
		foreach ( $settings['dirs'] as $dir ) {
			$dirs[] = apply_filters( 'webpc_dir_name', '', $dir );
		}
		return array_filter( $dirs );
	}
}

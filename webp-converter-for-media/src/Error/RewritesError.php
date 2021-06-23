<?php

namespace WebpConverter\Error;

use WebpConverter\Conversion\OutputPath;
use WebpConverter\Conversion\Format\WebpFormat;
use WebpConverter\Loader\LoaderAbstract;
use WebpConverter\Helper\FileLoader;

/**
 * Checks for configuration errors about non-working HTTP rewrites.
 */
class RewritesError extends ErrorAbstract implements ErrorInterface {

	const PATH_SOURCE_FILE_PNG  = '/assets/img/icon-test.png';
	const PATH_SOURCE_FILE_WEBP = '/assets/img/icon-test.webp';
	const PATH_OUTPUT_FILE_PNG  = '/webp-converter-for-media-test.png';
	const PATH_OUTPUT_FILE_PNG2 = '/webp-converter-for-media-test.png2';

	/**
	 * Returns list of error codes.
	 *
	 * @return string[] Error codes.
	 */
	public function get_error_codes(): array {
		$settings = $this->get_plugin()->get_settings();
		$errors   = [];
		if ( ! $settings['dirs'] || ! $settings['output_formats'] ) {
			return $errors;
		}

		$this->convert_images_for_debug();
		do_action( LoaderAbstract::ACTION_NAME, true, true );

		if ( $this->if_redirects_are_works() !== true ) {
			if ( $this->if_bypassing_apache_is_active() === true ) {
				$errors[] = 'bypassing_apache';
			} else {
				$errors[] = 'rewrites_not_working';
			}
		} elseif ( $this->if_redirects_are_cached() === true ) {
			$errors[] = 'rewrites_cached';
		}

		do_action( LoaderAbstract::ACTION_NAME, true );

		return $errors;
	}

	/**
	 * Converts and saves files needed for testing.
	 *
	 * @return void
	 */
	private function convert_images_for_debug() {
		$uploads_dir    = apply_filters( 'webpc_dir_path', '', 'uploads' );
		$path_file_png  = $uploads_dir . self::PATH_OUTPUT_FILE_PNG;
		$path_file_png2 = $uploads_dir . self::PATH_OUTPUT_FILE_PNG2;
		if ( ! is_writable( $uploads_dir ) ) {
			return;
		}

		if ( ! file_exists( $path_file_png ) || ! file_exists( $path_file_png2 ) ) {
			copy( WEBPC_PATH . self::PATH_SOURCE_FILE_PNG, $path_file_png );
			copy( WEBPC_PATH . self::PATH_SOURCE_FILE_PNG, $path_file_png2 );
		}

		if ( ( $output_path = OutputPath::get_path( $path_file_png, true, WebpFormat::FORMAT_EXTENSION ) )
			&& ! file_exists( $output_path ) ) {
			copy( WEBPC_PATH . self::PATH_SOURCE_FILE_WEBP, $output_path );
		}
		if ( ( $output_path = OutputPath::get_path( $path_file_png2, true, WebpFormat::FORMAT_EXTENSION ) )
			&& ! file_exists( $output_path ) ) {
			copy( WEBPC_PATH . self::PATH_SOURCE_FILE_WEBP, $output_path );
		}
	}

	/**
	 * Checks if redirects to output images are works.
	 *
	 * @return bool Verification status.
	 */
	private function if_redirects_are_works(): bool {
		$uploads_dir = apply_filters( 'webpc_dir_path', '', 'uploads' );
		$uploads_url = apply_filters( 'webpc_dir_url', '', 'uploads' );
		$ver_param   = sprintf( 'ver=%s', time() );

		$file_size = FileLoader::get_file_size_by_path(
			$uploads_dir . self::PATH_OUTPUT_FILE_PNG
		);
		$file_webp = FileLoader::get_file_size_by_url(
			$uploads_url . self::PATH_OUTPUT_FILE_PNG,
			$this->get_plugin(),
			true,
			$ver_param
		);

		return ( $file_webp < $file_size );
	}

	/**
	 * Checks if bypassing of redirects to output images is exists.
	 *
	 * @return bool Verification status.
	 */
	private function if_bypassing_apache_is_active(): bool {
		$uploads_url = apply_filters( 'webpc_dir_url', '', 'uploads' );
		$ver_param   = sprintf( '&?ver=%s', time() );

		$file_png  = FileLoader::get_file_size_by_url(
			$uploads_url . self::PATH_OUTPUT_FILE_PNG,
			$this->get_plugin(),
			true,
			$ver_param
		);
		$file_png2 = FileLoader::get_file_size_by_url(
			$uploads_url . self::PATH_OUTPUT_FILE_PNG2,
			$this->get_plugin(),
			true,
			$ver_param
		);

		return ( $file_png > $file_png2 );
	}

	/**
	 * Checks if redirects to output images are cached.
	 *
	 * @return bool Verification status.
	 */
	private function if_redirects_are_cached(): bool {
		$uploads_url = apply_filters( 'webpc_dir_url', '', 'uploads' );
		$ver_param   = sprintf( 'ver=%s', time() );

		$file_webp     = FileLoader::get_file_size_by_url(
			$uploads_url . self::PATH_OUTPUT_FILE_PNG,
			$this->get_plugin(),
			true,
			$ver_param
		);
		$file_original = FileLoader::get_file_size_by_url(
			$uploads_url . self::PATH_OUTPUT_FILE_PNG,
			$this->get_plugin(),
			false,
			$ver_param
		);

		return ( ( $file_webp > 0 ) && ( $file_webp === $file_original ) );
	}
}

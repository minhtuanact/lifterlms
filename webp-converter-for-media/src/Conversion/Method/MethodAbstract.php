<?php

namespace WebpConverter\Conversion\Method;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\Conversion\Exception;
use WebpConverter\Conversion\Method\MethodInterface;
use WebpConverter\Conversion\OutputPath;
use WebpConverter\Conversion\SkipLarger;

/**
 * Abstract class for class that supports endpoint.
 */
abstract class MethodAbstract extends PluginAccessAbstract implements PluginAccessInterface, MethodInterface {

	/**
	 * Messages of errors that occurred during conversion.
	 *
	 * @var string[]
	 */
	private $errors = [];

	/**
	 * Sum of size of source images before conversion.
	 *
	 * @var int
	 */
	private $size_before = 0;

	/**
	 * Sum of size of output images after conversion.
	 *
	 * @var int
	 */
	private $size_after = 0;

	/**
	 * Returns errors generated during image conversion.
	 *
	 * @return string[] Errors messages.
	 */
	public function get_errors(): array {
		return $this->errors;
	}

	/**
	 * Returns weight of source files before converting.
	 *
	 * @return int Source files weight.
	 */
	public function get_size_before(): int {
		return $this->size_before;
	}

	/**
	 * Returns weight of output files after converting.
	 *
	 * @return int Output files weight.
	 */
	public function get_size_after(): int {
		return $this->size_after;
	}

	/**
	 * Converts source paths to output formats.
	 *
	 * @param string[] $paths Server paths of source images.
	 *
	 * @return void
	 */
	public function convert_paths( array $paths ) {
		$output_formats = $this->get_plugin()->get_settings()['output_formats'];
		foreach ( $output_formats as $output_format ) {
			foreach ( $paths as $path ) {
				try {
					$response = $this->convert_path( $path, $output_format );

					$this->size_before += $response['data']['size_before'];
					$this->size_after  += $response['data']['size_after'];
				} catch ( \Exception $e ) {
					$this->errors[] = $e->getMessage();
				}
			}
		}
	}

	/**
	 * Converts source path to output formats.
	 *
	 * @param string $path   Server path of source image.
	 * @param string $format Extension of output format.
	 *
	 * @return mixed[] Results data of conversion.
	 * @throws Exception\SourcePathException
	 * @throws Exception\OutputPathException
	 */
	public function convert_path( string $path, string $format ): array {
		ini_set( 'memory_limit', '1G' ); // phpcs:ignore
		if ( strpos( ini_get( 'disable_functions' ) ?: '', 'set_time_limit' ) === false ) {
			set_time_limit( 120 );
		}

		try {
			$source_path = $this->get_image_source_path( $path );
			$image       = $this->create_image_by_path( $source_path );
			$output_path = $this->get_image_output_path( $source_path, $format );

			if ( file_exists( $output_path . '.' . SkipLarger::DELETED_FILE_EXTENSION ) ) {
				unlink( $output_path . '.' . SkipLarger::DELETED_FILE_EXTENSION );
			}

			$this->convert_image_to_output( $image, $source_path, $output_path, $format );
			do_action( 'webpc_convert_after', $output_path, $source_path );

			return [
				'success' => true,
				'message' => null,
				'data'    => $this->get_conversion_stats( $source_path, $output_path ),
			];
		} catch ( \Exception $e ) {
			$features = $this->get_plugin()->get_settings()['features'] ?? [];
			if ( in_array( 'debug_enabled', $features ) ) {
				error_log( sprintf( 'WebP Converter for Media: %s', $e->getMessage() ) ); // phpcs:ignore
			}

			throw $e;
		}
	}

	/**
	 * Checks server path of source image.
	 *
	 * @param string $source_path Server path of source image.
	 *
	 * @return string Server path of source image.
	 * @throws Exception\SourcePathException
	 */
	public function get_image_source_path( string $source_path ): string {
		$path = urldecode( $source_path );
		if ( ! is_readable( $path ) ) {
			throw new Exception\SourcePathException( $path );
		}

		return $path;
	}

	/**
	 * Returns server path for output image.
	 *
	 * @param string $source_path Server path of source image.
	 * @param string $format      Extension of output format.
	 *
	 * @return string Server path of output image.
	 * @throws Exception\OutputPathException
	 */
	public function get_image_output_path( string $source_path, string $format ): string {
		if ( ! $output_path = OutputPath::get_path( $source_path, true, $format ) ) {
			throw new Exception\OutputPathException( $source_path );
		}

		return $output_path;
	}

	/**
	 * Returns results data of conversion.
	 *
	 * @param string $source_path Server path of source image.
	 * @param string $output_path Server path of output image.
	 *
	 * @return int[] Results data of conversion.
	 */
	public function get_conversion_stats( string $source_path, string $output_path ): array {
		$size_before = filesize( $source_path );
		$size_after  = ( file_exists( $output_path ) ) ? filesize( $output_path ) : $size_before;

		return [
			'size_before' => $size_before ?: 0,
			'size_after'  => $size_after ?: 0,
		];
	}
}

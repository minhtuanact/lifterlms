<?php

namespace WebpConverter\Conversion\Method;

use WebpConverter\Conversion\Format\WebpFormat;
use WebpConverter\Conversion\Format\AvifFormat;
use WebpConverter\Conversion\Exception;

/**
 * Supports image conversion method using GD library.
 */
class GdMethod extends MethodAbstract implements MethodInterface {

	const METHOD_NAME        = 'gd';
	const MAX_METHOD_QUALITY = 99.9;

	/**
	 * Returns name of conversion method.
	 *
	 * @return string Method name.
	 */
	public function get_name(): string {
		return self::METHOD_NAME;
	}

	/**
	 * Returns label of conversion method.
	 *
	 * @return string Method label.
	 */
	public function get_label(): string {
		return 'GD';
	}

	/**
	 * Returns status of whether method is installed.
	 *
	 * @return bool Is method installed?
	 */
	public static function is_method_installed(): bool {
		return ( extension_loaded( 'gd' ) );
	}

	/**
	 * Returns status of whether method is active.
	 *
	 * @param string $format Extension of output format.
	 *
	 * @return bool Is method active?
	 */
	public static function is_method_active( string $format ): bool {
		if ( ! self::is_method_installed() || ! ( $function = self::get_format_function( $format ) ) ) {
			return false;
		}
		return function_exists( $function );
	}

	/**
	 * Returns name of function to convert source image to output image.
	 *
	 * @param string $format Extension of output format.
	 *
	 * @return string|null Function name using for conversion.
	 */
	private static function get_format_function( string $format ) {
		switch ( $format ) {
			case WebpFormat::FORMAT_EXTENSION:
				return 'imagewebp';
			case AvifFormat::FORMAT_EXTENSION:
				return 'imageavif';
			default:
				return null;
		}
	}

	/**
	 * Creates image object based on source path.
	 *
	 * @param string $source_path Server path of source image.
	 *
	 * @return resource Image object.
	 * @throws Exception\ExtensionUnsupportedException
	 * @throws Exception\FunctionUnavailableException
	 * @throws Exception\ImageInvalidException
	 */
	public function create_image_by_path( string $source_path ) {
		$extension = strtolower( pathinfo( $source_path, PATHINFO_EXTENSION ) );
		$methods   = apply_filters(
			'webpc_gd_create_methods',
			[
				'imagecreatefromjpeg' => [ 'jpg', 'jpeg' ],
				'imagecreatefrompng'  => [ 'png' ],
				'imagecreatefromgif'  => [ 'gif' ],
			]
		);
		$settings  = $this->get_plugin()->get_settings();

		foreach ( $methods as $method => $extensions ) {
			if ( ! in_array( $extension, $settings['extensions'] ) || ! in_array( $extension, $extensions ) ) {
				continue;
			} elseif ( ! function_exists( $method ) ) {
				throw new Exception\FunctionUnavailableException( $method );
			} elseif ( ! $image = @$method( $source_path ) ) { // phpcs:ignore
				throw new Exception\ImageInvalidException( $source_path );
			}
		}

		if ( ! isset( $image ) ) {
			throw new Exception\ExtensionUnsupportedException( [ $extension, $source_path ] );
		}

		return $this->update_image_resource( $image, $extension );
	}

	/**
	 * Updates image object before converting to output format.
	 *
	 * @param resource $image     Image object.
	 * @param string   $extension Extension of output format.
	 *
	 * @return resource Image object.
	 * @throws Exception\FunctionUnavailableException
	 */
	private function update_image_resource( $image, string $extension ) {
		if ( ! function_exists( 'imageistruecolor' ) ) {
			throw new Exception\FunctionUnavailableException( 'imageistruecolor' );
		}

		if ( ! imageistruecolor( $image ) ) {
			if ( ! function_exists( 'imagepalettetotruecolor' ) ) {
				throw new Exception\FunctionUnavailableException( 'imagepalettetotruecolor' );
			}
			imagepalettetotruecolor( $image );
		}

		switch ( $extension ) {
			case 'png':
				if ( ! function_exists( 'imagealphablending' ) ) {
					throw new Exception\FunctionUnavailableException( 'imagealphablending' );
				}
				imagealphablending( $image, false );

				if ( ! function_exists( 'imagesavealpha' ) ) {
					throw new Exception\FunctionUnavailableException( 'imagesavealpha' );
				}
				imagesavealpha( $image, true );
				break;
		}

		return $image;
	}

	/**
	 * Converts image and saves to output location.
	 *
	 * @param resource $image       Image object.
	 * @param string   $source_path Server path of source image.
	 * @param string   $output_path Server path for output image.
	 * @param string   $format      Extension of output format.
	 *
	 * @return void
	 * @throws Exception\ConversionErrorException
	 * @throws Exception\FunctionUnavailableException
	 * @throws Exception\ResolutionOversizeException
	 */
	public function convert_image_to_output( $image, string $source_path, string $output_path, string $format ) {
		$function = self::get_format_function( $format );
		if ( $function === null ) {
			return;
		}

		$image          = apply_filters( 'webpc_gd_before_saving', $image, $source_path );
		$settings       = $this->get_plugin()->get_settings();
		$output_quality = min( $settings['quality'], self::MAX_METHOD_QUALITY );

		if ( ! function_exists( $function ) ) {
			throw new Exception\FunctionUnavailableException( $function );
		} elseif ( ( imagesx( $image ) > 8192 ) || ( imagesy( $image ) > 8192 ) ) {
			throw new Exception\ResolutionOversizeException( $source_path );
		} elseif ( is_callable( $function ) && ! $function( $image, $output_path, $output_quality ) ) {
			throw new Exception\ConversionErrorException( $source_path );
		}

		if ( filesize( $output_path ) % 2 === 1 ) {
			file_put_contents( $output_path, "\0", FILE_APPEND );
		}
	}
}

<?php

namespace WebpConverter\Conversion\Method;

use WebpConverter\Conversion\Format\WebpFormat;
use WebpConverter\Conversion\Format\AvifFormat;
use WebpConverter\Conversion\Exception;

/**
 * Supports image conversion method using Imagick library.
 */
class ImagickMethod extends MethodAbstract implements MethodInterface {

	const METHOD_NAME        = 'imagick';
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
		/* translators: %s method name */
		return sprintf( __( '%s (recommended)', 'webp-converter-for-media' ), 'Imagick' );
	}

	/**
	 * Returns status of whether method is installed.
	 *
	 * @return bool Is method installed?
	 */
	public static function is_method_installed(): bool {
		return ( extension_loaded( 'imagick' ) && class_exists( '\Imagick' ) );
	}

	/**
	 * Returns status of whether method is active.
	 *
	 * @param string $format Extension of output format.
	 *
	 * @return bool Is method active?
	 */
	public static function is_method_active( string $format ): bool {
		if ( ! self::is_method_installed()
			|| ! ( $formats = ( new \Imagick() )->queryformats() )
			|| ! ( $extension = self::get_format_extension( $format ) ) ) {
			return false;
		}
		return in_array( $extension, $formats );
	}

	/**
	 * Returns name of supported format to convert source image to output image.
	 *
	 * @param string $format Extension of output format.
	 *
	 * @return string|null Supported format using for conversion.
	 */
	private static function get_format_extension( string $format ) {
		switch ( $format ) {
			case WebpFormat::FORMAT_EXTENSION:
				return 'WEBP';
			case AvifFormat::FORMAT_EXTENSION:
				return 'AVIF';
			default:
				return null;
		}
	}

	/**
	 * Creates image object based on source path.
	 *
	 * @param string $source_path Server path of source image.
	 *
	 * @return \Imagick Image object.
	 * @throws Exception\ImagickUnavailableException
	 * @throws Exception\ExtensionUnsupportedException
	 * @throws Exception\ImageInvalidException
	 */
	public function create_image_by_path( string $source_path ) {
		$extension = strtolower( pathinfo( $source_path, PATHINFO_EXTENSION ) );
		$settings  = $this->get_plugin()->get_settings();

		if ( ! extension_loaded( 'imagick' ) || ! class_exists( 'Imagick' ) ) {
			throw new Exception\ImagickUnavailableException();
		} elseif ( ! in_array( $extension, $settings['extensions'] ) ) {
			throw new Exception\ExtensionUnsupportedException( [ $extension, $source_path ] );
		}

		try {
			return new \Imagick( $source_path );
		} catch ( \ImagickException $e ) {
			throw new Exception\ImageInvalidException( $source_path );
		}
	}

	/**
	 * Converts image and saves to output location.
	 *
	 * @param \Imagick $image       Image object.
	 * @param string   $source_path Server path of source image.
	 * @param string   $output_path Server path for output image.
	 * @param string   $format      Extension of output format.
	 *
	 * @return void
	 * @throws Exception\ConversionErrorException
	 * @throws Exception\ImagickNotSupportWebpException
	 */
	public function convert_image_to_output( $image, string $source_path, string $output_path, string $format ) {
		$extension      = self::get_format_extension( $format );
		$image          = apply_filters( 'webpc_imagick_before_saving', $image, $source_path );
		$settings       = $this->get_plugin()->get_settings();
		$output_quality = min( $settings['quality'], self::MAX_METHOD_QUALITY );

		if ( ! in_array( $extension, $image->queryFormats() ) ) {
			throw new Exception\ImagickNotSupportWebpException();
		}

		$image->setImageFormat( $extension );
		if ( ! in_array( 'keep_metadata', $settings['features'] ) ) {
			$image->stripImage();
		}
		$image->setImageCompressionQuality( $output_quality );
		$blob = $image->getImageBlob();

		if ( ! file_put_contents( $output_path, $blob ) ) {
			throw new Exception\ConversionErrorException( $source_path );
		}
	}
}

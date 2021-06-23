<?php

namespace WebpConverter\Conversion\Method;

use WebpConverter\PluginAccessInterface;
use WebpConverter\Conversion\Exception;

/**
 * Interface for class that supports image conversion method.
 */
interface MethodInterface extends PluginAccessInterface {

	/**
	 * Returns name of conversion method.
	 *
	 * @return string Method name.
	 */
	public function get_name(): string;

	/**
	 * Returns label of conversion method.
	 *
	 * @return string Method label.
	 */
	public function get_label(): string;

	/**
	 * Returns status of whether method is installed.
	 *
	 * @return bool Is method installed?
	 */
	public static function is_method_installed(): bool;

	/**
	 * Returns status of whether method is active.
	 *
	 * @param string $format Extension of output format.
	 *
	 * @return bool Is method active?
	 */
	public static function is_method_active( string $format ): bool;

	/**
	 * Returns errors generated during image conversion.
	 *
	 * @return string[] Errors messages.
	 */
	public function get_errors(): array;

	/**
	 * Returns weight of source files before converting.
	 *
	 * @return int Source files weight.
	 */
	public function get_size_before(): int;

	/**
	 * Returns weight of output files after converting.
	 *
	 * @return int Output files weight.
	 */
	public function get_size_after(): int;

	/**
	 * Converts source paths to output formats.
	 *
	 * @param string[] $paths Server paths of source images.
	 *
	 * @return void
	 */
	public function convert_paths( array $paths );

	/**
	 * Converts source path to output formats.
	 *
	 * @param string $path   Server path of source image.
	 * @param string $format Extension of output format.
	 *
	 * @return mixed[] Results data of conversion.
	 * @throws Exception\OutputPathException
	 * @throws Exception\SourcePathException
	 */
	public function convert_path( string $path, string $format ): array;

	/**
	 * Checks server path of source image.
	 *
	 * @param string $source_path Server path of source image.
	 *
	 * @return string Server path of source image.
	 * @throws Exception\SourcePathException
	 */
	public function get_image_source_path( string $source_path ): string;

	/**
	 * Creates image object based on source path.
	 *
	 * @param string $source_path Server path of source image.
	 *
	 * @return mixed Image object.
	 * @throws Exception\ExtensionUnsupportedException
	 * @throws Exception\FunctionUnavailableException
	 * @throws Exception\ImageInvalidException
	 */
	public function create_image_by_path( string $source_path );

	/**
	 * Returns server path for output image.
	 *
	 * @param string $source_path Server path of source image.
	 * @param string $format      Extension of output format.
	 *
	 * @return string Server path of output image.
	 * @throws Exception\OutputPathException
	 */
	public function get_image_output_path( string $source_path, string $format ): string;

	/**
	 * Converts image and saves to output location.
	 *
	 * @param mixed  $image       Image object.
	 * @param string $source_path Server path of source image.
	 * @param string $output_path Server path for output image.
	 * @param string $format      Extension of output format.
	 *
	 * @return void
	 */
	public function convert_image_to_output( $image, string $source_path, string $output_path, string $format );

	/**
	 * Returns results data of conversion.
	 *
	 * @param string $source_path Server path of source image.
	 * @param string $output_path Server path of output image.
	 *
	 * @return int[] Results data of conversion.
	 */
	public function get_conversion_stats( string $source_path, string $output_path ): array;
}

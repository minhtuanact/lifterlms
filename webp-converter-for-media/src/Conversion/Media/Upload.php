<?php

namespace WebpConverter\Conversion\Media;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\HookableInterface;

/**
 * Initializes image conversion when uploading images to media library.
 */
class Upload extends PluginAccessAbstract implements PluginAccessInterface, HookableInterface {

	/**
	 * Paths of converted images.
	 *
	 * @var string[]
	 */
	private $converted_paths = [];

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_filter( 'wp_update_attachment_metadata', [ $this, 'init_attachment_convert' ], 10, 2 );
	}

	/**
	 * Initializes converting attachment images while attachment is uploaded.
	 *
	 * @param mixed[] $data          Updated attachment meta data.
	 * @param int     $attachment_id ID of attachment.
	 *
	 * @return mixed[] Attachment meta data.
	 * @internal
	 */
	public function init_attachment_convert( array $data, int $attachment_id ): array {
		if ( ! $data || ! isset( $data['file'] ) || ! isset( $data['sizes'] ) ) {
			return $data;
		}

		$paths = $this->get_sizes_paths( $data );
		$paths = apply_filters( 'webpc_attachment_paths', $paths, $attachment_id );
		$paths = apply_filters( 'webpc_files_paths', $paths, false );

		$paths                 = array_diff( $paths, $this->converted_paths );
		$this->converted_paths = array_merge( $this->converted_paths, $paths );
		$this->init_conversion( $paths );

		return $data;
	}

	/**
	 * Returns server paths of attachment image sizes.
	 *
	 * @param mixed[] $data Updated attachment meta data.
	 *
	 * @return string[] Server paths of source images.
	 */
	private function get_sizes_paths( array $data ): array {
		$directory = $this->get_attachment_directory( $data['file'] );
		$list      = [];

		$list[] = $directory . basename( $data['file'] );
		foreach ( $data['sizes'] as $size ) {
			$path = $directory . $size['file'];
			if ( ! in_array( $path, $list ) ) {
				$list[] = $path;
			}
		}
		return array_values( array_unique( $list ) );
	}

	/**
	 * Returns server path of source image.
	 *
	 * @param string $path Relative path of source image.
	 *
	 * @return string Server path of source image.
	 */
	private function get_attachment_directory( string $path ): string {
		$upload = wp_upload_dir();
		$source = rtrim( $upload['basedir'], '/\\' ) . '/' . rtrim( dirname( $path ), '/\\' ) . '/';
		$source = str_replace( '\\', '/', $source );
		return $source;
	}

	/**
	 * Initializes conversion of attachment image sizes.
	 *
	 * @param string[] $paths Server paths of source images.
	 *
	 * @return void
	 */
	private function init_conversion( array $paths ) {
		$settings = $this->get_plugin()->get_settings();

		if ( in_array( 'cron_conversion', $settings['features'] ) ) {
			wp_schedule_single_event( ( time() + 1 ), 'webpc_convert_paths', [ $paths ] );
		} else {
			do_action( 'webpc_convert_paths', $paths );
		}
	}
}

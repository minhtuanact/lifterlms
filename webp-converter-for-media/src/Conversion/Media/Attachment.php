<?php

namespace WebpConverter\Conversion\Media;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;

/**
 * Returns all image paths for attachment.
 */
class Attachment extends PluginAccessAbstract implements PluginAccessInterface {

	/**
	 * Current upload directory path and URL.
	 *
	 * @var string[]
	 */
	private $upload_dir;

	/**
	 * Available intermediate image size names.
	 *
	 * @var string[]
	 */
	private $image_sizes;

	/**
	 * Attachment constructor.
	 */
	public function __construct() {
		$this->upload_dir  = wp_upload_dir();
		$this->image_sizes = get_intermediate_image_sizes();
	}

	/**
	 * Returns server paths to source images of attachment.
	 *
	 * @param int $attachment_id ID of attachment.
	 *
	 * @return string[] Server paths of source images.
	 */
	public function get_attachment_paths( int $attachment_id ): array {
		$settings = $this->get_plugin()->get_settings();
		return $this->get_paths_by_attachment( $attachment_id, $settings );
	}

	/**
	 * Returns server paths to source images of attachment by file extensions.
	 *
	 * @param int     $post_id  ID of attachment.
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return string[] Server paths of source images.
	 */
	private function get_paths_by_attachment( int $post_id, array $settings ): array {
		$list     = [];
		$metadata = wp_get_attachment_metadata( $post_id );
		if ( ! $metadata ) {
			return $list;
		}

		$extension = strtolower( pathinfo( $metadata['file'], PATHINFO_EXTENSION ) );
		if ( ! isset( $metadata['file'] )
			|| ! in_array( $extension, $settings['extensions'] ) ) {
			return $list;
		}

		$paths = $this->get_paths_by_sizes( $post_id, $metadata['file'] );
		$paths = apply_filters( 'webpc_attachment_paths', $paths, $post_id );
		$paths = apply_filters( 'webpc_files_paths', $paths, false );
		return $paths;
	}

	/**
	 * Returns unique server paths to source images of attachment.
	 *
	 * @param int    $post_id ID of attachment.
	 * @param string $path    Path of source image.
	 *
	 * @return string[] Server paths of source images.
	 */
	private function get_paths_by_sizes( int $post_id, string $path ): array {
		$list   = [];
		$list[] = str_replace( '\\', '/', implode( '/', [ $this->upload_dir['basedir'], $path ] ) );

		foreach ( $this->image_sizes as $size ) {
			$src = wp_get_attachment_image_src( $post_id, $size );
			if ( ! is_array( $src ) || ! is_string( $src[0] ) ) {
				continue;
			}

			$url    = str_replace( $this->upload_dir['baseurl'], $this->upload_dir['basedir'], $src[0] );
			$url    = str_replace( '\\', '/', $url );
			$list[] = $url;
		}
		return array_values( array_unique( $list ) );
	}
}

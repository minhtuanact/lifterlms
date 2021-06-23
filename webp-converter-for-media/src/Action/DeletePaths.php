<?php

namespace WebpConverter\Action;

use WebpConverter\HookableInterface;
use WebpConverter\Conversion\OutputPath;
use WebpConverter\Conversion\SkipLarger;

/**
 * Deletes all images in list of paths.
 */
class DeletePaths implements HookableInterface {

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'webpc_delete_paths', [ $this, 'delete_files_by_paths' ] );
	}

	/**
	 * Deletes images from output directory.
	 *
	 * @param string[] $paths Server paths of output images.
	 *
	 * @return void
	 * @internal
	 */
	public function delete_files_by_paths( array $paths ) {
		foreach ( $paths as $path ) {
			$this->delete_file_by_path( $path );
		}
	}

	/**
	 * Deletes image from output directory.
	 *
	 * @param string $path Server path of output image.
	 *
	 * @return void
	 */
	private function delete_file_by_path( string $path ) {
		if ( ! ( $source_paths = OutputPath::get_paths( $path ) ) ) {
			return;
		}

		foreach ( $source_paths as $source_path ) {
			if ( is_writable( $source_path ) ) {
				unlink( $source_path );
			} elseif ( is_writable( $source_path . '.' . SkipLarger::DELETED_FILE_EXTENSION ) ) {
				unlink( $source_path . '.' . SkipLarger::DELETED_FILE_EXTENSION );
			}
		}
	}
}

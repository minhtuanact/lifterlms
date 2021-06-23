<?php

namespace WebpConverter\Plugin\Uninstall;

use WebpConverter\Error\RewritesError;

/**
 * Removes files needed for debugging from /uploads directory.
 */
class DebugFiles {

	/**
	 * Removes files used for debugging from /uploads directory.
	 *
	 * @return void
	 */
	public static function remove_debug_files() {
		$uploads_dir = apply_filters( 'webpc_dir_path', '', 'uploads' );

		if ( is_writable( $uploads_dir . RewritesError::PATH_OUTPUT_FILE_PNG ) ) {
			unlink( $uploads_dir . RewritesError::PATH_OUTPUT_FILE_PNG );
		}
		if ( is_writable( $uploads_dir . RewritesError::PATH_OUTPUT_FILE_PNG2 ) ) {
			unlink( $uploads_dir . RewritesError::PATH_OUTPUT_FILE_PNG2 );
		}
	}
}

<?php

namespace WebpConverter\Action;

use WebpConverter\HookableInterface;

/**
 * Initializes conversion of all image sizes in directory.
 */
class ConvertDir implements HookableInterface {

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'webpc_convert_dir', [ $this, 'convert_files_by_directory' ], 10, 2 );
	}

	/**
	 * Converts all images in directory to output formats.
	 *
	 * @param string $dir_path    Server path of directory.
	 * @param bool   $skip_exists Skip converted images?
	 *
	 * @return void
	 * @internal
	 */
	public function convert_files_by_directory( string $dir_path, bool $skip_exists = true ) {
		$paths = apply_filters( 'webpc_dir_filess', [], $dir_path, $skip_exists );
		do_action( 'webpc_convert_paths', $paths );
	}
}

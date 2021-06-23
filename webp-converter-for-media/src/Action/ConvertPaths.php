<?php

namespace WebpConverter\Action;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\HookableInterface;
use WebpConverter\Conversion\Method\MethodIntegrator;

/**
 * Initializes conversion of all images in list of paths.
 */
class ConvertPaths extends PluginAccessAbstract implements PluginAccessInterface, HookableInterface {

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'webpc_convert_paths', [ $this, 'convert_files_by_paths' ] );
	}

	/**
	 * Converts all given images to output formats.
	 *
	 * @param string[] $paths Server paths of images.
	 *
	 * @return void
	 * @internal
	 */
	public function convert_files_by_paths( array $paths ) {
		$method_integrator = new MethodIntegrator();
		$method_integrator->set_plugin( $this->get_plugin() );
		$method_integrator->init_conversion( $this->remove_paths_of_excluded_dirs( $paths ) );
	}

	/**
	 * Removes paths of source images in excluded directories.
	 *
	 * @param string[] $paths Server paths of images.
	 *
	 * @return string[] Server paths of images.
	 */
	private function remove_paths_of_excluded_dirs( array $paths ): array {
		$excluded_dirs = apply_filters( 'webpc_dir_excluded', [] );
		foreach ( $paths as $path_index => $path ) {
			foreach ( $excluded_dirs as $excluded_dir ) {
				$dir_pattern = str_replace( '.', '\.', $excluded_dir );
				if ( ! preg_match( '/(\\\\|\/)(' . $dir_pattern . ')(\\\\|\/)/', $path ) ) {
					continue;
				}
				unset( $paths[ $path_index ] );
				break;
			}
		}
		return $paths;
	}
}

<?php

namespace WebpConverter\Conversion;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\HookableInterface;
use WebpConverter\Conversion\Exception;

/**
 * Deletes output after conversion if it is larger than original.
 */
class SkipLarger extends PluginAccessAbstract implements PluginAccessInterface, HookableInterface {

	const DELETED_FILE_EXTENSION = 'deleted';

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'webpc_convert_after', [ $this, 'remove_image_if_is_larger' ], 10, 2 );
	}

	/**
	 * Removes converted output image if it is larger than original image.
	 *
	 * @param string $webp_path     Server path of output image.
	 * @param string $original_path Server path of source image.
	 *
	 * @return void
	 * @throws Exception\LargerThanOriginalException
	 * @internal
	 */
	public function remove_image_if_is_larger( string $webp_path, string $original_path ) {
		if ( ( ! $settings = $this->get_plugin()->get_settings() )
			|| ! in_array( 'only_smaller', $settings['features'] )
			|| ( ! file_exists( $webp_path ) || ! file_exists( $original_path ) )
			|| ( filesize( $webp_path ) < filesize( $original_path ) ) ) {
			return;
		}

		$file = fopen( $webp_path . '.' . self::DELETED_FILE_EXTENSION, 'w' );
		if ( $file !== false ) {
			fclose( $file );
			unlink( $webp_path );
		}

		throw new Exception\LargerThanOriginalException( $original_path );
	}
}

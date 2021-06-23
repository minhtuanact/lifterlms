<?php

namespace WebpConverter\Helper;

/**
 * Supports loading views from /templates directory.
 */
class ViewLoader {

	const ROOT_DIRECTORY = WEBPC_PATH;

	/**
	 * Loads view with given variables.
	 *
	 * @param string  $path   Server path relative to plugin root directory.
	 * @param mixed[] $params Variables for view.
	 *
	 * @return void
	 */
	public static function load_view( string $path, array $params = [] ) {
		extract( $params ); // phpcs:ignore
		$view_path = sprintf( '%1$s/templates/%2$s', self::ROOT_DIRECTORY, $path );
		if ( file_exists( $view_path ) ) {
			/** @noinspection PhpIncludeInspection */ // phpcs:ignore
			require_once $view_path;
		}
	}
}

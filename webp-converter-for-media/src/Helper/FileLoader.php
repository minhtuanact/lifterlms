<?php

namespace WebpConverter\Helper;

use WebpConverter\Loader\PassthruLoader;
use WebpConverter\WebpConverter;

/**
 * Returns size of image downloaded based on server path or URL.
 */
class FileLoader {

	/**
	 * Checks size of file by sending request using active image loader.
	 *
	 * @param string        $url         URL of image.
	 * @param WebpConverter $plugin      .
	 * @param bool          $set_headers Whether to send headers to confirm that browser supports WebP?
	 * @param string        $extra_param Additional GET param.
	 *
	 * @return int Size of retrieved file.
	 */
	public static function get_file_size_by_url( string $url, WebpConverter $plugin, bool $set_headers = true, string $extra_param = '' ): int {
		$headers = [
			'Accept: image/webp',
			'Referer: ' . WEBPC_URL,
		];

		$loader = new PassthruLoader();
		$loader->set_plugin( $plugin );

		$image_url = $loader->update_image_urls( $url, true );
		if ( $extra_param ) {
			$image_url .= ( ( strpos( $image_url, '?' ) !== false ) ? '&' : '?' ) . $extra_param;
		}

		return self::get_file_size_for_loaded_file( $image_url, ( $set_headers ) ? $headers : [] );
	}

	/**
	 * Returns size of file.
	 *
	 * @param string $path Server path of file.
	 *
	 * @return int Size of file.
	 */
	public static function get_file_size_by_path( string $path ): int {
		return ( file_exists( $path ) ) ? ( filesize( $path ) ?: 0 ) : 0;
	}

	/**
	 * Checks size of file by sending cURL request.
	 *
	 * @param string   $url     URL of image.
	 * @param string[] $headers Headers for cURL connection.
	 *
	 * @return int Size of retrieved file.
	 */
	private static function get_file_size_for_loaded_file( string $url, array $headers ): int {
		foreach ( wp_get_nocache_headers() as $header_key => $header_value ) {
			$headers[] = sprintf( '%s: %s', $header_key, $header_value );
		}

		$ch = curl_init( $url );
		if ( $ch === false ) {
			return 0;
		}

		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt( $ch, CURLOPT_FRESH_CONNECT, 1 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		$response = curl_exec( $ch );
		$code     = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );

		return ( $code === 200 )
			? strlen( is_string( $response ) ? $response : '' )
			: 0;
	}
}

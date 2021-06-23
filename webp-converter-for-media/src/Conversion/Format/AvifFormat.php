<?php

namespace WebpConverter\Conversion\Format;

/**
 * Supports AVIF as output format for images.
 */
class AvifFormat extends FormatAbstract implements FormatInterface {

	const FORMAT_EXTENSION = 'avif';

	/**
	 * Returns extension of output format.
	 *
	 * @return string Format extension
	 */
	public function get_extension(): string {
		return self::FORMAT_EXTENSION;
	}

	/**
	 * Returns mime type of output format.
	 *
	 * @return string Format mime type
	 */
	public function get_mime_type(): string {
		return 'image/avif';
	}

	/**
	 * Returns label of output format.
	 *
	 * @return string Format label.
	 */
	public function get_label(): string {
		/* translators: %s format name */
		return sprintf( __( '%s (planned soon)', 'webp-converter-for-media' ), 'AVIF' );
	}

	/**
	 * Returns status is output format available?
	 *
	 * @param string $conversion_method Type of conversion method.
	 *
	 * @return bool Is format available?
	 */
	public function is_available( string $conversion_method ): bool {
		return false;
	}
}

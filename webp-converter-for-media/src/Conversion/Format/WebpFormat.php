<?php

namespace WebpConverter\Conversion\Format;

/**
 * Supports WebP as output format for images.
 */
class WebpFormat extends FormatAbstract implements FormatInterface {

	const FORMAT_EXTENSION = 'webp';

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
		return 'image/webp';
	}

	/**
	 * Returns label of output format.
	 *
	 * @return string Format label.
	 */
	public function get_label(): string {
		return 'WebP';
	}
}

<?php

namespace WebpConverter\Conversion\Directory;

/**
 * Supports data about /gallery directory.
 */
class GalleryDirectory extends DirectoryAbstract implements DirectoryInterface {

	const DIRECTORY_TYPE = 'gallery';
	const DIRECTORY_PATH = 'wp-content/gallery';

	/**
	 * Returns type of directory.
	 *
	 * @return string Directory type.
	 */
	public function get_type(): string {
		return self::DIRECTORY_TYPE;
	}

	/**
	 * Returns label of directory.
	 *
	 * @return string Directory label.
	 */
	public function get_label(): string {
		return '/' . self::DIRECTORY_TYPE;
	}

	/**
	 * Returns relative path of directory.
	 *
	 * @return string Relative path of directory.
	 */
	public function get_relative_path(): string {
		return self::DIRECTORY_PATH;
	}
}

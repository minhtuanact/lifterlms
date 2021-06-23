<?php

namespace WebpConverter\Conversion\Directory;

/**
 * Supports data about /uploads directory.
 */
class UploadsDirectory extends DirectoryAbstract implements DirectoryInterface {

	const DIRECTORY_TYPE = 'uploads';
	const DIRECTORY_PATH = 'wp-content/uploads';

	/**
	 * Returns type of directory.
	 *
	 * @return string Directory type.
	 */
	public function get_type(): string {
		return self::DIRECTORY_TYPE;
	}

	/**
	 * Returns status if directory is available.
	 *
	 * @return bool Directory is available?
	 */
	public function is_available(): bool {
		return true;
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

<?php

namespace WebpConverter\Conversion\Directory;

/**
 * Supports data about /uploads-webpc directory.
 */
class UploadsWebpcDirectory extends DirectoryAbstract implements DirectoryInterface {

	const DIRECTORY_TYPE = 'webp';
	const DIRECTORY_PATH = 'wp-content/uploads-webpc';

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
	 * Returns status if directory is destined for output.
	 *
	 * @return bool Directory for output?
	 */
	public function is_output_directory(): bool {
		return true;
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

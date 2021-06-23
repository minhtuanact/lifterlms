<?php

namespace WebpConverter\Conversion\Directory;

/**
 * Supports data about /themes directory.
 */
class ThemesDirectory extends DirectoryAbstract implements DirectoryInterface {

	const DIRECTORY_TYPE = 'themes';
	const DIRECTORY_PATH = 'wp-content/themes';

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

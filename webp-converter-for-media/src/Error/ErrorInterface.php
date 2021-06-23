<?php

namespace WebpConverter\Error;

use WebpConverter\PluginAccessInterface;

/**
 * Interface for class that checks for configuration errors.
 */
interface ErrorInterface extends PluginAccessInterface {

	/**
	 * Returns list of error codes.
	 *
	 * @return string[] Error codes.
	 */
	public function get_error_codes(): array;
}

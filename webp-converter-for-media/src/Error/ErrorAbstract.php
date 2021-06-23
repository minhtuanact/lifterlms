<?php

namespace WebpConverter\Error;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;

/**
 * Abstract class for class that checks for configuration errors.
 */
abstract class ErrorAbstract extends PluginAccessAbstract implements PluginAccessInterface, ErrorInterface {

	/**
	 * Returns list of error codes.
	 *
	 * @return string[] Error codes.
	 */
	public function get_error_codes(): array {
		return [];
	}
}

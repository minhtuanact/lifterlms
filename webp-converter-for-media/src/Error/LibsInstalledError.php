<?php

namespace WebpConverter\Error;

use WebpConverter\Conversion\Method\GdMethod;
use WebpConverter\Conversion\Method\ImagickMethod;

/**
 * Checks for configuration errors about non-installed methods for converting images.
 */
class LibsInstalledError extends ErrorAbstract implements ErrorInterface {

	/**
	 * Returns list of error codes.
	 *
	 * @return string[] Error codes.
	 */
	public function get_error_codes(): array {
		$errors = [];

		if ( $this->if_libs_are_installed() !== true ) {
			$errors[] = 'libs_not_installed';
		}
		return $errors;
	}

	/**
	 * Checks if any conversion method is installed.
	 *
	 * @return bool Verification status.
	 */
	private function if_libs_are_installed(): bool {
		return ( GdMethod::is_method_installed() || ImagickMethod::is_method_installed() );
	}
}

<?php

namespace WebpConverter\Error;

/**
 * Checks for configuration errors about disabled REST API.
 */
class RestapiError extends ErrorAbstract implements ErrorInterface {

	/**
	 * Returns list of error codes.
	 *
	 * @return string[] Error codes.
	 */
	public function get_error_codes(): array {
		$errors = [];

		if ( $this->if_rest_api_is_enabled() !== true ) {
			$errors[] = 'rest_api_disabled';
		}
		return $errors;
	}

	/**
	 * Checks if REST API is enabled.
	 *
	 * @return bool Verification status.
	 */
	private function if_rest_api_is_enabled(): bool {
		return ( ( apply_filters( 'rest_enabled', true ) === true )
			&& ( apply_filters( 'rest_jsonp_enabled', true ) === true )
			&& ( apply_filters( 'rest_authentication_errors', true ) === true ) );
	}
}

<?php

namespace WebpConverter\Conversion\Exception;

/**
 * Handles "server_configuration" exception when converting images.
 */
class FunctionUnavailableException extends ExceptionAbstract implements ExceptionInterface {

	const ERROR_MESSAGE = 'Server configuration: "%s" function is not available.';
	const ERROR_CODE    = 'server_configuration';

	/**
	 * Returns message of error.
	 *
	 * @param string[] $values Params from class constructor.
	 *
	 * @return string Error message.
	 */
	public function get_error_message( array $values ): string {
		return sprintf( self::ERROR_MESSAGE, $values[0] );
	}

	/**
	 * Returns status of error.
	 *
	 * @return string Error status.
	 */
	public function get_error_status(): string {
		return self::ERROR_CODE;
	}
}

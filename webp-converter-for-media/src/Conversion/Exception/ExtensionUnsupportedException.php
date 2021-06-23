<?php

namespace WebpConverter\Conversion\Exception;

/**
 * Handles "unsupported_extension" exception when converting images.
 */
class ExtensionUnsupportedException extends ExceptionAbstract implements ExceptionInterface {

	const ERROR_MESSAGE = 'Unsupported extension "%s" for file "%s".';
	const ERROR_CODE    = 'unsupported_extension';

	/**
	 * Returns message of error.
	 *
	 * @param string[] $values Params from class constructor.
	 *
	 * @return string Error message.
	 */
	public function get_error_message( array $values ): string {
		return sprintf( self::ERROR_MESSAGE, $values[0], $values[1] );
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

<?php

namespace WebpConverter\Conversion\Exception;

/**
 * Handles "output_path" exception when converting images.
 */
class OutputPathException extends ExceptionAbstract implements ExceptionInterface {

	const ERROR_MESSAGE = 'An error occurred creating destination directory for "%s" file.';
	const ERROR_CODE    = 'output_path';

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

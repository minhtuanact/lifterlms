<?php

namespace WebpConverter\Conversion\Exception;

/**
 * Handles "file_unreadable" exception when converting images.
 */
class SourcePathException extends ExceptionAbstract implements ExceptionInterface {

	const ERROR_MESSAGE = 'Source path "%s" for image does not exist or is unreadable.';
	const ERROR_CODE    = 'file_unreadable';

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

<?php

namespace WebpConverter\Conversion\Exception;

/**
 * Handles "invalid_image" exception when converting images.
 */
class ImageInvalidException extends ExceptionAbstract implements ExceptionInterface {

	const ERROR_MESSAGE = '"%s" is not a valid image file.';
	const ERROR_CODE    = 'invalid_image';

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

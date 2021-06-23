<?php

namespace WebpConverter\Conversion\Exception;

/**
 * Handles "convert_error" exception when converting images.
 */
class ConversionErrorException extends ExceptionAbstract implements ExceptionInterface {

	const ERROR_MESSAGE = 'Error occurred while converting image: "%s".';
	const ERROR_CODE    = 'convert_error';

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

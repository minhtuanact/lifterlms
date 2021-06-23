<?php

namespace WebpConverter\Conversion\Exception;

/**
 * Handles "max_resolution" exception when converting images.
 */
class ResolutionOversizeException extends ExceptionAbstract implements ExceptionInterface {

	const ERROR_MESSAGE = 'Image is larger than maximum 8K resolution: "%s".';
	const ERROR_CODE    = 'max_resolution';

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

<?php

namespace WebpConverter\Conversion\Exception;

/**
 * Handles "larger_than_original" exception when converting images.
 */
class LargerThanOriginalException extends ExceptionAbstract implements ExceptionInterface {

	const ERROR_MESSAGE = 'Image "%s" converted to WebP is larger than original and has been deleted.';
	const ERROR_CODE    = 'larger_than_original';

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

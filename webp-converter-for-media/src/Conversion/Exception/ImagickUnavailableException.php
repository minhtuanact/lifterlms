<?php

namespace WebpConverter\Conversion\Exception;

/**
 * Handles "server_configuration" exception when converting images.
 */
class ImagickUnavailableException extends ExceptionAbstract implements ExceptionInterface {

	const ERROR_MESSAGE = 'Server configuration: Imagick module is not available with this PHP installation.';
	const ERROR_CODE    = 'server_configuration';

	/**
	 * Returns message of error.
	 *
	 * @param string[] $values Params from class constructor.
	 *
	 * @return string Error message.
	 */
	public function get_error_message( array $values ): string {
		return self::ERROR_MESSAGE;
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

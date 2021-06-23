<?php

namespace WebpConverter\Conversion\Exception;

/**
 * Abstract class for class that supports exception when converting images.
 */
abstract class ExceptionAbstract extends \Exception implements ExceptionInterface {

	/**
	 * ExceptionInterface constructor.
	 *
	 * @param string[]|string $value Params of exception.
	 */
	final public function __construct( $value = [] ) {
		$this->code = $this->get_error_status();
		parent::__construct( $this->get_error_message( (array) $value ) );
	}
}

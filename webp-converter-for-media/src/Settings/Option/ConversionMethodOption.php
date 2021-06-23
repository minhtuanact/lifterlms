<?php

namespace WebpConverter\Settings\Option;

use WebpConverter\Conversion\Method\MethodFactory;

/**
 * Handles data about "Conversion method" field in plugin settings.
 */
class ConversionMethodOption extends OptionAbstract implements OptionInterface {

	const LOADER_TYPE = 'method';

	/**
	 * Object of integration class supports all output formats.
	 *
	 * @var MethodFactory
	 */
	private $methods_integration;

	/**
	 * ConversionMethodOption constructor.
	 */
	public function __construct() {
		$this->methods_integration = new MethodFactory();
	}

	/**
	 * Returns name of option.
	 *
	 * @return string Option name.
	 */
	public function get_name(): string {
		return self::LOADER_TYPE;
	}

	/**
	 * Returns type of field.
	 *
	 * @return string Field type.
	 */
	public function get_type(): string {
		return OptionAbstract::OPTION_TYPE_RADIO;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_label(): string {
		return __( 'Conversion method', 'webp-converter-for-media' );
	}

	/**
	 * Returns additional information of field.
	 *
	 * @return string Additional information.
	 */
	public function get_info(): string {
		return __( 'The configuration for advanced users.', 'webp-converter-for-media' );
	}

	/**
	 * Returns available values for field.
	 *
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return string[] Values for field.
	 */
	public function get_values( array $settings ): array {
		return $this->methods_integration->get_methods();
	}

	/**
	 * Returns unavailable values for field.
	 *
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return string[] Disabled values for field.
	 */
	public function get_disabled_values( array $settings ): array {
		$methods           = $this->methods_integration->get_methods();
		$methods_available = $this->methods_integration->get_available_methods();
		return array_keys( array_diff( $methods, $methods_available ) );
	}

	/**
	 * Returns default value of field.
	 *
	 * @param mixed[]|null $settings Plugin settings.
	 *
	 * @return string Default value of field.
	 */
	public function get_default_value( array $settings = null ): string {
		$methods_available = $this->methods_integration->get_available_methods();
		return array_keys( $methods_available )[0] ?? '';
	}
}

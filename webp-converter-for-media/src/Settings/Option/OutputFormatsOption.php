<?php

namespace WebpConverter\Settings\Option;

use WebpConverter\Conversion\Format\FormatFactory;
use WebpConverter\Conversion\Format\WebpFormat;

/**
 * Handles data about "Supported output formats" field in plugin settings.
 */
class OutputFormatsOption extends OptionAbstract implements OptionInterface {

	const LOADER_TYPE = 'output_formats';

	/**
	 * Object of integration class supports all conversion methods.
	 *
	 * @var FormatFactory
	 */
	private $formats_integration;

	/**
	 * OutputFormatsOption constructor.
	 */
	public function __construct() {
		$this->formats_integration = new FormatFactory();
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
		return OptionAbstract::OPTION_TYPE_CHECKBOX;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_label(): string {
		return __( 'List of supported output formats', 'webp-converter-for-media' );
	}

	/**
	 * Returns available values for field.
	 *
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return string[] Values for field.
	 */
	public function get_values( array $settings ): array {
		return $this->formats_integration->get_formats();
	}

	/**
	 * Returns default value of field.
	 *
	 * @param mixed[]|null $settings Plugin settings.
	 *
	 * @return string[] Default value of field.
	 */
	public function get_default_value( array $settings = null ): array {
		$method  = $settings['method'] ?? ( new ConversionMethodOption() )->get_default_value();
		$formats = array_keys( $this->formats_integration->get_available_formats( $method ) );

		return ( in_array( WebpFormat::FORMAT_EXTENSION, $formats ) ) ? [ WebpFormat::FORMAT_EXTENSION ] : [];
	}

	/**
	 * Returns unavailable values for field.
	 *
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return string[] Disabled values for field.
	 */
	public function get_disabled_values( array $settings ): array {
		$method            = $settings['method'] ?? ( new ConversionMethodOption() )->get_default_value();
		$formats           = $this->formats_integration->get_formats();
		$formats_available = $this->formats_integration->get_available_formats( $method );
		return array_keys( array_diff( $formats, $formats_available ) );
	}
}

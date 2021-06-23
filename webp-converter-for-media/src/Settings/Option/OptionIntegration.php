<?php

namespace WebpConverter\Settings\Option;

/**
 * Allows to integrate with field in plugin settings by specifying its settings and value.
 */
class OptionIntegration {

	/**
	 * Objects of supported settings options.
	 *
	 * @var OptionInterface
	 */
	private $option;

	/**
	 * OptionIntegration constructor.
	 *
	 * @param OptionInterface $option .
	 */
	public function __construct( OptionInterface $option ) {
		$this->option = $option;
	}

	/**
	 * Returns data of option based on plugin settings.
	 *
	 * @param mixed[] $settings Plugin settings.
	 * @param bool    $is_debug Is debugging?
	 * @param bool    $is_save  Is saving?
	 *
	 * @return mixed[] Data of option.
	 */
	public function get_option_data( array $settings, bool $is_debug, bool $is_save ): array {
		$option_name     = $this->option->get_name();
		$option_type     = $this->option->get_type();
		$values          = $this->option->get_values( $settings );
		$disabled_values = $this->option->get_disabled_values( $settings );

		if ( $is_debug ) {
			$value = $this->option->get_value_for_debug( $settings );
		} else {
			$value = ( isset( $settings[ $option_name ] ) || $is_save )
				? $this->get_option_value( $settings[ $option_name ] ?? '', $option_type, $values, $disabled_values )
				: $this->option->get_default_value( $settings );
		}

		return [
			'name'     => $this->option->get_name(),
			'type'     => $option_type,
			'label'    => $this->option->get_label(),
			'info'     => $this->option->get_info(),
			'values'   => $values,
			'disabled' => $disabled_values,
			'value'    => $value,
		];
	}

	/**
	 * Returns value of option based on plugin settings.
	 *
	 * @param mixed    $current_value   Value from plugin settings.
	 * @param string   $option_type     Key of option.
	 * @param string[] $values          Values of option.
	 * @param string[] $disabled_values Disabled values of option.
	 *
	 * @return string[]|string Value of option.
	 */
	private function get_option_value( $current_value, string $option_type, array $values, array $disabled_values ) {
		$valid_values = [];
		foreach ( (array) $current_value as $option_value ) {
			if ( array_key_exists( $option_value, $values ) && ! in_array( $option_value, $disabled_values ) ) {
				$valid_values[] = $option_value;
			}
		}

		return ( in_array( $option_type, [ OptionAbstract::OPTION_TYPE_RADIO, OptionAbstract::OPTION_TYPE_QUALITY ] ) )
			? ( $valid_values[0] ?? '' )
			: $valid_values;
	}
}

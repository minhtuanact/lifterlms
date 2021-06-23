<?php

namespace WebpConverter\Settings\Option;

/**
 * Interface for class that supports data field in plugin settings.
 */
interface OptionInterface {

	/**
	 * Returns name of option.
	 *
	 * @return string Option name.
	 */
	public function get_name(): string;

	/**
	 * Returns type of field.
	 *
	 * @return string Field type.
	 */
	public function get_type(): string;

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_label(): string;

	/**
	 * Returns additional information of field.
	 *
	 * @return string Additional information.
	 */
	public function get_info(): string;

	/**
	 * Returns available values for field.
	 *
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return string[] Values for field.
	 */
	public function get_values( array $settings ): array;

	/**
	 * Returns unavailable values for field.
	 *
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return string[] Disabled values for field.
	 */
	public function get_disabled_values( array $settings ): array;

	/**
	 * Returns default value of field.
	 *
	 * @param mixed[]|null $settings Plugin settings.
	 *
	 * @return string|string[] Default value of field.
	 */
	public function get_default_value( array $settings = null );

	/**
	 * Returns default value of field when debugging.
	 *
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return string|string[] Default value of field for debug.
	 */
	public function get_value_for_debug( array $settings );
}

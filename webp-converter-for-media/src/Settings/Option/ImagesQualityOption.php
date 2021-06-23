<?php

namespace WebpConverter\Settings\Option;

/**
 * Handles data about "Images quality" field in plugin settings.
 */
class ImagesQualityOption extends OptionAbstract implements OptionInterface {

	const LOADER_TYPE = 'quality';

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
		return OptionAbstract::OPTION_TYPE_QUALITY;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_label(): string {
		return __( 'Images quality', 'webp-converter-for-media' );
	}

	/**
	 * Returns additional information of field.
	 *
	 * @return string Additional information.
	 */
	public function get_info(): string {
		return __( 'Adjust the quality of the images being converted. Remember that higher quality also means larger file sizes. The recommended value is 85%.', 'webp-converter-for-media' );
	}

	/**
	 * Returns available values for field.
	 *
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return string[] Values for field.
	 */
	public function get_values( array $settings ): array {
		return [
			'75'  => '75%',
			'80'  => '80%',
			'85'  => '85%',
			'90'  => '90%',
			'95'  => '95%',
			'100' => '100%',
		];
	}

	/**
	 * Returns default value of field.
	 *
	 * @param mixed[]|null $settings Plugin settings.
	 *
	 * @return string Default value of field.
	 */
	public function get_default_value( array $settings = null ): string {
		return '85';
	}
}

<?php

namespace WebpConverter\Settings\Option;

use WebpConverter\Loader\HtaccessLoader;
use WebpConverter\Loader\PassthruLoader;

/**
 * Handles data about "Image loading mode" field in plugin settings.
 */
class LoaderTypeOption extends OptionAbstract implements OptionInterface {

	const LOADER_TYPE = 'loader_type';

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
		return __( 'Image loading mode', 'webp-converter-for-media' );
	}

	/**
	 * Returns additional information of field.
	 *
	 * @return string Additional information.
	 */
	public function get_info(): string {
		return __( 'By changing image loading mode it allows you to bypass some server configuration problems.', 'webp-converter-for-media' );
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
			HtaccessLoader::LOADER_TYPE => sprintf(
			/* translators: %s: loader type */
				__( '%s (recommended)', 'webp-converter-for-media' ),
				__( 'via .htaccess', 'webp-converter-for-media' )
			),
			PassthruLoader::LOADER_TYPE => sprintf(
			/* translators: %s: loader type */
				__( '%s (without rewrites in .htaccess files or Nginx configuration)', 'webp-converter-for-media' ),
				'Pass Thru'
			),
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
		return HtaccessLoader::LOADER_TYPE;
	}
}

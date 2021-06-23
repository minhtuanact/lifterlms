<?php

namespace WebpConverter\Settings\Option;

use WebpConverter\Conversion\Method\ImagickMethod;
use WebpConverter\Loader\HtaccessLoader;

/**
 * Handles data about "Extra features" field in plugin settings.
 */
class ExtraFeaturesOption extends OptionAbstract implements OptionInterface {

	const LOADER_TYPE = 'features';

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
		return __( 'Extra features', 'webp-converter-for-media' );
	}

	/**
	 * Returns additional information of field.
	 *
	 * @return string Additional information.
	 */
	public function get_info(): string {
		return __( 'Options allow you to enable new functionalities that will increase capabilities of plugin', 'webp-converter-for-media' );
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
			'only_smaller'     => __(
				'Automatic removal of WebP files larger than original',
				'webp-converter-for-media'
			),
			'mod_expires'      => __(
				'Browser Caching for WebP files (saving images in browser cache memory)',
				'webp-converter-for-media'
			),
			'keep_metadata'    => __(
				'Keep images metadata stored in EXIF or XMP formats (only available for Imagick conversion method)',
				'webp-converter-for-media'
			),
			'cron_enabled'     => __(
				'Enable cron to automatically convert images from outside Media Library (images from Media Library are converted immediately after upload)',
				'webp-converter-for-media'
			),
			'cron_conversion'  => __(
				'Enable cron to convert images uploaded to Media Library to speed up process of adding images (deactivate this option if images added to Media Library are not automatically converted)',
				'webp-converter-for-media'
			),
			'referer_disabled' => __(
				'Force redirections to WebP for all domains (by default, images in WebP are loaded only in domain of your website - when image is displayed via URL on another domain that original file is loaded)',
				'webp-converter-for-media'
			),
			'debug_enabled'    => __(
				'Log errors while converting to debug.log file (when debugging in WordPress is active)',
				'webp-converter-for-media'
			),
		];
	}

	/**
	 * Returns unavailable values for field.
	 *
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return string[] Disabled values for field.
	 */
	public function get_disabled_values( array $settings ): array {
		$values = [];
		if ( ( $settings[ ConversionMethodOption::LOADER_TYPE ] ?? '' ) !== ImagickMethod::METHOD_NAME ) {
			$values[] = 'keep_metadata';
		}
		if ( ( $settings[ LoaderTypeOption::LOADER_TYPE ] ?? '' ) !== HtaccessLoader::LOADER_TYPE ) {
			$values[] = 'referer_disabled';
		}
		return $values;
	}

	/**
	 * Returns default value of field.
	 *
	 * @param mixed[]|null $settings Plugin settings.
	 *
	 * @return string[] Default value of field.
	 */
	public function get_default_value( array $settings = null ): array {
		return [
			'only_smaller',
			'mod_expires',
			'cron_conversion',
			'debug_enabled',
		];
	}
}

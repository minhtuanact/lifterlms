<?php

namespace WebpConverter\Settings\Option;

use WebpConverter\Settings\SettingsSave;

/**
 * Allows to integration with plugin settings by providing list of settings fields and saved values.
 */
class OptionFactory {

	/**
	 * Objects of supported options.
	 *
	 * @var OptionInterface[]
	 */
	private $options = [];

	/**
	 * OptionFactory constructor.
	 */
	public function __construct() {
		$this->set_integration( new LoaderTypeOption() );
		$this->set_integration( new SupportedExtensionsOption() );
		$this->set_integration( new SupportedDirectoriesOption() );
		$this->set_integration( new ConversionMethodOption() );
		$this->set_integration( new OutputFormatsOption() );
		$this->set_integration( new ImagesQualityOption() );
		$this->set_integration( new ExtraFeaturesOption() );
	}

	/**
	 * Sets integration for option.
	 *
	 * @param OptionInterface $option .
	 *
	 * @return void
	 */
	private function set_integration( OptionInterface $option ) {
		$this->options[] = $option;
	}

	/**
	 * Returns options of plugin settings.
	 *
	 * @param bool         $is_debug        Is debugging?
	 * @param array[]|null $posted_settings Settings submitted in form.
	 *
	 * @return array[] Options of plugin settings.
	 */
	public function get_options( bool $is_debug = false, array $posted_settings = null ): array {
		$is_save  = ( $posted_settings !== null );
		$settings = ( $is_save ) ? $posted_settings : get_option( SettingsSave::SETTINGS_OPTION, [] );

		$options = [];
		foreach ( $this->options as $option_object ) {
			$options[] = ( new OptionIntegration( $option_object ) )->get_option_data( $settings, $is_debug, $is_save );
		}
		return $options;
	}

	/**
	 * Returns values of plugin settings.
	 *
	 * @param bool         $is_debug        Is debugging?
	 * @param array[]|null $posted_settings Settings submitted in form.
	 *
	 * @return array[] Values of plugin settings.
	 */
	public function get_values( bool $is_debug = false, array $posted_settings = null ): array {
		$values = [];
		foreach ( $this->get_options( $is_debug, $posted_settings ) as $option ) {
			$values[ $option['name'] ] = $option['value'];
		}
		return $values;
	}
}

<?php

namespace WebpConverter\Error;

use WebpConverter\PluginAccessInterface;

/**
 * Checks for configuration errors about incorrectly saved plugin settings.
 */
class SettingsError extends ErrorAbstract implements PluginAccessInterface, ErrorInterface {

	/**
	 * Returns list of error codes.
	 *
	 * @return string[] Error codes.
	 */
	public function get_error_codes(): array {
		$errors = [];

		if ( $this->if_settings_are_correct() !== true ) {
			$errors[] = 'settings_incorrect';
		}
		return $errors;
	}

	/**
	 * Checks if plugin settings are correct.
	 *
	 * @return bool Verification status.
	 */
	private function if_settings_are_correct(): bool {
		$settings = $this->get_plugin()->get_settings();
		if ( ( ! isset( $settings['extensions'] ) || ! $settings['extensions'] )
			|| ( ! isset( $settings['dirs'] ) || ! $settings['dirs'] )
			|| ( ! isset( $settings['method'] ) || ! $settings['method'] )
			|| ( ! isset( $settings['output_formats'] ) || ! $settings['output_formats'] )
			|| ( ! isset( $settings['quality'] ) || ! $settings['quality'] ) ) {
			return false;
		}

		return true;
	}
}

<?php

namespace WebpConverter\Plugin\Deactivation;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\Settings\AdminAssets;
use WebpConverter\Helper\ViewLoader;

/**
 * Displays modal with poll in list of plugins when you try to deactivate plugin.
 */
class Modal extends PluginAccessAbstract implements PluginAccessInterface {

	const FEEDBACK_API_URL = 'https://feedback.gbiorczyk.pl/';

	/**
	 * Initializes display of poll modal with poll when plugin is deactivated.
	 *
	 * @return void
	 */
	public function show_deactivation_modal() {
		if ( basename( ( $_SERVER['SCRIPT_FILENAME'] ?? '' ), '.php' ) !== 'plugins' ) { // phpcs:ignore
			return;
		}

		( new AdminAssets() )->init_hooks();
		add_action( 'admin_footer', [ $this, 'load_deactivation_modal' ] );
	}

	/**
	 * Loads modal with poll when plugin is deactivated.
	 *
	 * @return void
	 */
	public function load_deactivation_modal() {
		ViewLoader::load_view(
			'views/deactivation-modal.php',
			[
				'errors'   => apply_filters( 'webpc_server_errors', [] ),
				'reasons'  => $this->get_reasons(),
				'settings' => $this->get_plugin()->get_settings(),
				'api_url'  => self::FEEDBACK_API_URL,
			]
		);
	}

	/**
	 * Returns list of reasons for plugin deactivation.
	 *
	 * @return array[] Reasons for plugin deactivation.
	 */
	private function get_reasons(): array {
		return [
			[
				'key'         => 'server_config',
				'label'       => __( 'I have "Server configuration error" in plugin settings', 'webp-converter-for-media' ),
				'placeholder' => esc_attr( __( 'What is your error? Have you been looking for solution to this issue?', 'webp-converter-for-media' ) ),
			],
			[
				'key'         => 'website_broken',
				'label'       => __( 'This plugin broke my website', 'webp-converter-for-media' ),
				'placeholder' => esc_attr( __( 'What exactly happened?', 'webp-converter-for-media' ) ),
			],
			[
				'key'         => 'better_plugin',
				'label'       => __( 'I found a better plugin', 'webp-converter-for-media' ),
				'placeholder' => esc_attr( __( 'What is name of this plugin? Why is it better?', 'webp-converter-for-media' ) ),
			],
			[
				'key'         => 'misunderstanding',
				'label'       => __( 'I do not understand how the plugin works', 'webp-converter-for-media' ),
				'placeholder' => esc_attr( __( 'What is non-understandable to you? Did you search for this in plugin FAQ?', 'webp-converter-for-media' ) ),
			],
			[
				'key'         => 'temporary_deactivation',
				'label'       => __( 'This is a temporary deactivation', 'webp-converter-for-media' ),
				'placeholder' => '',
			],
			[
				'key'         => 'other',
				'label'       => __( 'Other reason', 'webp-converter-for-media' ),
				'placeholder' => esc_attr( __( 'What is reason? What can we improve for you?', 'webp-converter-for-media' ) ),
			],
		];
	}
}

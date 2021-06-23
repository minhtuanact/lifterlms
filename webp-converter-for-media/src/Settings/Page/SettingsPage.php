<?php


namespace WebpConverter\Settings\Page;

use WebpConverter\Conversion\Endpoint\PathsEndpoint;
use WebpConverter\Conversion\Endpoint\RegenerateEndpoint;
use WebpConverter\Helper\ViewLoader;
use WebpConverter\Loader\LoaderAbstract;
use WebpConverter\Settings\Option\OptionFactory;
use WebpConverter\Settings\SettingsSave;

/**
 * Supports default tab in plugin settings page.
 */
class SettingsPage extends PageAbstract implements PageInterface {

	const PAGE_VIEW_PATH = 'views/settings.php';

	/**
	 * Returns status if view is active.
	 *
	 * @return bool Is view active?
	 */
	public function is_page_active(): bool {
		return ( ! isset( $_GET['action'] ) || ( $_GET['action'] !== 'server' ) ); // phpcs:ignore
	}

	/**
	 * Displays view for plugin settings page.
	 *
	 * @return void
	 */
	public function show_page_view() {
		$settings_save = new SettingsSave();
		$settings_save->set_plugin( $this->get_plugin() );
		$settings_save->save_settings();

		ViewLoader::load_view(
			self::PAGE_VIEW_PATH,
			[
				'errors'             => apply_filters( 'webpc_server_errors_messages', [], false, true ),
				'options'            => ( new OptionFactory() )->get_options(),
				'submit_value'       => SettingsSave::SUBMIT_VALUE,
				'settings_url'       => sprintf(
					'%1$s&%2$s=%3$s',
					PageIntegration::get_settings_page_url(),
					SettingsSave::NONCE_PARAM_KEY,
					wp_create_nonce( SettingsSave::NONCE_PARAM_VALUE )
				),
				'settings_debug_url' => sprintf(
					'%s&action=server',
					PageIntegration::get_settings_page_url()
				),
				'api_paths_url'      => ( new PathsEndpoint() )->get_route_url(),
				'api_regenerate_url' => ( new RegenerateEndpoint() )->get_route_url(),
			]
		);

		do_action( LoaderAbstract::ACTION_NAME, true );
	}
}

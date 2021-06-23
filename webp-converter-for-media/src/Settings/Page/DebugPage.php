<?php


namespace WebpConverter\Settings\Page;

use WebpConverter\Loader\LoaderAbstract;
use WebpConverter\Settings\SettingsSave;
use WebpConverter\Helper\ViewLoader;
use WebpConverter\Helper\FileLoader;
use WebpConverter\Error\RewritesError;

/**
 * Supports debug tab in plugin settings page.
 */
class DebugPage extends PageAbstract implements PageInterface {

	const PAGE_VIEW_PATH = 'views/settings-debug.php';

	/**
	 * Returns status if view is active.
	 *
	 * @return bool Is view active?
	 */
	public function is_page_active(): bool {
		return ( isset( $_GET['action'] ) && ( $_GET['action'] === 'server' ) ); // phpcs:ignore
	}

	/**
	 * Displays view for plugin settings page.
	 *
	 * @return void
	 */
	public function show_page_view() {
		$plugin       = $this->get_plugin();
		$uploads_url  = apply_filters( 'webpc_dir_url', '', 'uploads' );
		$uploads_path = apply_filters( 'webpc_dir_path', '', 'uploads' );
		$ver_param    = sprintf( 'ver=%s', time() );

		do_action( LoaderAbstract::ACTION_NAME, true, true );

		ViewLoader::load_view(
			self::PAGE_VIEW_PATH,
			[
				'settings_url'          => sprintf(
					'%1$s&%2$s=%3$s',
					PageIntegration::get_settings_page_url(),
					SettingsSave::NONCE_PARAM_KEY,
					wp_create_nonce( SettingsSave::NONCE_PARAM_VALUE )
				),
				'settings_debug_url'    => sprintf(
					'%s&action=server',
					PageIntegration::get_settings_page_url()
				),
				'size_png_path'         => FileLoader::get_file_size_by_path(
					$uploads_path . RewritesError::PATH_OUTPUT_FILE_PNG
				),
				'size_png2_path'        => FileLoader::get_file_size_by_path(
					$uploads_path . RewritesError::PATH_OUTPUT_FILE_PNG2
				),
				'size_png_url'          => FileLoader::get_file_size_by_url(
					$uploads_url . RewritesError::PATH_OUTPUT_FILE_PNG,
					$plugin,
					false,
					$ver_param
				),
				'size_png2_url'         => FileLoader::get_file_size_by_url(
					$uploads_url . RewritesError::PATH_OUTPUT_FILE_PNG2,
					$plugin,
					false,
					$ver_param
				),
				'size_png_as_webp_url'  => FileLoader::get_file_size_by_url(
					$uploads_url . RewritesError::PATH_OUTPUT_FILE_PNG,
					$plugin,
					true,
					$ver_param
				),
				'size_png2_as_webp_url' => FileLoader::get_file_size_by_url(
					$uploads_url . RewritesError::PATH_OUTPUT_FILE_PNG2,
					$plugin,
					true,
					$ver_param
				),
			]
		);

		do_action( LoaderAbstract::ACTION_NAME, true );
	}
}

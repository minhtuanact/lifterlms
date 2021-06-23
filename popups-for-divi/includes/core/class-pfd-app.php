<?php
/**
 * Popups for Divi
 * Main plugin instance/controller. The main popup logic is done in javascript,
 * so we mainly need to make sure that our JS/CSS is loaded on the correctly.
 *
 * @package Popups_For_Divi
 */

defined( 'ABSPATH' ) || die();

/**
 * The application entry class.
 */
class PFD_App extends PFD_Component {

	/**
	 * Hook up the module.
	 *
	 * @since 1.0.0
	 * @since 2.0.4 function renamed from __construct() to setup().
	 */
	public function setup() {
		// Do not load the JS library, when the Pro version is active.
		if ( defined( 'DIVI_AREAS_PLUGIN' ) ) {
			return;
		}

		// Add dependencies.
		$this->add_module( 'welcome', 'PFD_Onboarding' )->setup_on( 'divi_popups_loaded' );
		$this->add_module( 'editor', 'PFD_Editor' )->setup_on( 'divi_popups_loaded' );
		$this->add_module( 'asset', 'PFD_Asset' )->setup_on( 'divi_popups_loaded' );

		$this->setup_hooks();

		/**
		 * Initialize dependencies.
		 *
		 * @since 2.0.4
		 */
		do_action( 'divi_popups_loaded' );
	}

	/**
	 * Hook into WordPress actions.
	 *
	 * @since 2.2.2
	 * @return void
	 */
	public function setup_hooks() {
		add_filter(
			'init',
			[ $this, 'translate_plugin' ]
		);

		add_filter(
			'plugin_action_links_' . DIVI_POPUP_PLUGIN,
			[ $this, 'plugin_add_settings_link' ]
		);

		add_filter(
			'plugin_row_meta',
			[ $this, 'plugin_row_meta' ],
			10,
			4
		);

		add_action(
			'wp_enqueue_scripts',
			[ $this->module( 'asset' ), 'enqueue_js_library' ]
		);
	}

	/**
	 * Load plugin translations.
	 *
	 * Note: Loading the plugin translations should not be done during plugins_loaded
	 * action since that is too early and prevent other language related plugins from
	 * correctly hooking up with load_textdomain() function. Calling
	 * load_plugin_textdomain() should be delayed until init action.
	 *
	 * @since 2.2.5
	 */
	public function translate_plugin() {
		load_plugin_textdomain(
			'popups-for-divi',
			false,
			dirname( DIVI_POPUP_PLUGIN ) . '/languages/'
		);
	}

	/**
	 * Display a custom link in the plugins list
	 *
	 * @since  1.0.2
	 *
	 * @param array $links List of plugin links.
	 *
	 * @return array New list of plugin links.
	 */
	public function plugin_add_settings_link( $links ) {
		$links[] = sprintf(
			'<a href="%s" target="_blank">%s</a>',
			'https://divimode.com/divi-popup/?utm_source=wpadmin&utm_medium=link&utm_campaign=popups-for-divi',
			__( 'How it works', 'divi-popup' )
		);

		return $links;
	}

	/**
	 * Display additional details in the right column of the "Plugins" page.
	 *
	 * @param string[] $plugin_meta An array of the plugin's metadata,
	 *                              including the version, author,
	 *                              author URI, and plugin URI.
	 * @param string   $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array    $plugin_data An array of plugin data.
	 * @param string   $status      Status of the plugin. Defaults are 'All', 'Active',
	 *                              'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
	 *                              'Drop-ins', 'Search'.
	 *
	 * @return string[]
	 * @since 1.6.0
	 *
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		if ( DIVI_POPUP_PLUGIN !== $plugin_file ) {
			return $plugin_meta;
		}

		$plugin_meta[] = sprintf(
			'<a href="%s" target="_blank">%s</a>',
			'https://divimode.com/divi-areas-pro/?utm_source=wpadmin&utm_medium=link&utm_campaign=popups-for-divi',
			__( 'Divi Areas <strong>Pro</strong>', 'divi-popup' )
		);

		return $plugin_meta;
	}
}

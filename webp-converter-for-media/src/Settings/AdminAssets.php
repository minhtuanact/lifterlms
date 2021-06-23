<?php

namespace WebpConverter\Settings;

use WebpConverter\HookableInterface;

/**
 * Initializes loading of assets in admin panel.
 */
class AdminAssets implements HookableInterface {

	/**
	 * URL of CSS file.
	 *
	 * @var string
	 */
	private $path_css = WEBPC_URL . 'assets/build/css/styles.css';

	/**
	 * URL of JS file.
	 *
	 * @var string
	 */
	private $path_js = WEBPC_URL . 'assets/build/js/scripts.js';

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_filter( 'admin_enqueue_scripts', [ $this, 'load_styles' ] );
		add_filter( 'admin_enqueue_scripts', [ $this, 'load_scripts' ] );
	}

	/**
	 * Loads CSS assets.
	 *
	 * @return void
	 * @internal
	 */
	public function load_styles() {
		wp_register_style( 'webp-converter', $this->path_css, [], WEBPC_VERSION );
		wp_enqueue_style( 'webp-converter' );
	}

	/**
	 * Loads JavaScript assets.
	 *
	 * @return void
	 * @internal
	 */
	public function load_scripts() {
		wp_register_script( 'webp-converter', $this->path_js, [ 'jquery' ], WEBPC_VERSION, true );
		wp_enqueue_script( 'webp-converter' );
	}
}

<?php

namespace WebpConverter\Notice;

use WebpConverter\HookableInterface;
use WebpConverter\Settings\AdminAssets;
use WebpConverter\Helper\ViewLoader;

/**
 * Supports ability to display notice and its management.
 */
class NoticeIntegration implements HookableInterface {

	/**
	 * Object of notice.
	 *
	 * @var NoticeInterface
	 */
	private $notice;

	/**
	 * NoticeIntegration constructor.
	 *
	 * @param NoticeInterface $notice .
	 */
	public function __construct( NoticeInterface $notice ) {
		$this->notice = $notice;
	}

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'admin_init', [ $this, 'init_notice_hooks' ] );

		if ( $ajax_action = $this->notice->get_ajax_action_to_disable() ) {
			add_action( 'wp_ajax_' . $ajax_action, [ $this->notice, 'disable_notice' ] );
		}
	}

	/**
	 * Initializes displaying notice in administration panel.
	 *
	 * @return void
	 * @internal
	 */
	public function init_notice_hooks() {
		if ( ! $this->notice->is_available() ) {
			return;
		}

		( new AdminAssets() )->init_hooks();
		add_action( 'admin_notices', [ $this, 'load_notice' ] );
		add_action( 'network_admin_notices', [ $this, 'load_notice' ] );
	}

	/**
	 * Loads view template for notice.
	 *
	 * @return void
	 * @internal
	 */
	public function load_notice() {
		if ( ! $this->notice->is_available() ) {
			return;
		}

		ViewLoader::load_view(
			$this->notice->get_output_path(),
			$this->notice->get_vars_for_view()
		);
	}

	/**
	 * Sets value for option that specifies whether to display notice.
	 *
	 * @return void
	 */
	public function set_default_value() {
		if ( get_option( $this->notice->get_option_name(), null ) !== null ) {
			return;
		}
		update_option( $this->notice->get_option_name(), $this->notice->get_default_value() );
	}
}

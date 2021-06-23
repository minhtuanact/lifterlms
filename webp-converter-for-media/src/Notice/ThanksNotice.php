<?php

namespace WebpConverter\Notice;

/**
 * Supports notice displayed as thank you for using plugin.
 */
class ThanksNotice extends NoticeAbstract implements NoticeInterface {

	const NOTICE_OPTION    = 'webpc_notice_hidden';
	const NOTICE_VIEW_PATH = 'components/notices/thanks.php';

	/**
	 * Returns name for option that specifies whether to display notice.
	 *
	 * @return string Option name.
	 */
	public function get_option_name(): string {
		return self::NOTICE_OPTION;
	}

	/**
	 * Returns default value for option that specifies whether to display notice.
	 *
	 * @return string Default value.
	 */
	public function get_default_value(): string {
		return (string) strtotime( '+ 1 week' );
	}

	/**
	 * Returns status if notice is active.
	 *
	 * @return bool Is notice available?
	 */
	public function is_available(): bool {
		if ( basename( $_SERVER['PHP_SELF'] ) !== 'index.php' ) { // phpcs:ignore
			return false;
		}

		$value = get_option( self::NOTICE_OPTION, 0 );
		return ( $value < time() );
	}

	/**
	 * Returns server path for view template.
	 *
	 * @return string Server path relative to plugin root.
	 */
	public function get_output_path(): string {
		return self::NOTICE_VIEW_PATH;
	}

	/**
	 * Returns variables with values using in view template.
	 *
	 * @return string[] Args extract in view template.
	 */
	public function get_vars_for_view(): array {
		return [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		];
	}

	/**
	 * Returns name of action using in WP Ajax.
	 *
	 * @return string Name of ajax action.
	 */
	public function get_ajax_action_to_disable(): string {
		return 'webpc_notice';
	}

	/**
	 * Sets options to disable notice.
	 *
	 * @return void
	 * @internal
	 */
	public static function disable_notice() {
		$is_permanent = ( isset( $_POST['is_permanently'] ) && $_POST['is_permanently'] ); // phpcs:ignore
		$expires_date = strtotime( ( $is_permanent ) ? '+10 years' : '+ 1 month' );

		update_option( self::NOTICE_OPTION, $expires_date );
	}
}

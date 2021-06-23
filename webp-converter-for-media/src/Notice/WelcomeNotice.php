<?php

namespace WebpConverter\Notice;

use WebpConverter\Settings\Page\PageIntegration;

/**
 * Supports notice displayed after plugin installation.
 */
class WelcomeNotice extends NoticeAbstract implements NoticeInterface {

	const NOTICE_OPTION    = 'webpc_is_new_installation';
	const NOTICE_VIEW_PATH = 'components/notices/welcome.php';

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
		return '1';
	}

	/**
	 * Returns status if notice is active.
	 *
	 * @return bool Is notice available?
	 */
	public function is_available(): bool {
		return ( get_option( self::NOTICE_OPTION, 0 ) === $this->get_default_value() );
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
			'settings_url' => PageIntegration::get_settings_page_url(),
		];
	}

	/**
	 * Sets options to disable notice.
	 *
	 * @return void
	 * @internal
	 */
	public static function disable_notice() {
		update_option( self::NOTICE_OPTION, '0' );
	}
}

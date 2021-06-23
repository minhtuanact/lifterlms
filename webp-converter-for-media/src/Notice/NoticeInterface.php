<?php

namespace WebpConverter\Notice;

/**
 * Interface for class that supports notice displayed in admin panel.
 */
interface NoticeInterface {

	/**
	 * Returns name for option that specifies whether to display notice.
	 *
	 * @return string Option name.
	 */
	public function get_option_name(): string;

	/**
	 * Returns default value for option that specifies whether to display notice.
	 *
	 * @return string Default value.
	 */
	public function get_default_value(): string;

	/**
	 * Returns status if notice is active.
	 *
	 * @return bool Is notice available?
	 */
	public function is_available(): bool;

	/**
	 * Returns server path for view template.
	 *
	 * @return string Server path relative to plugin root.
	 */
	public function get_output_path(): string;

	/**
	 * Returns variables with values using in view template.
	 *
	 * @return string[] Args extract in view template.
	 */
	public function get_vars_for_view(): array;

	/**
	 * Returns name of action using in WP Ajax.
	 *
	 * @return string Name of ajax action.
	 */
	public function get_ajax_action_to_disable(): string;

	/**
	 * Sets options to disable notice.
	 *
	 * @return void
	 * @internal
	 */
	public static function disable_notice();
}

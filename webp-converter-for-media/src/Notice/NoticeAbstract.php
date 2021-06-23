<?php

namespace WebpConverter\Notice;

/**
 * Abstract class for class that supports data field in plugin settings.
 */
abstract class NoticeAbstract implements NoticeInterface {

	/**
	 * Returns name of action using in WP Ajax.
	 *
	 * @return string Name of ajax action.
	 */
	public function get_ajax_action_to_disable(): string {
		return '';
	}

	/**
	 * Returns variables with values using in view template.
	 *
	 * @return string[] Args extract in view template.
	 */
	public function get_vars_for_view(): array {
		return [];
	}
}

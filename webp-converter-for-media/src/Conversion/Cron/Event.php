<?php

namespace WebpConverter\Conversion\Cron;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\HookableInterface;

/**
 * Adds cron event that converts images.
 */
class Event extends PluginAccessAbstract implements PluginAccessInterface, HookableInterface {

	const CRON_ACTION = 'webpc_regenerate_all';

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'init', [ $this, 'add_cron_event' ] );
	}

	/**
	 * Initializes cron event to convert all images.
	 *
	 * @return void
	 * @internal
	 */
	public function add_cron_event() {
		if ( wp_next_scheduled( self::CRON_ACTION )
			|| ! ( $settings = $this->get_plugin()->get_settings() )
			|| ! in_array( 'cron_enabled', $settings['features'] ) ) {
			return;
		}

		wp_schedule_event( time(), Schedules::CRON_SCHEDULE, self::CRON_ACTION );
	}
}

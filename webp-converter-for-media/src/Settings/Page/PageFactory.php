<?php

namespace WebpConverter\Settings\Page;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\HookableInterface;

/**
 * Initializes integration for all plugin settings pages.
 */
class PageFactory extends PluginAccessAbstract implements PluginAccessInterface, HookableInterface {

	/**
	 * Object of pages integration.
	 *
	 * @var PageIntegration
	 */
	private $page_integration;

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		$this->set_integration( new SettingsPage() );
		$this->set_integration( new DebugPage() );
	}

	/**
	 * Sets integration for page.
	 *
	 * @param PageInterface $page .
	 *
	 * @return void
	 */
	private function set_integration( PageInterface $page ) {
		if ( $this->page_integration === null ) {
			$this->page_integration = new PageIntegration();
			$this->page_integration->set_plugin_hookable( $this->get_plugin() );
		}
		$this->page_integration->set_page_integration( $page );
	}
}

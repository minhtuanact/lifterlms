<?php
/**
* Plugin Name: 			Custom Login Page Customizer
* Plugin URI: 			https://loginpress.pro/?utm_source=login-customizer-lite&utm_medium=plugin-url-link
* Description: 			Custom Login Customizer plugin allows you to easily customize your login page straight from your WordPress Customizer! Awesome, right?
* Version: 				2.1.4
* Requires at least: 	5.0
* Requires PHP:      	5.6
* Author: 				Hardeep Asrani
* Author URI: 			https://loginpress.pro/?utm_source=login-customizer-lite&utm_medium=author-url-link
* WordPress Available:  yes
* Requires License:     no
* License: 				GPLv2+
* Text Domain: 			login-customizer
* Domain Path: 			/resources/languages
*
* @package 			LoginCustomizer
* @author 			WPBrigade
* @copyright 		Copyright (c) 2021, WPBrigade
* @link 			https://loginpress.pro/
* @license			https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

namespace LoginCustomizer;

defined( 'ABSPATH' ) || exit;

include_once 'autoload.php';

use LoginCustomizer\Plugin;

/**
 * Wrapper for the plugin instance.
 *
 * @since  2.2.0
 * @access public
 * @return void
 */
function plugin() {
	
	static $instance = null;

	if ( is_null( $instance ) ) {
		$instance = new Plugin( __DIR__, plugin_dir_url( __FILE__ ) );
	}

	return $instance;
}

# Boot the plugin.
plugin();

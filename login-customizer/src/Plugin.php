<?php
/**
 * Main Plugin File to run Everything
 *
 * Runs every main function
 *
 * @author 			WPBrigade
 * @copyright 		Copyright (c) 2021, WPBrigade
 * @link 			https://loginpress.pro/
 * @license			https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace LoginCustomizer;

use LoginCustomizer\Essentials;
use LoginCustomizer\Includes\Plugin_Meta;
use LoginCustomizer\Includes\Notification;
use LoginCustomizer\Settings\Setup;
use LoginCustomizer\Customizer\Create_Customizer;
use LoginCustomizer\Settings\Features\Login_Order;
use LoginCustomizer\Settings\Features\Custom_Register_Password;
/**
 * Constant class.
 *
 * @since  2.2.0
 * @version 2.2.0
 * @access public
 */

class Plugin {

	function __construct() {

		/**
		 * Instance of Essentials Class for Defining Variables
		 */
		add_action( 'init', function() {
			new Essentials; 
		}, 1 );
 

		// Customizer Settings Creation
		$customizer_settings = new Create_Customizer;
		$customizer_settings->customizer_settings_creation();

		/**
		 * Plugin Settings API and Plugin Meta
		 */
		$settings = new Setup;

		// PLugin Meta in Plugins.php
		$plugin_meta = new Plugin_Meta;
		$plugin_meta->hooks();


		/**
		 * Settings
		 */
		new Notification();
		$logincust_setting 		= get_option( 'logincust_setting' );
		$login_order 			= isset( $logincust_setting['login_order'] ) ? $logincust_setting['login_order'] : '';
		$enable_reg_pass_field 	= isset( $logincust_setting['enable_reg_pass_field'] ) ?  $logincust_setting['enable_reg_pass_field'] : 'off';


		//Custom Register Fields if option is enbled from Login Customizer and WordPress Settings 
		if ( 'off' != $enable_reg_pass_field && get_option( 'users_can_register' ) !== '0' ) {
			new Custom_Register_Password;
		}

		//Login Order
		if ( 'default' != $login_order ) {
			new Login_Order();
		}
		
	}
}

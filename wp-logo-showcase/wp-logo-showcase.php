<?php
/**
 * Plugin Name: Logo Slider and Showcase
 * Plugin URI: https://radiustheme.com/
 * Description: Logo Slider and Showcase plugin is fully Responsive and Mobile Friendly to display your partner logo in slider and grid views.
 * Author: RadiusTheme
 * Version: 1.3.37
 * Text Domain: wp-logo-showcase
 * Domain Path: /languages
 * Author URI: https://radiustheme.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$plugin_data = get_file_data( __FILE__, array( 'version' => 'Version', 'author' => 'Author' ), false );
define( 'RT_WLS_PLUGIN_VERSION', $plugin_data['version'] );
define( 'RT_WLS_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'RT_WLS_PLUGIN_ACTIVE_FILE_NAME', plugin_basename( __FILE__ ) );
define( 'RT_WLS_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'RT_WLS_PLUGIN_SLUG', basename( dirname( __FILE__ ) ) );
define( 'RT_WLS_PLUGIN_LANGUAGE_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages' );

require( 'lib/init.php' );
<?php
/*
Plugin Name: Clever Fox
Plugin URI:
Description: Clever Fox plugin to enhance the functionality of free themes made by Nayra Themes. More than 40000+ trusted websites with us. Clever Fox contains all features which are required to create a complete website. See below free themes.
Version: 5.9
Author: nayrathemes
Author URI: https://nayrathemes.com
Text Domain: clever-fox
Requires PHP: 5.6
*/
define( 'CLEVERFOX_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CLEVERFOX_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

function cleverfox_activate() {
	
	/**
	 * Load Custom control in Customizer
	 */
	define( 'CLEVERFOX_DIRECTORY', plugin_dir_url( __FILE__ ) . '/inc/custom-controls/' );
	define( 'CLEVERFOX_DIRECTORY_URI', plugin_dir_url( __FILE__ ) . '/inc/custom-controls/' );
	if ( class_exists( 'WP_Customize_Control' ) ) {
		require_once('inc/custom-controls/controls/range-validator/range-control.php');	
	}
	
	$theme = wp_get_theme(); // gets the current theme
		if ( 'StartKit' == $theme->name){	
			require_once('inc/startkit/startkit.php');
		}
		
		if ( 'StartBiz' == $theme->name){	
			require_once('inc/startbiz/startbiz.php');
		}
		
		if ('Arowana' == $theme->name){	
			 require_once('inc/arowana/arowana.php');
		}
		
		if ('Envira' == $theme->name){	
			 require_once('inc/envira/envira.php');			
		}
		
		if( 'Hantus' == $theme->name){
			require_once('inc/hantus/hantus.php');	
		}
		
		if( 'Thai Spa' == $theme->name){
			require_once('inc/thai-spa/thai-spa.php');	
		}
		
		if( 'Conceptly' == $theme->name){
			require_once('inc/conceptly/conceptly.php');
		}
		
		if( 'Ameya' == $theme->name){
			require_once('inc/ameya/ameya.php');
		}
		
		if( 'Azwa' == $theme->name){
			require_once('inc/azwa/azwa.php');
		}
		
		if( 'Avril' == $theme->name){
			require_once('inc/avril/avril.php');
		}
		
		if( 'Aera' == $theme->name){
			require_once('inc/aera/aera.php');
		}
		
		if( 'Avail' == $theme->name){
			require_once('inc/avail/avail.php');
		}
		
		if( 'Fiona Blog' == $theme->name){
			require_once('inc/fiona-blog/fiona-blog.php');
		}
		
		if( 'MetaSoft' == $theme->name ){
			require_once('inc/metasoft/metasoft.php');
		}
		
		if( 'Belltech' == $theme->name){
			require_once('inc/belltech/belltech.php');
		}
		
		if( 'Fiona Food' == $theme->name){
			require_once('inc/fiona-food/fiona-food.php');
		}
		
	}
add_action( 'init', 'cleverfox_activate' );

$theme = wp_get_theme();

/**
 * Fiona Widgets
 */
if( 'Fiona Blog' == $theme->name || 'Fiona Food' == $theme->name){
	require CLEVERFOX_PLUGIN_DIR . 'inc/fiona-blog/widgets/class-fiona-widgets.php';
}

/**
 * The code during plugin activation.
 */
function activate_cleverfox() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/cleverfox-activator.php';
	Cleverfox_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_cleverfox' );
<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'wplc_admin_support_menu', 5);
add_action('admin_enqueue_scripts', 'wplc_add_support_page_resources',11);

function wplc_admin_support_menu(){
	$wplc_settings = TCXSettings::getSettings();
	if ( $wplc_settings->wplc_channel !== 'phone' ) {
		$support_page_hook = wplc_add_ordered_submenu_page( 'wplivechat-menu', __( 'Support', 'wp-live-chat-support' ), __( 'Support', 'wp-live-chat-support' ), 'wplc_cap_admin', 'wplivechat-menu-support-page', 'wplc_admin_support', 100 );
	}
}


function wplc_add_support_page_resources($hook)
{
    if($hook != TCXUtilsHelper::wplc_get_page_hook('wplivechat-menu-support-page' ))
        {
            return;
        }
    global $wplc_base_file;

    wp_register_style( 'wplc-support-style', wplc_plugins_url('support-css.css', __FILE__  ), array(), WPLC_PLUGIN_VERSION);
    wp_enqueue_style( 'wplc-support-style' );

}



function wplc_admin_support()
{
    $support_controller = new SupportController("support");
    $support_controller->run();
}
?>
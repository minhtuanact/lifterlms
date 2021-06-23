<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('wplc_version_migration', 'wplc_offline_messages_activation' );
add_action('admin_menu', 'wplc_admin_offline_messages_menu', 5);

function wplc_admin_offline_messages_menu(){
	$wplc_settings = TCXSettings::getSettings();
	if($wplc_settings->wplc_channel!=='phone') {
		$offline_messages_hook = wplc_add_ordered_submenu_page( 'wplivechat-menu', __( 'Offline Messages', 'wp-live-chat-support' ), __( 'Offline Messages', 'wp-live-chat-support' ), 'wplc_cap_show_offline', 'wplivechat-menu-offline-messages', 'wplc_admin_offline_messages', 50 );
	}
}

function wplc_admin_offline_messages()
{

    $offline_messages_controller = new OfflineMessagesController("offlineMessages");
    $offline_messages_controller->run();
    
}



function wplc_offline_messages_activation()
{
	TCXOfflineMessagesHelper::module_db_integration();
}

?>
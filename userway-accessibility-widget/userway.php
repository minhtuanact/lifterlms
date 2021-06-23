<?php
/*
Plugin Name: Accessibility by UserWay
Plugin URI: https://userway.org
Description: The UserWay Accessibility Widget is a WordPress plugin that helps make your WordPress site more accessible without refactoring your website's existing code and will increase compliance with WCAG 2.1, ATAG 2.0, ADA, & Section 508 requirements.
Version: 2.0
Author: UserWay.org
Author URI: https://userway.org
*/

/*
    Copyright 2020  UserWay  (email: admin@userway.org)
*/

define('USW_USERWAY_DIR', plugin_dir_path(__FILE__));
define('USW_USERWAY_URL', plugin_dir_url(__FILE__));

register_activation_hook(__FILE__, 'usw_userway_activation');
register_deactivation_hook(__FILE__, 'usw_userway_deactivation');

function usw_userway_activation() {
    global $wpdb;
    $tableName = $wpdb->prefix . 'userway';
	$charset_collate = $wpdb->get_charset_collate();

    $sql = "
    DROP TABLE IF EXISTS `$tableName`;
    CREATE TABLE `$tableName` (
        `preference_id` INT(10) NOT NULL AUTO_INCREMENT,
        `account_id`    VARCHAR(255) NOT NULL,
        `state`         smallint(5) NOT NULL,
		`created_time`  TIMESTAMP NOT NULL,
        `updated_time`  TIMESTAMP NOT NULL,
        PRIMARY KEY (`preference_id`)
    ) $charset_collate
    ";
	
	if (!function_exists('dbDelta')) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    }

    dbDelta($sql);
}

function usw_userway_deactivation() {
    global $wpdb;
    $tableName = $wpdb->prefix . 'userway';

    $sql = "DROP TABLE IF EXISTS `$tableName`";
	if (!function_exists('dbDelta')) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    }

    dbDelta($sql);
}

function usw_userway_load(){
    if(is_admin()) require_once(USW_USERWAY_DIR.'includes/admin.php');
    require_once(USW_USERWAY_DIR . 'includes/controller.php');
}
usw_userway_load();

function usw_addplugin_footer_notice(){
    global $wpdb;
    wp_register_style('akismet.css', plugin_dir_url( __FILE__ ) . 'assets/style.css', array());
    wp_enqueue_style('akismet.css');

    $tableName = $wpdb->prefix . 'userway';
    $account = $wpdb->get_results("SELECT * FROM $tableName LIMIT 0, 1")[0];

    if(isset($account->account_id) && mb_strlen($account->account_id) > 0 && (boolean)$account->state === true) {
        echo "<script>
              (function(e){
                  var el = document.createElement('script');
                  el.setAttribute('data-account', '" . $account->account_id . "');
                  el.setAttribute('src', 'https://cdn.userway.org/widget.js');
                  document.body.appendChild(el);
                })();
              </script>";
    }
}
add_action('wp_footer', 'usw_addplugin_footer_notice');

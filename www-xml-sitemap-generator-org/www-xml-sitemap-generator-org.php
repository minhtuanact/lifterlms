<?php
namespace xmlSitemapGenerator;
/*
Plugin Name: Google XML Sitemap Generator
Plugin URI: https://XmlSitemapGenerator.org
Description: HTML, RSS and Google XML Sitemap generator compatible with Google, Bing, Baidu, Yandex and more.
Version: 2.0.1
Author: XmlSitemapGenerator.org
Author URI: https://XmlSitemapGenerator.org
License: GPL2
*/

include 'code/core.php';


function myPluginFile() {
	
	return __FILE__;
}
function xsgPluginPath() {
	return plugins_url() . "/" .  XSG_PLUGIN_NAME . "/";
	 
}

 
if(defined('ABSPATH') && defined('WPINC')) {

	register_activation_hook(__FILE__, 'xmlSitemapGenerator\core::activatePlugin');
	 
	add_action("init", 'xmlSitemapGenerator\core::initialisePlugin');
	
	add_action( 'activated_plugin', 'xmlSitemapGenerator\core::activated');
	
	add_action( 'plugins_loaded', 'xmlSitemapGenerator\core::checkUpgrade' );


		
	// when ever a new blog is created in network mode
	add_action( 'wpmu_new_blog', 'xmlSitemapGenerator\core::activate_new_blog' );
	 
}



?>

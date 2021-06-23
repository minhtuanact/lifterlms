<?php

namespace xmlSitemapGenerator;


class upgrader
{
	
	
	
	
	public static function checkUpgrade()
	{
		$current_update = 00002;
		$installed_Update =  get_option( "xmsg_Update"   , 00000  );
		
		if ($installed_Update < $current_update )
		{
			self::runScripts($installed_Update);
			update_option( "xmsg_Update", $current_update , true);
		}
	}
	
	public static function runScripts($installed_Update)
	{
		global $wpdb;		
		$tablemeta = $wpdb->prefix . 'xsg_sitemap_meta';
		if ($installed_Update < 00001)
		{
			$cmd = "UPDATE {$tablemeta} SET frequency = 8 WHERE frequency = 7";
			$wpdb->query($cmd);
		}
		
		if ($installed_Update < 00002)
		{
			$cmd = "UPDATE {$tablemeta} ADD COLUMN news  not null default 0 ";
			$wpdb->query($cmd);
		}
	}

	
}
?>
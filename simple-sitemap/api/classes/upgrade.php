<?php

namespace WPGO_Plugins\Plugin_Framework;

/*
 *    Run upgrade routine(s) when plugin updated to new (higher) version
 */

class Upgrade_FW
{

    protected $module_roots;

    /* Class constructor. */
    public function __construct($module_roots, $custom_plugin_data)
    {

        $this->module_roots = $module_roots;
				$this->custom_plugin_data = $custom_plugin_data;

				add_action('plugins_loaded', array(&$this, 'upgrade_routine'));
    }

    /**
     * Setup transient for admin notice to be displayed
     */
    public function upgrade_routine()
    {
        // only run upgrade routine on admin pages but not on post editor (for performance)
        if ( !is_admin() || isset($_GET['post'])) {
            return;
        }

				// Only run on plugin settings pages, plugin main index page, and Dashboard > Updates page
        // if (isset($_GET['page'])) {
            // $settings_page_prefix = $this->custom_plugin_data->plugin_slug;
            // $pos = strpos($_GET['page'], $settings_page_prefix);
            // if ($pos !== 0) {
            //     return;
            // }
        // } else {
        //     return;
        // }

				$opt_pfx = $this->custom_plugin_data->db_option_prefix;
				$stored_version_str = $opt_pfx . '-plugin-version';
        $plugin_data = get_plugin_data($this->module_roots['file'], false, false);
        $current_version = $plugin_data['Version'];
        $stored_version = get_option($stored_version_str, '0.0.0');

				//echo "<br>>>>>>>>>>>>>>>>>>>>> upgrade.php (BEFORE): [" . $current_version . "][" . $stored_version . "]<br>";

				// run upgrade routine if current plugin version is not equal to stored version
				if (version_compare($current_version, $stored_version, '=')) {
					//echo ">>>>>>>>>>>>>>>>>>>> DON'T RUN UPGRADE ROUTINE<br>"; 
					return;
				}

				// if a new plugin version has been detected scan for new features and add numbered icon to plugin menu/tab
				//echo ">>>>>>>>>>>>>>>>>>>> RUN UPGRADE ROUTINE<br>"; 
				update_option($opt_pfx . '-new-features-numbered-icon', 'true');
				update_option($stored_version_str, $current_version);
        
        //echo ">>>>>>>>>>>>>>>>>>>>> upgrade.php (AFTER): [" . $current_version . "][" . get_option($stored_version_str, '0.0.0') . "]<br>";
    }

    public static function calc_new_features($opt_pfx, $new_features_arr, $plugin_data)
    {
      // Calc numbered icon and send to JS
      $new_features_number = 0;
      $display_numbered_icon = get_option($opt_pfx . '-new-features-numbered-icon', 'false');
      if ($display_numbered_icon === 'true') {
          //echo ">>>>>>>>>>>>>>>>>>>> display_numbered_icon [" . $display_numbered_icon . "]<br>";
          foreach ($new_features_arr as $key => $new_feature) {
              if ($plugin_data['Version'] === $new_feature->version || $new_feature->version === 'latest') {
                  $new_features_number++;
              }
          }
      }

      // echo ">>>>>>>>>>>>>>>>>>>> DISPLAY NUMBERED ICON: [" . $display_numbered_icon . "]<br>";
      // echo ">>>>>>>>>>>>>>>>>>>> OPT_PFX: [" . $opt_pfx . "]<br>";
      // echo ">>>>>>>>>>>>>>>>>>>> TOTAL NUMBER [" . $new_features_number . "]<br>";

      return $new_features_number;
    }

} /* End class definition */
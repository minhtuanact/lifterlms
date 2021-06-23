<?php
if (!function_exists('get_plugin_data'))
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$plugin_data = get_plugin_data(plugin_file);

define("magenet_plugin_version", $plugin_data['Version']);

function magenet_upgrade_completed( $upgrader_object, $options ) 
{
    $magenetLinkAutoinstall = new MagenetLinkAutoinstall();
    $magenetLinkAutoinstall->table();
}
add_action( 'upgrader_process_complete', 'magenet_upgrade_completed', 10, 2 );

if (!class_exists('MagenetLinkAutoinstall')) {

    class MagenetLinkAutoinstall
    {
        private $cache_time = 3600;
        private $api_host = "http://api.magenet.com";
        private $api_get = "/wordpress/get";
        private $api_test = "/wordpress/test";
        private $api_activate = "/wordpress/activate";
        private $api_deactivate = "/wordpress/deactivate";
        private $api_uninstall = "/wordpress/uninstall";
        private $api_status = "/wordpress/status";
        //private $is_active_seo_plugin = false;
        private $key = false;
        //private $link_shown = 0;
        private $lastError = 0;
        private $the_content_log;
        private $plugin_name;
        private $plugin_url;
        private $plugin_show_by;
        private $tbl_magenet_links;
        public static $shown;
        public static $counter;

        public function MagenetLinkAutoinstall()
        {
            global $wpdb;
            define('MagenetLinkAutoinstall', true);
            if(defined(MN_API_HOST)) {
                $this->api_host = MN_API_HOST;
            }
            $this->plugin_name = plugin_basename(plugin_file);
            $this->plugin_url = trailingslashit(WP_PLUGIN_URL . '/' . dirname(plugin_basename(plugin_file)));
            $this->tbl_magenet_links = $wpdb->prefix . 'magenet_links';
            $this->plugin_show_by = get_option("magenet_links_show_by");

            register_activation_hook($this->plugin_name, array(&$this, 'activate'));
            register_deactivation_hook($this->plugin_name, array(&$this, 'deactivate'));
            //register_uninstall_hook($this->plugin_name, array(&$this, 'uninstall'));

            if (is_admin()) {
                //add_action('wp_print_scripts', array(&$this, 'admin_load_scripts'));
                add_action('admin_enqueue_scripts', array(&$this, 'admin_load_scripts'));
                //add_action('wp_print_styles', array(&$this, 'admin_load_styles'));
                add_action('admin_enqueue_scripts', array(&$this, 'admin_load_styles'));
                add_action('admin_menu', array(&$this, 'admin_generate_menu'));
            }
            else {
                if (!has_filter('the_content', array(&$this, 'add_magenet_links'))) {
                    add_filter('the_content', array(&$this, 'add_magenet_links'));
                    add_filter('the_excerpt', array(&$this, 'add_magenet_links'));
                }

                add_action('get_footer', array(&$this, 'footerFilter'), 10000);
            }
        }

        public function footerFilter()
        {
            echo '<!-- wmm ' . (get_option("magenet_links_show_by") == 1 ? 'w' : 'd') . ' -->';
        }

        public function getKey()
        {
            if (!$this->key) {
                $this->key = get_option("magenet_links_autoinstall_key");
            }
            return $this->key;
        }

        public function setKey($key)
        {
            update_option("magenet_links_autoinstall_key", $key);
            $this->key = $key;
        }

        public function activate()
        {
            //global $wpdb;
            //require_once(ABSPATH . 'wp-admin/upgrade-functions.php'); // depricated
            //require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            
            $this->table();

            /*$table = $this->tbl_magenet_links;
            $charset_collate = '';

            if (@version_compare(mysqli_get_server_info(), '4.1.0', '>=')) {
                if (!empty($wpdb->charset)) {
                    $charset_collate = " DEFAULT CHARACTER SET {$wpdb->charset} ";
                }
                if (!empty($wpdb->collate)) {
                    $charset_collate .= " COLLATE {$wpdb->collate} ";
                }
            }

            $sql_table_magenet_links = "
                CREATE TABLE `" . $wpdb->prefix . "magenet_links` (
                `ID` INT(10) NOT NULL AUTO_INCREMENT,
                `page_url` TEXT NOT NULL DEFAULT '',
                `link_html` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`ID`)
                )" . $charset_collate . ";";
            $sql_add_index = "CREATE INDEX page_url ON `" . $wpdb->prefix . "magenet_links` (page_url(100));";

            if ($wpdb->get_var("show tables like '" . $table . "'") != $table) {
                dbDelta($sql_table_magenet_links);
                $wpdb->query($sql_add_index);
            }*/

            $result = $this->sendRequest($this->api_host . $this->api_activate, $this->getKey());
        }
        
        public function table()
        {
            global $wpdb;
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $table = $this->tbl_magenet_links;
            $charset_collate = '';
            
            if (@version_compare(mysqli_get_server_info(), '4.1.0', '>=')) {
                if (!empty($wpdb->charset)) {
                    $charset_collate = " DEFAULT CHARACTER SET {$wpdb->charset} ";
                }
                if (!empty($wpdb->collate)) {
                    $charset_collate .= " COLLATE {$wpdb->collate} ";
                }
            }

            $sql_table_magenet_links = "
                CREATE TABLE `" . $wpdb->prefix . "magenet_links` (
                `ID` INT(10) NOT NULL AUTO_INCREMENT,
                `page_url` TEXT NOT NULL DEFAULT '',
                `link_html` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`ID`)
                )" . $charset_collate . ";";
            $sql_add_index = "CREATE INDEX page_url ON `" . $wpdb->prefix . "magenet_links` (page_url(100));";

            if ($wpdb->get_var("show tables like '" . $table . "'") != $table) {
                dbDelta($sql_table_magenet_links);
                $wpdb->query($sql_add_index);
            }
        }

        public function deactivate()
        {
            $result = $this->sendRequest($this->api_host . $this->api_deactivate, $this->getKey());
            return true;
        }

        public function uninstall()
        {
            global $wpdb;
            $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}magenet_links");
            $result = $this->sendRequest($this->api_host . $this->api_uninstall, $this->getKey());
        }

        public function admin_load_scripts()
        {
            wp_register_script('magenetLinkAutoinstallAdminJs', $this->plugin_url . 'js/admin-scripts.js', array('jquery-ui-dialog'));
            wp_enqueue_script('magenetLinkAutoinstallAdminJs');
        }

        public function admin_load_styles()
        {
            wp_register_style('magenetLinkAutoinstallAdminCss', $this->plugin_url . 'css/admin-style.css');
            wp_enqueue_style('magenetLinkAutoinstallAdminCss');
            wp_enqueue_style('wp-jquery-ui-dialog');
        }

        public function admin_generate_menu()
        {
            add_options_page('Website Monetization by MageNet', 'Website Monetization by MageNet', 'manage_options', 'magenet-links-settings', array(&$this, 'admin_magenet_settings'));
        }

        public function add_magenet_links($content)
        {
            if ($this->plugin_show_by == 1) {
                return $content;
            }

            //global $post;

            $uniq_post_id = md5(substr($content, 0, 100));
            if (strlen($content) > 0)
                empty($this->the_content_log[$uniq_post_id]) ? $this->the_content_log[$uniq_post_id] = 1 : $this->the_content_log[$uniq_post_id] ++;

            $mads_block = '';
            if (is_array($this->the_content_log) && count($this->the_content_log) == 1) {
                //global $wpdb;
                $link_data = $this->getLinks();
                $mads_block = '<div class="mads-block">';
                if (is_array($link_data) && count($link_data) > 0) {
                    foreach ($link_data as $link) {
                        $mads_block .= "\n" . $link['link_html'];
                    }
                }
                $mads_block .= '</div>';
            }

            $content .= $mads_block;

            return $content;
        }

        public function admin_magenet_settings()
        {
            global $wpdb;
            $magenet_key = $this->getKey();
            // set or update magenet key
            if (isset($_POST['key'])) { 
                if(!empty($_POST['key'])) {
                    if ($this->testKey($_POST['key'])) {
                        $magenet_key = $_POST['key'];
                        $this->setKey($magenet_key);
                        $result_text = '<span style="color: #009900;">Key confirmed</span>';
                        if ($this->status() == 2) {
                            $result_text .= '<br><span style="color: #ff3838;">Check the box "Switch to Automatic" in "Your Sites" interface to start selling backlinks from your website in automatic mode.</span>';
                        }
                    }
                    else {
                        if ($this->lastError == 0) {
                            $result_text = '<span style="color: #ca2222;">Incorrect Key. Please try again.</span>';
                        }
                        else {
                            $result_text = '<span style="color: #ca2222;">Temporary Error (' . $this->lastError . '). Please try again later. If you continue to see this error over an extended period of time, <a href="http://www.magenet.com/contact-us/" target="_blank">please let us know</a> so we can look into the issue.</span>';
                        }
                    }
                } 
                else {
                    $result_text = '<span style="color: #ca2222;">The field can\'t be empty. Please try again.</span>';
                }
            }
            // update links data
            if (isset($_POST['update_data']) && $_POST['update_data'] == 1) {
                $result = $this->sendRequest($this->api_host . $this->api_get, $magenet_key);
                if ($result) {
                    $wpdb->query("DELETE FROM {$this->tbl_magenet_links} WHERE 1");
                    $new_links = json_decode($result, TRUE);
                    if (count($new_links) > 0) {
                        foreach ($new_links as $new_link) {
                            if (isset($new_link['page_url']) && isset($new_link['issue_html'])) {
                                $wpdb->query($wpdb->prepare("INSERT INTO {$this->tbl_magenet_links}(page_url, link_html) VALUES (%s, %s)", $new_link['page_url'], $new_link['issue_html']));
                            }
                        }
                    }
                }
                update_option("magenet_links_last_update", time());
                $result_update_text = '<span style="color: #009900;">Ads have been updated.</span>';
            }
            $link_data = $wpdb->get_results("SELECT * FROM `" . $this->tbl_magenet_links . "`", ARRAY_A);
            // update placement method
            if (isset($_POST['links_show_by'])) {
                update_option('magenet_links_show_by', $_POST['links_show_by']);
                $result_showBy_text = '<span style="color: #009900;">Ads location and placement method has been updated.</span>';
            }

            $this->plugin_show_by = get_option("magenet_links_show_by");

            include_once('view-settings.php');
        }

        public function testKey($key)
        {
            $result = $this->sendRequest($this->api_host . $this->api_test, $key);
            return $result === "1";
        }

        public function getLinks()
        {
            global $wpdb;
            $key = $this->getKey();
            if ($key) {
                $last_update_time = get_option("magenet_links_last_update");
                if ($last_update_time + $this->cache_time < time()) {
                    $this->updateLinks($key);
                }
                
                $site_url = str_replace("'", "\'", get_option("siteurl"));
                $page_url = parse_url($site_url . str_replace("'", "\'", $_SERVER["REQUEST_URI"]));

                $url_for_check = $page_url['scheme'] . "://" . (isset($page_url['host']) ? $page_url['host'] : '') . (isset($page_url['path']) ? $page_url['path'] : '') . (isset($page_url['query'])
                            ? '?' . $page_url['query'] : '');

                // ? trailing slash
                $url_for_check_slash = (substr($url_for_check, -1) == '/' ? substr($url_for_check, 0, -1) : $url_for_check . '/' );

                // ? www
                $url_for_check_www = (strpos($url_for_check, "://www.") > 0 ? $this->str_replace_first("www.", "", $url_for_check) : str_replace("://", "://www.", $url_for_check) );

                // ? www +++ ? trailing slash
                $url_for_check_www_slash = (substr($url_for_check_www, -1) == '/' ? substr($url_for_check_www, 0, -1) : $url_for_check_www . '/' );

                // http or https
                $url_for_check_http = (substr($url_for_check, 0, 5) == 'https' ? $this->str_replace_first("https", "http", $url_for_check) : $this->str_replace_first("http", "https",
                            $url_for_check) );

                // http or https +++ ? trailing slash
                $url_for_check_http_slash = (substr($url_for_check_slash, 0, 5) == 'https' ? $this->str_replace_first("https", "http", $url_for_check_slash) : $this->str_replace_first("http",
                            "https", $url_for_check_slash) );

                // http or https +++ ? www
                $url_for_check_http_www = (substr($url_for_check_www, 0, 5) == 'https' ? $this->str_replace_first("https", "http", $url_for_check_www) : $this->str_replace_first("http",
                            "https", $url_for_check_www) );

                // http or https +++ ? www +++ ? trailing slash
                $url_for_check_http_www_slash = (substr($url_for_check_www_slash, 0, 5) == 'https' ? $this->str_replace_first("https", "http", $url_for_check_www_slash) : $this->str_replace_first("http",
                            "https", $url_for_check_www_slash) );

                $link_sql = $wpdb->prepare("SELECT * FROM {$this->tbl_magenet_links} WHERE page_url = '%s' or page_url = '%s' or page_url = '%s' or page_url = '%s' or page_url = '%s' or page_url = '%s' or page_url = '%s' or page_url = '%s' ",
                    $url_for_check, $url_for_check_slash, $url_for_check_www, $url_for_check_www_slash, $url_for_check_http, $url_for_check_http_slash, $url_for_check_http_www,
                    $url_for_check_http_www_slash);

                $link_data = $wpdb->get_results($link_sql, ARRAY_A);
                return $link_data;
            }
            return false;
        }
        
        public function updateLinks($key)
        {
            global $wpdb;
            $result = $this->sendRequest($this->api_host . $this->api_get, $key);
            if ($result) {
                $wpdb->query("DELETE FROM {$this->tbl_magenet_links} WHERE 1");
                $new_links = json_decode($result, TRUE);
                if (count($new_links) > 0) {
                    foreach ($new_links as $new_link) {
                        if (isset($new_link['page_url']) && isset($new_link['issue_html'])) {
                            $wpdb->query($wpdb->prepare("INSERT INTO {$this->tbl_magenet_links}(page_url, link_html) VALUES (%s, %s)", 
                            $new_link['page_url'], $new_link['issue_html']));
                        }
                    }
                }
            }
            update_option("magenet_links_last_update", time());
        }

        public function sendRequest($url, $key)
        {
            $siteurl = get_option("siteurl");
            $params = http_build_query(array(
                'url' => $siteurl,
                'key' => $key,
                'version' => magenet_plugin_version
            ));
            if (function_exists('curl_init') && function_exists('curl_exec')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
                curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                $curl_result = curl_exec($ch);

                $this->lastError = curl_errno($ch);
                if (!$this->lastError) {
                    $result = $curl_result;
                }
                else {
                    $result = false;
                }
                curl_close($ch);
            }
            else {
                $url = $url . "?" . $params;
                $data = file_get_contents($url, false);
                $result = $data;
            }
            return $result;
        }

        public function status()
        {
            $result = $this->sendRequest($this->api_host . $this->api_status, $this->getKey());
            return intval($result);
        }

        public function showLinksWidget($show = 0)
        {
            $show = abs((int) $show);

            $link_data = $this->getLinks();

            $mads_block = '<div class="mads-block">';

            $counter_loop = 0;
            $counter_show = 0;
            if (is_array($link_data) && count($link_data) > 0) {
                foreach ($link_data as $link) {
                    $counter_loop++;

                    if (self::$counter > 0 && self::$counter >= $counter_loop) {
                        continue;
                    }

                    self::$counter++;
                    $counter_show++;

                    $mads_block .= "\n" . $link['link_html'];

                    if ($show == $counter_show) {
                        break;
                    }
                }
            }

            $mads_block .= '</div>';

            self::$shown = true;

            return $mads_block;
        }
        
        public function str_replace_first($search, $replace, $subject)
        {
            $search = '/' . preg_quote($search, '/') . '/';
            return preg_replace($search, $replace, $subject, 1);
        }
    }
}

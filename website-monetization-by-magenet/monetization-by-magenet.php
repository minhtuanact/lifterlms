<?php
/*
  Plugin Name: Website Monetization by MageNet
  Description: Website Monetization by MageNet allows you to sell contextual ads from your pages automatically and receive payments with PayPal. To get started: 1) Click the "Activate" link to the left of this description, 2) <a href="http://www.magenet.com" target="_blank">Sign up for a MageNet Key</a>, and 3) Go to Settings > "Website Monetization by MageNet" configuration page, and save your MageNet Key.
  Version: 1.0.26
  Author: MageNet.com
  Author URI: http://www.magenet.com
  Text Domain: website-monetization-by-magenet
 */
define("plugin_file", __FILE__);
$error_param = ini_get('display_errors');
ini_set('display_errors', 0);

// Stop direct call
if (preg_match('#' . basename(plugin_file) . '#', $_SERVER['PHP_SELF'])) {
    if (isset($_GET['check']) && !empty($_GET['check']) && strlen($_GET['check']) >= 10)
        die(md5('magenet.com'));

    die('You are not allowed to call this page directly.');
}

if (!function_exists('json_decode')) {
    function json_decode($json, $assoc)
    {
        include_once('JSON.php');
        $use = $assoc ? SERVICES_JSON_LOOSE_TYPE : 0;
        $jsonO = new Services_JSON($use);
        return $jsonO->decode($json);
    }
}

require_once( plugin_dir_path(plugin_file) . 'MagenetLinkAutoinstall.php' );
global $magenetLinkAutoinstall;
$magenetLinkAutoinstall = new MagenetLinkAutoinstall();
ini_set('display_errors', $error_param);

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'my_plugin_action_links');

function my_plugin_action_links($links)
{
    $links[] = '<a href="http://www.magenet.com/magenet-services/#vip" target="_blank">Premium Support</a>';
    $links[] = '<a href="javascript:void(0)" class="show-magenet-tutorial">?</a>';
    return $links;
}

function magenet_notices()
{
    if (strstr($_SERVER['SCRIPT_NAME'], 'plugins.php')) {
        ?>
        <div id="mn-0" class="magenet-tutorial-popup" style="display: none;" title="Congratulations!">
            Monetizing Plugin is installed successfully!<br><br>
            Now you are ready to make profit from your site automatically!<br><br>
            Click "Start tutorial" to set up the plugin efficiently.
            <?php magenet_tutorial_buttons(-2); ?>
        </div>

        <div id="mn-1" class="magenet-tutorial-popup" style="display: none" title="Step 1">
            <a href="http://cp.magenet.com/" target="_blank">Log in to MageNet</a>&nbsp;&nbsp;if you already have <strong>MageNet</strong> account<br><br>
            or<br><br>
            <a href="http://www.magenet.com/#sign_up" target="_blank">Sign up for MageNet</a>&nbsp;&nbsp;to provide plugin with ads from our marketplace
            <?php magenet_tutorial_buttons(-1); ?>
        </div>

        <div id="mn-2" class="magenet-tutorial-popup" style="display: none" title="Step 2">
            <a href="http://cp.magenet.com/sites/sites/add" target="_blank">Add</a> your site to your <strong>MageNet</strong> account<br><br>
            then click <span class="confirm-website">Confirm Website</span><br><br>
            in the opened pop-up copy of <b>Magenet Key</b><br>situated under "Install Wordpress Plugin" link
            <?php magenet_tutorial_buttons(); ?>
        </div>

        <div id="mn-3" class="magenet-tutorial-popup" style="display: none" title="Step 3">
            Go to <strong>Settings</strong> section in the WordPress admin menu at the left and click "Website Monetization by MageNet" in the list below
            <?php magenet_tutorial_buttons(); ?>
        </div>

        <div id="mn-4" class="magenet-tutorial-popup" style="display: none" title="Step 4">
            Paste the copied code to the MageNet Key field and click "Save" button
            <?php magenet_tutorial_buttons(); ?>
        </div>

        <div id="mn-5" class="magenet-tutorial-popup" style="display: none" title="Success!">
            &nbsp;<br>&nbsp;
            <h3 style="text-align: center">Plugin is activated</h3>
            <?php magenet_tutorial_buttons(1); ?>
        </div>

        <?php
    }
}
add_action('admin_notices', 'magenet_notices');

function magenet_tutorial_buttons($direction = 0)
{
    ?>
    <div style="position: absolute; bottom: 0; height: 48px; width: 468px;">
        <div>
            <div style="width: 40%; float: left; text-align: left;">
                <?php if ($direction >= 0) { ?><span class="btn_prev tutorial-stop">&lt;&lt; Prev</span><?php } ?>
            </div>
            <div style="width: 40%; float: right; text-align: right;">
                <?php if ($direction <= 0) { ?>
                    <?php if ($direction == -2) { ?>
                        <span class="btn_next tutorial-next">Start tutorial</span>
                    <?php
                    }
                    else {
                        ?>
                        <span class="btn_next tutorial-next">Next &gt;&gt;</span>
                    <?php } ?>
                <?php
                }
                else {
                    ?>
                    <span class="btn_again show-magenet-tutorial">Watch Again</span>
    <?php } ?>
            </div>
        </div>
    </div>
    <?php
}

function magenet_action_callback()
{
    echo (!get_option("magenet_links_autoinstall_key") ? 1 : 2);
    wp_die();
}
add_action('wp_ajax_magenet_action', 'magenet_action_callback');

// widget
class Magenet_Widget extends WP_Widget
{
    // constructor
    function __construct()
    {
        parent::__construct(false, $name = 'Magenet Widget');
    }

    // widget form creation
    function form($instance)
    {
        // Check values
        if ($instance)
            $select = esc_attr($instance['select']);
        else
            $select = 0;
?>
<?php /*
        <p>You can set up as many widgets and locations as you wish and adjust the widgets to show as many ads as affordable.</p>
        <p>Exapmle:</p>
        <p>1st widget adjusted to place 2 ads - the first 2 sold ads will be placed at the location of the 1st widget then.</p>
        <p>2nd widget adjusted to place 1 ad - the 3rd sold ad will be placed at the location of 2nd widget.</p>
        <p>3rd widget adjusted to place the rest of ads (All ads chosen) - all the ads after 3rd will be placed in the 3rd widget.</p> */
?>
        <p>
            <label for="<?php echo $this->get_field_id('select'); ?>">Select the number ads to place in current widget</label>
            <select name="<?php echo $this->get_field_name('select'); ?>" id="<?php echo $this->get_field_id('select'); ?>" class="widefat">
                <?php
                for ($i = 0; $i <= 10; $i++) {
                    $text = ($i == 0 ? 'All ads (default)' : $i . ' ad' . ($i == 1 ? '' : 's'));
                    echo '<option value="', $i, '"', $select == $i ? ' selected="selected"' : '', '>', $text, '</option>';
                }
                ?>
            </select>
        </p>
        <?php
    }

    // widget update
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        // Fields
        $instance['select'] = strip_tags($new_instance['select']);

        return $instance;
    }

    // widget display
    function widget($args, $instance)
    {
        extract($args);

        // these are the widget options
        $select = (int) $instance['select'];

        // Display the widget
        echo $before_widget;
        echo '<aside class="widget magenet_widget_box">';

        global $magenetLinkAutoinstall;
        echo $magenetLinkAutoinstall->showLinksWidget($select);

        echo '</aside>';
        echo $after_widget;
    }
}
// register widget
if (get_option("magenet_links_show_by") == 1)
    add_action('widgets_init', create_function('', 'return register_widget("Magenet_Widget");'));

// dashboard widget
function magenet_dashboard_widget() {
?>
<script type="text/javascript">
jQuery.ajax(ajaxurl, {
    dataType: 'html',
    type: 'GET',
    data: { action: 'magenet_dashboard_action' },
    success: function(response, status, xhr) {
        jQuery("#magenet_dw").html(response);
    }
});
</script>
<div class="rss-widget" id="magenet_dw">Loading data ...</div>
<?php     
}

// register dashboard widget function
function add_dashboard_widgets() {
    wp_add_dashboard_widget('dashboard_widget', 'Magenet Info', 'magenet_dashboard_widget');
}

// register dashboard widget hook
add_action('wp_dashboard_setup', 'add_dashboard_widgets' );

function magenet_dashboard_action_callback()
{
    $key = get_option("magenet_links_autoinstall_key");
    if ($key) {
        $mn_api_url = 'https://cp.magenet.com/mn-api/?k=' . $key . '&s=' . base64_encode(get_option("siteurl"));
        $mn_api_data = file_get_contents($mn_api_url);
        $mn_api_str = '';

        if (strlen($mn_api_data) > 0) {
            $response = base64_decode($mn_api_data);

            if (isset($response['ok']) && $response['ok'] == "-1") {
                $mn_api_str = 'Wrong key';
            }
            else if (isset($response['ok']) && $response['ok'] == "0") {
                $mn_api_str = 'Can\t find site';
            }
            else {
                $mn_api_str = $response;

                $rss = fetch_feed( 'http://www.magenet.com/feed/' );
                if ( ! is_wp_error( $rss ) ) :
                    $maxitems = $rss->get_item_quantity( 1 ); 
                    $rss_items = $rss->get_items( 0, $maxitems );
                    $mn_api_str .= '<hr><b>Recent blog post:</b> ';
                    $mn_api_str .= '<a href="' . $rss_items[0]->get_permalink() . '" target="_blank">' . $rss_items[0]->get_title() . '</a>';
                endif;

            }

        }
    }

    echo $mn_api_str;
    wp_die();
}

add_action('wp_ajax_magenet_dashboard_action', 'magenet_dashboard_action_callback');
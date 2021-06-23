<?php
add_action('plugin_action_links_quick-adsense/quick-adsense.php', function($links) {
	$links = array_merge(
		array('<a href="'.esc_url(admin_url('/admin.php?page=quick-adsense')).'">Settings</a>'),
		$links
	);
	return $links;
});

add_action('admin_menu', function() {
	add_menu_page('Quick Adsense Options', 'Quick Adsense', 'manage_options', 'quick-adsense', 'quick_adsense_settings_page');
});

add_action('admin_enqueue_scripts', function($hook) {
		if($hook != 'toplevel_page_quick-adsense') {
            return;
        }
		wp_register_script('quick-adsense-minicolors', plugins_url('/js/jquery.minicolors.js', __FILE__), array('jquery', 'jquery-ui-core'));
		wp_enqueue_script('quick-adsense-minicolors');
		wp_register_script('quick-adsense-chart-js', plugins_url('/js/Chart.bundle.min.js', __FILE__), array('jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-dialog'));
		wp_enqueue_script('quick-adsense-chart-js');
		wp_register_style('quick-adsense-jquery-ui', 'https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('quick-adsense-jquery-ui');
        wp_enqueue_style('quick_adsense_admin_css', plugins_url('/css/admin.css', __FILE__), array(), '2.7');
		wp_enqueue_script('quick_adsense_admin_js', plugins_url('/js/admin.js', __FILE__), array('jquery', 'jquery-ui-core', 'jquery-ui-tabs'), '2.7');
});

add_action('admin_init', function() {
	register_setting('quick_adsense_settings', 'quick_adsense_settings', 'quick_adsense_validate');
    add_settings_section('quick_adsense_general', '', 'quick_adsense_general_content', 'quick-adsense-general');
	add_settings_section('quick_adsense_onpost', '', 'quick_adsense_onpost_content', 'quick-adsense-onpost');
	add_settings_section('quick_adsense_widgets', '', 'quick_adsense_widgets_content', 'quick-adsense-widgets');
	add_settings_section('quick_adsense_header_footer_codes', '', 'quick_adsense_header_footer_codes_content', 'quick-adsense-header-footer-codes');
});

function quick_adsense_settings_page() { ?>
    <div class="wrap">
		<h2 id="quick_adsense_title">Quick Adsense Setting <span style="font-size: 14px;">(Version 2.7)</span></h2>
		<form id="quick_adsense_settings_form" method="post" action="options.php" name="wp_auto_commenter_form" style="display: none;">
			<?php settings_fields('quick_adsense_settings'); ?>
			<a style="display: inline-block; margin: 0 0 10px;" target="_blank" href="https://www.adpushup.com/lp/quick-adsense/"><img style="max-width: 100%;" src= "<?php echo plugins_url('/images/adpushup-970x250-red.png', __FILE__); ?>" /></a>
			<?php quick_adsense_settings_page_tabs(); ?>		
		</form>
		<input type="hidden" id="quick_adsense_admin_ajax" name="quick_adsense_admin_ajax" value="<?php echo admin_url('admin-ajax.php'); ?>" />
		<input type="hidden" id="quick_adsense_nonce" name="quick_adsense_nonce" value="<?php echo wp_create_nonce('quick-adsense'); ?>" />
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#quick_adsense_settings_tabs").tabs();
		});
		</script>
    </div>
<?php
}

function quick_adsense_settings_page_tabs() {
	echo '<div id="quick_adsense_settings_tabs">';
		echo '<ul>';
			echo '<li><a href="#tabs-settings">Settings</a></li>';									
			echo '<li><a href="#tabs-post-body-ads">Ads on Post Body</a></li>';
			echo '<li><a href="#tabs-sidebar-widget-ads">Sidebar Widget</a></li>';
			echo '<li><a href="#tabs-header-footer-codes">Header / Footer Codes</a></li>';
		echo '</ul>';
		echo '<div id="tabs-settings">';
			echo '<div id="quick_adsense_top_sections_wrapper">';
				do_settings_sections('quick-adsense-general');
			echo '</div>';
			submit_button('Save Changes');
		echo '</div>';
		echo '<div id="tabs-post-body-ads">';
			do_settings_sections('quick-adsense-onpost');
			submit_button('Save Changes');
		echo '</div>';
		echo '<div id="tabs-sidebar-widget-ads">';
			do_settings_sections('quick-adsense-widgets');
			submit_button('Save Changes');
		echo '</div>';
		echo '<div id="tabs-header-footer-codes">';
			do_settings_sections('quick-adsense-header-footer-codes');
			submit_button('Save Changes');
		echo '</div>';
	echo '</div>';
}

function quick_adsense_header_footer_codes_content() {
	$settings = get_option('quick_adsense_settings');
	echo '<div id="quick_adsense_top_sections_wrapper">';
		echo '<div class="quick_adsense_block">';
			echo '<div class="quick_adsense_block_labels">';
				echo '<span>Header<br />Embed Code</span>';
			echo '</div>';
			echo '<div class="quick_adsense_block_controls">';
				echo quickadsense_get_control('textarea-big', '', 'quick_adsense_settings_header_embed_code', 'quick_adsense_settings[header_embed_code]', ((isset($settings['header_embed_code']))?$settings['header_embed_code']:''));
			echo '</div>';
			echo '<div class="clear"></div>';
			echo '<div class="quick_adsense_block_labels">';
				echo '<span>Footer<br />Embed Code</span>';
			echo '</div>';
			echo '<div class="quick_adsense_block_controls">';
				echo quickadsense_get_control('textarea-big', '', 'quick_adsense_settings_footer_embed_code', 'quick_adsense_settings[footer_embed_code]', ((isset($settings['footer_embed_code']))?$settings['footer_embed_code']:''));
			echo '</div>';
			echo '<div class="clear"></div>';
		echo '</div>';
	echo '</div>';
}

function quick_adsense_general_content() {	
	$settings = get_option('quick_adsense_settings');
	echo '<div class="quick_adsense_block">';
		echo '<div class="quick_adsense_block_labels">';
			echo '<span>Options</span>';
		echo '</div>';
		echo '<div class="quick_adsense_block_controls">';
			echo '<a id="quick_adsense_settings_reset_to_default" href="javascript:;">Reset to Default Settings</a>';
		echo '</div>';
		echo '<div class="clear"></div>';

		echo '<div class="quick_adsense_block_labels">';
			echo 'Adsense';
		echo '</div>';
		echo '<div class="quick_adsense_block_controls">';
			echo 'Place up to';
			$maxAdsCount = array();
			for($i = 0; $i <= 10; $i++) {
				$maxAdsCount[] = array('text' => $i, 'value' => $i);
			}
			echo quickadsense_get_control('select', '', 'quick_adsense_settings_max_ads_per_page', 'quick_adsense_settings[max_ads_per_page]', ((isset($settings['max_ads_per_page']))?$settings['max_ads_per_page']:''),  $maxAdsCount, 'input', 'margin: -2px 10px 0 40px;');
			echo 'Ads on a page';
		echo '</div>';
		echo '<div class="clear"></div>';
		echo '<div class="clear"></div>';
		echo '<div class="quick_adsense_block_labels">';
			echo 'Assign position<br />(Default)';
		echo '</div>';
		echo '<div class="quick_adsense_block_controls">';
			$adPositions = array(
				array('text' => 'Random Ads', 'value' => '0')
			);
			for($i = 1; $i <= 10; $i++) {
				$adPositions[] = array('text' => 'Ads'.$i, 'value' => $i);
			}
			
			$elementCount = array();
			for($i = 1; $i <= 50; $i++) {
				$elementCount[] = array('text' => $i, 'value' => $i);
			}
			echo '<p>';
				echo quickadsense_get_control('checkbox', '', 'quick_adsense_settings_enable_position_beginning_of_post', 'quick_adsense_settings[enable_position_beginning_of_post]', ((isset($settings['enable_position_beginning_of_post']))?$settings['enable_position_beginning_of_post']:''),  null, 'input', '');
				echo quickadsense_get_control('select', '', 'quick_adsense_settings_ad_beginning_of_post', 'quick_adsense_settings[ad_beginning_of_post]', ((isset($settings['ad_beginning_of_post']))?$settings['ad_beginning_of_post']:''),  $adPositions, 'input', 'margin: -2px 10px 0 20px;');
				echo '<b style="width: 120px; display: inline-block;">Beginning of Post</b>';
			echo '</p>';
			echo '<p>';
				echo quickadsense_get_control('checkbox', '', 'quick_adsense_settings_enable_position_middle_of_post', 'quick_adsense_settings[enable_position_middle_of_post]', ((isset($settings['enable_position_middle_of_post']))?$settings['enable_position_middle_of_post']:''),  null, 'input', '');
				echo quickadsense_get_control('select', '', 'quick_adsense_settings_ad_middle_of_post', 'quick_adsense_settings[ad_middle_of_post]', ((isset($settings['ad_middle_of_post']))?$settings['ad_middle_of_post']:''),  $adPositions, 'input', 'margin: -2px 10px 0 20px;');
				echo '<b style="width: 120px; display: inline-block;">Middle of Post</b>';
			echo '</p>';
			echo '<p>';
				echo quickadsense_get_control('checkbox', '', 'quick_adsense_settings_enable_position_end_of_post', 'quick_adsense_settings[enable_position_end_of_post]', ((isset($settings['enable_position_end_of_post']))?$settings['enable_position_end_of_post']:''),  null, 'input', '');
				echo quickadsense_get_control('select', '', 'quick_adsense_settings_ad_end_of_post', 'quick_adsense_settings[ad_end_of_post]', ((isset($settings['ad_end_of_post']))?$settings['ad_end_of_post']:''),  $adPositions, 'input', 'margin: -2px 10px 0 20px;');
				echo '<b>End of Post</b>';
			echo '</p>';
			echo '<div class="clear"></div>';
			echo '<p>';
				echo quickadsense_get_control('checkbox', '', 'quick_adsense_settings_enable_position_after_more_tag', 'quick_adsense_settings[enable_position_after_more_tag]', ((isset($settings['enable_position_after_more_tag']))?$settings['enable_position_after_more_tag']:''),  null, 'input', '');
				echo quickadsense_get_control('select', '', 'quick_adsense_settings_ad_after_more_tag', 'quick_adsense_settings[ad_after_more_tag]', ((isset($settings['ad_after_more_tag']))?$settings['ad_after_more_tag']:''),  $adPositions, 'input', 'margin: -2px 10px 0 20px;');
				echo 'right after <b>the &lt;!--more--&gt; tag</b>';
			echo '</p>';
			echo '<p>';
				echo quickadsense_get_control('checkbox', '', 'quick_adsense_settings_enable_position_before_last_para', 'quick_adsense_settings[enable_position_before_last_para]', ((isset($settings['enable_position_before_last_para']))?$settings['enable_position_before_last_para']:''),  null, 'input', '');
				echo quickadsense_get_control('select', '', 'quick_adsense_settings_ad_before_last_para', 'quick_adsense_settings[ad_before_last_para]', ((isset($settings['ad_before_last_para']))?$settings['ad_before_last_para']:''),  $adPositions, 'input', 'margin: -2px 10px 0 20px;');
				echo 'right before <b>the last Paragraph</b>';
			echo '</p>';
			echo '<div class="clear"></div>';
			for($i = 1; $i <= 3; $i++) {
				echo '<p>';
					echo quickadsense_get_control('checkbox', '', 'quick_adsense_settings_enable_position_after_para_option_'.$i, 'quick_adsense_settings[enable_position_after_para_option_'.$i.']', ((isset($settings['enable_position_after_para_option_'.$i]))?$settings['enable_position_after_para_option_'.$i]:''),  null, 'input', '');
					echo quickadsense_get_control('select', '', 'quick_adsense_settings_ad_after_para_option_'.$i, 'quick_adsense_settings[ad_after_para_option_'.$i.']', ((isset($settings['ad_after_para_option_'.$i]))?$settings['ad_after_para_option_'.$i]:''),  $adPositions, 'input', 'margin: -2px 10px 0 20px;');
					echo '<span style="width: 110px;display: inline-block;"><b>after Paragraph</b></span>';
					echo quickadsense_get_control('select', '', 'quick_adsense_settings_position_after_para_option_'.$i, 'quick_adsense_settings[position_after_para_option_'.$i.']', ((isset($settings['position_after_para_option_'.$i]))?$settings['position_after_para_option_'.$i]:''),  $elementCount, 'input', 'margin: -2px 10px 0 10px;');
					echo 'repeat';
					echo quickadsense_get_control('checkbox', '', 'quick_adsense_settings_enable_jump_position_after_para_option_'.$i, 'quick_adsense_settings[enable_jump_position_after_para_option_'.$i.']', ((isset($settings['enable_jump_position_after_para_option_'.$i]))?$settings['enable_jump_position_after_para_option_'.$i]:''),  null, 'input', 'margin: -1px 10px 0;');
					echo '<b>to End of Post</b> if fewer paragraphs are found';
				echo '</p>';
			}
			echo '<div class="clear"></div>';
			for($i = 1; $i <= 1; $i++) {
				echo '<p>';
					echo quickadsense_get_control('checkbox', '', 'quick_adsense_settings_enable_position_after_image_option_'.$i, 'quick_adsense_settings[enable_position_after_image_option_'.$i.']', ((isset($settings['enable_position_after_image_option_'.$i]))?$settings['enable_position_after_image_option_'.$i]:''),  null, 'input', '');
					echo quickadsense_get_control('select', '', 'quick_adsense_settings_ad_after_image_option_'.$i, 'quick_adsense_settings[ad_after_image_option_'.$i.']', ((isset($settings['ad_after_image_option_'.$i]))?$settings['ad_after_image_option_'.$i]:''),  $adPositions, 'input', 'margin: -2px 10px 0 20px;');
					echo '<span style="width: 110px;display: inline-block;">after Image</span>';
					echo quickadsense_get_control('select', '', 'quick_adsense_settings_position_after_image_option_'.$i, 'quick_adsense_settings[position_after_image_option_'.$i.']', ((isset($settings['position_after_image_option_'.$i]))?$settings['position_after_image_option_'.$i]:''),  $elementCount, 'input', 'margin: -2px 10px 0 10px;');
					echo 'repeat';
					echo quickadsense_get_control('checkbox', '', 'quick_adsense_settings_enable_jump_position_after_image_option_'.$i, 'quick_adsense_settings[enable_jump_position_after_image_option_'.$i.']', ((isset($settings['enable_jump_position_after_image_option_'.$i]))?$settings['enable_jump_position_after_image_option_'.$i]:''),  null, 'input', 'margin: -1px 10px 0;');
					echo 'after <b>Image\'s outer &lt;div&gt; wp-caption</b> if any';
				echo '</p>';
			}
		echo '</div>';
		echo '<div class="clear"></div>';
	echo '</div>';
	
	echo '<div class="quick_adsense_block">';
		echo '<div class="quick_adsense_block_labels">';
			echo 'Appearance';
		echo '</div>';
		echo '<div class="quick_adsense_block_controls">';
			echo '<p>';
				echo '<span>'.quickadsense_get_control('checkbox', '<b id="quick_adsense_settings_enable_on_posts_label">Posts</b>', 'quick_adsense_settings_enable_on_posts', 'quick_adsense_settings[enable_on_posts]', ((isset($settings['enable_on_posts']))?$settings['enable_on_posts']:''),  null, 'input', 'margin: -1px 10px 0 0;').'</span>';
				echo '<span>'.quickadsense_get_control('checkbox', '<b id="quick_adsense_settings_enable_on_pages_label">Pages</b>', 'quick_adsense_settings_enable_on_pages', 'quick_adsense_settings[enable_on_pages]', ((isset($settings['enable_on_pages']))?$settings['enable_on_pages']:''),  null, 'input', 'margin: -1px 10px 0 15px;').'</span>';
			echo '</p>';
			echo '<p>';
				echo '<span>'.quickadsense_get_control('checkbox', '<b id="quick_adsense_settings_enable_on_homepage_label">Homepage</b>', 'quick_adsense_settings_enable_on_homepage', 'quick_adsense_settings[enable_on_homepage]', ((isset($settings['enable_on_homepage']))?$settings['enable_on_homepage']:''),  null, 'input', 'margin: -1px 10px 0 0;').'</span>';
				echo '<span>'.quickadsense_get_control('checkbox', '<b id="quick_adsense_settings_enable_on_categories_label">Categories</b>', 'quick_adsense_settings_enable_on_categories', 'quick_adsense_settings[enable_on_categories]', ((isset($settings['enable_on_categories']))?$settings['enable_on_categories']:''),  null, 'input', 'margin: -1px 10px 0 15px;').'</span>';
				echo '<span>'.quickadsense_get_control('checkbox', '<b id="quick_adsense_settings_enable_on_archives_label">Archives</b>', 'quick_adsense_settings_enable_on_archives', 'quick_adsense_settings[enable_on_archives]', ((isset($settings['enable_on_archives']))?$settings['enable_on_archives']:''),  null, 'input', 'margin: -1px 10px 0 15px;').'</span>';
				echo '<span>'.quickadsense_get_control('checkbox', '<b id="quick_adsense_settings_enable_on_tags_label">Tags</b>', 'quick_adsense_settings_enable_on_tags', 'quick_adsense_settings[enable_on_tags]', ((isset($settings['enable_on_tags']))?$settings['enable_on_tags']:''),  null, 'input', 'margin: -1px 10px 0 15px;').'</span>';
				echo '<span>'.quickadsense_get_control('checkbox', '<b id="quick_adsense_settings_enable_all_possible_ads_label">Place all possible Ads on these pages</b>', 'quick_adsense_settings_enable_all_possible_ads', 'quick_adsense_settings[enable_all_possible_ads]', ((isset($settings['enable_all_possible_ads']))?$settings['enable_all_possible_ads']:''),  null, 'input', 'margin: -1px 10px 0 35px;').'</span>';
			echo '</p>';
			echo '<p>';
				echo '<span>'.quickadsense_get_control('checkbox', '<b id="quick_adsense_settings_disable_widgets_on_homepage_label">Disable AdsWidget on Homepage</b>', 'quick_adsense_settings_disable_widgets_on_homepage', 'quick_adsense_settings[disable_widgets_on_homepage]', ((isset($settings['disable_widgets_on_homepage']))?$settings['disable_widgets_on_homepage']:''),  null, 'input', 'margin: -1px 10px 0 0;').'</span>';
			echo '</p>';
			echo '<p>';
				echo '<span>'.quickadsense_get_control('checkbox', '<b id="quick_adsense_settings_disable_for_loggedin_users_label">Hide Ads when user is logged in to Wordpress</b>', 'quick_adsense_settings_disable_for_loggedin_users', 'quick_adsense_settings[disable_for_loggedin_users]', ((isset($settings['disable_for_loggedin_users']))?$settings['disable_for_loggedin_users']:''),  null, 'input', 'margin: -1px 10px 0 0;').'</span>';
			echo '</p>';
		echo '</div>';
		echo '<div class="clear"></div>';
	echo '</div>';
	
	echo '<div class="quick_adsense_block">';
		echo '<div class="quick_adsense_block_labels">';
			echo 'Quicktag';
		echo '</div>';
		echo '<div class="quick_adsense_block_controls">';
			echo '<p>';
				echo quickadsense_get_control('checkbox', '<b>Show Quicktag Buttons on the HTML Edit Post SubPanel</b>', 'quick_adsense_settings_enable_quicktag_buttons', 'quick_adsense_settings[enable_quicktag_buttons]', ((isset($settings['enable_quicktag_buttons']))?$settings['enable_quicktag_buttons']:''),  null, 'input', 'margin: -1px 10px 0 0;');
			echo '</p>';
			echo '<p>';
				echo quickadsense_get_control('checkbox', 'Hide <b>&lt;!--RndAds--&gt;</b> from Quicktag Buttons', 'quick_adsense_settings_disable_randomads_quicktag_button', 'quick_adsense_settings[disable_randomads_quicktag_button]', ((isset($settings['disable_randomads_quicktag_button']))?$settings['disable_randomads_quicktag_button']:''),  null, 'input', 'margin: -1px 10px 0 0;');
			echo '</p>';
			echo '<p>';
				echo quickadsense_get_control('checkbox', 'Hide <b>&lt;!--NoAds--&gt;</b>, <b>&lt;!--OffDef--&gt;</b>, <b>&lt;!--OffWidget--&gt;</b> from Quicktag Buttons', 'quick_adsense_settings_disable_disablead_quicktag_buttons', 'quick_adsense_settings[disable_disablead_quicktag_buttons]', ((isset($settings['disable_disablead_quicktag_buttons']))?$settings['disable_disablead_quicktag_buttons']:''),  null, 'input', 'margin: -1px 10px 0 0;');
			echo '</p>';
			echo '<p>';
				echo quickadsense_get_control('checkbox', 'Hide <b>&lt;!--OffBegin--&gt;</b>, <b>&lt;!--OffMiddle--&gt;</b>, <b>&lt;!--OffEnd--&gt;</b>, <b>&lt;!--OffAfMore--&gt;</b>, <b>&lt;!--OffBfLastPara--&gt;</b> from Quicktag Buttons', 'quick_adsense_settings_disable_positionad_quicktag_buttons', 'quick_adsense_settings[disable_positionad_quicktag_buttons]', ((isset($settings['disable_positionad_quicktag_buttons']))?$settings['disable_positionad_quicktag_buttons']:''),  null, 'input', 'margin: -1px 10px 0 0;');
			echo '</p>';
			echo '<div class="clear"></div>';
			echo 'Insert Ads into a post, on-the-fly:';
			echo '<ol>';
				echo '<li>Insert <b>&lt;!--Ads1--&gt;</b>, <b>&lt;!--Ads2--&gt;</b> etc. into a post to show the <b>Particular Ads</b> at specific location.</li>';
				echo '<li>Insert <b>&lt;!--RndAds--&gt;</b> (or more) into a post to show the <b>Random Ads</b> at specific location.</li>';
			echo '</ol>';
			echo '<div class="clear"></div>';
			echo 'Disable Ads in a post, on-the-fly:';
			echo '<ol>';
				echo '<li>Insert <b>&lt;!--NoAds--&gt;</b> to disable all Ads in a post <i>(does not affect Ads on Sidebar)</i>.</li>';
				echo '<li>Insert <b>&lt;!--OffDef--&gt;</b> to disable the default positioned Ads, and use &lt;!--Ads1--&gt;, &lt;!--Ads2--&gt;, etc. to insert Ad <i>(does not affect Ads on Sidebar)</i>.</li>';
				echo '<li>Insert <b>&lt;!--OffWidget--&gt;</b> to disable all Ads on Sidebar.</li>';
				echo '<li>Insert <b>&lt;!--OffBegin--&gt;</b>, <b>&lt;!--OffMiddle--&gt;</b>, <b>&lt;!--OffEnd--&gt;</b> to <b>disable Ads at Beginning</b>, <b>Middle or End of Post</b>.</li>';
				echo '<li>Insert <b>&lt;!--OffAfMore--&gt;</b>, <b>&lt;!--OffBfLastPara--&gt;</b> to <b>disable Ads right after the &lt;!--more--&gt; tag</b>, or <b>right before the last Paragraph</b>.</li>';
			echo '</ol>';
			echo '<div class="clear"></div>';
			echo '<i>Tags can be inserted into a post via the additional Quicktag Buttons at the HTML Edit Post SubPanel.</i>';
		echo '</div>';
		echo '<div class="clear"></div>';
	echo '</div>';
}

function quick_adsense_onpost_content() {
	$settings = get_option('quick_adsense_settings');
	$alignmentOptions = array(		
		array('text' => 'Left', 'value' => '1'),
		array('text' => 'Center', 'value' => '2'),
		array('text' => 'Right', 'value' => '3'),
		array('text' => 'None', 'value' => '4')
	);
	$marginOptions = array();
	for($i = 1; $i <= 50; $i++) {
		$marginOptions[] = array('text' => $i, 'value' => $i);
	}
	echo '<div id="quick_adsense_block_bottom" class="quick_adsense_block" style="margin: 30px 0 0;">';
		echo '<div class="quick_adsense_block_labels" style="width: auto;">';
			echo '<span>Adsense Codes - Ads on Post Body</span>';
		echo '</div>';
		echo '<div class="clear"></div>';
		echo '<p>Paste up to 10 Ads codes on Post Body as assigned above. Ads codes provided must not be identical, repeated codes may result the Ads not being display correctly. Ads will never displays more than once in a page.</p>';
	echo '</div>';
	
	echo '<div id="quick_adsense_onpost_content_controls_wrapper">';
		echo '<div id="quick_adsense_onpost_content_global_controls_wrapper">';			
			echo '<p class="quick_adsense_onpost_adunits_styling_controls">';
				echo quickadsense_get_control('checkbox', '', 'quick_adsense_settings_onpost_enable_global_style', 'quick_adsense_settings[onpost_enable_global_style]', ((isset($settings['onpost_enable_global_style']))?$settings['onpost_enable_global_style']:''),  null, 'input', 'margin: -3px 10px 0 0;');
				echo '<span>Use for all</span>';
				echo '<wbr />Alignment';
				echo quickadsense_get_control('select', '', 'quick_adsense_settings_onpost_global_alignment', 'quick_adsense_settings[onpost_global_alignment]', ((isset($settings['onpost_global_alignment']))?$settings['onpost_global_alignment']:''),  $alignmentOptions, 'input', 'margin: -6px 20px 0 10px; width: 73px;');
				echo '<wbr />margin';
				echo quickadsense_get_control('number', '', 'quick_adsense_settings_onpost_global_margin', 'quick_adsense_settings[onpost_global_margin]', ((isset($settings['onpost_global_margin']))?$settings['onpost_global_margin']:''),  $marginOptions, 'input', 'margin: -1px 10px 0 10px; width: 62px;');
				echo 'px';
			echo '</p>';
		echo '</div>';

		echo '<div id="quick_adsense_onpost_content_adunits_wrapper">';
			echo '<div id="quick_adsense_onpost_content_adunits_initial_wrapper">';	
				for($i = 1; $i <= 3; $i++) {
					quick_adsense_onpost_adunits_controls($i, $settings, $alignmentOptions, $marginOptions);
				}
			echo '</div>';
			echo '<div id="quick_adsense_onpost_content_adunits_all_wrapper" style="display: none;">';	
				for($i = 4; $i <= 10; $i++) {
					quick_adsense_onpost_adunits_controls($i, $settings, $alignmentOptions, $marginOptions);
				}
			echo '</div>';
			echo '<a id="quick_adsense_onpost_content_adunits_showall_button" class="input button-secondary"><span class="dashicons dashicons-arrow-down"></span> <b>Show All</b></a>';
		echo '</div>';
	echo '</div>';
}

function quick_adsense_onpost_adunits_controls($index, $settings, $alignmentOptions, $marginOptions) {
	echo '<div id="quick_adsense_onpost_adunits_control_'.$index.'" class="quick_adsense_onpost_adunits_control_wrapper">';
		echo '<div class="quick_adsense_onpost_adunits_label">Ads'.$index.'</div>';
		echo '<div class="quick_adsense_onpost_adunits_control">';
			echo quickadsense_get_control('textarea', '', 'quick_adsense_settings_onpost_ad_'.$index.'_content', 'quick_adsense_settings[onpost_ad_'.$index.'_content]', ((isset($settings['onpost_ad_'.$index.'_content']))?$settings['onpost_ad_'.$index.'_content']:''),  null, 'input', 'display: block; margin: 0 0 10px 0', 'Enter Code');
			echo '<p class="quick_adsense_onpost_adunits_styling_controls">';
				echo 'Alignment';
				echo quickadsense_get_control('select', '', 'quick_adsense_settings_onpost_ad_'.$index.'_alignment', 'quick_adsense_settings[onpost_ad_'.$index.'_alignment]', ((isset($settings['onpost_ad_'.$index.'_alignment']))?$settings['onpost_ad_'.$index.'_alignment']:''),  $alignmentOptions, 'input', 'margin: -2px 20px 0 10px;');
				echo '<wbr />margin';
				echo quickadsense_get_control('number', '', 'quick_adsense_settings_onpost_ad_'.$index.'_margin', 'quick_adsense_settings[onpost_ad_'.$index.'_margin]', ((isset($settings['onpost_ad_'.$index.'_margin']))?$settings['onpost_ad_'.$index.'_margin']:''),  $marginOptions, 'input', 'margin: -2px 10px 0 10px; width: 52px;');
				echo 'px';
			echo '</p>';
			quick_adsense_advanced_controls($index, $settings, 'onpost');
		echo '</div>';
		echo '<div class="clear"></div>';
	echo '</div>';
}

function quick_adsense_widgets_content() {
	$settings = get_option('quick_adsense_settings');
	$alignmentOptions = array(		
		array('text' => 'Left', 'value' => '1'),
		array('text' => 'Center', 'value' => '2'),
		array('text' => 'Right', 'value' => '3'),
		array('text' => 'None', 'value' => '4')
	);
	$marginOptions = array();
	for($i = 1; $i <= 50; $i++) {
		$marginOptions[] = array('text' => $i, 'value' => $i);
	}
	echo '<div id="quick_adsense_block_bottom" class="quick_adsense_block" style="margin: 30px 0 0;">';
		echo '<div class="quick_adsense_block_labels" style="width: auto;">';
			echo '<span>Adsense Codes - <a href="'.admin_url('widgets.php').'">Sidebar WIdget</a></span>';
		echo '</div>';
		echo '<div class="clear"></div>';
		echo '<p>Paste up to 10 Ads codes on Sidebar Widget. Ads codes provided must not be identical, repeated codes may result the Ads not being display correctly. Ads will never displays more than once in a page.</p>';
	echo '</div>';
	
	echo '<div id="quick_adsense_widget_controls_wrapper">';
		echo '<div id="quick_adsense_widget_global_controls_wrapper" style="visibility: hidden;">';			
			echo '<p class="quick_adsense_widget_adunits_styling_controls">';
				echo quickadsense_get_control('checkbox', '', 'quick_adsense_settings_widget_enable_global_style', 'quick_adsense_settings[widget_enable_global_style]', ((isset($settings['widget_enable_global_style']))?$settings['widget_enable_global_style']:''),  null, 'input', 'margin: -3px 10px 0 0;');
				echo '<span>Use for all</span>';
				echo '<wbr />Alignment';
				echo quickadsense_get_control('select', '', 'quick_adsense_settings_widget_global_alignment', 'quick_adsense_settings[widget_global_alignment]', ((isset($settings['widget_global_alignment']))?$settings['widget_global_alignment']:''),  $alignmentOptions, 'input', 'margin: -6px 20px 0 10px; width: 73px;');
				echo '<wbr />margin';
				echo quickadsense_get_control('number', '', 'quick_adsense_settings_widget_global_margin', 'quick_adsense_settings[widget_global_margin]', ((isset($settings['widget_global_margin']))?$settings['widget_global_margin']:''),  $marginOptions, 'input', 'margin: -1px 10px 0 10px; width: 62px;');
				echo 'px';
			echo '</p>';
		echo '</div>';
		
		echo '<div id="quick_adsense_widget_adunits_wrapper">';			
			echo '<div id="quick_adsense_widget_adunits_initial_wrapper">';	
			for($i = 1; $i <= 3; $i++) {
				quick_adsense_widgets_controls($i, $settings);
			}
			echo '</div>';
			echo '<div id="quick_adsense_widget_adunits_all_wrapper" style="display: none;">';	
				for($i = 4; $i <= 10; $i++) {
					quick_adsense_widgets_controls($i, $settings);
				}
			echo '</div>';
			echo '<a id="quick_adsense_widget_adunits_showall_button" class="input button-secondary"><span class="dashicons dashicons-arrow-down"></span> <b>Show All</b></a>';
		echo '</div>';
	echo '</div>';
}

function quick_adsense_widgets_controls($index, $settings) {
	echo '<div id="quick_adsense_widget_adunits_control_'.$index.'" class="quick_adsense_widget_adunits_control_wrapper">';
		echo '<div class="quick_adsense_widget_adunits_label">AdsWidget'.$index.'</div>';
		echo '<div class="quick_adsense_widget_adunits_control">';
			echo quickadsense_get_control('textarea', '', 'quick_adsense_settings_widget_ad_'.$index.'_content', 'quick_adsense_settings[widget_ad_'.$index.'_content]', ((isset($settings['widget_ad_'.$index.'_content']))?$settings['widget_ad_'.$index.'_content']:''),  null, 'input', 'display: block; margin: 0 0 10px 0', 'Enter Code');
		echo '</div>';
		echo '<div class="clear"></div>';
	echo '</div>';
}

function quick_adsense_advanced_controls($index, $settings, $location) {
	echo '<p class="quick_adsense_'.$location.'_adunits_device_controls">';
		echo '<b>Hide by Device Type:</b><br />';
		echo quickadsense_get_control('checkbox', 'Mobile', 'quick_adsense_settings_'.$location.'_ad_'.$index.'_hide_device_mobile', 'quick_adsense_settings['.$location.'_ad_'.$index.'_hide_device_mobile]', ((isset($settings[$location.'_ad_'.$index.'_hide_device_mobile']))?$settings[$location.'_ad_'.$index.'_hide_device_mobile']:''),  null, 'input', 'margin: -1px 5px 0 0;');
		echo quickadsense_get_control('checkbox', 'Tablet', 'quick_adsense_settings_'.$location.'_ad_'.$index.'_hide_device_tablet', 'quick_adsense_settings['.$location.'_ad_'.$index.'_hide_device_tablet]', ((isset($settings[$location.'_ad_'.$index.'_hide_device_tablet']))?$settings[$location.'_ad_'.$index.'_hide_device_tablet']:''),  null, 'input', 'margin: -1px 5px 0 15px;');
		echo quickadsense_get_control('checkbox', 'Desktop', 'quick_adsense_settings_'.$location.'_ad_'.$index.'_hide_device_desktop', 'quick_adsense_settings['.$location.'_ad_'.$index.'_hide_device_desktop]', ((isset($settings[$location.'_ad_'.$index.'_hide_device_desktop']))?$settings[$location.'_ad_'.$index.'_hide_device_desktop']:''),  null, 'input', 'margin: -1px 5px 0 15px;');
	echo '</p>';
	echo '<p class="quick_adsense_'.$location.'_adunits_device_controls">';
		echo '<b>Hide by Visitor Source:</b><br />';
		echo quickadsense_get_control('checkbox', 'Search Engine', 'quick_adsense_settings_'.$location.'_ad_'.$index.'_hide_visitor_searchengine', 'quick_adsense_settings['.$location.'_ad_'.$index.'_hide_visitor_searchengine]', ((isset($settings[$location.'_ad_'.$index.'_hide_visitor_searchengine']))?$settings[$location.'_ad_'.$index.'_hide_visitor_searchengine']:''),  null, 'input', 'margin: -1px 5px 0 0;');
		echo quickadsense_get_control('checkbox', 'Indirect', 'quick_adsense_settings_'.$location.'_ad_'.$index.'_hide_visitor_indirect', 'quick_adsense_settings['.$location.'_ad_'.$index.'_hide_visitor_indirect]', ((isset($settings[$location.'_ad_'.$index.'_hide_visitor_indirect']))?$settings[$location.'_ad_'.$index.'_hide_visitor_indirect']:''),  null, 'input', 'margin: -1px 5px 0 15px;');
		echo quickadsense_get_control('checkbox', 'Direct', 'quick_adsense_settings_'.$location.'_ad_'.$index.'_hide_visitor_direct', 'quick_adsense_settings['.$location.'_ad_'.$index.'_hide_visitor_direct]', ((isset($settings[$location.'_ad_'.$index.'_hide_visitor_direct']))?$settings[$location.'_ad_'.$index.'_hide_visitor_direct']:''),  null, 'input', 'margin: -1px 5px 0 15px;');
	echo '</p>';
	echo '<p class="quick_adsense_'.$location.'_adunits_device_controls">';
		echo '<b>Hide by Visitor Type:</b><br />';
		echo quickadsense_get_control('checkbox', 'Bots', 'quick_adsense_settings_'.$location.'_ad_'.$index.'_hide_visitor_bot', 'quick_adsense_settings['.$location.'_ad_'.$index.'_hide_visitor_bot]', ((isset($settings[$location.'_ad_'.$index.'_hide_visitor_bot']))?$settings[$location.'_ad_'.$index.'_hide_visitor_bot']:''),  null, 'input', 'margin: -1px 5px 0 0;');
		echo quickadsense_get_control('checkbox', 'Known Browser', 'quick_adsense_settings_'.$location.'_ad_'.$index.'_hide_visitor_knownbrowser', 'quick_adsense_settings['.$location.'_ad_'.$index.'_hide_visitor_knownbrowser]', ((isset($settings[$location.'_ad_'.$index.'_hide_visitor_knownbrowser']))?$settings[$location.'_ad_'.$index.'_hide_visitor_knownbrowser']:''),  null, 'input', 'margin: -1px 5px 0 15px;');
		echo quickadsense_get_control('checkbox', 'Unknown Browser', 'quick_adsense_settings_'.$location.'_ad_'.$index.'_hide_visitor_unknownbrowser', 'quick_adsense_settings['.$location.'_ad_'.$index.'_hide_visitor_unknownbrowser]', ((isset($settings[$location.'_ad_'.$index.'_hide_visitor_unknownbrowser']))?$settings[$location.'_ad_'.$index.'_hide_visitor_unknownbrowser']:''),  null, 'input', 'margin: -1px 5px 0 15px;');
		echo '<br/ >';
		echo quickadsense_get_control('checkbox', 'Guest', 'quick_adsense_settings_'.$location.'_ad_'.$index.'_hide_visitor_guest', 'quick_adsense_settings['.$location.'_ad_'.$index.'_hide_visitor_guest]', ((isset($settings[$location.'_ad_'.$index.'_hide_visitor_guest']))?$settings[$location.'_ad_'.$index.'_hide_visitor_guest']:''),  null, 'input', 'margin: -1px 5px 0 0;');
		echo quickadsense_get_control('checkbox', 'Logged-in', 'quick_adsense_settings_'.$location.'_ad_'.$index.'_hide_visitor_loggedin', 'quick_adsense_settings['.$location.'_ad_'.$index.'_hide_visitor_loggedin]', ((isset($settings[$location.'_ad_'.$index.'_hide_visitor_loggedin']))?$settings[$location.'_ad_'.$index.'_hide_visitor_loggedin']:''),  null, 'input', 'margin: -1px 5px 0 15px;');				
	echo '</p>';
	echo '<p class="quick_adsense_'.$location.'_adunits_device_controls">';
		echo '<b>Limit by Visitor Countries:</b><br />';
		echo quickadsense_get_control('multiselect', '', 'quick_adsense_settings_'.$location.'_ad_'.$index.'_limit_visitor_country', 'quick_adsense_settings['.$location.'_ad_'.$index.'_limit_visitor_country][]', ((isset($settings[$location.'_ad_'.$index.'_limit_visitor_country']))?$settings[$location.'_ad_'.$index.'_limit_visitor_country']:''),  quick_adsense_get_countries());
	echo '</p>';
		echo '<p class="quick_adsense_'.$location.'_adunits_device_controls">';
			echo '<b>Ad Stats:</b><br />';
			echo quickadsense_get_control('checkbox', 'Enable Stats', 'quick_adsense_settings_'.$location.'_ad_'.$index.'_enable_stats', 'quick_adsense_settings['.$location.'_ad_'.$index.'_enable_stats]', ((isset($settings[$location.'_ad_'.$index.'_enable_stats']))?$settings[$location.'_ad_'.$index.'_enable_stats']:''),  null, 'input', 'margin: -1px 5px 0 0;');
			echo '<br /><input class="quick_adsense_'.$location.'_ad_show_stats input button-secondary" data-index="'.$index.'" type="button" value="View Stats" />&nbsp;<input class="quick_adsense_'.$location.'_ad_reset_stats input button-secondary right" data-index="'.$index.'" type="button" value="Reset Stats" />';
		echo '</p>';
}

function quick_adsense_validate($input) {
	delete_transient('quick_adsense_adstxt_adsense_autocheck_content');
	return $input;
}

add_action('wp_ajax_quick_adsense_onpost_ad_reset_stats', 'quick_adsense_onpost_ad_reset_stats');
function quick_adsense_onpost_ad_reset_stats() {
	if(isset($_POST['index'])) {
		delete_option('quick_adsense_onpost_ad_'.$_POST['index'].'_stats');
		echo '###SUCCESS###';
	}
	die();
}

add_action('wp_ajax_quick_adsense_onpost_ad_get_stats_chart', 'quick_adsense_onpost_ad_get_stats_chart');
function quick_adsense_onpost_ad_get_stats_chart() {
	if(isset($_POST['index'])) {
		echo '###SUCCESS###';
		echo '<div id="quick_adsense_ad_stats_chart_wrapper">';
			echo '<p>&nbsp;</p>';
			echo '<canvas id="quick_adsense_ad_stats_chart" width="1377" height="360"></canvas>';	
			echo '<textarea id="quick_adsense_ad_stats_chart_data" style="display: none;">[';
			$stats = get_option('quick_adsense_onpost_ad_'.$_POST['index'].'_stats');
			for($i = 0; $i < 30; $i++) {
				$clicks = 0;
				$impressions = 0;
				if(isset($stats) && is_array($stats) && isset($stats[date('dmY', strtotime('-'.$i.' day'))])) {
					$clicks = $stats[date('dmY', strtotime('-'.$i.' day'))]['c'];
					$impressions = $stats[date('dmY', strtotime('-'.$i.' day'))]['i'];
				}
				if($i != 0) {
					echo ',';
				}
				echo '{"x": "'.date('m/d/Y', strtotime('-'.$i.' day')).'", "y": "'.$impressions.'", "y1": "'.$clicks.'"}';
			}
			echo ']</textarea>';
			echo '<p><small>The Stats recorded by the plugin will be different than that recorded by your ad provider due to different factors like accidental clicks, fraud detection, spam clicks etc.<br />Use the stats provided by the ad provider in case of discrepancy.</small></p>';
		echo '</div>';
	}
	die();
}

add_action('admin_enqueue_scripts', function($hook) {
		if($hook != 'toplevel_page_quick-adsense') {
            return;
        }
		if(isset($_GET['deactivate'])) {
			delete_option('quick_adsense_validate');
		}
		wp_enqueue_script('quick_adsense_admin_js', plugins_url('/js/admin.js', __FILE__), array('jquery', 'jquery-ui-core', 'jquery-ui-tabs'));
});

function quick_adsense_get_countries() {
	$data = array(
		array('value' => 'AD', 'text' => 'Andorra'),
		array('value' => 'AE', 'text' => 'United Arab Emirates'),
		array('value' => 'AF', 'text' => 'Afghanistan'),
		array('value' => 'AG', 'text' => 'Antigua and Barbuda'),
		array('value' => 'AI', 'text' => 'Anguilla'),
		array('value' => 'AL', 'text' => 'Albania'),
		array('value' => 'AM', 'text' => 'Armenia'),
		array('value' => 'AN', 'text' => 'Netherlands Antilles'),
		array('value' => 'AO', 'text' => 'Angola'),
		array('value' => 'AQ', 'text' => 'Antarctica'),
		array('value' => 'AR', 'text' => 'Argentina'),
		array('value' => 'AS', 'text' => 'American Samoa'),
		array('value' => 'AT', 'text' => 'Austria'),
		array('value' => 'AU', 'text' => 'Australia'),
		array('value' => 'AW', 'text' => 'Aruba'),
		array('value' => 'AZ', 'text' => 'Azerbaijan'),
		array('value' => 'BA', 'text' => 'Bosnia and Herzegovina'),
		array('value' => 'BB', 'text' => 'Barbados'),
		array('value' => 'BD', 'text' => 'Bangladesh'),
		array('value' => 'BE', 'text' => 'Belgium'),
		array('value' => 'BF', 'text' => 'Burkina Faso'),
		array('value' => 'BG', 'text' => 'Bulgaria'),
		array('value' => 'BH', 'text' => 'Bahrain'),
		array('value' => 'BI', 'text' => 'Burundi'),
		array('value' => 'BJ', 'text' => 'Benin'),
		array('value' => 'BM', 'text' => 'Bermuda'),
		array('value' => 'BN', 'text' => 'Brunei Darussalam'),
		array('value' => 'BO', 'text' => 'Bolivia'),
		array('value' => 'BR', 'text' => 'Brazil'),
		array('value' => 'BS', 'text' => 'Bahamas'),
		array('value' => 'BT', 'text' => 'Bhutan'),
		array('value' => 'BV', 'text' => 'Bouvet Island'),
		array('value' => 'BW', 'text' => 'Botswana'),
		array('value' => 'BY', 'text' => 'Belarus'),
		array('value' => 'BZ', 'text' => 'Belize'),
		array('value' => 'CA', 'text' => 'Canada'),
		array('value' => 'CC', 'text' => 'Cocos (Keeling) Islands'),
		array('value' => 'CD', 'text' => 'The Democratic Republic of the Congo'),
		array('value' => 'CF', 'text' => 'Central African Republic'),
		array('value' => 'CG', 'text' => 'Congo'),
		array('value' => 'CH', 'text' => 'Switzerland'),
		array('value' => 'CI', 'text' => 'Cote D\'Ivoire'),
		array('value' => 'CK', 'text' => 'Cook Islands'),
		array('value' => 'CL', 'text' => 'Chile'),
		array('value' => 'CM', 'text' => 'Cameroon'),
		array('value' => 'CN', 'text' => 'China'),
		array('value' => 'CO', 'text' => 'Colombia'),
		array('value' => 'CR', 'text' => 'Costa Rica'),
		array('value' => 'CU', 'text' => 'Cuba'),
		array('value' => 'CV', 'text' => 'Cape Verde'),
		array('value' => 'CX', 'text' => 'Christmas Island'),
		array('value' => 'CY', 'text' => 'Cyprus'),
		array('value' => 'CZ', 'text' => 'Czech Republic'),
		array('value' => 'DE', 'text' => 'Germany'),
		array('value' => 'DJ', 'text' => 'Djibouti'),
		array('value' => 'DK', 'text' => 'Denmark'),
		array('value' => 'DM', 'text' => 'Dominica'),
		array('value' => 'DO', 'text' => 'Dominican Republic'),
		array('value' => 'DZ', 'text' => 'Algeria'),
		array('value' => 'EC', 'text' => 'Ecuador'),
		array('value' => 'EE', 'text' => 'Estonia'),
		array('value' => 'EG', 'text' => 'Egypt'),
		array('value' => 'EH', 'text' => 'Western Sahara'),
		array('value' => 'ER', 'text' => 'Eritrea'),
		array('value' => 'ES', 'text' => 'Spain'),
		array('value' => 'ET', 'text' => 'Ethiopia'),
		array('value' => 'FI', 'text' => 'Finland'),
		array('value' => 'FJ', 'text' => 'Fiji'),
		array('value' => 'FK', 'text' => 'Falkland Islands (Malvinas)'),
		array('value' => 'FM', 'text' => 'Federated States of Micronesia'),
		array('value' => 'FO', 'text' => 'Faroe Islands'),
		array('value' => 'FR', 'text' => 'France'),
		array('value' => 'FX', 'text' => 'France Metropolitan'),
		array('value' => 'GA', 'text' => 'Gabon'),
		array('value' => 'GB', 'text' => 'United Kingdom'),
		array('value' => 'GD', 'text' => 'Grenada'),
		array('value' => 'GE', 'text' => 'Georgia'),
		array('value' => 'GF', 'text' => 'French Guiana'),
		array('value' => 'GH', 'text' => 'Ghana'),
		array('value' => 'GI', 'text' => 'Gibraltar'),
		array('value' => 'GL', 'text' => 'Greenland'),
		array('value' => 'GM', 'text' => 'Gambia'),
		array('value' => 'GN', 'text' => 'Guinea'),
		array('value' => 'GP', 'text' => 'Guadeloupe'),
		array('value' => 'GQ', 'text' => 'Equatorial Guinea'),
		array('value' => 'GR', 'text' => 'Greece'),
		array('value' => 'GS', 'text' => 'South Georgia and the South Sandwich Islands'),
		array('value' => 'GT', 'text' => 'Guatemala'),
		array('value' => 'GU', 'text' => 'Guam'),
		array('value' => 'GW', 'text' => 'Guinea-Bissau'),
		array('value' => 'GY', 'text' => 'Guyana'),
		array('value' => 'HK', 'text' => 'Hong Kong'),
		array('value' => 'HM', 'text' => 'Heard Island and McDonald Islands'),
		array('value' => 'HN', 'text' => 'Honduras'),
		array('value' => 'HR', 'text' => 'Croatia'),
		array('value' => 'HT', 'text' => 'Haiti'),
		array('value' => 'HU', 'text' => 'Hungary'),
		array('value' => 'ID', 'text' => 'Indonesia'),
		array('value' => 'IE', 'text' => 'Ireland'),
		array('value' => 'IL', 'text' => 'Israel'),
		array('value' => 'IN', 'text' => 'India'),
		array('value' => 'IO', 'text' => 'British Indian Ocean Territory'),
		array('value' => 'IQ', 'text' => 'Iraq'),
		array('value' => 'IR', 'text' => 'Islamic Republic of Iran'),
		array('value' => 'IS', 'text' => 'Iceland'),
		array('value' => 'IT', 'text' => 'Italy'),
		array('value' => 'JM', 'text' => 'Jamaica'),
		array('value' => 'JO', 'text' => 'Jordan'),
		array('value' => 'JP', 'text' => 'Japan'),
		array('value' => 'KE', 'text' => 'Kenya'),
		array('value' => 'KG', 'text' => 'Kyrgyzstan'),
		array('value' => 'KH', 'text' => 'Cambodia'),
		array('value' => 'KI', 'text' => 'Kiribati'),
		array('value' => 'KM', 'text' => 'Comoros'),
		array('value' => 'KN', 'text' => 'Saint Kitts and Nevis'),
		array('value' => 'KP', 'text' => 'Democratic People\'s Republic of Korea'),
		array('value' => 'KR', 'text' => 'Republic of Korea'),
		array('value' => 'KW', 'text' => 'Kuwait'),
		array('value' => 'KY', 'text' => 'Cayman Islands'),
		array('value' => 'KZ', 'text' => 'Kazakhstan'),
		array('value' => 'LA', 'text' => 'Lao People\'s Democratic Republic'),
		array('value' => 'LB', 'text' => 'Lebanon'),
		array('value' => 'LC', 'text' => 'Saint Lucia'),
		array('value' => 'LI', 'text' => 'Liechtenstein'),
		array('value' => 'LK', 'text' => 'Sri Lanka'),
		array('value' => 'LR', 'text' => 'Liberia'),
		array('value' => 'LS', 'text' => 'Lesotho'),
		array('value' => 'LT', 'text' => 'Lithuania'),
		array('value' => 'LU', 'text' => 'Luxembourg'),
		array('value' => 'LV', 'text' => 'Latvia'),
		array('value' => 'LY', 'text' => 'Libyan Arab Jamahiriya'),
		array('value' => 'MA', 'text' => 'Morocco'),
		array('value' => 'MC', 'text' => 'Monaco'),
		array('value' => 'MD', 'text' => 'Republic of Moldova'),
		array('value' => 'MG', 'text' => 'Madagascar'),
		array('value' => 'MH', 'text' => 'Marshall Islands'),
		array('value' => 'MK', 'text' => 'Macedonia'),
		array('value' => 'ML', 'text' => 'Mali'),
		array('value' => 'MM', 'text' => 'Myanmar'),
		array('value' => 'MN', 'text' => 'Mongolia'),
		array('value' => 'MO', 'text' => 'Macau'),
		array('value' => 'MP', 'text' => 'Northern Mariana Islands'),
		array('value' => 'MQ', 'text' => 'Martinique'),
		array('value' => 'MR', 'text' => 'Mauritania'),
		array('value' => 'MS', 'text' => 'Montserrat'),
		array('value' => 'MT', 'text' => 'Malta'),
		array('value' => 'MU', 'text' => 'Mauritius'),
		array('value' => 'MV', 'text' => 'Maldives'),
		array('value' => 'MW', 'text' => 'Malawi'),
		array('value' => 'MX', 'text' => 'Mexico'),
		array('value' => 'MY', 'text' => 'Malaysia'),
		array('value' => 'MZ', 'text' => 'Mozambique'),
		array('value' => 'NA', 'text' => 'Namibia'),
		array('value' => 'NC', 'text' => 'New Caledonia'),
		array('value' => 'NE', 'text' => 'Niger'),
		array('value' => 'NF', 'text' => 'Norfolk Island'),
		array('value' => 'NG', 'text' => 'Nigeria'),
		array('value' => 'NI', 'text' => 'Nicaragua'),
		array('value' => 'NL', 'text' => 'Netherlands'),
		array('value' => 'NO', 'text' => 'Norway'),
		array('value' => 'NP', 'text' => 'Nepal'),
		array('value' => 'NR', 'text' => 'Nauru'),
		array('value' => 'NU', 'text' => 'Niue'),
		array('value' => 'NZ', 'text' => 'New Zealand'),
		array('value' => 'OM', 'text' => 'Oman'),
		array('value' => 'PA', 'text' => 'Panama'),
		array('value' => 'PE', 'text' => 'Peru'),
		array('value' => 'PF', 'text' => 'French Polynesia'),
		array('value' => 'PG', 'text' => 'Papua New Guinea'),
		array('value' => 'PH', 'text' => 'Philippines'),
		array('value' => 'PK', 'text' => 'Pakistan'),
		array('value' => 'PL', 'text' => 'Poland'),
		array('value' => 'PM', 'text' => 'Saint Pierre and Miquelon'),
		array('value' => 'PN', 'text' => 'Pitcairn Islands'),
		array('value' => 'PR', 'text' => 'Puerto Rico'),
		array('value' => 'PS', 'text' => 'Palestinian Territory'),
		array('value' => 'PT', 'text' => 'Portugal'),
		array('value' => 'PW', 'text' => 'Palau'),
		array('value' => 'PY', 'text' => 'Paraguay'),
		array('value' => 'QA', 'text' => 'Qatar'),
		array('value' => 'RE', 'text' => 'Reunion'),
		array('value' => 'RO', 'text' => 'Romania'),
		array('value' => 'RU', 'text' => 'Russian Federation'),
		array('value' => 'RW', 'text' => 'Rwanda'),
		array('value' => 'SA', 'text' => 'Saudi Arabia'),
		array('value' => 'SB', 'text' => 'Solomon Islands'),
		array('value' => 'SC', 'text' => 'Seychelles'),
		array('value' => 'SD', 'text' => 'Sudan'),
		array('value' => 'SE', 'text' => 'Sweden'),
		array('value' => 'SG', 'text' => 'Singapore'),
		array('value' => 'SH', 'text' => 'Saint Helena'),
		array('value' => 'SI', 'text' => 'Slovenia'),
		array('value' => 'SJ', 'text' => 'Svalbard and Jan Mayen'),
		array('value' => 'SK', 'text' => 'Slovakia'),
		array('value' => 'SL', 'text' => 'Sierra Leone'),
		array('value' => 'SM', 'text' => 'San Marino'),
		array('value' => 'SN', 'text' => 'Senegal'),
		array('value' => 'SO', 'text' => 'Somalia'),
		array('value' => 'SR', 'text' => 'Suriname'),
		array('value' => 'ST', 'text' => 'Sao Tome and Principe'),
		array('value' => 'SV', 'text' => 'El Salvador'),
		array('value' => 'SY', 'text' => 'Syrian Arab Republic'),
		array('value' => 'SZ', 'text' => 'Swaziland'),
		array('value' => 'TC', 'text' => 'Turks and Caicos Islands'),
		array('value' => 'TD', 'text' => 'Chad'),
		array('value' => 'TF', 'text' => 'French Southern Territories'),
		array('value' => 'TG', 'text' => 'Togo'),
		array('value' => 'TH', 'text' => 'Thailand'),
		array('value' => 'TJ', 'text' => 'Tajikistan'),
		array('value' => 'TK', 'text' => 'Tokelau'),
		array('value' => 'TM', 'text' => 'Turkmenistan'),
		array('value' => 'TN', 'text' => 'Tunisia'),
		array('value' => 'TO', 'text' => 'Tonga'),
		array('value' => 'TL', 'text' => 'Timor-Leste'),
		array('value' => 'TR', 'text' => 'Turkey'),
		array('value' => 'TT', 'text' => 'Trinidad and Tobago'),
		array('value' => 'TV', 'text' => 'Tuvalu'),
		array('value' => 'TW', 'text' => 'Taiwan'),
		array('value' => 'TZ', 'text' => 'United Republic of Tanzania'),
		array('value' => 'UA', 'text' => 'Ukraine'),
		array('value' => 'UG', 'text' => 'Uganda'),
		array('value' => 'UM', 'text' => 'United States Minor Outlying Islands'),
		array('value' => 'US', 'text' => 'United States'),
		array('value' => 'UY', 'text' => 'Uruguay'),
		array('value' => 'UZ', 'text' => 'Uzbekistan'),
		array('value' => 'VA', 'text' => 'Holy See (Vatican City State)'),
		array('value' => 'VC', 'text' => 'Saint Vincent and the Grenadines'),
		array('value' => 'VE', 'text' => 'Venezuela'),
		array('value' => 'VG', 'text' => 'British Virgin Islands'),
		array('value' => 'VI', 'text' => 'U.S. Virgin Islands'),
		array('value' => 'VN', 'text' => 'Vietnam'),
		array('value' => 'VU', 'text' => 'Vanuatu'),
		array('value' => 'WF', 'text' => 'Wallis and Futuna'),
		array('value' => 'WS', 'text' => 'Samoa'),
		array('value' => 'YE', 'text' => 'Yemen'),
		array('value' => 'YT', 'text' => 'Mayotte'),
		array('value' => 'RS', 'text' => 'Serbia'),
		array('value' => 'ZA', 'text' => 'South Africa'),
		array('value' => 'ZM', 'text' => 'Zambia'),
		array('value' => 'ME', 'text' => 'Montenegro'),
		array('value' => 'ZW', 'text' => 'Zimbabwe'),
		array('value' => 'AX', 'text' => 'Aland Islands'),
		array('value' => 'GG', 'text' => 'Guernsey'),
		array('value' => 'IM', 'text' => 'Isle of Man'),
		array('value' => 'JE', 'text' => 'Jersey'),
		array('value' => 'BL', 'text' => 'Saint Barthelemy'),
		array('value' => 'MF', 'text' => 'Saint Martin')
	);
	array_multisort(array_column($data, 'text'), SORT_ASC, $data);
	return $data;
}
?>
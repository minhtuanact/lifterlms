<?php

function SPDSGVOExplicitPermissionShortcode($atts){

	$content  = '<div style="display:block; width:100%;">';
	$content .= 	'<a class="sp-dsgvo-btn" href="'. SPDSGVOExplicitPermissionAction::url(['permission' => 'granted']) .'">'.__('Accepted','shapepress-dsgvo').'</a>';
	$content .= 	'<a class="sp-dsgvo-btn sp-dsgvo-btn-red" href="'. SPDSGVOExplicitPermissionAction::url(['permission' => 'declined']) .'">'.__('Declined','shapepress-dsgvo').'</a>';
	$content .= '<div/>';

	$content .= '<br/>';
	$content .= '<br/>';
	$content .= '<br/>';
	$content .= '<br/>';

	$content .= '<div style="display:block; width:100%;">';
	$content .= 	apply_filters('the_content', SPDSGVOSettings::get('terms_conditions'));
	$content .= '<div/>';
	
	return $content;
}

add_shortcode('explicit_permission', 'SPDSGVOExplicitPermissionShortcode');


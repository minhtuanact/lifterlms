<?php

function SPDSGVOPrivacyPolicyLinkShortcode($atts)
{

    $params = shortcode_atts(array(
        'class' => '',
        'text' => SPDSGVOSettings::get('privacy_policy_custom_header'),
    ), $atts);


    return '<a href="#" class="sp-dsgvo-navigate-privacy-policy ' . $params['class'] . '">' . $params['text'] . "</a>";
}

add_shortcode('pp_link', 'SPDSGVOPrivacyPolicyLinkShortcode');




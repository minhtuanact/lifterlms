<?php
/*
 *
 * Social Icon
 */
function fiona_blog_get_social_icon_default() {
	return apply_filters(
		'fiona_blog_get_social_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'fiona' ),
					'link'	  =>  esc_html__( '#', 'fiona' ),
					'id'              => 'customizer_repeater_header_social_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'fiona' ),
					'link'	  =>  esc_html__( '#', 'fiona' ),
					'id'              => 'customizer_repeater_header_social_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-instagram', 'fiona' ),
					'link'	  =>  esc_html__( '#', 'fiona' ),
					'id'              => 'customizer_repeater_header_social_004',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-pinterest', 'fiona' ),
					'link'	  =>  esc_html__( '#', 'fiona' ),
					'id'              => 'customizer_repeater_header_social_007',
				),
			)
		)
	);
}
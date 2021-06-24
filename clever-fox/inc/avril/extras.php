<?php
/*
 *
 * Social Icon
 */
function avril_get_social_icon_default() {
	return apply_filters(
		'avril_get_social_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'clever-fox' ),
					'link'	  =>  esc_html__( '#', 'clever-fox' ),
					'id'              => 'customizer_repeater_header_social_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-google-plus', 'clever-fox' ),
					'link'	  =>  esc_html__( '#', 'clever-fox' ),
					'id'              => 'customizer_repeater_header_social_002',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'clever-fox' ),
					'link'	  =>  esc_html__( '#', 'clever-fox' ),
					'id'              => 'customizer_repeater_header_social_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-linkedin', 'clever-fox' ),
					'link'	  =>  esc_html__( '#', 'clever-fox' ),
					'id'              => 'customizer_repeater_header_social_004',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-behance', 'clever-fox' ),
					'link'	  =>  esc_html__( '#', 'clever-fox' ),
					'id'              => 'customizer_repeater_header_social_005',
				),
			)
		)
	);
}


/*
 *
 * Slider Default
 */
 function avril_get_slider_default() {
	return apply_filters(
		'avril_get_slider_default', json_encode(
				 array(
				array(
					'image_url'       => CLEVERFOX_PLUGIN_URL .'inc/avril/images/slider/img01.jpg',
					'title'           => esc_html__( 'Global Project Managment', 'clever-fox' ),
					'subtitle'         => esc_html__( 'Services & Solutions', 'clever-fox' ),
					'text'            => esc_html__( 'There are many variations of passages of Lorem Ipsum available but the majority have suffered injected humour dummy now.', 'clever-fox' ),
					'text2'	  =>  esc_html__( 'Get Started', 'clever-fox' ),
					'link'	  =>  esc_html__( '#', 'clever-fox' ),
					'button_second'	  =>  esc_html__( 'Read More', 'clever-fox' ),
					'link2'	  =>  esc_html__( '#', 'clever-fox' ),
					"slide_align" => "left", 
					'id'              => 'customizer_repeater_slider_001',
				),
				array(
					'image_url'       => CLEVERFOX_PLUGIN_URL .'inc/avril/images/slider/img02.jpg',
					'title'           => esc_html__( 'Develop Stronger Minds', 'clever-fox' ),
					'subtitle'         => esc_html__( 'Better Coaching Gets', 'clever-fox' ),
					'text'            => esc_html__( 'There are many variations of passages of Lorem Ipsum available but the majority have suffered injected humour dummy now.', 'clever-fox' ),
					'text2'	  =>  esc_html__( 'Get Started', 'clever-fox' ),
					'link'	  =>  esc_html__( '#', 'clever-fox' ),
					'button_second'	  =>  esc_html__( 'Read More', 'clever-fox' ),
					'link2'	  =>  esc_html__( '#', 'clever-fox' ),
					"slide_align" => "center", 
					'id'              => 'customizer_repeater_slider_002',
				),
				array(
					'image_url'       => CLEVERFOX_PLUGIN_URL .'inc/avril/images/slider/img03.jpg',
					'title'           => esc_html__( 'Industry Analysis', 'clever-fox' ),
					'subtitle'         => esc_html__( 'Marketing & Strategy', 'clever-fox' ),
					'text'            => esc_html__( 'There are many variations of passages of Lorem Ipsum available but the majority have suffered injected humour dummy now.', 'clever-fox' ),
					'text2'	  =>  esc_html__( 'Get Started', 'clever-fox' ),
					'link'	  =>  esc_html__( '#', 'clever-fox' ),
					'button_second'	  =>  esc_html__( 'Read More', 'clever-fox' ),
					'link2'	  =>  esc_html__( '#', 'clever-fox' ),
					"slide_align" => "right", 
					'id'              => 'customizer_repeater_slider_003',
			
				),
			)
		)
	);
}


/*
 *
 * Service Default
 */
 function avril_get_service_default() {
	return apply_filters(
		'avril_get_service_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( 'Well Documented', 'clever-fox' ),
					'text'            => esc_html__( 'Lorem Ipsum is simply dummy of printing and typesetting and industry. Lorem Ipsum been.', 'clever-fox' ),
					'icon_value'       => 'fa-folder-open-o',
					'id'              => 'customizer_repeater_service_001',
					
				),
				array(
					'title'           => esc_html__( 'Simple To Use', 'clever-fox' ),
					'text'            => esc_html__( 'Lorem Ipsum is simply dummy of printing and typesetting and industry. Lorem Ipsum been.', 'clever-fox' ),
					'icon_value'       => 'fa-list',
					'id'              => 'customizer_repeater_service_002',				
				),
				array(
					'title'           => esc_html__( 'High Performance', 'clever-fox' ),
					'text'            => esc_html__( 'Lorem Ipsum is simply dummy of printing and typesetting and industry. Lorem Ipsum been.', 'clever-fox' ),
					'icon_value'       => 'fa-rocket',
					'id'              => 'customizer_repeater_service_003',
				),
			)
		)
	);
}


/*
 *
 * Features Default
 */
 function avril_get_features_default() {
	return apply_filters(
		'avril_get_features_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( 'Business Growth', 'clever-fox' ),
					'text'            => esc_html__( 'Many variations of at Lorem Ipsum but the majority have suffered. Lorem Ipsum the majority suffered.', 'clever-fox' ),
					'icon_value'       => 'fa-bar-chart',
					'id'              => 'customizer_repeater_features_001',
					
				),
				array(
					'title'           => esc_html__( 'Business Sustainability', 'clever-fox' ),
					'text'            => esc_html__( 'Many variations of at Lorem Ipsum but the majority have suffered. Lorem Ipsum the majority suffered.', 'clever-fox' ),
					'icon_value'       => 'fa-connectdevelop',
					'id'              => 'customizer_repeater_features_002',			
				),
				array(
					'title'           => esc_html__( 'Business Performance', 'clever-fox' ),
					'text'            => esc_html__( 'Many variations of at Lorem Ipsum but the majority have suffered. Lorem Ipsum the majority suffered.', 'clever-fox' ),
					'icon_value'       => 'fa-tachometer',
					'id'              => 'customizer_repeater_features_003',
				),
				array(
					'title'           => esc_html__( 'Business Organization', 'clever-fox' ),
					'text'            => esc_html__( 'Many variations of at Lorem Ipsum but the majority have suffered. Lorem Ipsum the majority suffered.', 'clever-fox' ),
					'icon_value'       => 'fa-user-secret',
					'id'              => 'customizer_repeater_features_004',
				),
				array(
					'title'           => esc_html__( 'Dedicated Teams', 'clever-fox' ),
					'text'            => esc_html__( 'Many variations of at Lorem Ipsum but the majority have suffered. Lorem Ipsum the majority suffered.', 'clever-fox' ),
					'icon_value'       => 'fa-folder-open-o',
					'id'              => 'customizer_repeater_features_005',
				),
				array(
					'title'           => esc_html__( '24X7 support', 'clever-fox' ),
					'text'            => esc_html__( 'Many variations of at Lorem Ipsum but the majority have suffered. Lorem Ipsum the majority suffered.', 'clever-fox' ),
					'icon_value'       => 'fa-users',
					'id'              => 'customizer_repeater_features_006',
				),
			)
		)
	);
}

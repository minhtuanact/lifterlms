<?php
function metasoft_cta_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	CTA  Section
	=========================================*/
	$wp_customize->add_section(
		'cta_setting', array(
			'title' => esc_html__( 'Call to Action Section', 'clever-fox' ),
			'priority' => 12,
			'panel' => 'metasoft_frontpage_sections',
		)
	);
	
	// CTA Content Section // 
	$wp_customize->add_setting(
		'cta_contents'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'metasoft_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'cta_contents',
		array(
			'type' => 'hidden',
			'label' => __('Content','clever-fox'),
			'section' => 'cta_setting',
		)
	);
	// Style // 
	$wp_customize->add_setting(
    	'cta_type',
    	array(
	        'default'			=> '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'metasoft_sanitize_select',
			'priority' => 3,
		)
	);	

	$wp_customize->add_control(
		'cta_type',
		array(
		    'label'   		=> __('Style','clever-fox'),
		    'section' 		=> 'cta_setting',
			'type'			=> 'select',
			'choices'        => 
			array(
				'1' => __( 'Style 1', 'clever-fox' ),
				'2' => __( 'Style 2 ', 'clever-fox' ),
			) 
		) 
	);
	
	// CTA Title // 
	$wp_customize->add_setting(
    	'cta_title',
    	array(
	        'default'			=> __('Need Emergency <span>Plumbing Service?</span> Call us at','clever-fox'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'metasoft_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_title',
		array(
		    'label'   => __('Title','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	// CTA Description // 
	$wp_customize->add_setting(
    	'cta_description',
    	array(
	        'default'			=> __('24 hours, 7 days a week, 365 days a year','clever-fox'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'metasoft_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_description',
		array(
		    'label'   => __('Description','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Button First // 	
	$wp_customize->add_setting(
		'cta_btn_first'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'metasoft_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'cta_btn_first',
		array(
			'type' => 'hidden',
			'label' => __('Button First','clever-fox'),
			'section' => 'cta_setting',
		)
	);
	
	$wp_customize->add_setting(
    	'cta_btn_lbl1',
    	array(
	        'default'			=> __('Contact With Us','clever-fox'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'metasoft_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 8,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_btn_lbl1',
		array(
		    'label'   => __('Button Label','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	$wp_customize->add_setting(
    	'cta_btn_link1',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'metasoft_sanitize_url',
			'priority' => 9,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_btn_link1',
		array(
		    'label'   => __('Link','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	// Button Second //  
	$wp_customize->add_setting(
		'cta_btn_second'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'metasoft_sanitize_text',
			'priority' => 10,
		)
	);
	
	$wp_customize->add_control(
	'cta_btn_second',
		array(
			'type' => 'hidden',
			'label' => __('Button Second','clever-fox'),
			'section' => 'cta_setting',
		)
	);
	
	$wp_customize->add_setting(
	'cta_btn_second_ttl'
		,array(
		'default' => __('or','clever-fox'),
		'capability'     	=> 'edit_theme_options',
		'sanitize_callback' => 'metasoft_sanitize_text',
		'priority' => 10,
		)
	);

	$wp_customize->add_control(
	'cta_btn_second_ttl',
		array(
			'type' => 'text',
			'label' => __('Middle Content','clever-fox'),
			'section' => 'cta_setting',
		)
	);
	
	$wp_customize->add_setting(
    	'cta_btn_lbl2',
    	array(
	        'default'			=> __('+1 (088) 456 888 (24/7)','clever-fox'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'metasoft_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 11,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_btn_lbl2',
		array(
		    'label'   => __('Label','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	$wp_customize->add_setting(
    	'cta_btn_link2',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'metasoft_sanitize_url',
			'priority' => 12,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_btn_link2',
		array(
		    'label'   => __('Link','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
}

add_action( 'customize_register', 'metasoft_cta_setting' );

// CTA selective refresh
function metasoft_ata_section_partials( $wp_customize ){
	
	// cta_title
	$wp_customize->selective_refresh->add_partial( 'cta_title', array(
		'selector'            => '.home-cta .head h2',
		'settings'            => 'cta_title',
		'render_callback'  => 'metasoft_cta_title_render_callback',
	
	) );
	
	// cta_description
	$wp_customize->selective_refresh->add_partial( 'cta_description', array(
		'selector'            => '.home-cta .head p',
		'settings'            => 'cta_description',
		'render_callback'  => 'metasoft_cta_description_render_callback',
	) );
	
	// cta_btn_lbl1
	$wp_customize->selective_refresh->add_partial( 'cta_btn_lbl1', array(
		'selector'            => '.home-cta  a.first',
		'settings'            => 'cta_btn_lbl1',
		'render_callback'  => 'metasoft_cta_btn_lbl1_render_callback',
	) );
	// cta_btn_lbl2
	$wp_customize->selective_refresh->add_partial( 'cta_btn_lbl2', array(
		'selector'            => '.home-cta .learn-more a',
		'settings'            => 'cta_btn_lbl2',
		'render_callback'  => 'metasoft_cta_btn_lbl2_render_callback',
	) );
	}

add_action( 'customize_register', 'metasoft_ata_section_partials' );

// cta_title
function metasoft_cta_title_render_callback() {
	return get_theme_mod( 'cta_title' );
}


// cta_description
function metasoft_cta_description_render_callback() {
	return get_theme_mod( 'cta_description' );
}

// cta_btn_lbl1
function metasoft_cta_btn_lbl1_render_callback() {
	return get_theme_mod( 'cta_btn_lbl1' );
}

// cta_btn_lbl2
function metasoft_cta_btn_lbl2_render_callback() {
	return get_theme_mod( 'cta_btn_lbl2' );
}
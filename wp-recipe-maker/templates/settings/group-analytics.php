<?php
/**
 * Template for the plugin settings structure.
 *
 * @link       http://bootstrapped.ventures
 * @since      6.5.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/templates/settings
 */

$analytics = array(
	'id' => 'analytics',
	'icon' => 'chart',
	'name' => __( 'Analytics', 'wp-recipe-maker' ),
	'settings' => array(
		array(
			'id' => 'analytics_enabled',
			'name' => __( 'Enable Analytics', 'wp-recipe-maker' ),
			'description' => __( 'Track different visitor actions related to recipes. Might require changes to your cookie or privacy policy!', 'wp-recipe-maker' ),
			'type' => 'toggle',
			'default' => false,
		),
		array(
			'id' => 'analytics_exclude_ips',
			'name' => __( 'Exclude IPs', 'wp-recipe-maker' ),
			'description' => __( 'Do not track any analytics for these IP addresses. One address or range per line.', 'wp-recipe-maker' ),
			'type' => 'textarea',
			'default' => '',
		),
	),
);

$analytics['settings'][] = array(
	'id' => 'honey_home_integration',
	'name' => __( 'DailyGrub Integration', 'wp-recipe-maker' ),
	'description' => __( 'Advanced recipe and audience analytics.', 'wp-recipe-maker' ),
	'documentation' => 'https://dailygrub.com',
	'type' => 'toggle',
	'default' => false,
	'dependency' => array(
		'id' => 'analytics_enabled',
		'value' => true,
	),
);

$hh_integration_status = get_option( 'hh_integration_status', false );

$description = __( 'Add your DailyGrub tracking ID to enable syncing data with the platform.', 'wp-recipe-maker' );
if ( false !== $hh_integration_status ) {
	if ( $hh_integration_status['success'] ) {
		$description = __( 'The integration is currently active.', 'wp-recipe-maker' );
	} else {
		$description = __( 'There was a problem with activating the integration:', 'wp-recipe-maker' ) . ' ' . $hh_integration_status['message'];
	}
}

$analytics['settings'][] = array(
	'id' => 'honey_home_token',
	'name' => __( 'DailyGrub Tracking ID', 'wp-recipe-maker' ),
	'description' => $description,
	'type' => 'text',
	'default' => '',
	'sanitize' => function( $value ) {
		return trim( sanitize_text_field( $value ) );
	},
	'dependency' => array(
		array(
			'id' => 'analytics_enabled',
			'value' => true,
		),
		array(
			'id' => 'honey_home_integration',
			'value' => true,
		),
	),
);
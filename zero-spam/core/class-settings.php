<?php
/**
 * Settings class
 *
 * @package ZeroSpam
 */

namespace ZeroSpam\Core;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * Settings
 */
class Settings {

	/**
	 * Settings
	 *
	 * @var Settings
	 */
	public static $settings = array();

	/**
	 * Sections
	 *
	 * @var Sections
	 */
	public static $sections = array();

	/**
	 * Returns the plugin setting sections
	 */
	public static function get_sections() {
		self::$sections['general'] = array(
			'title' => __( 'General Settings', 'zerospam' ),
		);

		self::$sections['debug'] = array(
			'title' => __( 'Debug', 'zerospam' ),
		);

		return apply_filters( 'zerospam_setting_sections', self::$sections );
	}

	/**
	 * Configures the plugin's recommended settings.
	 */
	public static function auto_configure() {
		$settings = \ZeroSpam\Core\Settings::get_settings();

		$recommended_settings = array();
		foreach ( $settings as $key => $setting ) {
			$recommended_settings[ $key ] = $setting['value'];
			if ( isset( $setting['recommended'] ) ) {
				$recommended_settings[ $key ] = $setting['recommended'];
			}
		}

		if ( $recommended_settings ) {
			update_option( 'wpzerospam', $recommended_settings );
			update_option( 'zerospam_configured', 1 );
		}
	}

	/**
	 * Returns the plugin settings
	 */
	public static function get_settings( $key = false ) {
		$options = get_option( 'wpzerospam' );

		self::$settings['share_data'] = array(
			'title'       => __( 'Usage Data Sharing', 'zerospam' ),
			'section'     => 'general',
			'type'        => 'checkbox',
			'options'     => array(
				'enabled' => sprintf(
					wp_kses(
						/* translators: %s: url */
						__( 'Become a super contributor by opting in to share non-sensitive plugin data. <a href="%s" target="_blank" rel="noreferrer noopener">Learn more</a>.', 'zerospam' ),
						array(
							'a'    => array(
								'target' => array(),
								'href'   => array(),
								'rel'    => array(),
							),
						)
					),
					esc_url( 'https://github.com/bmarshall511/wordpress-zero-spam/wiki/FAQ#what-data-is-shared-when-usage-data-sharing-is-enabled' )
				),
			),
			'value'       => ! empty( $options['share_data'] ) ? $options['share_data'] : false,
			'recommended' => 'enabled',
		);

		self::$settings['block_handler'] = array(
			'title'   => __( 'IP Block Handler', 'zerospam' ),
			'desc'    => __( 'Determines how blocked IPs are handled when they attempt to access the site.', 'zerospam' ),
			'section' => 'general',
			'type'    => 'radio',
			'options' => array(
				'redirect' => __( 'Redirect user', 'zerospam' ),
				'403'      => sprintf(
					wp_kses(
						/* translators: %s: url */
						__( 'Display a <a href="%s" target="_blank" rel="noreferrer noopener"><code>403 Forbidden</code></a> error', 'zerospam' ),
						array(
							'code' => array(),
							'a'    => array(
								'target' => array(),
								'href'   => array(),
								'rel'    => array(),
							),
						)
					),
					esc_url( 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/403' )
				),
			),
			'value'   => ! empty( $options['block_handler'] ) ? $options['block_handler'] : 403,
		);

		$message = __( 'Your IP address has been blocked by WordPress Zero Spam due to detected spam/malicious activity.', 'zerospam' );

		self::$settings['blocked_message'] = array(
			'title'       => __( 'Blocked Message', 'zerospam' ),
			'desc'        => __( 'The message displayed to blocked users when \'Display a 403 Forbidden error\' is selected.', 'zerospam' ),
			'section'     => 'general',
			'type'        => 'text',
			'field_class' => 'large-text',
			'placeholder' => $message,
			'value'       => ! empty( $options['blocked_message'] ) ? $options['blocked_message'] : $message,
		);

		self::$settings['blocked_redirect_url'] = array(
			'title'       => __( 'Blocked Users Redirect', 'zerospam' ),
			'desc'        => __( 'The URL blocked users are redirected to when \'Redirect user\' is selected.', 'zerospam' ),
			'section'     => 'general',
			'type'        => 'url',
			'field_class' => 'regular-text',
			'placeholder' => 'https://wordpress.org/plugins/zero-spam/',
			'value'       => ! empty( $options['blocked_redirect_url'] ) ? $options['blocked_redirect_url'] : 'https://wordpress.org/plugins/zero-spam/',
		);

		self::$settings['log_blocked_ips'] = array(
			'title'       => __( 'Log Blocked IPs', 'zerospam' ),
			'section'     => 'general',
			'type'        => 'checkbox',
			'desc'        => __( 'Enables logging IPs that are blocked from accessing the site.', 'zerospam' ),
			'options'     => array(
				'enabled' => __( 'Enabled', 'zerospam' ),
			),
			'value'       => ! empty( $options['log_blocked_ips'] ) ? $options['log_blocked_ips'] : false,
			'recommended' => 'enabled',
		);

		self::$settings['max_logs'] = array(
			'title'       => __( 'Maximum Log Entries', 'zerospam' ),
			'desc'        => __( 'The maximum number of log entries when logging is enabled. When the maximum is reached, the oldest entries will be deleted.', 'zerospam' ),
			'section'     => 'general',
			'type'        => 'number',
			'field_class' => 'small-text',
			'placeholder' => 10000,
			'value'       => ! empty( $options['max_logs'] ) ? $options['max_logs'] : 10000,
		);

		self::$settings['ip_whitelist'] = array(
			'title'       => __( 'IP Whitelist', 'zerospam' ),
			'desc'        => __( 'Enter IPs that should be whitelisted (IPs that should never be blocked), one per line.', 'zerospam' ),
			'section'     => 'general',
			'type'        => 'textarea',
			'field_class' => 'regular-text code',
			'placeholder' => '',
			'value'       => ! empty( $options['ip_whitelist'] ) ? $options['ip_whitelist'] : false,
		);

		self::$settings['debug'] = array(
			'title'   => __( 'Debug', 'zerospam' ),
			'desc'    => __( 'For troubleshooting site issues.', 'zerospam' ),
			'section' => 'debug',
			'type'    => 'checkbox',
			'options' => array(
				'enabled' => __( 'Enabled', 'zerospam' ),
			),
			'value'   => ! empty( $options['debug'] ) ? $options['debug'] : false,
		);

		self::$settings['debug_ip'] = array(
			'title'       => __( 'Debug IP', 'zerospam' ),
			'desc'        => wp_kses(
				/* translators: %s: url */
				__( 'Mock a IP address for debugging. <strong>WARNING: This overrides all visitor IP addresses and while enabled could block legit visitors from accessing the site.</strong>', 'zerospam' ),
				array(
					'strong' => array(),
				)
			),
			'section'     => 'debug',
			'type'        => 'text',
			'placeholder' => '127.0.0.1',
			'value'       => ! empty( $options['debug_ip'] ) ? $options['debug_ip'] : false,
		);

		self::$settings['regenerate_honeypot'] = array(
			'title'   => __( 'Regenerate Honeypot ID', 'zerospam' ),
			'desc'    => __( 'Helpful if spam is getting through. Current honeypot ID: <code>' . \ZeroSpam\Core\Utilities::get_honeypot() . '</code>', 'zerospam' ),
			'section' => 'general',
			'type'    => 'html',
			'html'    => sprintf(
				wp_kses(
					/* translators: %s: url */
					__( '<a href="%s" class="button button-primary">Regenerate Honeypot ID</a>', 'zerospam' ),
					array(
						'a'    => array(
							'href'  => array(),
							'class' => array(),
						),
					)
				),
				esc_url( admin_url( 'options-general.php?page=wordpress-zero-spam-settings&zerospam-regenerate-honeypot=1' ) )
			),
		);

		self::$settings['sync_disallowed_keys'] = array(
			'title'       => __( 'Sync Disallowed Keys', 'zerospam' ),
			'desc'        => __( 'Automatically sync WP core\'s disallowed words option with <a href="https://github.com/splorp/wordpress-comment-blacklist/" target="_blank" rel="noreferrer noopener">splorp\'s Comment Blacklist for WordPress</a>.', 'zerospam' ),
			'section'     => 'general',
			'type'        => 'checkbox',
			'options'     => array(
				'enabled' => __( 'Enabled', 'zerospam' ),
			),
			'value'       => ! empty( $options['sync_disallowed_keys'] ) ? $options['sync_disallowed_keys'] : false,
			'recommended' => 'enabled',
		);

		$settings = apply_filters( 'zerospam_settings', self::$settings );

		if ( $key ) {
			if ( ! empty( $settings[ $key ]['value'] ) ) {
				return $settings[ $key ]['value'];
			}

			return false;
		}

		return $settings;
	}
}

<?php
/*
	Plugin Name: Print, PDF & Email by PrintFriendly
	Plugin URI: http://www.printfriendly.com
	Description: PrintFriendly & PDF button for your website. Optimizes your pages and brand for print, pdf, and email.
	Name and URL are included to ensure repeat visitors and new visitors when printed versions are shared.
	Version: 4.1
	Author: Print, PDF, & Email by PrintFriendly
	Author URI: http://www.printfriendly.com
	Domain Path: /languages
	Text Domain: printfriendly
*/

/**
 * PrintFriendly WordPress plugin. Allows easy embedding of printfriendly.com buttons.
 *
 * @package PrintFriendly_WordPress
 * @author PrintFriendly <support@printfriendly.com>
 * @copyright Copyright (C) 2012, PrintFriendly
 */
if ( ! class_exists( 'PrintFriendly_WordPress' ) ) {

	/**
	 * Class containing all the plugins functionality.
	 *
	 * @package PrintFriendly_WordPress
	 */
	class PrintFriendly_WordPress {

		/**
		 * Current plugin version.
		 *
		 * @var string
		 */
		var $plugin_version = '4.1';

		/**
		 * The hook, used for text domain as well as hooks on pages and in get requests for admin.
		 *
		 * @var string
		 */
		var $hook = 'printfriendly';

		/**
		 * The option name, used throughout to refer to the plugins option and option group.
		 *
		 * @var string
		 */
		var $option_name = 'printfriendly_option';

		/**
		 * The plugins options, loaded on init containing all the plugins settings.
		 *
		 * @var array
		 */
		var $options = array();

		/**
		 * Database version, used to allow for easy upgrades to / additions in plugin options between plugin versions.
		 *
		 * @var int
		 */
		var $db_version = 20;

		/**
		 * Settings page, used within the plugin to reliably load the plugins admin JS and CSS files only on the admin page.
		 *
		 * @var string
		 */
		var $settings_page = '';

		/**
		 * GetSentry error reporting client
		 *
		 * @var Raven_Client
		 */
		var $raven_client = null;

		/**
		 * Constructor
		 *
		 * @since 3.0
		 */
		function __construct() {
			define( 'PRINTFRIENDLY_BASEPATH', dirname( __FILE__ ) );
			define( 'PRINTFRIENDLY_BASEURL', plugins_url( '/', __FILE__ ) );

			// Retrieve the plugin options
			$this->options = get_option( $this->option_name );

			// If the options array is empty, set defaults
			if ( ! is_array( $this->options ) ) {
				$this->set_defaults();
			}

			/**
			 * Set page content selection option "WordPress Standard/Strict" to "WP Template"
			 */
			if ( isset( $this->options['pf_algo'] ) && $this->options['pf_algo'] === 'ws' ) {
				$this->options['pf_algo'] = 'wp';
				update_option( $this->option_name, $this->options );
			}

			// If the version number doesn't match, upgrade
			if ( $this->db_version > $this->options['db_version'] ) {
				$this->upgrade();
			}

			add_action( 'wp_head', array(&$this, 'front_head') );
			// automaticaly add the link
			if ( ! $this->is_manual() ) {
				add_filter( 'the_content', array(&$this, 'show_link_on_content') );
				add_filter( 'the_excerpt', array(&$this, 'show_link_on_excerpt') );
			}

			add_action( 'the_content', array(&$this, 'add_pf_content_class_around_content_hook') );

			if ( is_admin() ) {
				$this->admin_hooks();
			}
		}

		/**
		 * All admin hooks.
		 */
		function admin_hooks() {
			// Hook into init for registration of the option and the language files
			add_action( 'admin_init', array(&$this, 'init') );

			// Register the settings page
			add_action( 'admin_menu', array(&$this, 'add_config_page') );

			// Enqueue the needed scripts and styles
			add_action( 'admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts') );

			// Register a link to the settings page on the plugins overview page
			add_filter( 'plugin_action_links', array(&$this, 'filter_plugin_actions'), 10, 2 );
			add_filter( 'plugin_row_meta', array(&$this, 'additional_links'), 10, 2 );
		}

		/**
		 * Adds wraps content in pf-content class to help Printfriendly algo determine the content
		 *
		 * @since 3.2.8
		 **/
		function add_pf_content_class_around_content_hook( $content = false ) {
			if ( $this->is_enabled() && $this->is_wp_algo_on( $content ) ) {
				add_action( 'wp_footer', array(&$this, 'print_script_footer') );
				return '<div class="pf-content">' . $content . '</div>';
			} else {
				return $content;
			}
		}

		/**
		 *  Override to check if print-only command is being used
		 *
		 *  @since 3.3.0
		 **/
		function print_only_override( $content ) {
			$pattern = '/class\s*?=\s*?(["\']|["\']([^"\']*?)\s)print-only(["\']|\s([^"\']*?)["\'])/';
			$pf_pattern = '/class\s*?=\s*?(["\']|["\']([^"\']*?)\s)pf-content(["\']|\s([^"\']*?)["\'])/';

			return ( preg_match( $pattern, $content ) || preg_match( $pf_pattern, $content ) );
		}

		/**
		 *  Check if WP Algorithm is selected and content doesn't use print-only
		 *
		 *  @since 3.5.4
		 **/
		function is_wp_algo_on( $content ) {
			return ! class_exists( 'WooCommerce' ) && isset( $this->options['pf_algo'] ) && $content && $this->options['pf_algo'] === 'wp' && ! $this->print_only_override( $content );
		}

		/**
		 * PHP 4 Compatible Constructor
		 *
		 * @since 3.0
		 */
		function PrintFriendly_WordPress() {
			$this->__construct();
		}

		/**
		 * Check if button should be visible on particular page
		 */
		function is_enabled() {
			if ( ( is_page() && ( isset( $this->options['show_on_pages'] ) && 'on' === $this->options['show_on_pages'] ) )
			|| ( is_home() && ( ( isset( $this->options['show_on_homepage'] ) && 'on' === $this->options['show_on_homepage'] ) ) )
			|| ( is_tax() && ( ( isset( $this->options['show_on_taxonomies'] ) && 'on' === $this->options['show_on_taxonomies'] ) && $this->category_included() ) )
			|| ( is_category() && ( ( isset( $this->options['show_on_categories'] ) && 'on' === $this->options['show_on_categories'] ) && $this->category_included() ) )
			|| ( is_single() && ( ( isset( $this->options['show_on_posts'] ) && 'on' === $this->options['show_on_posts'] ) && $this->category_included() ) )
			) {
				return true;
			}
			return false;
		}

		/**
		 * Prints the PrintFriendly button CSS, in the header.
		 *
		 * @since 3.0
		 */
		function front_head() {
			if ( $this->is_enabled() ) {
				?>
				<?php
				if ( isset( $this->options['enable_css'] ) && $this->options['enable_css'] !== 'on' ) {
					return;
				}
				?>
		  <style type="text/css">
			@media screen {
			  .printfriendly {
				position: relative;
				  z-index: 1000;
				margin: <?php echo $this->options['margin_top'] . 'px ' . $this->options['margin_right'] . 'px ' . $this->options['margin_bottom'] . 'px ' . $this->options['margin_left'] . 'px'; ?>;
			  }
			  .printfriendly a, .printfriendly a:link, .printfriendly a:visited, .printfriendly a:hover, .printfriendly a:active {
				font-weight: 600;
				cursor: pointer;
				text-decoration: none;
				border: none;
				-webkit-box-shadow: none;
				-moz-box-shadow: none;
				box-shadow: none;
				outline:none;
				font-size: <?php echo $this->options['text_size']; ?>px !important;
				color: <?php echo $this->options['text_color']; ?> !important;
			  }
			  .printfriendly.pf-alignleft {float: left}.printfriendly.pf-alignright {float: right}.printfriendly.pf-aligncenter {display: flex;align-items: center;justify-content: center;}
			}

			@media print {
			  .printfriendly {display: none}
			}

			.pf-button.pf-button-excerpt {
				display: none;
			}

		  </style>
				<?php
			}
		}

		/**
		 * Prints the PrintFriendly JavaScript, in the footer, and loads it asynchronously.
		 *
		 * @since 3.0
		 */
		function print_script_footer() {
			$tagline = $this->options['tagline'];
			$image_url = $this->options['image_url'];
			if ( $this->options['logo'] === 'favicon' ) {
				$tagline = '';
				$image_url = '';
			}

			// Currently we use v3 for both: normal and password protected sites
			?>
		<script type="text/javascript" id="pf_script">
		  var pfHeaderImgUrl = '<?php echo esc_js( esc_url( $image_url ) ); ?>';
		  var pfHeaderTagline = '<?php echo esc_js( $tagline ); ?>';
		  var pfdisableClickToDel = '<?php echo esc_js( $this->options['click_to_delete'] ); ?>';
		  var pfImagesSize = '<?php echo esc_js( $this->options['images-size'] ); ?>';
		  var pfImageDisplayStyle = '<?php echo esc_js( $this->options['image-style'] ); ?>';
		  var pfEncodeImages = '<?php echo esc_js( $this->options['password_protected'] === 'yes' ? 1 : 0 ); ?>';
		  var pfDisableEmail = '<?php echo esc_js( $this->options['email'] ); ?>';
		  var pfDisablePDF = '<?php echo esc_js( $this->options['pdf'] ); ?>';
		  var pfDisablePrint = '<?php echo esc_js( $this->options['print'] ); ?>';
		  var pfCustomCSS = '<?php echo esc_js( esc_url( $this->get_custom_css() ) ); ?>';
		  var pfPlatform = 'WordPress';
		</script>
		<script async src='https://cdn.printfriendly.com/printfriendly.js'></script>
			<?php
		}

		/**
		 * Used as a filter for the_content.
		 *
		 * @since ?
		 *
		 * @param string $content The content of the post, when the function is used as a filter.
		 *
		 * @return string The content with the button added to the content.
		 */
		function show_link_on_content( $content ) {
			$content = $this->show_link( $content, true );

			return $content;
		}

		/**
		 * Used as a filter for the_excerpt.
		 *
		 * @since ?
		 *
		 * @param string $content The content of the post, when the function is used as a filter.
		 *
		 * @return string The content with the button added to the content.
		 */
		function show_link_on_excerpt( $content ) {
			return $this->show_link( $content, false );
		}

		/**
		 * Used to generate the the final content as per the required placement.
		 *
		 * @since 3.0
		 *
		 * @param string $content The content of the post, when the function is used as a filter.
		 * @param bool   $on_content True if showing on the_content and False if showing on the_excerpt.
		 *
		 * @return string $button or $content with the button added to the content when appropriate, just the content when button shouldn't be added or just button when called manually.
		 */
		private function show_link( $content = false, $on_content = true ) {
			$is_manual = $this->is_manual();

			if ( ! $content && ! $is_manual ) {
				return '';
			}

			$button = $this->getButton( false, $on_content ? 'pf-button-content' : 'pf-button-excerpt' );
			if ( $is_manual ) {
				// Hook the script call now, so it only get's loaded when needed, and need is determined by the user calling pf_button
				add_action( 'wp_footer', array(&$this, 'print_script_footer') );
				return $button;
			} else {
				if ( $this->is_enabled() ) {
					// Hook the script call now, so it only get's loaded when needed, and need is determined by the user calling pf_button
					add_action( 'wp_footer', array(&$this, 'print_script_footer') );

					if ( $this->options['content_placement'] === 'before' ) {
						return $button . $content;
					} else {
						return $content . $button;
					}
				} else {
					return $content;
				}
			}
		}

		/**
		 * Returns the HTML of the button.
		 *
		 * This can be called publicly from `pf_show_link()`.
		 *
		 * @since 3.3.8
		 *
		 * @param bool   $add_footer_script Whether to add the script in the footer.
		 * @param string $add_class Additional class for the HTML button div.
		 *
		 * @return Printfriendly Button HTML
		 */
		public function getButton( $add_footer_script = false, $add_class = '' ) {
			if ( $add_footer_script ) {
				add_action( 'wp_footer', array(&$this, 'print_script_footer') );
			}
			$js_enabled = $this->js_enabled();
			$analytics_code = '';
			$onclick = '';

			if ( $this->google_analytics_enabled() ) {
				$title_var = 'NULL';
				$analytics_code = "if(typeof(_gaq) != 'undefined') { _gaq.push(['_trackEvent','PRINTFRIENDLY', 'print', '" . $title_var . "']);
          }else if(typeof(ga) != 'undefined') {  ga('send', 'event','PRINTFRIENDLY', 'print', '" . $title_var . "'); }";
				if ( $js_enabled ) {
					$onclick = 'onclick="window.print();' . $analytics_code . ' return false;"';
				} else {
					$onclick = '';
				}
			} elseif ( $js_enabled ) {
				$onclick = 'onclick="window.print(); return false;"';
			}

			if ( $js_enabled ) {
				$href = '#';
			} else {
				$href = 'https://www.printfriendly.com/print?url=' . urlencode( get_permalink() );
			}

			if ( ! $js_enabled ) {
				if ( $this->google_analytics_enabled() ) {
					$onclick = $onclick . ' onclick="' . $analytics_code . '"';
				}
				$href = 'https://www.printfriendly.com/print?headerImageUrl=' . urlencode( $this->options['image_url'] ) . '&headerTagline=' . urlencode( $this->options['tagline'] ) . '&pfCustomCSS=' . urlencode( $this->options['custom_css_url'] ) . '&imageDisplayStyle=' . urlencode( $this->options['image-style'] ) . '&disableClickToDel=' . urlencode( $this->options['click_to_delete'] ) . '.&disablePDF=' . urlencode( $this->options['pdf'] ) . '&disablePrint=' . urlencode( $this->options['print'] ) . '&disableEmail=' . urlencode( $this->options['email'] ) . '&imagesSize=' . urlencode( $this->options['images-size'] ) . '&url=' . urlencode( get_permalink() ) . '&source=wp';
			}
			if ( ! is_singular() && ! empty( $onclick ) && $js_enabled ) {
				$onclick = '';
				$href = add_query_arg( 'pfstyle', 'wp', get_permalink() );
			}

			$align = '';
			if ( 'none' !== $this->options['content_position'] ) {
				$align = ' pf-align' . $this->options['content_position'];
			}
			$href = str_replace( '&', '&amp;', $href );
			$button = apply_filters( 'printfriendly_button', '<div class="printfriendly pf-button ' . $add_class . $align . '"><a href="' . $href . '" rel="nofollow" ' . $onclick . ' title="Printer Friendly, PDF & Email">' . $this->button() . '</a></div>' );
			return $button;
		}


		/**
		 * Checks if GA is enabled.
		 *
		 * @since 3.2.9
		 * @returns if google analytics enabled
		 */
		function google_analytics_enabled() {
			return isset( $this->options['enable_google_analytics'] ) && $this->options['enable_google_analytics'] === 'yes';
		}

		/**
		 * Is JS enabled?
		 *
		 * @since 3.2.6
		 * @return boolean true if JS is enabled for the plugin
		 **/
		function js_enabled() {
			return true;
		}

		/**
		 * Filter posts by category.
		 *
		 * @since 3.2.2
		 * @return boolean true if post belongs to category selected for button display
		 */
		function category_included() {
			return isset( $this->options['show_on_cat'] ) ? in_category( $this->options['show_on_cat'] ) : true;
		}

		/**
		 * Register the textdomain and the options array along with the validation function
		 *
		 * @since 3.0
		 */
		function init() {
			// Allow for localization
			load_plugin_textdomain( 'printfriendly', false, basename( dirname( __FILE__ ) ) . '/languages' );

			// Register our option array
			register_setting( $this->option_name, $this->option_name, array(&$this, 'options_validate') );
		}

		/**
		 * Validate the saved options.
		 *
		 * @since 3.0
		 * @param array $input with unvalidated options.
		 * @return array $valid_input with validated options.
		 */
		function options_validate( $input ) {
			// Prevent CSRF attack
			check_admin_referer( 'pf-options', 'pf-nonce' );

			$valid_input = $input;

			// Section 1 options
			if ( ! isset( $input['button_type'] ) || ! in_array(
				$input['button_type'], array(
					'buttons/printfriendly-pdf-email-button.png',
					'buttons/printfriendly-pdf-email-button-md.png',
					'buttons/printfriendly-pdf-email-button-notext.png', // buttongroup1
					'buttons/printfriendly-pdf-button.png',
					'buttons/printfriendly-pdf-button-nobg.png',
					'buttons/printfriendly-pdf-button-nobg-md.png', // buttongroup2
					'buttons/printfriendly-button.png',
					'buttons/printfriendly-button-nobg.png',
					'buttons/printfriendly-button-md.png',
					'buttons/printfriendly-button-lg.png', // buttongroup3
					'buttons/print-button.png',
					'buttons/print-button-nobg.png',
					'buttons/print-button-gray.png', // buttongroup3
					'custom-button', // custom
				), true
			) ) {
				$valid_input['button_type'] = 'printfriendly-pdf-button.png';
			}

			if ( ! isset( $input['custom_button_icon'] ) || ! in_array(
				$input['custom_button_icon'], array(
					'https://cdn.printfriendly.com/icons/printfriendly-icon-sm.png',
					'https://cdn.printfriendly.com/icons/printfriendly-icon-md.png',
					'https://cdn.printfriendly.com/icons/printfriendly-icon-lg.png',
					'custom-image',
					'no-image',
				), true
			) ) {
				$valid_input['custom_button_icon'] = 'https://cdn.printfriendly.com/icons/printfriendly-icon-md.png';
			}

			// if a custom image is not being chosen, reset it in case it existed in the past.
			if ( isset( $valid_input['custom_button_icon'] ) && $valid_input['custom_button_icon'] !== 'custom-image' ) {
				$valid_input['custom_image'] = '';
			}

			// @todo custom image url validation
			if ( ! isset( $input['custom_image'] ) || empty( $input['custom_image'] ) ) {
				$valid_input['custom_image'] = '';
			}

			if ( ! isset( $input['custom_button_text'] ) ) {
				$valid_input['custom_button_text'] = 'custom-text';
			}

			// @todo validate optional custom text
			if ( ! isset( $input['custom_text'] ) ) {
				$valid_input['custom_text'] = 'Print Friendly';
			}

			// Custom button selected, but no url nor text given, reset button type to default
			if ( 'custom-button' === $valid_input['button_type'] && ( '' === $valid_input['custom_image'] && '' === $input['custom_text'] ) ) {
				$valid_input['button_type'] = 'buttons/printfriendly-pdf-button.png';
				add_settings_error( $this->option_name, 'invalid_custom_image', __( 'No valid custom image url received, please enter a valid url to use a custom image.', 'printfriendly' ) );
			}

			$valid_input['text_size'] = (int) $input['text_size'];
			if ( ! isset( $valid_input['text_size'] ) || 0 === $valid_input['text_size'] ) {
				$valid_input['text_size'] = 14;
			} elseif ( 25 < $valid_input['text_size'] || 9 > $valid_input['text_size'] ) {
				$valid_input['text_size'] = 14;
				add_settings_error( $this->option_name, 'invalid_text_size', __( 'The text size you entered is invalid, please stay between 9px and 25px', 'printfriendly' ) );
			}

			if ( ! isset( $input['text_color'] ) ) {
				$valid_input['text_color'] = $this->options['text_color'];
			} elseif ( ! preg_match( '/^#[a-f0-9]{3,6}$/i', $input['text_color'] ) ) {
				// Revert to previous setting and throw error.
				$valid_input['text_color'] = $this->options['text_color'];
				add_settings_error( $this->option_name, 'invalid_color', __( 'The color you entered is not valid, it must be a valid hexadecimal RGB font color.', 'printfriendly' ) );
			}

			/* Section 2 options */
			if ( ! isset( $input['enable_css'] ) || 'off' !== $input['enable_css'] ) {
				$valid_input['enable_css'] = 'on';
			}

			if ( ! isset( $input['content_position'] ) || ! in_array( $input['content_position'], array('none', 'left', 'center', 'right'), true ) ) {
				$valid_input['content_position'] = 'left';
			}

			if ( ! isset( $input['content_placement'] ) || ! in_array( $input['content_placement'], array('before', 'after'), true ) ) {
				$valid_input['content_placement'] = 'after';
			}

			foreach ( array('margin_top', 'margin_right', 'margin_bottom', 'margin_left') as $opt ) {
				// if margin is not defined, don't throw a PHP notice
				if ( isset( $input[ $opt ] ) ) {
					$valid_input[ $opt ] = (int) $input[ $opt ];
				}
			}

			unset( $opt );

			/* Section 3 options */
			foreach ( array('show_on_posts', 'show_on_pages', 'show_on_homepage', 'show_on_categories', 'show_on_taxonomies') as $opt ) {
				if ( ! isset( $input[ $opt ] ) || 'on' !== $input[ $opt ] ) {
					unset( $valid_input[ $opt ] );
				}
			}
			unset( $opt );

			// Just in case
			if ( isset( $input['show_on_template'] ) ) {
				unset( $valid_input['show_on_template'] );
			}

			if ( isset( $input['category_ids'] ) ) {
				unset( $valid_input['category_ids'] );
			}

			/* Section 4 options */
			if ( ! isset( $input['logo'] ) || ! in_array( $input['logo'], array('favicon', 'upload-an-image'), true ) ) {
				$valid_input['logo'] = 'favicon';
			}

			// @todo custom logo url validation
			if ( ! isset( $input['image_url'] ) || empty( $input['image_url'] ) ) {
				$valid_input['image_url'] = '';
			}

			// @todo validate optional tagline text
			if ( ! isset( $input['tagline'] ) ) {
				$valid_input['tagline'] = '';
			}

			// Custom logo selected, but no valid url given, reset logo to default
			if ( 'upload-an-image' === $valid_input['logo'] && '' === $valid_input['image_url'] ) {
				$valid_input['logo'] = 'favicon';
				add_settings_error( $this->option_name, 'invalid_custom_logo', __( 'No valid custom logo url received, please enter a valid url to use a custom logo.', 'printfriendly' ) );
			}

			if ( ! isset( $input['image-style'] ) || ! in_array( $input['image-style'], array('right', 'left', 'none', 'block'), true ) ) {
				$valid_input['image-style'] = 'right';
			}

			foreach ( array('click_to_delete', 'email', 'pdf', 'print') as $opt ) {
				// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				if ( ! isset( $input[ $opt ] ) || ! in_array( $input[ $opt ], array('0', '1') ) ) {
					$valid_input[ $opt ] = '0';
				}
			}
			unset( $opt );

			if ( ! isset( $input['images-size'] ) || ! in_array( $input['images-size'], array('full-size', 'remove-images', 'large', 'medium', 'small'), true ) ) {
				$valid_input['images-size'] = 'full-size';
			}

			if ( $this->is_pro( 'custom-css' ) ) {
				// a file will be generated even if nothing is provided in the css box.
				$css = $input['custom_css'];

				// remove the <style> </style> tags.
				$css = str_replace( array( '<style>', '</style>' ), '', $css );
				$valid_input['custom_css'] = sanitize_textarea_field( $css );
				$file = $this->maybe_generate_custom_css_file( $valid_input['custom_css'] );
				if ( ! $file ) {
					$file = isset( $this->options['custom_css_url_pro'] ) ? $this->options['custom_css_url_pro'] : '';
				}
				$valid_input['custom_css_url_pro'] = $file;

				// if no file can be generated
				// check if this version is upgraded from the version that was using url instead of textbox
				// and reuse it
				if ( ! $file && isset( $this->options['custom_css_url'] ) ) {
					$valid_input['custom_css_url'] = $this->options['custom_css_url'];
				}
			}

			/* Section 5 options */
			if ( ! isset( $input['password_protected'] ) || ! in_array( $input['password_protected'], array('no', 'yes'), true ) ) {
				$valid_input['password_protected'] = 'no';
			}

			/*Analytics Options */
			if ( ! isset( $input['enable_google_analytics'] ) || ! in_array( $input['enable_google_analytics'], array('no', 'yes'), true ) ) {
				$valid_input['enable_google_analytics'] = 'no';
			}

			if ( ! isset( $input['pf_algo'] ) || ! in_array( $input['pf_algo'], array('wp', 'pf'), true ) ) {
				$valid_input['pf_algo'] = 'wp';
			}

			/* Database version */
			$valid_input['db_version'] = $this->db_version;

			// set the current tab from where the settings were saved from
			if ( isset( $_POST['tab'] ) ) {
				set_transient( 'pf-tab', $_POST['tab'], 5 );
			}

			return $valid_input;
		}

		/**
		 * Register the config page for all users that have the manage_options capability
		 *
		 * @since 3.0
		 */
		function add_config_page() {
			$this->settings_page = add_options_page( __( 'PrintFriendly Options', 'printfriendly' ), __( 'Print Friendly & PDF', 'printfriendly' ), 'manage_options', $this->hook, array(&$this, 'config_page') );

			// register  callback gets call prior your own page gets rendered
			add_action( 'load-' . $this->settings_page, array(&$this, 'on_load_printfriendly') );
		}

		/**
		 * Enqueue the scripts for the admin settings page
		 *
		 * @since 3.0
		 * @param string $screen_id check whether the current page is the PrintFriendly settings page.
		 */
		function admin_enqueue_scripts( $screen_id ) {
			if ( $this->settings_page === $screen_id ) {
				if ( ! did_action( 'wp_enqueue_media' ) ) {
					wp_enqueue_media();
				}

				if ( ! wp_script_is( 'clipboard' ) ) {
					wp_enqueue_script( 'clipboard', plugins_url( 'assets/js/lib/clipboard.min.js', __FILE__ ) );
				}

				if ( ! wp_script_is( 'select2' ) ) {
					wp_enqueue_script( 'select2', plugins_url( 'assets/js/lib/select2.min.js', __FILE__ ) );
				}

				wp_register_script( 'pf-admin', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery', 'jquery-ui-tabs', 'media-upload', 'wp-color-picker', 'clipboard', 'select2' ), $this->plugin_version );
				wp_localize_script(
					'pf-admin', 'config', array(
						'i10n' => array(
							'upload_window_title' => __( 'Custom Image', 'printfriendly' ),
							'upload_window_button_title' => __( 'Use Image', 'printfriendly' ),
							'invalid_image_url' => __( 'Invalid Image URL', 'printfriendly' ),
						),
					)
				);

				wp_register_script( 'pf-admin-pro', plugins_url( 'assets/js/admin_pro.js', __FILE__ ), array( 'pf-admin' ), $this->plugin_version );

				wp_localize_script(
					'pf-admin-pro', 'config', array(
						'nonce' => wp_create_nonce( $this->hook . $this->plugin_version ),
						'action' => $this->hook,
						'i10n' => array(
							'activation' => __( 'Activation', 'printfriendly' ),
							'check_status' => __( 'Checking status', 'printfriendly' ),
							'activate' => __( 'Activate', 'printfriendly' ),
							'active_trial' => __( 'Active Trial', 'printfriendly' ),
							'active' => __( 'Active', 'printfriendly' ),
							'expired' => __( 'Expired', 'printfriendly' ),
							'connection' => __( 'Please check Internet connection.', 'printfriendly' ),
						),
					)
				);
				wp_enqueue_script( 'pf-admin-pro' );

				wp_enqueue_style( 'pf-bulma', plugins_url( 'assets/css/lib/bulma.prefixed.min.css', __FILE__ ), array( 'wp-color-picker' ), $this->plugin_version );
				wp_enqueue_style( 'pf-select2', plugins_url( 'assets/css/lib/select2.min.css', __FILE__ ), array(), $this->plugin_version );
				wp_enqueue_style( 'pf-admin', plugins_url( 'assets/css/admin.css', __FILE__ ), array( 'pf-select2', 'pf-bulma' ), $this->plugin_version );
			}
		}

		/**
		 * Register the settings link for the plugins page
		 *
		 * @since 3.0
		 * @param array  $links the links for the plugins.
		 * @param string $file filename to check against plugins filename.
		 * @return array $links the links with the settings link added to it if appropriate.
		 */
		function filter_plugin_actions( $links, $file ) {
			// Static so we don't call plugin_basename on every plugin row.
			static $this_plugin;
			if ( ! $this_plugin ) {
				$this_plugin = plugin_basename( __FILE__ );
			}

			if ( $file === $this_plugin ) {
				$settings_link = '<a href="options-general.php?page=' . $this->hook . '">' . __( 'Settings', 'printfriendly' ) . '</a>';
				array_unshift( $links, $settings_link ); // before other links
			}

			return $links;
		}

		/**
		 * Register the additional link for the plugins page in the plugin description column.
		 *
		 * @since ?
		 * @param array  $links the links for the plugins.
		 * @param string $file filename to check against plugins filename.
		 * @return array $links the links with the links added to it if appropriate.
		 */
		function additional_links( $links, $file ) {
			// Static so we don't call plugin_basename on every plugin row.
			static $this_plugin;
			if ( ! $this_plugin ) {
				$this_plugin = plugin_basename( __FILE__ );
			}

			if ( $file === $this_plugin ) {
				$new_links = array( '<a href="https://printfriendly.freshdesk.com/support/solutions/folders/69000070847" target="_new">' . __( 'Documentation', 'printfriendly' ) . '</a>' );
				$links = array_merge( $links, $new_links );
			}
			return $links;
		}

		/**
		 * Set default values for the plugin. If old, as in pre 1.0, settings are there, use them and then delete them.
		 *
		 * @since 3.0
		 */
		function set_defaults() {
			// Set some defaults
			$this->options = array(
				'button_type' => 'buttons/printfriendly-pdf-button.png',
				'content_position' => 'left',
				'content_placement' => 'after',
				'custom_button_icon' => 'https://cdn.printfriendly.com/icons/printfriendly-icon-md.png',
				'custom_button_text' => 'custom-text',
				'custom_image' => '',
				'custom_text' => 'PrintFriendly',
				'enable_css' => 'on',
				'margin_top' => '12',
				'margin_right' => '12',
				'margin_bottom' => '12',
				'margin_left' => '12',
				'show_on_posts' => 'on',
				'show_on_pages' => 'on',
				'text_color' => '#3AAA11',
				'text_size' => 14,
				'logo' => 'favicon',
				'image_url' => '',
				'tagline' => '',
				'click_to_delete' => '0', // 0 - allow, 1 - do not allow
				'hide-images' => '0', // 0 - show images, 1 - hide images
				'image-style' => 'right', // 'right', 'left', 'none', 'block'
				'email' => '0', // 0 - allow, 1 - do not allow
				'pdf' => '0', // 0 - allow, 1 - do not allow
				'print' => '0', // 0 - allow, 1 - do not allow
				'password_protected' => 'no',
				'enable_google_analytics' => 'no',
				'enable_error_reporting' => 'yes',
				'pf_algo' => 'wp',
				'images-size' => 'full-size',
			);

			// Check whether the old badly named singular options are there, if so, use the data and delete them.
			foreach ( array_keys( $this->options ) as $opt ) {
				$old_opt = get_option( 'pf_' . $opt );
				if ( $old_opt !== false ) {
					$this->options[ $opt ] = $old_opt;
					delete_option( 'pf_' . $opt );
				}
			}

			// This should always be set to the latest immediately when defaults are pushed in.
			$this->options['db_version'] = $this->db_version;

			update_option( $this->option_name, $this->options );
		}

		/**
		 * Upgrades the stored options, used to add new defaults if needed etc.
		 *
		 * @since 3.0
		 */
		function upgrade() {
			// update options to version 2
			if ( $this->options['db_version'] < 2 ) {

				$additional_options = array(
					'enable_css' => 'on',
					'logo' => 'favicon',
					'image_url' => '',
					'tagline' => '',
					'click_to_delete' => '0',
					'password_protected' => 'no',
				);

				// correcting badly named option
				if ( isset( $this->options['disable_css'] ) ) {
					$additional_options['enable_css'] = $this->options['disable_css'];
					unset( $this->options['disable_css'] );
				}

				// check whether image we do not list any more was used
				if ( in_array( $this->options['button_type'], array('button-print-whgn20.png', 'pf_button_sq_qry_m.png', 'pf_button_sq_qry_l.png', 'pf_button_sq_grn_m.png', 'pf_button_sq_grn_l.png'), true ) ) {
					// previous version had a bug with button name
					if ( in_array( $this->options['button_type'], array('pf_button_sq_qry_m.png', 'pf_button_sq_qry_l.png'), true ) ) {
						$this->options['button_type'] = str_replace( 'qry', 'gry', $this->options['button_type'] );
					}

					$image_address = 'https://cdn.printfriendly.com/' . $this->options['button_type'];
					$this->options['button_type'] = 'custom-image';
					$this->options['custom_text'] = '';
					$this->options['custom_image'] = $image_address;
				}

				$this->options = array_merge( $this->options, $additional_options );
			}

			// update options to version 3
			if ( $this->options['db_version'] < 3 ) {

				$old_show_on = $this->options['show_list'];
				// 'manual' setting
				$additional_options = array('custom_css_url' => '');

				if ( $old_show_on === 'all' ) {
					$additional_options = array(
						'show_on_pages' => 'on',
						'show_on_posts' => 'on',
						'show_on_homepage' => 'on',
						'show_on_categories' => 'on',
						'show_on_taxonomies' => 'on',
					);
				}

				if ( $old_show_on === 'single' ) {
					$additional_options = array(
						'show_on_pages' => 'on',
						'show_on_posts' => 'on',
					);
				}

				if ( $old_show_on === 'posts' ) {
					$additional_options = array(
						'show_on_posts' => 'on',
					);
				}

				unset( $this->options['show_list'] );

				$this->options = array_merge( $this->options, $additional_options );
			}

			// update options to version 4
			if ( $this->options['db_version'] < 4 ) {

				$additional_options = array(
					'email' => '0',
					'pdf' => '0',
					'print' => '0',
				);

				$this->options = array_merge( $this->options, $additional_options );
			}

			// update options to version 6
			// Replacement for db version 5 - should also be run for those already upgraded
			if ( $this->options['db_version'] < 6 ) {
				unset( $this->options['category_ids'] );
			}

			if ( $this->options['db_version'] < 7 ) {
				$additional_options = array(
					'hide-images' => '0',
					'image-style' => 'right',
				);

				$this->options = array_merge( $this->options, $additional_options );
			}

			if ( $this->options['db_version'] < 8 ) {
				$this->options['enable_google_analytics'] = 'no';
			}

			if ( $this->options['db_version'] < 9 ) {
				$this->options['pf_algo'] = 'wp';
			}

			if ( $this->options['db_version'] < 10 ) {
				$this->options['enable_error_reporting'] = 'yes';
			}

			if ( $this->options['db_version'] < 11 ) {
				if ( ! isset( $this->options['custom_css_url'] ) ) {
					$this->options['custom_css_url'] = '';
				}
			}

			if ( $this->options['db_version'] < 12 ) {
				// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				$this->options['images-size'] = $this->options['hide-images'] == '1' ? 'remove-images' : 'full-size';
			}

			if ( $this->options['db_version'] < 13 ) {
				switch ( $this->options['button_type'] ) {
					case 'pf-button.gif':
						$this->options['button_type'] = 'buttons/printfriendly-button.png';
						break;
					case 'pf-button-both.gif':
						$this->options['button_type'] = 'buttons/printfriendly-pdf-button.png';
						break;
					case 'pf-button-big.gif':
						$this->options['button_type'] = 'buttons/printfriendly-button-lg.png';
						break;
					case 'pf-button-print-pdf-mail.png':
						$this->options['button_type'] = 'buttons/printfriendly-pdf-email-button-notext.png';
						break;
					case 'button-print-grnw20.png':
						$this->options['button_type'] = 'buttons/print-button.png';
						break;
					case 'button-print-blu20.png':
						$this->options['button_type'] = 'buttons/print-button-nobg.png';
						break;
					case 'button-print-gry20.png':
						$this->options['button_type'] = 'buttons/print-button-gray.png';
						break;
					case 'pf-icon-small.gif':
					case 'pf-icon.gif':
						$this->options['button_type'] = 'custom-button';
						$this->options['custom_button_icon'] = 'icons/printfriendly-icon-md.png';
						$this->options['custom_button_text'] = 'custom-text';
						$this->options['custom_image'] = 'icons/printfriendly-icon-md.png';
						break;
					case 'text-only':
						$this->options['button_type'] = 'custom-button';
						$this->options['custom_button_icon'] = 'no-image';
						$this->options['custom_button_text'] = 'custom-text';
						break;
				}
			}

			if ( $this->options['db_version'] < 14 ) {
				if ( $this->options['button_type'] === 'pf-icon-both.gif' ) {
					$this->options['button_type'] = 'buttons/printfriendly-pdf-button-nobg.png';
				}
			}

			if ( $this->options['db_version'] < 15 ) {
				if ( $this->options['button_type'] === 'custom-image' ) {
					$this->options['button_type'] = 'custom-button';

					switch ( $this->options['custom_image'] ) {
						case 'icons/printfriendly-icon-sm.png':
						case 'icons/printfriendly-icon-md.png':
						case 'icons/printfriendly-icon-lg.png':
							$this->options['custom_button_icon'] = $this->options['custom_image'];
							break;
						case '':
							$this->options['custom_button_icon'] = 'no-image';
							break;
						default:
							$this->options['custom_button_icon'] = 'custom-image';
					}

					if ( $this->options['custom_text'] === '' ) {
						$this->options['custom_button_text'] = 'no-text';
					} else {
						$this->options['custom_button_text'] = 'custom-text';
					}
				}
			}

			if ( $this->options['db_version'] < 16 ) {
				if ( $this->options['custom_button_icon'] === 'icons/printfriendly-icon-md.png' ) {
					$this->options['custom_button_icon'] = 'https://cdn.printfriendly.com/icons/printfriendly-icon-md.png';
				}
			}

			if ( $this->options['db_version'] < 17 ) {
				$this->options['pro_email'] = get_bloginfo( 'admin_email' );

				$url = get_bloginfo( 'url' );
				$parsed_url = parse_url( $url );
				$this->options['pro_domain'] = $parsed_url['host'];
			}

			$this->options['db_version'] = $this->db_version;

			update_option( $this->option_name, $this->options );
		}

		/**
		 * Displays radio button in the admin area
		 *
		 * @since 3.0
		 * @param string  $name the name of the radio button to generate.
		 * @param boolean $br whether or not to add an HTML <br> tag, defaults to true.
		 * @param boolean $value if this is null, will have the same value as $name.
		 */
		function radio( $name, $br = false, $value = null ) {
			if ( is_null( $value ) ) {
				$value = $name;
			}

			$var = '<input id="' . $name . '" class="radio" name="' . $this->option_name . '[button_type]" type="radio" value="' . $value . '" ' . $this->checked( 'button_type', $value, false ) . '/>';
			$button = $this->button( $name );
			if ( ! empty( $button ) ) {
				echo '<label for="' . $name . '">' . $var . $button . '</label>';
			} else {
				echo $var;
			}

			if ( $br ) {
				echo '<br>';
			}
		}

		/**
		 * Displays radio button in the admin area
		 *
		 * @since 3.12.0
		 * @param string $value the value of the radio button to generate.
		 */
		function radio_custom_image( $value ) {
			?>
	  <label class="radio-custom-btn">
		<input type="radio" name="<?php echo $this->option_name; ?>[custom_button_icon]" value="<?php echo $value; ?>" <?php $this->checked( 'custom_button_icon', $value ); ?>>
		<img src="<?php echo $value; ?>" alt="Print Friendly, PDF & Email" style="display: inline; vertical-align: text-bottom; margin-right: 6px;" />
	  </label>
			<?php
		}

		/**
		 * Displays button image in the admin area
		 *
		 * @since 3.0
		 * @param string $name the name of the button to generate.
		 */
		function button( $name = false ) {
			if ( ! $name ) {
				$name = $this->options['button_type'];
			}

			$button_css  = 'border:none;-webkit-box-shadow:none; -moz-box-shadow: none; box-shadow:none; padding:0; margin:0';

			$img_path = 'https://cdn.printfriendly.com/';

			$return = '';

			if ( $name === 'custom-button' ) {
				if ( $this->options['custom_button_icon'] === 'custom-image' && '' !== trim( $this->options['custom_image'] ) ) {
					$return = '<img src="' . esc_url( $this->options['custom_image'] ) . '" alt="Print Friendly, PDF & Email" style="display: inline; vertical-align:text-bottom; margin:0; padding:0; border:none; -webkit-box-shadow:none; -moz-box-shadow:none; box-shadow: none;" />';
				} elseif ( $this->options['custom_button_icon'] !== 'no-image' ) {
					$return = '<img src="' . esc_url( $this->options['custom_button_icon'] ) . '" alt="Print Friendly, PDF & Email" style="display:inline; vertical-align:text-bottom; margin: 0 6px 0 0; padding:0; border:none; -webkit-box-shadow:none; -moz-box-shadow:none; box-shadow:none;" />';
				}

				/* esc_html was removerd to support custom html, CSRF prevents from attack by using this field */
				if ( $this->options['custom_button_text'] === 'custom-text' ) {
					$return .= $this->options['custom_text'];
				}

				return $return;
			} elseif ( $name === 'custom-btn' ) {
				return __( 'Custom Button', 'printfriendly' );
			} else {
				return '<img style="' . $button_css . '" src="' . $img_path . $name . '" alt="Print Friendly, PDF & Email" />';
			}
		}

		/**
		 * Convenience function to output a value custom button preview elements
		 *
		 * @since 3.0.9
		 */
		function custom_button_preview() {
			$img = $url = $button_text = $style = '';
			switch ( $this->options['custom_button_icon'] ) {
				case 'no-image':
					break;
				case 'custom-image':
					$url = $this->options['custom_image'];
					break;
				default:
					$url = $this->options['custom_button_icon'];
					break;
			}

			if ( ! empty( $url ) ) {
				$img = sprintf( '<img src="%s" alt="Print Friendly, PDF & Email">', esc_url( $url ) );
			}

			if ( $this->options['custom_button_text'] !== 'no-text' ) {
				$button_text = $this->options['custom_text'];
			}

			if ( '' !== $this->options['text_color'] ) {
				$style = 'color: ' . $this->options['text_color'] . ';';
			}

			$button_preview = sprintf( '<span><span id="pf-custom-button-preview" style="display:inline; vertical-align:text-bottom; margin: 0 6px 0 0; padding:0; border:none; -webkit-box-shadow:none; -moz-box-shadow:none; box-shadow:none;">%s</span><span id="printfriendly-text2" style="%s">%s</span></span>', $img, $style, esc_html( $button_text ) );

			echo $button_preview;
		}

		/**
		 * Convenience function to output a value for an input
		 *
		 * @since 3.0
		 * @param string $val value to check.
		 */
		function val( $val, $echo = true ) {
			$value = '';
			if ( isset( $this->options[ $val ] ) ) {
				$value = esc_attr( $this->options[ $val ] );
			}

			if ( $echo ) {
				echo $value;
			}
			return $value;
		}


		/**
		 * Like the WordPress checked() function but it doesn't throw notices when the array key isn't set and uses the plugins options array.
		 *
		 * @since 3.0
		 * @param mixed   $val value to check.
		 * @param mixed   $check_against value to check against.
		 * @param boolean $echo whether or not to echo the output.
		 * @return string checked, when true, empty, when false.
		 */
		function checked( $val, $check_against = true, $echo = true ) {
			if ( ! isset( $this->options[ $val ] ) ) {
				return;
			}

			// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			if ( $this->options[ $val ] == $check_against ) {
				if ( $echo ) {
					echo ' checked="checked" ';
				} else {
					return ' checked="checked" ';
				}
			}
		}

		/**
		 * Helper for creating checkboxes.
		 *
		 * @since 3.1.5
		 * @param string $name string used for various parts of checkbox.
		 */
		function create_checkbox( $name, $label = '', $labelid = '' ) {
			// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
			$label = ( ! empty( $label ) ? $label : __( ucfirst( $name ), 'printfriendly' ) );
			echo '<label' . ( ! empty( $labelid ) ? ' id=' . $labelid : '' ) . '><input type="checkbox" class="show_list" name="' . $this->option_name . '[show_on_' . $name . ']" value="on" ';
			$this->checked( 'show_on_' . $name, 'on' );
			echo ' />' . $label . "</label>\r\n";
		}


		/**
		 * Helper that checks if any of the content types is checked to display pf button.
		 *
		 * @since 3.1.5
		 * @return boolean true if none of the content types is checked
		 */
		function is_manual() {
			return ! ( isset( $this->options['show_on_posts'] ) ||
			isset( $this->options['show_on_pages'] ) ||
			isset( $this->options['show_on_homepage'] ) ||
			isset( $this->options['show_on_categories'] ) ||
			// (isset($this->options['category_ids']) && count($this->options['category_ids']) > 0) ||
			isset( $this->options['show_on_taxonomies'] ) );
		}


		/**
		 * Like the WordPress selected() function but it doesn't throw notices when the array key isn't set and uses the plugins options array.
		 *
		 * @since 3.0.9
		 * @param mixed $val value to check.
		 * @param mixed $check_against value to check against.
		 * @return string checked, when true, empty, when false.
		 */
		function selected( $val, $check_against = true ) {
			if ( ! isset( $this->options[ $val ] ) ) {
				return;
			}

			return selected( $this->options[ $val ], $check_against );
		}

		/**
		 * For use with page metabox.
		 *
		 * @since 3.2.2
		 */
		function get_page_post_type() {
			$post_types = get_post_types( array('name' => 'page'), 'object' );
			// echo '<pre>'.print_r($post_types,1).'</pre>';
			// die;

			return $post_types['page'];
		}


		/**
		 * Helper that checks if wp versions is above 3.0.
		 *
		 * @since 3.2.2
		 * @return boolean true wp version is above 3.0
		 */
		function wp_version_gt30() {
			global $wp_version;
			return version_compare( $wp_version, '3.0', '>=' );
		}


		/**
		 * Create box for picking individual categories.
		 *
		 * @since 3.2.2
		 */
		function create_category_metabox() {
			$obj = new stdClass();
			$obj->ID = 0;
			do_meta_boxes( 'settings_page_' . $this->hook, 'normal', $obj );
		}


		/**
		 * Load metaboxes advanced button display settings.
		 *
		 * @since 3.2.2
		 */
		function on_load_printfriendly() {
			global $wp_version;
			if ( $this->wp_version_gt30() ) {
				// require_once(dirname(__FILE__).'/includes/meta-boxes.php');
				// require_once(dirname(__FILE__).''includes/nav-menu.php');
				wp_enqueue_script( 'post' );

				add_meta_box( 'categorydiv', __( 'Only display when post is in:', 'printfriendly' ), 'post_categories_meta_box', 'settings_page_' . $this->hook, 'normal', 'core' );
			}
		}

		/**
		 * Returns if the user is a pro user.
		 */
		function is_pro( $feature = null ) {
			$licensed = $this->val( 'license_status', false ) === 'pro';

			switch ( $feature ) {
				case 'custom-css':
					// custom css needs to be available for all irrespective of the license.
					return true;
			}

			return $licensed;
		}

		/**
		 * Returns the custom css url.
		 */
		function get_custom_css() {
			// don't throw a PHP notice if custom_css_url is not defined.
			$css_url = isset( $this->options['custom_css_url'] ) ? $this->options['custom_css_url'] : '';
			if ( ! $this->is_pro( 'custom-css' ) ) {
				return $css_url;
			}

			// upgrading from a version that was using urls instead of the textarea?
			if ( ! empty( $css_url ) ) {
				return $css_url;
			}

			// don't throw a PHP notice if custom_css_url_pro is not defined.
			$css_url = isset( $this->options['custom_css_url_pro'] ) ? $this->options['custom_css_url_pro'] : '';
			if ( empty( $css_url ) ) {
				return null;
			}

			$dirs = wp_get_upload_dir();

			return $dirs['baseurl'] . '/' . $this->options['custom_css_url_pro'];
		}

		/**
		 * Generates the custom css file from the CSS block.
		 */
		function maybe_generate_custom_css_file( $css ) {
			$custom_css_old = html_entity_decode( $this->val( 'custom_css', false ) );

			// generate a new file if the CSS has changed.
			if ( $custom_css_old === $css ) {
				return false;
			}

			$dirs = wp_get_upload_dir();

			// delete old file, if it exists
			$file = $this->options['custom_css_url_pro'];
			if ( ! empty( $file ) ) {
				wp_delete_file( $dirs['basedir'] . '/' . $this->options['custom_css_url_pro'] );
			}

			// add a comment so that users know whence this file came.
			$date = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
			$comment = sprintf( '/* DO NOT EDIT - FILE AUTO-GENERATED BY PRINTFRIENDLY v%s ON %s */', $this->plugin_version, $date );
			$css = $comment . PHP_EOL . PHP_EOL . $css;

			// create new file, suffixed with the current time.
			$file = sprintf( '%s_%s.css', $this->hook, time() );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			WP_Filesystem();
			global $wp_filesystem;
			$wp_filesystem->put_contents(
				$dirs['basedir'] . '/' . $file,
				$css,
				FS_CHMOD_FILE
			);

			// return the new file name
			return $file;
		}

		/**
		 * If upgrading from a previous version that was using urls instead of the textarea
		 * it will return an appropriate message for the user.
		 */
		function get_custom_css_upgrade_message() {
			// upgrading from a version that was using urls instead of the textarea?
			if ( isset( $this->options['custom_css_url'] ) && ! empty( $this->options['custom_css_url'] ) ) {
				$css_url = $this->options['custom_css_url'];
				return sprintf( __( 'You are currently using %1$s%2$s%3$s. You can copy copy its contents into the textbox if you want to update the styles.', 'printfriendly' ), '<a href="' . $css_url . '" target="_blank">', $css_url, '</a>' );
			}

			return null;
		}

		/**
		 * Output the config page
		 *
		 * @since 3.0
		 */
		function config_page() {

			// Since WP 3.2 outputs these errors by default, only display them when we're on versions older than 3.2 that do support the settings errors.
			global $wp_version;
			if ( version_compare( $wp_version, '3.2', '<' ) && $this->wp_version_gt30() ) {
				settings_errors();
			}

			include_once PRINTFRIENDLY_BASEPATH . '/views/settings.php';
		}

		/**
		 * Returns the current tab to activate.
		 *
		 * @since 4.0.1
		 */
		function is_tab( $tab_id ) {
			$tab = get_transient( 'pf-tab' );
			// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			return empty( $tab ) && $tab_id == 0 ? true : $tab == $tab_id;
		}

	}
	$printfriendly = new PrintFriendly_WordPress();
}

// Add shortcode for printfriendly button
add_shortcode( 'printfriendly', 'pf_show_link' );

/**
 * Convenience function for use in templates.
 *
 * @since 3.0
 * @return string returns a button to be printed.
 */
function pf_show_link() {
	global $printfriendly;
	return $printfriendly->getButton( true );
}

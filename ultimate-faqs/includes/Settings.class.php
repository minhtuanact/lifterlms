<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdufaqSettings' ) ) {
/**
 * Class to handle configurable settings for Ultimate FAQs
 * @since 2.0.0
 */
class ewdufaqSettings {

	/**
	 * Default values for settings
	 * @since 2.0.0
	 */
	public $defaults = array();

	/**
	 * Stored values for settings
	 * @since 2.0.0
	 */
	public $settings = array();

	public function __construct() {

		add_action( 'init', array( $this, 'set_defaults' ) );

		add_action( 'init', array( $this, 'load_settings_panel' ), 11 );
	}

	/**
	 * Load the plugin's default settings
	 * @since 2.0.0
	 */
	public function set_defaults() {

		$this->defaults = array(

			'include-permalink'				=> 'none',
			'permalink-type'				=> 'same_page',
			'access-role'					=> 'manage_options',

			'display-style'					=> 'default',
			'slug-base'						=> 'ufaqs',
			'number-of-columns'				=> 'one',
			'faqs-per-page'					=> 100000,
			'page-type'						=> 'distinct',
			'wpforms-faq-location'			=> 'above',

			'category-order'				=> 'asc',
			'faq-order-by'					=> 'title',
			'faq-order'						=> 'asc',

			'faq-fields'					=> array(),

			'styling-toggle-symbol'			=> 'A',
			'styling-category-heading-type'	=> 'h3',
			'styling-faq-heading-type'		=> 'h4',

			'faq-elements-order'			=> json_encode( 
				array(
					'categories'					=> 'Categories',
					'body'							=> 'Body',
					'author_date'					=> 'Author/Date',
					'custom_fields'					=> 'Custom Fields',
					'tags'							=> 'Tags',
					'ratings'						=> 'Ratings',
					'social_media'					=> 'Social Media',
					'permalink'						=> 'Permalink',
					'comments'						=> 'Comments',
					'back_to_top'					=> 'Back to Top',
				) 
			),

			'label-retrieving-results'		=> __( 'Retrieving Results', 'ultimate-faqs' ),
			'label-no-results-found'		=> __( 'No result FAQ\'s contained the term \'%s\'', 'ultimate-faqs' ),
			'label-woocommerce-tab'			=> __( 'FAQs', 'ultimate-faqs' ),
			'label-thank-you-submit'		=> __( 'Thank you for submitting an FAQ.', 'ultimate-faqs' ),
		);

		$this->defaults = apply_filters( 'ewd_ufaq_defaults', $this->defaults );
	}

	/**
	 * Get a setting's value or fallback to a default if one exists
	 * @since 2.0.0
	 */
	public function get_setting( $setting ) { 

		if ( empty( $this->settings ) ) {
			$this->settings = get_option( 'ewd-ufaq-settings' );
		}
		
		if ( ! empty( $this->settings[ $setting ] ) ) {
			return apply_filters( 'ewd-ufaq-settings-' . $setting, $this->settings[ $setting ] );
		}

		if ( ! empty( $this->defaults[ $setting ] ) ) { 
			return apply_filters( 'ewd-ufaq-settings-' . $setting, $this->defaults[ $setting ] );
		}

		return apply_filters( 'ewd-ufaq-settings-' . $setting, null );
	}

	/**
	 * Set a setting to a particular value
	 * @since 2.0.0
	 */
	public function set_setting( $setting, $value ) {

		$this->settings[ $setting ] = $value;
	}

	/**
	 * Save all settings, to be used with set_setting
	 * @since 2.0.0
	 */
	public function save_settings() {
		
		update_option( 'ewd-ufaq-settings', $this->settings );
	}

	/**
	 * Load the admin settings page
	 * @since 2.0.0
	 * @sa https://github.com/NateWr/simple-admin-pages
	 */
	public function load_settings_panel() {

		global $ewd_ufaq_controller;
	
		require_once( EWD_UFAQ_PLUGIN_DIR . '/lib/simple-admin-pages/simple-admin-pages.php' );
		$sap = sap_initialize_library(
			$args = array(
				'version'       => '2.5.0',
				'lib_url'       => EWD_UFAQ_PLUGIN_URL . '/lib/simple-admin-pages/',
			)
		);
		
		$sap->add_page(
			'submenu',
			array(
				'id'            => 'ewd-ufaq-settings',
				'title'         => __( 'Settings', 'ultimate-faqs' ),
				'menu_title'    => __( 'Settings', 'ultimate-faqs' ),
				'parent_menu'	=> 'edit.php?post_type=ufaq',
				'description'   => '',
				'capability'    => $this->get_setting( 'access-role' ),
				'default_tab'   => 'ewd-ufaq-basic-tab',
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array(
				'id'            => 'ewd-ufaq-basic-tab',
				'title'         => __( 'Basic', 'ultimate-faqs' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array(
				'id'            => 'ewd-ufaq-general',
				'title'         => __( 'General', 'ultimate-faqs' ),
				'tab'	        => 'ewd-ufaq-basic-tab',
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-general',
			'warningtip',
			array(
				'id'			=> 'shortcodes-reminder',
				'title'			=> __( 'REMINDER:', 'ultimate-faqs' ),
				'placeholder'	=> __( 'To display FAQs, place the [ultimate-faqs] shortcode on a page' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-general',
			'textarea',
			array(
				'id'			=> 'custom-css',
				'title'			=> __( 'Custom CSS', 'ultimate-faqs' ),
				'description'	=> __( 'You can add custom CSS styles to your FAQs in the box above.', 'ultimate-faqs' ),			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-general',
			'toggle',
			array(
				'id'			=> 'scroll-to-top',
				'title'			=> __( 'Scroll To Top', 'ultimate-faqs' ),
				'description'	=> __( 'Should the browser scroll to the top of the FAQ when it\'s opened?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-general',
			'toggle',
			array(
				'id'			=> 'comments-on',
				'title'			=> __( 'Turn On Comment Support', 'ultimate-faqs' ),
				'description'	=> __( 'Should comment support be turned on, so that if the "Allow Comments" checkbox is selected for a given FAQ, comments are shown in the FAQ list?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-general',
			'toggle',
			array(
				'id'			=> 'disable-microdata',
				'title'			=> __( 'Disable Microdata', 'ultimate-faqs' ),
				'description'	=> __( 'By default, the plugin adds FAQ page scheme when the shortcode is used. Select this option to disable this behaviour.', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-general',
			'radio',
			array(
				'id'			=> 'include-permalink',
				'title'			=> __( 'Include Permalink', 'ultimate-faqs' ),
				'description'	=> __( 'Display permalink to each question? If so, text, icon or both?', 'ultimate-faqs' ),
				'options'		=> array(
					'none'			=> __( 'None', 'ultimate-faqs' ),
					'text'			=> __( 'Text', 'ultimate-faqs' ),
					'icon'			=> __( 'Icon', 'ultimate-faqs' ),
					'both'			=> __( 'Both', 'ultimate-faqs' ),
				),
				'default'		=> $this->defaults['include-permalink']
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-general',
			'radio',
			array(
				'id'			=> 'permalink-type',
				'title'			=> __( 'Permalink Destination', 'ultimate-faqs' ),
				'description'	=> __( 'Should the permalink link to the main FAQ page or the individual FAQ page?', 'ultimate-faqs' ),
				'options'		=> array(
					'same_page'			=> __( 'Main FAQ Page', 'ultimate-faqs' ),
					'individual_page'	=> __( 'Individual FAQ Page', 'ultimate-faqs' ),
				),
				'default'		=> $this->defaults['permalink-type']
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-general',
			'select',
			array(
				'id'            => 'access-role',
				'title'         => __( 'Set Access Role', 'ultimate-faqs' ),
				'description'   => __( 'Which level of user should have access to FAQs, Settings, etc.?.', 'ultimate-faqs' ), 
				'blank_option'	=> false,
				'options'       => array(
					'administrator'				=> __( 'Administrator', 'ultimate-faqs' ),
					'delete_others_pages'		=> __( 'Editor', 'ultimate-faqs' ),
					'delete_published_posts'	=> __( 'Author', 'ultimate-faqs' ),
					'delete_posts'				=> __( 'Contributor', 'ultimate-faqs' ),
				)
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array(
				'id'            => 'ewd-ufaq-functionality',
				'title'         => __( 'Functionality', 'ultimate-faqs' ),
				'tab'	        => 'ewd-ufaq-basic-tab',
			)
		);
		
		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-functionality',
			'toggle',
			array(
				'id'			=> 'disable-faq-toggle',
				'title'			=> __( 'Disable FAQ Toggle', 'ultimate-faqs' ),
				'description'	=> __( 'Should the FAQs open on a separate page when clicked, instead of opening and closing?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-functionality',
			'toggle',
			array(
				'id'			=> 'faq-accordion',
				'title'			=> __( 'FAQ Accordion', 'ultimate-faqs' ),
				'description'	=> __( 'Should the FAQs accordion? (Only one FAQ is open at a time, requires FAQ Toggle)', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-functionality',
			'toggle',
			array(
				'id'			=> 'faq-category-toggle',
				'title'			=> __( 'FAQ Category Toggle', 'ultimate-faqs' ),
				'description'	=> __( 'Should the FAQ categories hide/open when they are clicked, if FAQs are being grouped by category ("Group FAQs by Category" in the "Ordering" area)?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-functionality',
			'toggle',
			array(
				'id'			=> 'faq-category-accordion',
				'title'			=> __( 'FAQ Category Accordion', 'ultimate-faqs' ),
				'description'	=> __( 'Should it only be possible to open one FAQ category at a time, if FAQ categories are being toggled ("FAQ Category Toggle" must be enabled above)?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-functionality',
			'toggle',
			array(
				'id'			=> 'expand-collapse-all',
				'title'			=> __( 'FAQ Expand/Collapse All', 'ultimate-faqs' ),
				'description'	=> __( 'Should there be a control to open and close all FAQs simultaneously?', 'ultimate-faqs' )
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array(
				'id'            => 'ewd-ufaq-display',
				'title'         => __( 'Display', 'ultimate-faqs' ),
				'tab'	        => 'ewd-ufaq-basic-tab',
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-display',
			'toggle',
			array(
				'id'			=> 'hide-categories',
				'title'			=> __( 'Hide Categories', 'ultimate-faqs' ),
				'description'	=> __( 'Should the categories for each FAQ be hidden?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-display',
			'toggle',
			array(
				'id'			=> 'hide-tags',
				'title'			=> __( 'Hide Tags', 'ultimate-faqs' ),
				'description'	=> __( 'Should the tags for each FAQ be hidden?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-display',
			'toggle',
			array(
				'id'			=> 'display-all-answers',
				'title'			=> __( 'Display All Answers', 'ultimate-faqs' ),
				'description'	=> __( 'Should all answers be displayed when the page loads? (Careful if FAQ Accordion is on)', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-display',
			'toggle',
			array(
				'id'			=> 'display-author',
				'title'			=> __( 'Display Post Author', 'ultimate-faqs' ),
				'description'	=> __( 'Should the display name of the post\'s author be displayed beneath the FAQ title?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-display',
			'toggle',
			array(
				'id'			=> 'display-date',
				'title'			=> __( 'Display Post Date', 'ultimate-faqs' ),
				'description'	=> __( 'Should the date the post was created be displayed beneath the FAQ title?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-display',
			'toggle',
			array(
				'id'			=> 'display-back-to-top',
				'title'			=> __( 'Display \'Back to Top\'', 'ultimate-faqs' ),
				'description'	=> __( 'Should a link to return to the top of the page be added to each FAQ post?', 'ultimate-faqs' )
			)
		);

		if ( ! $ewd_ufaq_controller->permissions->check_permission( 'premium' ) ) {
			$ewd_ufaq_premium_permissions = array(
				'disabled'		=> true,
				'disabled_image'=> '#',
				'purchase_link'	=> 'https://www.etoilewebdesign.com/plugins/ultimate-faq/'
			);
		}
		else { $ewd_ufaq_premium_permissions = array(); }

		$sap->add_section(
			'ewd-ufaq-settings',
			array(
				'id'            => 'ewd-ufaq-premium-tab',
				'title'         => __( 'Premium', 'ultimate-faqs' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array_merge(
				array(
					'id'            => 'ewd-ufaq-premium-display',
					'title'         => __( 'Display', 'ultimate-faqs' ),
					'tab'	        => 'ewd-ufaq-premium-tab',
				),
				$ewd_ufaq_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-display',
			'radio',
			array(
				'id'			=> 'display-style',
				'title'			=> __( 'FAQ Display Style', 'ultimate-faqs' ),
				'description'	=> __( 'Which theme should be used to display the FAQ\'s?', 'ultimate-faqs' ),
				'options'		=> array(
					'default'		=> __( 'Default', 'ultimate-faqs' ),
					'block'			=> __( 'Block', 'ultimate-faqs' ),
					'border_block'	=> __( 'Border Block', 'ultimate-faqs' ),
					'contemporary'	=> __( 'Contemporary', 'ultimate-faqs' ),
					'list'			=> __( 'List', 'ultimate-faqs' ),
					'minimalist'	=> __( 'Minimalist', 'ultimate-faqs' ),
				),
				'default'		=> $this->defaults['display-style']
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-display',
			'radio',
			array(
				'id'			=> 'number-of-columns',
				'title'			=> __( 'Number of Columns', 'ultimate-faqs' ),
				'description'	=> __( 'In how many columns would you like your FAQs displayed?', 'ultimate-faqs' ),
				'options'		=> array(
					'one'			=> __( 'One', 'ultimate-faqs' ),
					'two'			=> __( 'Two', 'ultimate-faqs' ),
					'three'			=> __( 'Three', 'ultimate-faqs' ),
					'four'			=> __( 'Four', 'ultimate-faqs' ),
				),
				'default'		=> $this->defaults['number-of-columns']
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-display',
			'toggle',
			array(
				'id'			=> 'responsive-columns',
				'title'			=> __( 'Responsive Columns', 'ultimate-faqs' ),
				'description'	=> __( 'If you have more than one column, would you like them to be responsive? If this option is disabled, the number of columns will remain the same on all screen sizes.', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-display',
			'select',
			array(
				'id'            => 'reveal-effect',
				'title'         => __( 'Reveal Effect', 'ultimate-faqs' ),
				'description'   => __( 'How should FAQ\'s be displayed when their titles are clicked?', 'ultimate-faqs' ), 
				'blank_option'	=> false,
				'options'       => array(
					'none'				=> __( 'None', 'ultimate-faqs' ),
					'blind'				=> __( 'Blind', 'ultimate-faqs' ),
					'bounce'			=> __( 'Bounce', 'ultimate-faqs' ),
					'clip'				=> __( 'Clip', 'ultimate-faqs' ),
					'drop'				=> __( 'Drop', 'ultimate-faqs' ),
					'explode'			=> __( 'Explode', 'ultimate-faqs' ),
					'fade'				=> __( 'Fade', 'ultimate-faqs' ),
					'fold'				=> __( 'Fold', 'ultimate-faqs' ),
					'highlight'			=> __( 'Highlight', 'ultimate-faqs' ),
					'puff'				=> __( 'Puff', 'ultimate-faqs' ),
					'pulsate'			=> __( 'Pulsate', 'ultimate-faqs' ),
					'shake'				=> __( 'Shake', 'ultimate-faqs' ),
					'size'				=> __( 'Size', 'ultimate-faqs' ),
					'slide'				=> __( 'Slide', 'ultimate-faqs' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-display',
			'text',
			array(
				'id'            => 'faqs-per-page',
				'title'         => __( 'FAQs Per Page', 'ultimate-faqs' ),
				'description'	=> __( 'How many FAQs should be displayed on each page? (Leave blank to display all FAQs)', 'ultimate-faqs' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-display',
			'radio',
			array(
				'id'			=> 'page-type',
				'title'			=> __( 'FAQ Page Type', 'ultimate-faqs' ),
				'description'	=> __( 'If FAQs are in pages, how should pages load?', 'ultimate-faqs' ),
				'options'		=> array(
					'distinct'			=> __( 'Distinct Pages', 'ultimate-faqs' ),
					'load_more'			=> __( 'Load More Button', 'ultimate-faqs' ),
					'infinite_scroll'	=> __( 'Infinite Scroll', 'ultimate-faqs' )
				),
				'default'		=> $this->defaults['page-type']
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array_merge(
				array(
					'id'            => 'ewd-ufaq-premium-general',
					'title'         => __( 'General', 'ultimate-faqs' ),
					'tab'	        => 'ewd-ufaq-premium-tab',
				),
				$ewd_ufaq_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-general',
			'toggle',
			array(
				'id'			=> 'faq-ratings',
				'title'			=> __( 'FAQ Ratings', 'ultimate-faqs' ),
				'description'	=> __( 'Should visitors be able to up or down vote FAQs to let others know if they found them helpful?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-general',
			'file-upload',
			array(
				'id'			=> 'thumbs-up-image',
				'title'			=> __( 'FAQ Ratings \'Thumbs Up\' Image', 'ultimate-faqs' ),
				'description'	=> __( 'You can use this to upload your own image to replace the default "thumbs up" image.', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-general',
			'file-upload',
			array(
				'id'			=> 'thumbs-down-image',
				'title'			=> __( 'FAQ Ratings \'Thumbs Down\' Image', 'ultimate-faqs' ),
				'description'	=> __( 'You can use this to upload your own image to replace the default "thumbs down" image.', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-general',
			'toggle',
			array(
				'id'			=> 'pretty-permalinks',
				'title'			=> __( 'Pretty Permalinks', 'ultimate-faqs' ),
				'description'	=> __( 'Should an SEO friendly permalink structure be used for the link to the FAQ?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-general',
			'toggle',
			array(
				'id'			=> 'disable-homepage-canoncial-redirect',
				'title'			=> __( 'Disable Front-Page Canonical Redirects', 'ultimate-faqs' ),
				'description'	=> __( 'Should canonical redirects be disabled for the front page of your site? Only necessary if you\'re using the plugin on your homepage and have pretty permalinks enabled.', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-general',
			'toggle',
			array(
				'id'			=> 'auto-complete-titles',
				'title'			=> __( 'FAQ Auto Complete Titles', 'ultimate-faqs' ),
				'description'	=> __( 'Should the FAQ Titles auto complete when using the FAQ search shortcode?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-general',
			'text',
			array(
				'id'            => 'slug-base',
				'title'         => __( 'FAQ Slug Base', 'ultimate-faqs' ),
				'description'	=> __( 'This option can be used to change the slug base for all FAQ posts. Be sure to go to "Settings" -> "Permalinks" in the WordPress sidebar and hit "Save Changes" to avoid 404 errors.', 'ultimate-faqs' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-premium-general',
			'checkbox',
			array(
				'id'			=> 'social-media',
				'title'			=> __( 'Social Media Options', 'ultimate-faqs' ),
				'description'	=> '',
				'options'		=> array(
					'facebook' 		=> 'Facebook',
					'twitter'		=> 'Twitter',
					'linkedin'		=> 'LinkedIn',
					'pinterest'		=> 'Pinterest',
					'email'			=> 'Email',
				)
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array_merge(
				array(
					'id'            => 'ewd-ufaq-woocommerce',
					'title'         => __( 'WooCommerce', 'ultimate-faqs' ),
					'tab'	        => 'ewd-ufaq-premium-tab',
				),
				$ewd_ufaq_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-woocommerce',
			'toggle',
			array(
				'id'			=> 'woocommerce-faqs',
				'title'			=> __( 'WooCommerce FAQs', 'ultimate-faqs' ),
				'description'	=> __( 'Should FAQs for a given product be displayed as an extra tab on the WooCommerce product page? For this to work correctly, an FAQ category needs to be created with the same name as a given WooCommerce product.', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-woocommerce',
			'toggle',
			array(
				'id'			=> 'woocommerce-use-product',
				'title'			=> __( 'Use WooCommerce Product Object', 'ultimate-faqs' ),
				'description'	=> __( 'Should the FAQ tab be set up using the WooCommerce product object, as in the WC documentation, or just using the ID of the page?', 'ultimate-faqs' )
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array_merge(
				array(
					'id'            => 'ewd-ufaq-wpforms-integration',
					'title'         => __( 'WP Forms', 'ultimate-faqs' ),
					'tab'	        => 'ewd-ufaq-premium-tab',
				),
				$ewd_ufaq_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-wpforms-integration',
			'toggle',
			array(
				'id'			=> 'wpforms-integration',
				'title'			=> __( 'WP Forms Integration', 'ultimate-faqs' ),
				'description'	=> __( 'Should FAQs be displayed when a visitor starts typing a message into a paragraph text field in WP Forms? This can be turned off for specific forms via the form\'s settings panel.', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-wpforms-integration',
			'text',
			array(
				'id'            => 'wpforms-post-count',
				'title'         => __( 'WP Forms Post Count', 'ultimate-faqs' ),
				'description'	=> __( 'How many posts should be displayed below paragraph text fields, if WP Forms Integration has been turned on?', 'ultimate-faqs' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-wpforms-integration',
			'radio',
			array(
				'id'			=> 'wpforms-faq-location',
				'title'			=> __( 'WP Forms FAQ Location', 'ultimate-faqs' ),
				'description'	=> __( 'If WP Forms Integration has been turned on, where should the FAQs be displayed?', 'ultimate-faqs' ),
				'options'		=> array(
					'above'			=> __( 'Above', 'ultimate-faqs' ),
					'below'			=> __( 'Below', 'ultimate-faqs' ),
				),
				'default'		=> $this->defaults['wpforms-faq-location']
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array_merge(
				array(
					'id'            => 'ewd-ufaq-submit-faq',
					'title'         => __( 'Submit FAQ', 'ultimate-faqs' ),
					'tab'	        => 'ewd-ufaq-premium-tab',
				),
				$ewd_ufaq_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-submit-faq',
			'toggle',
			array(
				'id'			=> 'allow-proposed-answer',
				'title'			=> __( 'Allow Proposed Answer', 'ultimate-faqs' ),
				'description'	=> __( 'When using the user-submitted question shortcode, should users be able to propose an answer to the question they\'re submitting?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-submit-faq',
			'toggle',
			array(
				'id'			=> 'submit-custom-fields',
				'title'			=> __( 'Submit Custom Fields', 'ultimate-faqs' ),
				'description'	=> __( 'When using the user-submitted question shortcode, should users be able to fill in custom fields for the question they\'re submitting? File type custom fields cannot be submitted for security reasons.', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-submit-faq',
			'toggle',
			array(
				'id'			=> 'submit-question-captcha',
				'title'			=> __( 'Submit Question Captcha', 'ultimate-faqs' ),
				'description'	=> __( 'When using the user-submitted question shortcode, should a captcha field be added to the form to reduce the volume of spam FAQs?', 'ultimate-faqs' )
			)
		);

		$args = array(
			'hide_empty'	=> false,
			'taxonomy'		=> EWD_UFAQ_FAQ_CATEGORY_TAXONOMY
		);

		$categories = get_terms( $args );

		$category_options = array();

		foreach ( $categories as $category ) {

			$category_options[ $category->slug ] = $category->name;
		}

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-submit-faq',
			'select',
			array(
				'id'            => 'submitted-default-category',
				'title'         => __( 'Default Category', 'ultimate-faqs' ),
				'description'   => __( 'Which category should submitted questions default to?', 'ultimate-faqs' ), 
				'blank_option'	=> true,
				'options'       => $category_options
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-submit-faq',
			'toggle',
			array(
				'id'			=> 'admin-question-notification',
				'title'			=> __( 'Admin Question Notification', 'ultimate-faqs' ),
				'description'	=> __( 'Should an email be sent to the site administrator when a question is submitted?', 'ultimate-faqs' )
			)
		);	

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-submit-faq',
			'text',
			array(
				'id'            => 'admin-notification-email',
				'title'         => __( 'Admin Notification Email', 'ultimate-faqs' ),
				'description'	=> __( 'What email address should the notifications be sent to if "Admin Question Notification" is enabled above? If blank, the default WordPress admin email will be used.', 'ultimate-faqs' ),
				'small'			=> true
			)
		);

		$emails = array();
		if ( post_type_exists( 'uwpm_mail_template' ) ) {

			$email_posts = get_posts( array( 'post_type' => 'uwpm_mail_template', 'posts_per_page' => -1 ) );
			foreach ( $email_posts as $email_post ) { $emails[$email_post->ID ] = $email_post->post_title; }
		}
		
		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-submit-faq',
			'select',
			array(
				'id'            => 'submit-faq-email',
				'title'         => __( 'FAQ Submitted Thank You E-mail', 'ultimate-faqs' ),
				'description'   => __( 'You can use the <a href="https://wordpress.org/plugins/ultimate-wp-mail/">Ultimate WP Mail plugin</a> to create a custom email that is sent whenever an faq is submitted.', 'ultimate-faqs' ), 
				'blank_option'	=> true,
				'options'       => $emails
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array_merge(
				array(
					'id'            => 'ewd-ufaq-faq-elements-order',
					'title'         => __( 'FAQ Elements Order', 'ultimate-faqs' ),
					'tab'	        => 'ewd-ufaq-premium-tab',
				),
				$ewd_ufaq_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-faq-elements-order',
			'ordering-table',
			array(
				'id'            => 'faq-elements-order',
				'title'         => __( 'FAQ Elements Order', 'ultimate-faqs' ),
				'description'   => __( 'You can use this table to drag-and-drop the elements of an FAQ into a different order.', 'ultimate-faqs' ), 
				'items'       	=> $this->get_setting( 'faq-elements-order' )
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array(
				'id'            => 'ewd-ufaq-ordering-tab',
				'title'         => __( 'Ordering', 'ultimate-faqs' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array(
				'id'            => 'ewd-ufaq-ordering-settings',
				'title'         => __( 'Settings', 'ultimate-faqs' ),
				'tab'	        => 'ewd-ufaq-ordering-tab',
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-ordering-settings',
			'toggle',
			array(
				'id'			=> 'group-by-category',
				'title'			=> __( 'Group FAQs by Category', 'ultimate-faqs' ),
				'description'	=> __( 'Should FAQs be grouped by category, or should all categories be mixed together?', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-ordering-settings',
			'toggle',
			array(
				'id'			=> 'group-by-category-count',
				'title'			=> __( 'Display FAQ Category Count', 'ultimate-faqs' ),
				'description'	=> __( 'If FAQs are grouped by category, should the number of FAQs in a category be displayed beside the category name?', 'ultimate-faqs' )
			)
		);	

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-ordering-settings',
			'select',
			array(
				'id'            => 'category-order-by',
				'title'         => __( 'Sort Categories', 'ultimate-faqs' ),
				'description'   => __( 'How should FAQ categories be ordered? (Only used if "Group FAQs by Category" above is enabled.)', 'ultimate-faqs' ), 
				'blank_option'	=> false,
				'options'       => array(
					'name'				=> __( 'Name', 'ultimate-faqs' ),
					'count'				=> __( 'FAQ Count', 'ultimate-faqs' ),
					'slug'				=> __( 'Slug', 'ultimate-faqs' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-ordering-settings',
			'radio',
			array(
				'id'			=> 'category-order',
				'title'			=> __( 'Sort Categories Ordering', 'ultimate-faqs' ),
				'description'	=> __( 'How should FAQ categories be ordered? (Only used if "Group FAQs by Category" above is enabled.)', 'ultimate-faqs' ),
				'options'		=> array(
					'asc'			=> __( 'Ascending', 'ultimate-faqs' ),
					'desc'			=> __( 'Descending', 'ultimate-faqs' ),
				),
				'default'		=> $this->defaults['category-order']
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-ordering-settings',
			'radio',
			array(
				'id'            => 'faq-order-by',
				'title'         => __( 'FAQ Ordering', 'ultimate-faqs' ),
				'description'   => __( 'How should individual FAQs be ordered?', 'ultimate-faqs' ), 
				'blank_option'	=> false,
				'options'       => array(
					'date'				=> __( 'Created Date', 'ultimate-faqs' ),
					'title'				=> __( 'Title', 'ultimate-faqs' ),
					'modified'			=> __( 'Modified Date', 'ultimate-faqs' ),
					'set_order'			=> __( 'Selected Order (Premium option)', 'ultimate-faqs' ),
				),
				'default'		=> $this->defaults['faq-order-by']
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-ordering-settings',
			'radio',
			array(
				'id'			=> 'faq-order',
				'title'			=> __( 'Sort FAQs Ordering', 'ultimate-faqs' ),
				'description'	=> __( 'Should FAQ be ascending or descending order, based on the ordering criteria above?', 'ultimate-faqs' ),
				'options'		=> array(
					'asc'			=> __( 'Ascending', 'ultimate-faqs' ),
					'desc'			=> __( 'Descending', 'ultimate-faqs' ),
				),
				'default'		=> $this->defaults['faq-order']
			)
		);

		if ( ! $ewd_ufaq_controller->permissions->check_permission( 'fields' ) ) {
			$ewd_ufaq_fields_permissions = array(
				'disabled'		=> true,
				'disabled_image'=> '#',
				'purchase_link'	=> 'https://www.etoilewebdesign.com/plugins/ultimate-faq/'
			);
		}
		else { $ewd_ufaq_fields_permissions = array(); }

		$sap->add_section(
			'ewd-ufaq-settings',
			array(
				'id'            => 'ewd-ufaq-fields-tab',
				'title'         => __( 'Fields', 'ultimate-faqs' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array_merge(
				array(
					'id'            => 'ewd-ufaq-fields-settings',
					'title'         => __( 'Settings', 'ultimate-faqs' ),
					'tab'	        => 'ewd-ufaq-fields-tab',
				),
				$ewd_ufaq_fields_permissions
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-fields-settings',
			'toggle',
			array(
				'id'			=> 'hide-blank-fields',
				'title'			=> __( 'Hide Blank Fields', 'ultimate-faqs' ),
				'description'	=> __( 'Should field labels be hidden if a field hasn\'t been filled out for a particular FAQ?', 'ultimate-faqs' )
			)
		);

		$fields_description = __( 'Should any extra fields be added to the FAQs?', 'ultimate-faqs' ) . '<br />';
		$fields_description .= __( 'The "Field Values" should be a comma-separated list of values for the select, radio or checkbox field types (no extra spaces after the comma).', 'ultimate-faqs' ) . '<br />';
		$fields_description .= __( 'For security reasons, file fields cannot be included in the submit FAQ form.', 'ultimate-faqs' ) . '<br />';

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-fields-settings',
			'infinite_table',
			array(
				'id'			=> 'faq-fields',
				'title'			=> __( 'FAQ Custom Fields', 'ultimate-faqs' ),
				'add_label'		=> __( '&plus; ADD', 'ultimate-faqs' ),
				'del_label'		=> __( 'Delete', 'ultimate-faqs' ),
				'description'	=> $fields_description,
				'fields'		=> array(
					'id' => array(
						'type' 		=> 'hidden',
						'label' 	=> 'Field ID',
						'required' 	=> true
					),
					'name' => array(
						'type' 		=> 'text',
						'label' 	=> 'Field Name',
						'required' 	=> true
					),
					'type' => array(
						'type' 		=> 'select',
						'label' 	=> __( 'Field Type', 'ultimate-faqs' ),
						'options' 	=> array(
							'text'		=> __( 'Text', 'ultimate-faqs' ),
							'textarea'	=> __( 'Text Area', 'ultimate-faqs' ),
							'select'	=> __( 'Select Box', 'ultimate-faqs' ),
							'radio'		=> __( 'Radio Buttons', 'ultimate-faqs' ),
							'checkbox'	=> __( 'Checkbox', 'ultimate-faqs' ),
							'file'		=> __( 'File', 'ultimate-faqs' ),
							'link'		=> __( 'Link', 'ultimate-faqs' ),
							'date'		=> __( 'Date', 'ultimate-faqs' ),
							'datetime'	=> __( 'Date/Time', 'ultimate-faqs' ),
						)
					),
					'options' => array(
						'type' 		=> 'text',
						'label' 	=> __( 'Field Values', 'ultimate-faqs' ),
						'required' 	=> false
					)
				)
			)
		);

		if ( ! $ewd_ufaq_controller->permissions->check_permission( 'labelling' ) ) {
			$ewd_ufaq_labelling_permissions = array(
				'disabled'		=> true,
				'disabled_image'=> '#',
				'purchase_link'	=> 'https://www.etoilewebdesign.com/plugins/ultimate-faq/'
			);
		}
		else { $ewd_ufaq_labelling_permissions = array(); }

		$sap->add_section(
			'ewd-ufaq-settings',
			array(
				'id'            => 'ewd-ufaq-labelling-tab',
				'title'         => __( 'Labelling', 'ultimate-faqs' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array_merge(
				array(
					'id'            => 'ewd-ufaq-labelling-faq-and-search',
					'title'         => __( 'FAQ Page and Search', 'ultimate-faqs' ),
					'tab'	        => 'ewd-ufaq-labelling-tab',
				),
				$ewd_ufaq_labelling_permissions
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-posted',
				'title'         => __( 'Posted', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-by',
				'title'         => __( 'By', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-on',
				'title'         => __( 'On', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-categories',
				'title'         => __( 'Categories', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-tags',
				'title'         => __( 'Tags', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-permalink',
				'title'         => __( 'Permalink', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-back-to-top',
				'title'         => __( 'Back To Top', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-woocommerce-tab',
				'title'         => __( 'WooCommerce Tab', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-share-faq',
				'title'         => __( 'Share', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-find-faq-helpful',
				'title'         => __( 'Did you find this FAQ helpful?', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-enter-question',
				'title'         => __( 'Enter your quetion', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-search-placeholder',
				'title'         => __( 'Search Placeholder', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-search',
				'title'         => __( 'Search Button', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-retrieving-results',
				'title'         => __( 'Retrieving Results', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-and-search',
			'text',
			array(
				'id'            => 'label-no-results-found',
				'title'         => __( 'No result FAQ\'s contained the term \'%s\'', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array_merge(
				array(
					'id'            => 'ewd-ufaq-labelling-faq-submit-page',
					'title'         => __( 'FAQ Submit Page', 'ultimate-faqs' ),
					'tab'	        => 'ewd-ufaq-labelling-tab',
				),
				$ewd_ufaq_labelling_permissions
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-submit-page',
			'text',
			array(
				'id'            => 'label-thank-you-submit',
				'title'         => __( 'Thank you for submitting an FAQ', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-submit-page',
			'text',
			array(
				'id'            => 'label-submit-question',
				'title'         => __( 'Submit a Question', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-submit-page',
			'text',
			array(
				'id'            => 'label-please-fill-form-below',
				'title'         => __( 'Please fill out the form below to submit a question.', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-submit-page',
			'text',
			array(
				'id'            => 'label-send-question',
				'title'         => __( 'Send Question', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-submit-page',
			'text',
			array(
				'id'            => 'label-question-title',
				'title'         => __( 'Question Title', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-submit-page',
			'text',
			array(
				'id'            => 'label-question-title-explanation',
				'title'         => __( 'What question is being answered?', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-submit-page',
			'text',
			array(
				'id'            => 'label-proposed-answer',
				'title'         => __( 'Proposed Answer', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-submit-page',
			'text',
			array(
				'id'            => 'label-proposed-answer-explanation',
				'title'         => __( 'What answer should be displayed for this question?', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-submit-page',
			'text',
			array(
				'id'            => 'label-question-author',
				'title'         => __( 'Question Author', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-submit-page',
			'text',
			array(
				'id'            => 'label-captcha-image-number',
				'title'         => __( 'Image Number', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-labelling-faq-submit-page',
			'text',
			array(
				'id'            => 'label-question-author-explanation',
				'title'         => __( 'What name should be displayed with your question?', 'ultimate-faqs' ),
				'description'	=> ''
			)
		);

		if ( ! $ewd_ufaq_controller->permissions->check_permission( 'styling' ) ) {
			$ewd_ufaq_styling_permissions = array(
				'disabled'		=> true,
				'disabled_image'=> '#',
				'purchase_link'	=> 'https://www.etoilewebdesign.com/plugins/ultimate-faq/'
			);
		}
		else { $ewd_ufaq_styling_permissions = array(); }

		$sap->add_section(
			'ewd-ufaq-settings',
			array(
				'id'            => 'ewd-ufaq-styling-tab',
				'title'         => __( 'Styling', 'ultimate-faqs' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array_merge(
				array(
					'id'            => 'ewd-ufaq-styling-toggle-symbol',
					'title'         => __( 'Toggle Symbol', 'ultimate-faqs' ),
					'tab'	        => 'ewd-ufaq-styling-tab',
				),
				$ewd_ufaq_styling_permissions
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-toggle-symbol',
			'radio',
			array(
				'id'			=> 'styling-toggle-symbol',
				'title'			=> __( 'Toggle Symbol', 'ultimate-lightbox' ),
				'columns'		=> '3',
				'options'		=> array(
					'A'				=> '<span class="ewd-ufaq-toggle-symbol">a A</span>',
					'B'				=> '<span class="ewd-ufaq-toggle-symbol">b B</span>',
					'C'				=> '<span class="ewd-ufaq-toggle-symbol">c C</span>',
					'D'				=> '<span class="ewd-ufaq-toggle-symbol">d D</span>',
					'E'				=> '<span class="ewd-ufaq-toggle-symbol">e E</span>',
					'F'				=> '<span class="ewd-ufaq-toggle-symbol">f F</span>',
					'G'				=> '<span class="ewd-ufaq-toggle-symbol">g G</span>',
					'H'				=> '<span class="ewd-ufaq-toggle-symbol">h H</span>',
					'I'				=> '<span class="ewd-ufaq-toggle-symbol">i I</span>',
					'J'				=> '<span class="ewd-ufaq-toggle-symbol">j J</span>',
					'K'				=> '<span class="ewd-ufaq-toggle-symbol">k K</span>',
					'L'				=> '<span class="ewd-ufaq-toggle-symbol">l L</span>',
					'M'				=> '<span class="ewd-ufaq-toggle-symbol">m M</span>',
					'N'				=> '<span class="ewd-ufaq-toggle-symbol">n N</span>',
					'O'				=> '<span class="ewd-ufaq-toggle-symbol">o O</span>',
				),
				'default'		=> $this->defaults['styling-toggle-symbol']
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-toggle-symbol',
			'colorpicker',
			array(
				'id'			=> 'styling-toggle-background-color',
				'title'			=> __( 'Toggle Background Color', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-toggle-symbol',
			'colorpicker',
			array(
				'id'			=> 'styling-toggle-font-color',
				'title'			=> __( 'Toggle Color', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-toggle-symbol',
			'colorpicker',
			array(
				'id'			=> 'styling-toggle-border-color',
				'title'			=> __( 'Toggle Border Color', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-toggle-symbol',
			'text',
			array(
				'id'            => 'styling-toggle-symbol-size',
				'title'         => __( 'Toggle Font Size', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-toggle-symbol',
			'text',
			array(
				'id'            => 'styling-toggle-border-size',
				'title'         => __( 'Border Size', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-toggle-symbol',
			'text',
			array(
				'id'            => 'styling-toggle-border-radius',
				'title'         => __( 'Border Radius', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array_merge(
				array(
					'id'            => 'ewd-ufaq-styling-themes',
					'title'         => __( 'Themes', 'ultimate-faqs' ),
					'tab'	        => 'ewd-ufaq-styling-tab',
				),
				$ewd_ufaq_styling_permissions
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-themes',
			'colorpicker',
			array(
				'id'			=> 'styling-block-background-color',
				'title'			=> __( 'Block and Border Block Background Color', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-themes',
			'colorpicker',
			array(
				'id'			=> 'styling-block-font-color',
				'title'			=> __( 'Block and Border Block Hover Font Color', 'ultimate-faqs' )
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-themes',
			'colorpicker',
			array(
				'id'			=> 'styling-list-font-color',
				'title'			=> __( 'List Font Color', 'ultimate-faqs' )
			)
		);


		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-themes',
			'text',
			array(
				'id'            => 'styling-list-font',
				'title'         => __( 'Font Family', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-themes',
			'text',
			array(
				'id'            => 'styling-list-font-size',
				'title'         => __( 'Font Size', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-themes',
			'text',
			array(
				'id'            => 'styling-list-margin',
				'title'         => __( 'Margin', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-themes',
			'text',
			array(
				'id'            => 'styling-list-padding',
				'title'         => __( 'Padding', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array_merge(
				array(
					'id'            => 'ewd-ufaq-styling-faq-elements',
					'title'         => __( 'FAQ Elements', 'ultimate-faqs' ),
					'tab'	        => 'ewd-ufaq-styling-tab',
				),
				$ewd_ufaq_styling_permissions
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'colorpicker',
			array(
				'id'			=> 'styling-question-font-color',
				'title'			=> __( 'Question Font Color', 'ultimate-faqs' )
			)
		);


		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-question-font',
				'title'         => __( 'Question Font Family', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-question-font-size',
				'title'         => __( 'Question Font Size', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-question-margin',
				'title'         => __( 'Question Margin', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-question-padding',
				'title'         => __( 'Question Padding', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-question-icon-top-margin',
				'title'         => __( 'Toggle Symbol Top Margin', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'colorpicker',
			array(
				'id'			=> 'styling-answer-font-color',
				'title'			=> __( 'Answer Font Color', 'ultimate-faqs' )
			)
		);


		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-answer-font',
				'title'         => __( 'Answer Font Family', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-answer-font-size',
				'title'         => __( 'Answer Font Size', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-answer-margin',
				'title'         => __( 'Answer Margin', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-answer-padding',
				'title'         => __( 'Answer Padding', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'colorpicker',
			array(
				'id'			=> 'styling-category-font-color',
				'title'			=> __( 'Categories, Tags Font Color', 'ultimate-faqs' )
			)
		);


		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-category-font',
				'title'         => __( 'Categories, Tags Font Family', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-category-font-size',
				'title'         => __( 'Categories, Tags Font Size', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-category-margin',
				'title'         => __( 'Categories, Tags Margin', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-category-padding',
				'title'         => __( 'Categories, Tags Padding', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'colorpicker',
			array(
				'id'			=> 'styling-postdate-font-color',
				'title'			=> __( 'Post Date Font Color', 'ultimate-faqs' )
			)
		);


		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-postdate-font',
				'title'         => __( 'Post Date Font Family', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-postdate-font-size',
				'title'         => __( 'Post Date Font Size', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-postdate-margin',
				'title'         => __( 'Post Date Margin', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-faq-elements',
			'text',
			array(
				'id'            => 'styling-postdate-padding',
				'title'         => __( 'Post Date Padding', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_section(
			'ewd-ufaq-settings',
			array_merge(
				array(
					'id'            => 'ewd-ufaq-styling-headings',
					'title'         => __( 'Headings', 'ultimate-faqs' ),
					'tab'	        => 'ewd-ufaq-styling-tab',
				),
				$ewd_ufaq_styling_permissions
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-headings',
			'radio',
			array(
				'id'			=> 'styling-category-heading-type',
				'title'			=> __( 'Category Heading Type', 'ultimate-faqs' ),
				'options'		=> array(
					'h1'			=> __( 'H1', 'ultimate-faqs' ),
					'h2'			=> __( 'H2', 'ultimate-faqs' ),
					'h3'			=> __( 'H3', 'ultimate-faqs' ),
					'h4'			=> __( 'H4', 'ultimate-faqs' ),
					'h5'			=> __( 'H5', 'ultimate-faqs' ),
					'h6'			=> __( 'H6', 'ultimate-faqs' ),
				),
				'default'		=> $this->defaults['styling-category-heading-type']
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-headings',
			'radio',
			array(
				'id'			=> 'styling-faq-heading-type',
				'title'			=> __( 'FAQ Heading Type', 'ultimate-faqs' ),
				'options'		=> array(
					'h1'			=> __( 'H1', 'ultimate-faqs' ),
					'h2'			=> __( 'H2', 'ultimate-faqs' ),
					'h3'			=> __( 'H3', 'ultimate-faqs' ),
					'h4'			=> __( 'H4', 'ultimate-faqs' ),
					'h5'			=> __( 'H5', 'ultimate-faqs' ),
					'h6'			=> __( 'H6', 'ultimate-faqs' ),
				),
				'default'		=> $this->defaults['styling-faq-heading-type']
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-headings',
			'colorpicker',
			array(
				'id'			=> 'styling-category-heading-font-color',
				'title'			=> __( 'Category Heading Font Color', 'ultimate-faqs' )
			)
		);


		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-headings',
			'text',
			array(
				'id'            => 'styling-category-heading-font',
				'title'         => __( 'Category Heading Font Family', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-ufaq-settings',
			'ewd-ufaq-styling-headings',
			'text',
			array(
				'id'            => 'styling-category-heading-font-size',
				'title'         => __( 'Category Heading Font Size', 'ultimate-faqs' ),
				'description'	=> '',
				'small'			=> true
			)
		);

		$sap = apply_filters( 'ewd_ufaq_settings_page', $sap );

		$sap->add_admin_menus();

	}
}
} // endif;

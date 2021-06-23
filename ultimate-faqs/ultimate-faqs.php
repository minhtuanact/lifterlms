<?php
/*
Plugin Name: Ultimate FAQs
Plugin URI: http://www.EtoileWebDesign.com/plugins/ultimate-faqs/
Description: Easily create and add FAQs to your WordPress site with a Gutenberg block, shortcode or widget. Includes FAQ schema, search, accordion toggle and more.
Author: Etoile Web Design
Author URI: http://www.EtoileWebDesign.com/plugins/ultimate-faqs/
Terms and Conditions: http://www.etoilewebdesign.com/plugin-terms-and-conditions/
Text Domain: ultimate-faqs
Version: 2.0.18
*/

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'ewdufaqInit' ) ) {
class ewdufaqInit {

	public $schema_faq_data = array();

	/**
	 * Initialize the plugin and register hooks
	 */
	public function __construct() {

		self::constants();
		self::includes();
		self::instantiate();
		self::wp_hooks();
	}

	/**
	 * Define plugin constants.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @return void
	 */
	protected function constants() {

		define( 'EWD_UFAQ_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'EWD_UFAQ_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
		define( 'EWD_UFAQ_PLUGIN_FNAME', plugin_basename( __FILE__ ) );
		define( 'EWD_UFAQ_TEMPLATE_DIR', 'ewd-ufaq-templates' );
		define( 'EWD_UFAQ_VERSION', '2.0.0' );

		define( 'EWD_UFAQ_FAQ_POST_TYPE', 'ufaq' );
		define( 'EWD_UFAQ_FAQ_CATEGORY_TAXONOMY', 'ufaq-category' );
		define( 'EWD_UFAQ_FAQ_TAG_TAXONOMY', 'ufaq-tag' );
	}

	/**
	 * Include necessary classes.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @return void
	 */
	protected function includes() {

		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/Ajax.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/Blocks.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/CustomPostTypes.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/Dashboard.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/DeactivationSurvey.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/Export.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/FAQ.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/Import.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/InstallationWalkthrough.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/Notifications.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/OrderingTable.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/Permissions.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/Query.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/ReviewAsk.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/Settings.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/template-functions.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/UltimateWPMail.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/Widgets.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/WooCommerce.class.php' );
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/WPForms.class.php' );
	}

	/**
	 * Spin up instances of our plugin classes.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @return void
	 */
	protected function instantiate() {

		new ewdufaqDashboard();
		new ewdufaqDeactivationSurvey();
		new ewdufaqInstallationWalkthrough();
		new ewdufaqReviewAsk();

		$this->cpts 		= new ewdufaqCustomPostTypes();
		$this->permissions 	= new ewdufaqPermissions();
		$this->settings 	= new ewdufaqSettings(); 

		if ( $this->settings->get_setting( 'woocommerce-faqs' ) ) {
			
			$this->woocommerce = new ewdufaqWooCommerce();
		}

		if ( $this->settings->get_setting( 'wpforms-integration' ) ) {
			
			$this->wpforms = new ewdufaqWPForms();
		}

		new ewdufaqAJAX();
		new ewdufaqBlocks();
		new ewdufaqExport();
		new ewdufaqImport();
		new ewdufaqNotifications();
		new ewdufaqOrderingTable();
		new ewdufaqUltimateWPMail();
		new ewdufaqWidgetManager();
	}

	/**
	 * Run walk-through, load assets, add links to plugin listing, etc.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @return void
	 */
	protected function wp_hooks() {

		register_activation_hook( __FILE__, 	array( $this, 'run_walkthrough' ) );
		register_activation_hook( __FILE__, 	array( $this, 'convert_options' ) );

		add_filter( 'init',						array( $this, 'rewrite_rules' ) );
		add_filter( 'query_vars',				array( $this, 'add_query_vars' ) );
		add_filter( 'redirect_canonical',		array( $this, 'disable_canonical_redirect_for_front_page' ) );

		add_filter( 'the_content', 				array( $this, 'alter_faq_content' ) );
		add_action( 'wp_footer', 				array( $this, 'output_ld_json_content' ) );

		add_action( 'init',			        	array( $this, 'load_view_files' ) );

		add_action( 'plugins_loaded',        	array( $this, 'load_textdomain' ) );

		add_action( 'admin_notices', 			array( $this, 'display_header_area' ) );

		add_action( 'admin_enqueue_scripts', 	array( $this, 'enqueue_admin_assets' ), 10, 1 );
		add_action( 'wp_enqueue_scripts', 		array( $this, 'register_assets' ) );
		add_action( 'wp_head',					'ewd_add_frontend_ajax_url' );

		add_filter( 'plugin_action_links',		array( $this, 'plugin_action_links' ), 10, 2);
	}

	/**
	 * Run the options conversion function on update if necessary
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function convert_options() {
		
		require_once( EWD_UFAQ_PLUGIN_DIR . '/includes/BackwardsCompatibility.class.php' );
		new ewdufaqBackwardsCompatibility();
	}

	/**
	 * Adds in the rewrite rules used by the plugin and flushes rules if necessary
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function rewrite_rules() {

		$review_rules = get_option( 'rewrite_rules' );
		$frontpage_id = get_option( 'page_on_front' );

		add_rewrite_tag( '%single_faq%', '([^&]+)' );
    	add_rewrite_tag( '%ufaq_category_slug%', '([^+]+)' );
    	add_rewrite_tag( '%ufaq_tag_slug%', '([^?]+)' );
	
		add_rewrite_rule( "single-faq/([^&]*)/?$", "index.php?page_id=". $frontpage_id . "&single_faq=\$matches[1]", 'top' );
		add_rewrite_rule( "(.?.+?)/single-faq/([^&]*)/?$", "index.php?pagename=\$matches[1]&single_faq=\$matches[2]", 'top' );
		add_rewrite_rule( "faq-category/([^+]*)/?$", "index.php?page_id=". $frontpage_id . "&ufaq_category_slug=\$matches[1]", 'top' );
		add_rewrite_rule( "(.?.+?)/faq-category/([^+]*)/?$", "index.php?pagename=\$matches[1]&ufaq_category_slug=\$matches[2]", 'top' );
		add_rewrite_rule( "faq-tag/([^?]*)/?$", "index.php?page_id=". $frontpage_id . "&ufaq_tag_slug=\$matches[1]", 'top' );
		add_rewrite_rule( "(.?.+?)/faq-tag/([^?]*)/?$", "index.php?pagename=\$matches[1]&ufaq_tag_slug=\$matches[2]", 'top' );

		if ( ! isset( $review_rules['(.?.+?)/single-faq/([^&]*)/?$'] ) ) { flush_rewrite_rules(); }
	}

	/**
	 * Adds in the query vars used by the plugin
	 *
	 * @since  2.0.0
	 * @access public
	 * @return array
	 */
	public function add_query_vars( $vars ) {

		$vars[] = 'single_faq';
		$vars[] = 'ufaq_category_slug';
		$vars[] = 'ufaq_tag_slug';

		return $vars;
	}

	/**
	 * Disables the automatic redirect that happens on the front-page
	 *
	 * @since  2.0.0
	 * @access public
	 * @return array
	 */
	public function disable_canonical_redirect_for_front_page( $redirect ) {
		global $ewd_ufaq_controller;

		if ( empty( $ewd_ufaq_controller->settings->get_setting( 'disable-homepage-canoncial-redirect' ) ) ) { return $redirect; }

		if ( ! is_page() ) { return $redirect; }

		$front_page = get_option( 'page_on_front' );

		if ( ! is_page( $front_page ) ) { return $redirect; }

		return false;
	}

	/**
	 * Load files needed for views
	 * @since 2.0.0
	 * @note Can be filtered to add new classes as needed
	 */
	public function load_view_files() {
	
		$files = array(
			EWD_UFAQ_PLUGIN_DIR . '/views/Base.class.php' // This will load all default classes
		);
	
		$files = apply_filters( 'ewd_ufaq_load_view_files', $files );
	
		foreach( $files as $file ) {
			require_once( $file );
		}
	
	}

	/**
	 * Load the plugin textdomain for localisation
	 * @since 2.0.0
	 */
	public function load_textdomain() {
		
		load_plugin_textdomain( 'ultimate-faqs', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Set a transient so that the walk-through gets run
	 * @since 2.0.0
	 */
	public function run_walkthrough() {

		set_transient( 'ewd-ufaq-getting-started', true, 30 );
	} 

	/**
	 * Enqueue the admin-only CSS and Javascript
	 * @since 2.0.0
	 */
	public function enqueue_admin_assets( $hook ) {
		global $post;

		$post_type = is_object( $post ) ?  $post->post_type : '';

		$screen = get_current_screen();

   		// Return if not ufaq post_type, we're not on a post-type page, or we're not on the settings or widget pages
   		if ( ( $post_type != EWD_UFAQ_FAQ_POST_TYPE or ( $hook != 'edit.php' and $hook != 'post-new.php' and $hook != 'post.php' ) ) and $hook != 'ufaq_page_ewd-ufaq-settings' and $hook != 'ufaq_page_ewd-ufaq-ordering-table' and $hook != 'widgets.php' and $screen->id != 'edit-ufaq-category' and $screen->id != 'edit-ufaq-tag' ) { return; }

		wp_enqueue_style( 'ewd-ufaq-admin-css', EWD_UFAQ_PLUGIN_URL . '/assets/css/ewd-ufaq-admin.css', array(), EWD_UFAQ_VERSION );

		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-sortable' );

		wp_register_script( 'ewd-ufaq-admin-js', EWD_UFAQ_PLUGIN_URL . '/assets/js/ewd-ufaq-admin.js', array( 'jquery', 'jquery-ui-sortable' ), EWD_UFAQ_VERSION, true );

		$args = array(
			'ordering' => $this->permissions->check_permission( 'ordering' )
		);
		
		wp_localize_script( 'ewd-ufaq-admin-js', 'ewd_ufaq_php_data', $args );

		wp_enqueue_script( 'ewd-ufaq-admin-js' );
	}

	/**
	 * Register the front-end CSS and Javascript for the FAQs
	 * @since 2.0.0
	 */
	function register_assets() {
		global $ewd_ufaq_controller;

		wp_enqueue_style( 'ewd-ufaq-rrssb', EWD_UFAQ_PLUGIN_URL . '/assets/css/rrssb-min.css', EWD_UFAQ_VERSION );
		wp_enqueue_style( 'ewd-ufaq-jquery-ui', EWD_UFAQ_PLUGIN_URL . '/assets/css/jquery-ui.min.css', EWD_UFAQ_VERSION );
		wp_register_style( 'ewd-ufaq-css', EWD_UFAQ_PLUGIN_URL . '/assets/css/ewd-ufaq.css', EWD_UFAQ_VERSION );
		
		wp_register_script( 'ewd-ufaq-js', EWD_UFAQ_PLUGIN_URL . '/assets/js/ewd-ufaq.js', array( 'jquery', 'jquery-ui-core' ), EWD_UFAQ_VERSION, true );
	}

	/**
	 * Add links to the plugin listing on the installed plugins page
	 * @since 2.0.0
	 */
	public function plugin_action_links( $links, $plugin ) {

		if ( $plugin == EWD_UFAQ_PLUGIN_FNAME ) {

			$links['settings'] = '<a href="admin.php?page=ewd-ufaq-settings" title="' . __( 'Head to the settings page for Ultimate FAQs', 'ultimate-faqs' ) . '">' . __( 'Settings', 'ultimate-faqs' ) . '</a>';
		}

		return $links;

	}

	/**
	 * Replace the content of the single FAQ page with the FAQ shortcode
	 * @since 2.0.0
	 */
	public function alter_faq_content( $content ) {
		global $post, $ewd_ufaq_controller;

		if ( $post->post_type != EWD_UFAQ_FAQ_POST_TYPE ) { return $content; }

		if ( ! empty( $ewd_ufaq_controller->shortcode_printing ) ) { return $content; }

		if ( is_archive() ) { return $content; }

		$ewd_ufaq_controller->single_page_print = true;

		$content = do_shortcode( '[select-faq faq_id="' . $post->ID . '"]' );

		$ewd_ufaq_controller->single_page_print = false;

		return $content;
	}

	/**
	 * Output any FAQ schema data, if enabled
	 *
	 * @since  2.0.0
	 */
	public function output_ld_json_content() {
		global $ewd_ufaq_controller;

		if ( empty( $this->schema_faq_data ) ) { return; }

		if ( $ewd_ufaq_controller->settings->get_setting( 'disable-microdata' ) ) { return; }

		$ewd_ufaq_schema_data = array(
			'@context' => 'https://schema.org',
			'@type' => 'FAQPage',
			'mainEntity' => $this->schema_faq_data
		);

		$ld_json_ouptut = apply_filters( 'bpfwp_ld_json_output', $ewd_ufaq_schema_data );

		echo '<script type="application/ld+json" class="ewd-ufaq-ld-json-data">';
		echo wp_json_encode( $ld_json_ouptut );
		echo '</script>';
	}

	/**
	 * Adds in a menu bar for the plugin
	 * @since 2.0.0
	 */
	public function display_header_area() {
		global $ewd_ufaq_controller;

		$screen = get_current_screen();
		
		if ( empty( $screen->parent_file ) or $screen->parent_file != 'edit.php?post_type=ufaq' ) { return; }
		
		if ( ! $ewd_ufaq_controller->permissions->check_permission( 'styling' ) or get_option( 'EWD_UFAQ_Trial_Happening' ) == 'Yes' ) {
			?>
			<div class="ewd-ufaq-dashboard-new-upgrade-banner">
				<div class="ewd-ufaq-dashboard-banner-icon"></div>
				<div class="ewd-ufaq-dashboard-banner-buttons">
					<a class="ewd-ufaq-dashboard-new-upgrade-button" href="https://www.etoilewebdesign.com/license-payment/?Selected=UFAQ&Quantity=1" target="_blank">UPGRADE NOW</a>
				</div>
				<div class="ewd-ufaq-dashboard-banner-text">
					<div class="ewd-ufaq-dashboard-banner-title">
						GET FULL ACCESS WITH OUR PREMIUM VERSION
					</div>
					<div class="ewd-ufaq-dashboard-banner-brief">
						WooCommerce Integration, Advanced styling options, Advanced control options and more!
					</div>
				</div>
			</div>
			<?php
		}
		
		?>
		<div class="ewd-ufaq-admin-header-menu">
			<h2 class="nav-tab-wrapper">
			<a id="ewd-ufaq-dash-mobile-menu-open" href="#" class="menu-tab nav-tab"><?php _e("MENU", 'ultimate-faqs'); ?><span id="ewd-ufaq-dash-mobile-menu-down-caret">&nbsp;&nbsp;&#9660;</span><span id="ewd-ufaq-dash-mobile-menu-up-caret">&nbsp;&nbsp;&#9650;</span></a>
			<a id="dashboard-menu" href='admin.php?page=ewd-ufaq-dashboard' class="menu-tab nav-tab <?php if ( $screen->id == 'ufaq_ewd-ufaq-dashboard' ) {echo 'nav-tab-active';}?>"><?php _e("Dashboard", 'ultimate-faqs'); ?></a>
			<a id="faqs-menu" href='edit.php?post_type=ufaq' class="menu-tab nav-tab <?php if ( $screen->id == 'edit-ufaq' ) {echo 'nav-tab-active';}?>"><?php _e("FAQs", 'ultimate-faqs'); ?></a>
			<a id="add-faq-menu" href='post-new.php?post_type=ufaq' class="menu-tab nav-tab"><?php _e("Add New", 'ultimate-faqs'); ?></a>
			<a id="categories-menu" href='edit-tags.php?taxonomy=ufaq-category&post_type=ufaq' class="menu-tab nav-tab <?php if ( $screen->id == 'edit-ufaq-category' ) {echo 'nav-tab-active';}?>"><?php _e("Categories", 'ultimate-faqs'); ?></a>
			<a id="tags-menu" href='edit-tags.php?taxonomy=ufaq-tag&post_type=ufaq' class="menu-tab nav-tab <?php if ( $screen->id == 'edit-ufaq-tag' ) {echo 'nav-tab-active';}?>"><?php _e("Tags", 'ultimate-faqs'); ?></a>
			<a id="import-menu" href='edit.php?post_type=ufaq&page=ewd-ufaq-import' class="menu-tab nav-tab <?php if ( $screen->id == 'ewd_ufaq_page_ewd-ufaq-import' ) {echo 'nav-tab-active';}?>"><?php _e("Import", 'ultimate-faqs'); ?></a>
			<a id="export-menu" href='edit.php?post_type=ufaq&page=ewd-ufaq-export' class="menu-tab nav-tab <?php if ( $screen->id == 'ewd_ufaq_page_ewd-ufaq-export' ) {echo 'nav-tab-active';}?>"><?php _e("Export", 'ultimate-faqs'); ?></a>
			<?php if ( $ewd_ufaq_controller->settings->get_setting( 'faq-order-by' ) == 'set_order' ) { ?>
				<a id="ordering-table" href='edit.php?post_type=ufaq&page=ewd-ufaq-ordering-table' class="menu-tab nav-tab <?php if ( $screen->id == 'ewd_ufaq_page_ewd-ufaq-ordering-table' ) {echo 'nav-tab-active';}?>"><?php _e("FAQ Order", 'ultimate-faqs'); ?></a>
			<?php } ?>
			<a id="options-menu" href='edit.php?post_type=ufaq&page=ewd-ufaq-settings' class="menu-tab nav-tab <?php if ( $screen->id == 'ewd_ufaq_page_ewd-ufaq-settings' ) {echo 'nav-tab-active';}?>"><?php _e("Settings", 'ultimate-faqs'); ?></a>
			</h2>
		</div>
		<?php
	}

}
} // endif;

global $ewd_ufaq_controller;
$ewd_ufaq_controller = new ewdufaqInit();


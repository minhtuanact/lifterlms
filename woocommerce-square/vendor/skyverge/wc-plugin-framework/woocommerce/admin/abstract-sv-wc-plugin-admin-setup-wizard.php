<?php
/**
 * WooCommerce Plugin Framework
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the plugin to newer
 * versions in the future. If you wish to customize the plugin for your
 * needs please refer to http://www.skyverge.com
 *
 * @author    SkyVerge
 * @copyright Copyright (c) 2013-2019, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace SkyVerge\WooCommerce\PluginFramework\v5_4_0\Admin;

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_4_0 as Framework;

if ( ! class_exists( '\\SkyVerge\\WooCommerce\\PluginFramework\\v5_4_0\\Admin\\Setup_Wizard' ) ) :

/**
 * The plugin Setup Wizard class.
 *
 * This creates a setup wizard so that plugins can provide a user-friendly
 * step-by-step interaction for configuring critical plugin options.
 *
 * Based on WooCommerce's \WC_Admin_Setup_Wizard
 *
 * @since 5.2.2
 */
abstract class Setup_Wizard {


	/** the "finish" step ID */
	const ACTION_FINISH = 'finish';


	/** @var string the user capability required to use this wizard */
	protected $required_capability = 'manage_woocommerce';

	/** @var string the current step ID */
	protected $current_step = '';

	/** @var array registered steps to be displayed */
	protected $steps = array();

	/** @var string setup handler ID  */
	private $id;

	/** @var Framework\SV_WC_Plugin plugin instance */
	private $plugin;


	/**
	 * Constructs the class.
	 *
	 * @param Framework\SV_WC_Plugin $plugin plugin instance
	 */
	public function __construct( Framework\SV_WC_Plugin $plugin ) {

		// sanity check for admin and permissions
		if ( ! is_admin() || ! current_user_can( $this->required_capability ) ) {
			return;
		}

		$this->id     = $plugin->get_id();
		$this->plugin = $plugin;

		// register the steps
		$this->register_steps();

		/**
		 * Filters the registered setup wizard steps.
		 *
		 * @since 5.2.2
		 *
		 * @param array $steps registered steps
		 */
		$this->steps = apply_filters( "wc_{$this->id}_setup_wizard_steps", $this->steps, $this );

		// only continue if there are registered steps
		if ( $this->has_steps() ) {

			// if requesting the wizard
			if ( $this->is_setup_page() ) {

				$this->init_setup();

			// otherwise, add the hooks for customizing the regular admin
			} else {

				$this->add_hooks();

				// mark the wizard as complete if specifically requested
				if ( Framework\SV_WC_Helper::get_request( "wc_{$this->id}_setup_wizard_complete" ) ) {
					$this->complete_setup();
				}
			}
		}
	}


	/**
	 * Registers the setup steps.
	 *
	 * Plugins should extend this to register their own steps.
	 *
	 * @since 5.2.2
	 */
	abstract protected function register_steps();


	/**
	 * Adds the action & filter hooks.
	 *
	 * @since 5.2.2
	 */
	protected function add_hooks() {

		// add any admin notices
		add_action( 'admin_notices', array( $this, 'add_admin_notices' ) );

		// add a 'Setup' link to the plugin action links if the wizard hasn't been completed
		if ( ! $this->is_complete() ) {
			add_filter( 'plugin_action_links_' . plugin_basename( $this->get_plugin()->get_plugin_file() ), array( $this, 'add_setup_link' ), 20 );
		}
	}


	/**
	 * Adds any admin notices.
	 *
	 * @since 5.2.2
	 */
	public function add_admin_notices() {

		$current_screen = get_current_screen();

		if ( ( $current_screen && 'plugins' === $current_screen->id ) || $this->get_plugin()->is_plugin_settings() ) {

			if ( $this->is_complete() && $this->get_documentation_notice_message() ) {
				$notice_id = "wc_{$this->id}_docs";
				$message   = $this->get_documentation_notice_message();
			} else {
				$notice_id = "wc_{$this->id}_setup";
				$message   = $this->get_setup_notice_message();
			}

			$this->get_plugin()->get_admin_notice_handler()->add_admin_notice( $message, $notice_id, array(
				'always_show_on_settings' => false,
			) );
		}
	}


	/**
	 * Gets the new installation documentation notice message.
	 *
	 * This prompts users to read the docs and is displayed if the wizard has
	 * already been completed.
	 *
	 * @since 5.2.2
	 *
	 * @return string
	 */
	protected function get_documentation_notice_message() {

		if ( $this->get_plugin()->get_documentation_url() ) {

			$message = sprintf(
				/** translators: Placeholders: %1$s - plugin name, %2$s - <a> tag, %3$s - </a> tag */
				__( 'Thanks for installing %1$s! To get started, take a minute to %2$sread the documentation%3$s :)', 'woocommerce-plugin-framework' ),
				esc_html( $this->get_plugin()->get_plugin_name() ),
				'<a href="' . esc_url( $this->get_plugin()->get_documentation_url() )  . '" target="_blank">', '</a>'
			);

		} else {

			$message = '';
		}

		return $message;
	}


	/**
	 * Gets the new installation setup notice message.
	 *
	 * This prompts users to start the setup wizard and is displayed if the
	 * wizard has not yet been completed.
	 *
	 * @since 5.2.2
	 *
	 * @return string
	 */
	protected function get_setup_notice_message() {

		return sprintf(
			/** translators: Placeholders: %1$s - plugin name, %2$s - <a> tag, %3$s - </a> tag */
			__( 'Thanks for installing %1$s! To get started, take a minute to complete these %2$squick and easy setup steps%3$s :)', 'woocommerce-plugin-framework' ),
			esc_html( $this->get_plugin()->get_plugin_name() ),
			'<a href="' . esc_url( $this->get_setup_url() )  . '">', '</a>'
		);
	}


	/**
	 * Adds a 'Setup' link to the plugin action links if the wizard hasn't been completed.
	 *
	 * This will override the plugin's standard "Configure" link with a link to this setup wizard.
	 *
	 * @internal
	 *
	 * @since 5.2.2
	 *
	 * @param array $action_links plugin action links
	 * @return array
	 */
	public function add_setup_link( $action_links ) {

		// remove the standard plugin "Configure" link
		unset( $action_links['configure'] );

		$setup_link = array(
			'setup' => sprintf( '<a href="%s">%s</a>', $this->get_setup_url(), esc_html__( 'Setup', 'woocommerce-plugin-framework' ) ),
		);

		return array_merge( $setup_link, $action_links );
	}


	/**
	 * Initializes setup.
	 *
	 * @since 5.2.2
	 */
	protected function init_setup() {

		// get a step ID from $_GET
		$current_step   = sanitize_key( Framework\SV_WC_Helper::get_request( 'step' ) );
		$current_action = sanitize_key( Framework\SV_WC_Helper::get_request( 'action' ) );

		if ( ! $current_action ) {

			if ( $this->has_step( $current_step ) ) {
				$this->current_step = $current_step;
			} elseif ( $first_step_url = $this->get_step_url( key( $this->steps ) ) ) {
				wp_safe_redirect( $first_step_url );
				exit;
			} else {
				wp_safe_redirect( $this->get_dashboard_url() );
				exit;
			}
		}

		// add the page to WP core
		add_action( 'admin_menu', array( $this, 'add_page' ) );

		// renders the entire setup page markup
		add_action( 'admin_init', array( $this, 'render_page' ) );
	}


	/**
	 * Adds the page to WordPress core.
	 *
	 * While this doesn't output any markup/menu items, it is essential to officially register the page to avoid permissions issues.
	 *
	 * @internal
	 *
	 * @since 5.2.2
	 */
	public function add_page() {

		add_dashboard_page( '', '', $this->required_capability, $this->get_slug(), '' );
	}


	/**
	 * Renders the entire setup page markup.
	 *
	 * @internal
	 *
	 * @since 5.2.2
	 */
	public function render_page() {

		// maybe save and move onto the next step
		$error_message = Framework\SV_WC_Helper::get_post( 'save_step' ) ? $this->save_step( $this->current_step ) : '';

		$page_title = sprintf(
			/* translators: Placeholders: %s - plugin name */
			__( '%s &rsaquo; Setup', 'woocommerce-plugin-framework' ),
			$this->get_plugin()->get_plugin_name()
		);

		// add the step name to the page title
		if ( ! empty( $this->steps[ $this->current_step ]['name'] ) ) {
			$page_title .= " &rsaquo; {$this->steps[ $this->current_step ]['name']}";
		}

		$this->load_scripts_styles();

		ob_start();

		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
			<head>
				<meta name="viewport" content="width=device-width" />
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title><?php echo esc_html( $page_title ); ?></title>
				<?php wp_print_scripts( 'wc-setup' ); ?>
				<?php do_action( 'admin_print_scripts' ); ?>
				<?php do_action( 'admin_print_styles' ); ?>
				<?php do_action( 'admin_head' ); ?>
			</head>
			<body class="wc-setup wp-core-ui <?php echo esc_attr( $this->get_slug() ); ?>">
				<?php $this->render_header(); ?>
				<?php $this->render_steps(); ?>
				<?php $this->render_content( $error_message ); ?>
				<?php $this->render_footer(); ?>
			</body>
		</html>
		<?php

		exit;
	}


	/**
	 * Saves a step.
	 *
	 * @since 5.2.2
	 *
	 * @param string $step_id the step ID being saved
     * @return void|string redirects upon success, returns an error message upon failure
	 */
	protected function save_step( $step_id ) {

		$error_message = __( 'Oops! An error occurred, please try again.', 'woocommerce-plugin-framework' );

		try {

			// bail early if the nonce is bad
			if ( ! wp_verify_nonce( Framework\SV_WC_Helper::get_post( 'nonce' ), "wc_{$this->id}_setup_wizard_save" ) ) {
				throw new Framework\SV_WC_Plugin_Exception( $error_message );
			}

			if ( $this->has_step( $step_id ) ) {

				// if the step has a saving callback defined, save the form fields
				if ( is_callable( $this->steps[ $step_id ]['save'] ) ) {
					call_user_func( $this->steps[ $step_id ]['save'], $this );
				}

				// move to the next step
				wp_safe_redirect( $this->get_next_step_url( $step_id ) );
				exit;
			}

		} catch ( Framework\SV_WC_Plugin_Exception $exception ) {

			return $exception->getMessage() ? $exception->getMessage() : $error_message;
		}
	}


	/**
	 * Registers and enqueues the wizard's scripts and styles.
	 *
	 * @since 5.2.2
	 */
	protected function load_scripts_styles() {

		// block UI
		wp_register_script( 'jquery-blockui', WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI.min.js', array( 'jquery' ), '2.70', true );

		// enhanced dropdowns
		wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array( 'jquery' ), '1.0.0' );
		wp_register_script( 'wc-enhanced-select', WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select.min.js', array( 'jquery', 'selectWoo' ), $this->get_plugin()->get_version() );
		wp_localize_script(
			'wc-enhanced-select',
			'wc_enhanced_select_params',
			array(
				'i18n_no_matches'           => _x( 'No matches found', 'enhanced select', 'woocommerce-plugin-framework' ),
				'i18n_ajax_error'           => _x( 'Loading failed', 'enhanced select', 'woocommerce-plugin-framework' ),
				'i18n_input_too_short_1'    => _x( 'Please enter 1 or more characters', 'enhanced select', 'woocommerce-plugin-framework' ),
				'i18n_input_too_short_n'    => _x( 'Please enter %qty% or more characters', 'enhanced select', 'woocommerce-plugin-framework' ),
				'i18n_input_too_long_1'     => _x( 'Please delete 1 character', 'enhanced select', 'woocommerce-plugin-framework' ),
				'i18n_input_too_long_n'     => _x( 'Please delete %qty% characters', 'enhanced select', 'woocommerce-plugin-framework' ),
				'i18n_selection_too_long_1' => _x( 'You can only select 1 item', 'enhanced select', 'woocommerce-plugin-framework' ),
				'i18n_selection_too_long_n' => _x( 'You can only select %qty% items', 'enhanced select', 'woocommerce-plugin-framework' ),
				'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', 'woocommerce-plugin-framework' ),
				'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', 'woocommerce-plugin-framework' ),
				'ajax_url'                  => admin_url( 'admin-ajax.php' ),
				'search_products_nonce'     => wp_create_nonce( 'search-products' ),
				'search_customers_nonce'    => wp_create_nonce( 'search-customers' ),
			)
		);

		// WooCommerce Setup core styles
		wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), $this->get_plugin()->get_version() );
		wp_enqueue_style( 'wc-setup', WC()->plugin_url() . '/assets/css/wc-setup.css', array( 'dashicons', 'install' ), $this->get_plugin()->get_version() );

		// framework bundled styles
		wp_enqueue_style( 'sv-wc-admin-setup', $this->get_plugin()->get_framework_assets_url() . '/css/admin/sv-wc-plugin-admin-setup-wizard.min.css', array( 'wc-setup' ), $this->get_plugin()->get_version() );
		wp_enqueue_script( 'sv-wc-admin-setup', $this->get_plugin()->get_framework_assets_url() . '/js/admin/sv-wc-plugin-admin-setup-wizard.min.js', array( 'jquery', 'wc-enhanced-select', 'jquery-blockui' ), $this->get_plugin()->get_version() );
	}


	/** Header Methods ************************************************************************************************/


	/**
	 * Renders the header markup.
	 *
	 * @since 5.2.2
	 */
	protected function render_header() {

		$title     = $this->get_plugin()->get_plugin_name();
		$link_url  = $this->get_plugin()->get_sales_page_url();
		$image_url = $this->get_header_image_url();

		$header_content = $image_url ? '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $title ) . '" />' : $title;

		?>
		<h1 id="wc-logo" class="sv-wc-plugin-logo <?php echo esc_attr( 'wc-' . $this->get_plugin()->get_id_dasherized() . '-logo' ); ?>">
			<?php if ( $link_url ) : ?>
				<a href="<?php echo esc_url( $link_url ); ?>" target="_blank"><?php echo $header_content; ?></a>
			<?php else : ?>
				<?php echo esc_html( $header_content ); ?>
			<?php endif; ?>
		</h1>
		<?php
	}


	/**
	 * Gets the header image URL.
	 *
	 * Plugins can override this to point to their own branding image URL.
	 *
	 * @since 5.2.2
	 *
	 * @return string
	 */
	protected function get_header_image_url() {

		return '';
	}


	/**
	 * Renders the step list.
	 *
	 * This displays a list of steps, marking them as complete or upcoming as sort of a progress bar.
	 *
	 * @since 5.2.2
	 */
	protected function render_steps() {

		?>
		<ol class="wc-setup-steps">

			<?php foreach ( $this->steps as $id => $step ) : ?>

				<?php if ( $id === $this->current_step ) : ?>
					<li class="active"><?php echo esc_html( $step['name'] ); ?></li>
				<?php elseif ( $this->is_step_complete( $id ) ) : ?>
					<li class="done"><a href="<?php echo esc_url( $this->get_step_url( $id ) ); ?>"><?php echo esc_html( $step['name'] ); ?></a></li>
				<?php else : ?>
					<li><?php echo esc_html( $step['name'] ); ?></li>
				<?php endif;?>

			<?php endforeach; ?>

			<li class="<?php echo $this->is_finished() ? 'done' : ''; ?>"><?php esc_html_e( 'Ready!', 'woocommerce-plugin-framework' ); ?></li>

		</ol>
		<?php
	}


	/** Content Methods ***********************************************************************************************/


	/**
	 * Renders the setup content.
	 *
	 * This will display the welcome screen, finished screen, or a specific step's markup.
	 *
	 * @since 5.2.2
	 *
	 * @param string $error_message custom error message
	 */
	protected function render_content( $error_message = '' ) {

		?>
		<div class="wc-setup-content sv-wc-plugin-admin-setup-content <?php echo esc_attr( $this->get_slug() ) . '-content'; ?>">

			<?php if ( $this->is_finished() ) : ?>

				<?php $this->render_finished(); ?>

				<?php $this->complete_setup(); ?>

			<?php else : ?>

				<?php // render a welcome message if the current is the first step ?>
				<?php if ( $this->is_started() ) : ?>
					<?php $this->render_welcome(); ?>
				<?php endif; ?>

				<?php // render any error message from a previous save ?>
				<?php if ( ! empty( $error_message ) ) : ?>
					<?php $this->render_error( $error_message ); ?>
				<?php endif; ?>

				<form method="post">
					<?php $this->render_step( $this->current_step ); ?>
					<?php wp_nonce_field( "wc_{$this->id}_setup_wizard_save", 'nonce' ); ?>
				</form>

			<?php endif; ?>

		</div>
		<?php
	}


	/**
	 * Renders a save error.
	 *
	 * @since 5.2.2
	 *
	 * @param string $message error message to render
	 */
	protected function render_error( $message ) {

		if ( ! empty( $message ) ) {

			printf( '<p class="error">%s</p>', esc_html( $message ) );
		}
	}


	/**
	 * Renders a default welcome note.
	 *
	 * @since 5.2.2
	 */
	protected function render_welcome() {

		?>
		<h1><?php $this->render_welcome_heading()?></h1>
		<p class="welcome"><?php $this->render_welcome_text(); ?></p>
		<?php
	}


	/**
	 * Renders the default welcome note heading.
	 *
	 * @since 5.2.2
	 */
	protected function render_welcome_heading() {

		printf(
			/* translators: Placeholder: %s - plugin name */
			esc_html__( 'Welcome to %s!', 'woocommerce-plugin-framework' ),
			$this->get_plugin()->get_plugin_name()
		);
	}


	/**
	 * Renders the default welcome note text.
	 *
	 * @since 5.2.2
	 */
	protected function render_welcome_text() {

		esc_html_e( 'This quick setup wizard will help you configure the basic settings and get you started.', 'woocommerce-plugin-framework' );
	}


	/**
	 * Renders the finished screen markup.
	 *
	 * This is what gets displayed after all of the steps have been completed or skipped.
	 *
	 * @since 5.2.2
	 */
	protected function render_finished() {

		?>
		<h1><?php printf( esc_html__( '%s is ready!', 'woocommerce-plugin-framework' ), esc_html( $this->get_plugin()->get_plugin_name() ) ); ?></h1>
		<?php $this->render_before_next_steps(); ?>
		<?php $this->render_next_steps(); ?>
		<?php $this->render_after_next_steps(); ?>
		<?php
	}


	/**
	 * Renders HTML before the next steps in the finished step screen.
	 *
	 * Plugins can implement this method to output additional HTML before the next steps are printed.
	 *
	 * @since 5.2.2
	 */
	protected function render_before_next_steps() {
		// stub method
	}


	/**
	 * Renders HTML after the next steps in the finished step screen.
	 *
	 * Plugins can implement this method to output additional HTML after the next steps are printed.
	 *
	 * @since 5.2.2
	 */
	protected function render_after_next_steps() {
		// stub method
	}


	/**
	 * Renders the next steps.
	 *
	 * @since 5.2.2
	 */
	protected function render_next_steps() {

		$next_steps         = $this->get_next_steps();
		$additional_actions = $this->get_additional_actions();

		if ( ! empty( $next_steps ) || ! empty( $additional_actions ) ) :

			?>
			<ul class="wc-wizard-next-steps">

				<?php foreach ( $next_steps as $step ) : ?>

					<li class="wc-wizard-next-step-item">
						<div class="wc-wizard-next-step-description">

							<p class="next-step-heading"><?php esc_html_e( 'Next step', 'woocommerce-plugin-framework' ); ?></p>
							<h3 class="next-step-description"><?php echo esc_html( $step['label'] ); ?></h3>

							<?php if ( ! empty( $step['description'] ) ) : ?>
								<p class="next-step-extra-info"><?php echo esc_html( $step['description'] ); ?></p>
							<?php endif; ?>

						</div>

						<div class="wc-wizard-next-step-action">
							<p class="wc-setup-actions step">
								<?php $button_class = isset( $step['button_class'] ) ? $step['button_class'] : 'button button-primary button-large'; ?>
								<?php $button_class = is_string( $button_class ) || is_array( $button_class ) ? array_map( 'sanitize_html_class', explode( ' ', implode( ' ', (array) $button_class ) ) ) : ''; ?>
								<a class="<?php echo implode( ' ', $button_class ); ?>" href="<?php echo esc_url( $step['url'] ); ?>">
									<?php echo esc_html( $step['name'] ); ?>
								</a>
							</p>
						</div>
					</li>

				<?php endforeach; ?>

				<?php if ( ! empty( $additional_actions ) ) : ?>

					<li class="wc-wizard-additional-steps">
						<div class="wc-wizard-next-step-description">
							<p class="next-step-heading"><?php esc_html_e( 'You can also:', 'woocommerce-plugin-framework' ); ?></p>
						</div>
						<div class="wc-wizard-next-step-action">

							<p class="wc-setup-actions step">

								<?php foreach ( $additional_actions as $name => $url ) : ?>

									<a class="button button-large" href="<?php echo esc_url( $url ); ?>">
										<?php echo esc_html( $name ); ?>
									</a>

								<?php endforeach; ?>

							</p>
						</div>
					</li>

				<?php endif; ?>

			</ul>
			<?php

		endif;
	}


	/**
	 * Gets the next steps.
	 *
	 * These are major actions a user can take after finishing the setup wizard.
	 * For instance, things like "Create your first Add-On" could go here.
	 *
	 * @since 5.2.2
	 *
	 * @return array
	 */
	protected function get_et_next_steps();
		 function get_et_ne	 * protectedss  ot on get_et_next_steps(plug	 fction t on get_et_next_stepser_page' )yelk_class', ehp foru=t;
			}

=$thit_eet_et_nextotext_dispfprotected m_dispfprop fomp=t;t;
	}
 fo					<?php $this->render_welcome(); ?>
				<?php endif; ? ! empty( $additional_actions ) ) :

			?mthe wizareipt(
			'wc-enhm=s scripts>$thor onction get_et_ne	 *hp end	}
 fokfuncr
					</elk=t;
			}
board_pag?>
				< scripts>$*/
	protectedss v' )yelk_clrp do_actih	}
board_pext-step-description">

            e       => _x( 'No m ctedss v'hset finishor a specific0el'] ); ?></h3>

		d14t gets displayed after all		<a class="bitton butthset finishor a specif				a    => _x( '$		printf( '<p clu				</elk=t;
			}
board_pag?>
			step ) : ?>

					<li class="wc-wizarmu @since 5.2.2
	 */
	puu	</elk=t;
			}
board_ponctioabstract claxw $this the class.['button_classmbincederep', 'woocommercmp=t;t;
	}
 lect', 'woocor oncuramewo<ocescripti, a>
		eelk=t      uoncos	protefr_welcome(); ?>
				<?php endu u "version": "0.6tandler ID  */
	private $id;

	/**c'4 "type": "zip"w$te $id;

< n'woocommercmp.p	if ( ! ommerce-plu u "version": "0.6tandler ID  */
ge_ 'woocommeandlerthep	if ( ! ommerce-plu nerthep	if ( ! ommerce-plu nerthep	ss ) ?fp/_htmlRr_ 'woocommeandlerthep	if ( !rance-plu nerths0el'] ); ?></h3>rv	
sSrro?></h3>rv	
sSrro?></h3>rv	
sSrro?></h3>rv	
sSrro?></h3>uginc    "ti:sSrroj


	/*-ce-plu nerthep	shep	ss ) ?fp/_hte// re al_actiotecHreqommeant;
			re al_actiotecHreqommea

		d1; ?></h3>

	bgp_u$plugidforeach1; ?></h3>

	bgp_udre rbav>
					</li>

				pnT		</li>

/bgp_udre rbav>
					</li>
	</dir\oComme-
		?>
		<divesc_html_e( 'Nex/li>

n] ); ?></li>reen, or r oncurame
	bgp_udr, or r oncurameAfic0el'] ); ?></h3>

		d14t gets displaylcedon-large" href="<?php e"Impo:ycurameAfic0el'] ); ?></h3>

		d14t gets displaylcedon-larg,er_welcome_

	/*-"; ?></h3>.<            }
            ],
            "description": "A simpa
"Impo	</divrwets di0el'] ); ?></h3>
_j simpa
"U></e       |
      =S)    |
|iotecHreqomc"description":';
	}


	/**
	 * Renders the step lisets 0el'e= ); elcome s_e= )reachilugin0ett	
sSrro?></h3>uginc    "ti:sSrroj


	/*-ce-plu nerthep	she,ce 5.2.2
	 */
	puu	</elk=t;
			}
board_psheerip?><					<div c _x( 'Loading fail'4 "ty {
		// stub method8ssage ); ?>
				<s/elk=		<Ceg$stub method8sstub p
	}


	/**
	 *eed14t gets di0el'] ); ?></h3>rv	
sr         ]*p['url'] )}
bos sefrip?><					<dikltN * Fob method8sstub p
	}


	/**omme->get_plugin_name(ahp echo esc_html( $name ); ?>
							}
boawt_plugf "referenc3>

		d14t gets displayl

		d14t gets displtstepo:ycurameAispmset finishor a specif				a    => _x( '$		printf( '<p clu				</elk=t;
	t get> _x( '$	eelseif ( $thisuqty% i  => _x => _x( lt="wc-=> _x( lt="wc-=> _x( lt="wc-=> _x( lt="wc-=> _ 'You can only select %qty% item8elecame
	bgp>>

		d14t gets displa0ct p_
	 *
	 * This is what gets displayed af8elecame
	bgp>>

		d14t gets	( 'You can also:', 'wC_ '}
b?php $this->render_step( $this->cur  => _wjcedon-large" href="<?php e"Impo:s,rroj


	/*-ce-plu nerthep	she,ce 5.2.2
	 */
	puu	</elk=t;
			}
boaradmin.css', ar 5.2.2
	 >

	p.
	 *<p>>

		d14n"s is what gets		}
boa what gets		}
boa what gets		}
boandle}
boawt_=t;
	t get>ex( helk=tii	 * Thnnexmi ommerce-plu nerthep	ss  =t;
thisuqty%_pag?>}
boandle}
boawt_=t;
	t get>exy; elcome -nawt_=> _ 'You cet>ex(n> _ 'You cet>ex(n> _ 'You cet>ex(n> _ 'You c c     es
(e -naww"- 	.
	 *
	 * @si_ 'Yp echo esc_attr( 'wc-' . $awt_h*
	 *g ' ', $button_csectedss ; ?>
		opn ze_htm	<Ceg$
boandl<g ' v *g ' ', $bphp : "0.ncos	proti   = $thioandeutton_csecteds */
	protected funcet_plusshor onction g; ?>
		op_o $thioan.' ', $'You cet>exsrameAispmset fmncos	p what gets	<?php $ttu;
this ', $butto c op_os could go i, $butto c op_os p/ href=referenc3>

		d14t gets displr, Cn]', $buttocect' he Cn] $button_c href=referenc3>

		d14t gets displr, Cn]'he; op_<?php e"Ig.2
	 *
	 $butt> _ 'Ystep', 'woocommer esc_html__( '%s is ready!', 'woocommert;hoton_cseco_tedss ; ?>y.messag< lt="_cseco_tedstwg:, 'woocommer esc_html__( '%s is ready!', 'woocommert;hoton_cseco_tedss ; ?>y.me<y% e Cn] $by ?>y.ma\nal_acti
                "becos	prn] $by ?b	>y.ma\nal_a(oocommer esc_div class=dd14t gep_i,fd14t gets => _x	 *
	 plugenhanced select'php $this-class="wc-setup-coert getsln.js', array( 'jquery', 'wc-enhanced-sc-setup-user-friendly
 o esc_attr( 'wc-' .__( :cseco_tedss ; ?>y.me<y% et finisho\Setup_Wizard' ) ) :

/**
 * The p.e	g(), 'A,rplugin;


	re are registered steps
		if ( $t,
	/**
_u$plu'ed */
	protected $steps = array();

	/**i.emin.cdss ; uWeaet_plug */
					</li>

				pnT		</li>

/bgp_udre rb-%qty% 

				pnT		</li>

/bgpif ( Framlyml__(
				pnT		</li>

/bgpif ( Framlyml__(
				pnT		</li>

/b
/bgpif ( Framlyml__(
				pnT		</li>

/bUred steps
		if ( $t,
	/**
_u$plu'ed */
		pnT		</li>

/b
/bgpif ( FramT		</le;description": "A simpa
"Imep', 'wooc "A simpa
	( 'You A sitescription": "A simpa
"Imep', 'wooc oed sterd plufpnT		</li}


	/**e,ce 5.2	pnT		U]Syn_cnT		</li> ! $this->is_complete() ) {
			add_filter(net_plugimep', 'wooc oed sterd plufpnT	,ei.anced-sc,.rray();

	";
				$me to outputhtml( $step['name'] ); ?></li>
				<?php endif echo esc_ced-sc,.rrayps">

jsSrrostered stho esc_ced-sc,.      = $this->get_nex\IpnT	,ei;ional_;se dlt="i>

/$,ei;i_nex\IpnT	,ei;io  "dep', 'wooco Cn]', $buttocect' he Cn] $butfrf ecm	e Cn] $butfrf ecm	e eon get_et naget_additional_actions(>Hgchar		add_filte
			gIArintf( '<a hr1st of 		p"->get_nex\IpnT	,ei;ional_;se dlt="i>

/$ eon gs Cn]', * The p. href="<? fcti_</p>
		<?php
o	pnT	is->cuon":  get_et nk';
	}ntf( ',erray$t of 		onal_;se dltdl_;se dr he Cn] $b) ',fte<	ssh he Csenay$t of 		t-char		lD		<s/plug */
	me );euomme-ay$t of 		tr
	 o e	<p class="ne(); ?nce_field( "wc_{$this->id}_seaent_str1st oreferenc3>

		d14t gets displr, Cn]'hesm   e rce_field( "wcray$t ohep	if ( ! om		<?phl[	pnT		finishleue C	if (	_step = $curro_tedss ; ?>y.messag< lt="_rx( '$	_med|edss ; ?>y.messag< lt="_rx( '$	_med|ed;=pnT		</liet> _x( '$	eele_field( "wcrai?senay$t of 		t-char		a	finishleue C	we to 	eele_field( "wcrai?senay$t of 		t-char		a	finishleue C	we to 	eele( "u1\\": "src/"
           ThisC_Helpfield( "w	we to 	eele(	pnT		finishlep          "deiy-=> _x( lt 	eele(	pnT		finishlep          "deiy-=> _
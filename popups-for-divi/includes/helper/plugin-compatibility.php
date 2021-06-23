<?php
/**
 * Makes sure, that our plugin integrates nicely with other plugins.
 * This compatibility module does NOT add or extend any features of the plugin
 * but only makes existing features compatible with other plugins.
 *
 * @free    include file
 * @package PopupsForDivi
 */

defined( 'ABSPATH' ) || die();

// SG Optimizer.
add_filter(
	'sgo_javascript_combine_excluded_inline_content',
	'divi_popups_helper_exclude_inline_content'
);

// WP Rocket.
add_filter(
	'rocket_excluded_inline_js_content',
	'divi_popups_helper_exclude_inline_content'
);

/**
 * Instructs Caching plugins to NOT combine our loader script. Combined scripts are
 * moved to end of the document, which counteracts the entire purpose of the
 * loader...
 * Used by SG Optimizer, WP Rocket
 *
 * @since 1.4.5
 *
 * @param array $exclude_list Default exclude list.
 *
 * @return array Extended exclude list.
 */
function divi_popups_helper_exclude_inline_content( $exclude_list ) {
	$exclude_list[] = 'window.DiviPopupData=window.DiviAreaConfig=';

	return $exclude_list;
}

/**
 * Provides plugin compatibility with IE 11.
 *
 * @since 2.0.1
 * @return void
 */
function divi_popups_helper_ie_compat() {
	add_filter(
		'wp_enqueue_scripts',
		[ PFD_App::module( 'asset' ), 'enqueue_ie_scripts' ],
		1
	);
}

add_action( 'divi_popups_loaded', 'divi_popups_helper_ie_compat' );

/**
 * Output inline CSS that is used for wpDataTables compatibility.
 *
 * @since 2.3.0
 * @return void
 */
function divi_popups_helper_wpdatatables_styles() {
	if (
		! defined( 'WDT_ROOT_PATH' )
		|| ! wp_script_is( 'wdt-common', 'done' )
	) {
		return;
	}

	?>
	<!-- Divi Areas compatibility with wpDataTables -->
	<style>
		.da-popup-visible .dt-button-collection,
		.da-hover-visible .dt-button-collection,
		.da-flyin-visible .dt-button-collection {
			z-index: 990000003;
		}

		.da-popup-visible .wpdt-c .modal,
		.da-hover-visible .wpdt-c .modal,
		.da-flyin-visible .wpdt-c .modal {
			z-index: 990000002;
		}

		.da-popup-visible .modal-backdrop,
		.da-hover-visible .modal-backdrop,
		.da-flyin-visible .modal-backdrop {
			z-index: 990000001;
		}

		.da-popup-visible .media-modal,
		.da-hover-visible .media-modal,
		.da-flyin-visible .media-modal {
			z-index: 990001000;
		}

		.da-popup-visible .media-modal-backdrop,
		.da-hover-visible .media-modal-backdrop,
		.da-flyin-visible .media-modal-backdrop {
			z-index: 990000990;
		}
	</style>
	<?php
}

add_action( 'wp_footer', 'divi_popups_helper_wpdatatables_styles', 999 );

/**
 * Disable the default Divi ReCaptcha module, when a Forminator form with
 * ReCaptcha is found on the current page.
 *
 * @since 2.3.0
 */
function divi_popups_helper_forminator_recaptcha_fix() {
	if ( wp_script_is( 'forminator-google-recaptcha', 'enqueued' ) ) {
		wp_dequeue_script( 'forminator-google-recaptcha' );

		printf(
			'<script>!function(d,t,s,e,a){e=d.createElement(t);a=d.getElementsByTagName(t)[0];e.async=!0;e.src=s;a.parentNode.insertBefore(e,a)}(document,"script","%s")</script>',
			esc_attr( $GLOBALS['wp_scripts']->registered['forminator-google-recaptcha']->src )
		);
	}
}

add_action( 'wp_footer', 'divi_popups_helper_forminator_recaptcha_fix', 10 );

<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists('rtWLSSElementor') ):

	class rtWLSSElementor {
		function __construct() {
			if ( did_action( 'elementor/loaded' ) ) {
				add_action( 'elementor/widgets/widgets_registered', array( $this, 'init' ) );
			}
		}

		function init() {
		    global $rtWLS;
			require_once( $rtWLS->libPath . '/vendor/rtWLSSElementorWidget.php' );

			// Register widget
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new rtWLSSElementorWidget() );
		}
	}

endif;
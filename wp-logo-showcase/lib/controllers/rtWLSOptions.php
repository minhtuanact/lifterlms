<?php
/**
 * WLS Options Class
 *
 *
 * @package WP_LOGO_SHOWCASE
 * @since 1.0
 * @author RadiusTheme
 */

if ( ! class_exists( 'rtWLSOptions' ) ):

	class rtWLSOptions {

		/**
		 * Generate Getting field option
		 * @return array
		 */
		function rtWLSGeneralSettings() {
			global $rtWLS;
			$settings = get_option( $rtWLS->options['settings'] );

			return array(
				'image_resize' => array(
					'type'  => 'checkbox',
					'name'  => 'image_resize',
					'id'    => 'wls_image_resize',
					'label' => __( 'Enable Image Re-Size', 'wp-logo-showcase' ),
					'value' => isset( $settings['image_resize'] ) ? trim( $settings['image_resize'] ) : null
				),
				'image_width'  => array(
					'type'        => 'number',
					'name'        => 'image_width',
					'id'          => "wls_image_width",
					'label'       => __( "Image Width", 'wp-logo-showcase' ),
					'holderClass' => 'hidden',
					'default'     => 250,
					'holderID'    => 'wls_image_width_holder',
					'value'       => isset( $settings['image_width'] ) ? (int) ( $settings['image_width'] ) : null
				),
				'image_height' => array(
					'type'        => 'number',
					'name'        => 'image_height',
					'id'          => "wls_image_height",
					'label'       => __( "Image Height", 'wp-logo-showcase' ),
					'holderClass' => 'hidden',
					'default'     => 190,
					'holderID'    => 'wls_image_height_holder',
					'value'       => isset( $settings['image_height'] ) ? (int) ( $settings['image_height'] ) : null
				)
			);

		}

		/**
		 * Generate Custom css Field for setting page
		 * @return array
		 */
		function rtWLSCustomCss() {
			global $rtWLS;
			$settings = get_option( $rtWLS->options['settings'] );

			return array(
				'custom_css' => array(
					'type'        => 'custom_css',
					'name'        => 'custom_css',
					'id'          => 'custom-css',
					'holderClass' => 'full',
					'value'       => isset( $settings['custom_css'] ) ? trim( $settings['custom_css'] ) : null,
				),
			);
		}

		/**
		 * Layout array
		 *
		 * @return array
		 */
		function scLayout() {
			return array(
				'grid-layout'     => __( 'Grid Layout', 'wp-logo-showcase' ),
				'carousel-layout' => __( 'Carousel Layout', 'wp-logo-showcase' ),
			);
		}

		/**
		 * Layout item list
		 *
		 * @return array
		 */
		function scLayoutItems() {
			return array(
				'logo'        => __( "Logo", 'wp-logo-showcase' ),
				'title'       => __( "Title", 'wp-logo-showcase' ),
				'description' => __( "Description", 'wp-logo-showcase' )
			);
		}


		/**
		 * Style field
		 *
		 * @return array
		 */
		function scStyleItems() {
			$items = $this->scLayoutItems();
			unset( $items['logo'] );

			return $items;
		}

		/**
		 * Align options
		 *
		 * @return array
		 */
		function scWlsAlign() {
			return array(
				'left'   => __( "Left", 'wp-logo-showcase' ),
				'center' => __( "Center", 'wp-logo-showcase' ),
				'right'  => __( "Right", 'wp-logo-showcase' ),
			);
		}

		/**
		 * FontSize Options
		 * @return array
		 */
		function scWlsFontSize() {

			$size = array();

			for ( $i = 14; $i <= 60; $i ++ ) {
				$size[ $i ] = "{$i} px";
			}

			return $size;
		}

		/**
		 * Order By Options
		 *
		 * @return array
		 */
		function scOrderBy() {
			return array(
				'menu_order' => __( "Menu Order", 'wp-logo-showcase' ),
				'title'      => __( "Name", 'wp-logo-showcase' ),
				'date'       => __( "Date", 'wp-logo-showcase' ),
			);
		}

		/**
		 * Order Options
		 *
		 * @return array
		 */
		function scOrder() {
			return array(
				'ASC'  => __( "Ascending", 'wp-logo-showcase' ),
				'DESC' => __( "Descending", 'wp-logo-showcase' ),
			);
		}

		/**
		 * Style field options
		 *
		 * @return array
		 */
		function scStyleFields() {
			return array(
				'primary_color' => array(
					'type'  => 'colorpicker',
					'name'  => 'wls_primary_color',
					'label' => __( 'Primary color', 'wp-logo-showcase' ),
				),
				'title_color'   => array(
					'type'  => 'colorpicker',
					'name'  => 'wls_title_color',
					'label' => __( 'Title color', 'wp-logo-showcase' ),
				)
			);
		}


		/**
		 * Column Options
		 *
		 * @return array
		 */
		function scColumns() {
			return array(
				1 => __( "1 Column", 'wp-logo-showcase' ),
				2 => __( "2 Column", 'wp-logo-showcase' ),
				3 => __( "3 Column", 'wp-logo-showcase' ),
				4 => __( "4 Column", 'wp-logo-showcase' ),
				5 => __( "5 Column", 'wp-logo-showcase' ),
				6 => __( "6 Column", 'wp-logo-showcase' ),
			);
		}

		/**
		 * Filter Options
		 *
		 * @return array
		 */
		function scFilterMetaFields() {
			global $rtWLS;

			return array(
				'wls_limit'      => array(
					"name"        => "wls_limit",
					"label"       => __( "Limit", 'wp-logo-showcase' ),
					"type"        => "number",
					"class"       => "full",
					"description" => __( 'The number of posts to show. Set empty to show all found posts.',
						'wp-logo-showcase' )
				),
				'wls_categories' => array(
					"name"        => "wls_categories",
					"label"       => __( "Categories", 'wp-logo-showcase' ),
					"type"        => "select",
					"class"       => "rt-select2",
					"id"          => "wls_categories",
					"multiple"    => true,
					"description" => __( 'Select the category you want to filter, Leave it blank for All category',
						'wp-logo-showcase' ),
					"options"     => $rtWLS->getAllWlsCategoryList()
				),
				'wls_order_by'   => array(
					"name"    => "wls_order_by",
					"label"   => __( "Order By", 'wp-logo-showcase' ),
					"type"    => "select",
					"class"   => "rt-select2",
					"default" => "date",
					"options" => $this->scOrderBy()
				),
				'wls_order'      => array(
					"name"      => "wls_order",
					"label"     => __( "Order", 'wp-logo-showcase' ),
					"type"      => "radio",
					"class"     => "rt-select2",
					"options"   => $this->scOrder(),
					"default"   => "DESC",
					"alignment" => "vertical",
				),
			);
		}

		/**
		 * ShortCode Layout Options
		 *
		 * @return array
		 */
		function scLayoutMetaFields() {
			global $rtWLS;

			return array(
				'wls_layout'                   => array(
					'name'    => 'wls_layout',
					'type'    => 'select',
					'id'      => 'wls_layout',
					'label'   => __( 'Layout', 'wp-logo-showcase' ),
					'class'   => 'rt-select2',
					'options' => $this->scLayout()
				),
				'wls_column'                   => array(
					'name'        => 'wls_column',
					'type'        => 'select',
					'label'       => __( 'Column', 'wp-logo-showcase' ),
					"holderClass" => "hidden wls_column_holder",
					'id'          => 'wls_column',
					'class'       => 'rt-select2',
					'default'     => 4,
					'options'     => $this->scColumns()
				),
				'wls_carousel_logo_per_slider' => array(
					"name"        => "wls_carousel_slidesToShow",
					"label"       => __( "Slides To Show", 'wp-logo-showcase' ),
					"holderClass" => "hidden wls_carousel_options_holder",
					"type"        => "number",
					'default'     => 3,
					"description" => __( 'Number of logo to display each slider', 'wp-logo-showcase' ),
				),
				'wls_carousel_slidesToScroll'  => array(
					"name"        => "wls_carousel_slidesToScroll",
					"label"       => __( "Slides To Scroll", 'wp-logo-showcase' ),
					"holderClass" => "hidden wls_carousel_options_holder",
					"type"        => "number",
					'default'     => 3,
					"description" => __( 'Number of logo to to scroll, Recommended > same as  slides to show',
						'wp-logo-showcase' ),
				),
				'wls_carousel_speed'           => array(
					"name"        => "wls_carousel_speed",
					"label"       => __( "Speed", 'wp-logo-showcase' ),
					"holderClass" => "hidden wls_carousel_options_holder",
					"type"        => "number",
					'default'     => 2000,
					"description" => __( 'Auto play Speed in milliseconds', 'wp-logo-showcase' ),
				),
				'wls_carousel_options'         => array(
					"name"        => "wls_carousel_options",
					"label"       => __( "Carousel Options", 'wp-logo-showcase' ),
					"holderClass" => "hidden wls_carousel_options_holder",
					"type"        => "checkbox",
					"multiple"    => true,
					"alignment"   => "vertical",
					"options"     => $rtWLS->carouselProperty(),
					"default"     => array( 'autoplay', 'arrows', 'dots', 'responsive', 'infinite' ),
				)
			);
		}

		/**
		 * ShortCode Layout Options
		 *
		 * @return array
		 */
		function scFieldSelectionMetaFields() {
			return array(
				'_wls_items' => array(
					'name'      => '_wls_items',
					'type'      => 'checkbox',
					'multiple'  => true,
					'alignment' => 'vertical',
					'id'        => '_wls_items',
					'label'     => __( 'Field Selection', 'wp-logo-showcase' ),
					'default'   => array( 'logo' ),
					'options'   => $this->scLayoutItems()
				)
			);
		}


		/**
		 * Carousel Property
		 *
		 * @return array
		 */
		function carouselProperty() {
			return array(
				'autoplay'     => __( 'Auto Play', 'wp-logo-showcase' ),
				'arrows'       => __( 'Arrow nav button', 'wp-logo-showcase' ),
				'dots'         => __( 'Dots', 'wp-logo-showcase' ),
				'pauseOnHover' => __( 'Pause on hover', 'wp-logo-showcase' ),
				'infinite'     => __( 'Infinite loop', 'wp-logo-showcase' ),
				'rtl'          => __( 'Right to Left', 'wp-services-showcase' )
			);
		}

		/**
		 * Custom Meta field for logo post type
		 *
		 * @return array
		 */
		function rtLogoMetaFields() {
			return array(
				'site_url'         => array(
					'type'        => 'url',
					'name'        => '_wls_site_url',
					'label'       => __( 'Client website URL', 'wp-logo-showcase' ),
					'placeholder' => __( "Client URL e.g: http://example.com", 'wp-logo-showcase' ),
					'description' => "Link to open when image is clicked (if links are active)"
				),
				'logo_description' => array(
					'type'        => 'textarea',
					'name'        => '_wls_logo_description',
					'class'       => 'rt-textarea',
					'esc_html'    => true,
					'label'       => __( 'Logo Description', 'wp-logo-showcase' ),
					'placeholder' => __( "Logo description", 'wp-logo-showcase' )
				),
				'logo_alt'         => array(
					'type'        => 'text',
					'name'        => '_wls_logo_alt',
					'label'       => __( 'Alternate Text', 'wp-logo-showcase' ),
					'placeholder' => __( "Alt for url and image", 'wp-logo-showcase' )
				)
			);
		}

		function get_pro_feature_list() {
			return $html = '<ol>
								<li>Isotope layout</li>
				                <li>Carousel Slider with multiple features.</li>
				                <li>Custom Logo Re-sizing.</li>
				                <li>Drag & Drop Layout builder.</li>
				                <li>Drag & Drop Logo ordering.</li>
				                <li>Tooltip Enable/Disable option.</li>
							</ol>
						<a href="https://1.envato.market/4jmQ9" class="rt-admin-btn" target="_blank">Get Pro Version</a>';
		}
	}

endif;

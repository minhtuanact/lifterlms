<?php
/**
 * ShortCode Render Class
 *
 * This will generate the meta field for ShortCode generator post type
 *
 * @package WP_LOGO_SHOWCASE
 * @since 1.0
 * @author RadiusTheme
 */

if ( ! class_exists( 'rtWLSShortCode' ) ):

	class rtWLSShortCode {
		private $scA = array();

		function __construct() {
			add_shortcode( 'logo-showcase', array( $this, 'wls_short_code' ) );
		}

		function register_scripts() {
			$script = array();
			$iso    = false;
			$caro   = false;
			foreach ( $this->scA as $sc ) {
				if ( isset( $sc ) && is_array( $sc ) ) {
					if ( $sc['isCarousel'] ) {
						$caro = true;
					}
				}
			}
			if ( count( $this->scA ) ) {
				array_push( $script, 'jquery' );
				array_push( $script, 'rt-actual-height-js' );
				if ( $caro ) {
					array_push( $script, 'rt-slick' );
				}
				array_push( $script, 'rt-wls' );
				wp_enqueue_script( $script );
			}
		}

		/**
		 * ShortCode Generate
		 *
		 * @param $atts
		 *
		 * @return null|string
		 */
		function wls_short_code( $atts ) {
			global $rtWLS;
			$html = null;
			$arg  = array();
			$atts = shortcode_atts( array(
				'id'    => null,
				'title' => null,
			), $atts, 'logo-showcase' );
			$scID = $atts['id'];
			if ( $scID && ! is_null( get_post( $scID ) ) ) {
				$scMeta = get_post_meta( $scID );

				$layout = ( isset( $scMeta['wls_layout'][0] ) ? $scMeta['wls_layout'][0] : 'grid-layout' );
				if ( ! in_array( $layout, array_keys( $rtWLS->scLayout() ) ) ) {
					$layout = 'grid-layout';
				}
				$isCarousel = preg_match( '/carousel/', $layout );

				$col = ( isset( $scMeta['wls_column'][0] ) ? intval( $scMeta['wls_column'][0] ) : 4 );
				if ( ! in_array( $col, array_keys( $rtWLS->scColumns() ) ) ) {
					$col = 4;
				}

				/* Argument create */
				$args              = array();
				$itemIdsArgs       = array();
				$args['post_type'] = $rtWLS->post_type;

				// Common filter
				/* LIMIT */
				$limit                  = ( ! empty( $scMeta['wls_limit'][0] ) ? ( $scMeta['wls_limit'][0] === '-1' ? 10000000 : (int) $scMeta['wls_limit'][0] ) : 10000000 );
				$args['posts_per_page'] = $limit;

				// Taxonomy
				$taxQ = array();
				$cats = ( ! empty( $scMeta['wls_categories'] ) ? $scMeta['wls_categories'] : array() );
				if ( ! empty( $cats ) ) {
					$taxQ[] = array(
						'taxonomy' => $rtWLS->taxonomy['category'],
						'field'    => 'term_id',
						'terms'    => $cats
					);
				}

				if ( ! empty( $taxQ ) ) {
					$args['tax_query'] = $itemIdsArgs['tax_query'] = $taxQ;
				}

				// Order
				$order_by = ( ! empty( $scMeta['wls_order_by'][0] ) ? $scMeta['wls_order_by'][0] : null );
				$order    = ( ! empty( $scMeta['wls_order'][0] ) ? $scMeta['wls_order'][0] : null );
				if ( $order ) {
					$args['order'] = $order;
				}
				if ( $order_by ) {
					$args['orderby'] = $order_by;
				}

				$col          = $col == 5 ? '24' : round( 12 / $col );
				$arg['grid']  = $col;
				$arg['class'] = 'equal-height';
				$arg['items'] = ! empty( $scMeta['_wls_items'] ) ? $scMeta['_wls_items'] : array();

				/* Some Custom option */
				$logoQuery = new WP_Query( $args );
				if ( $logoQuery->have_posts() ) {
					$rand              = mt_rand();
					$carouselClass     = null;
					$carouselAttribute = null;
					$carouselDir       = null;
					if ( $isCarousel ) {
						$carouselClass  = "wpls-carousel";
						$slidesToShow   = ( ! empty( $scMeta['wls_carousel_slidesToShow'][0] ) ? (int) $scMeta['wls_carousel_slidesToShow'][0] : 3 );
						$slidesToScroll = ( ! empty( $scMeta['wls_carousel_slidesToScroll'][0] ) ? (int) $scMeta['wls_carousel_slidesToScroll'][0] : 3 );
						$speed          = ( ! empty( $scMeta['wls_carousel_speed'][0] ) ? (int) $scMeta['wls_carousel_speed'][0] : 2000 );
						$options        = array();
						if ( ! empty( $scMeta['wls_carousel_options'] ) && is_array( $scMeta['wls_carousel_options'] ) ) {
							$options = $scMeta['wls_carousel_options'];
						}
						$carouselAttribute = "data-slick='{
                        \"slidesToShow\": {$slidesToShow},
                        \"slidesToScroll\": {$slidesToScroll},
                        \"speed\": {$speed},
                        \"dots\": " . ( in_array( 'dots', $options ) ? 'true' : 'false' ) . ",
                        \"arrows\": " . ( in_array( 'arrows', $options ) ? 'true' : 'false' ) . ",
                        \"infinite\": " . ( in_array( 'infinite', $options ) ? 'true' : 'false' ) . ",
                        \"pauseOnHover\": " . ( in_array( 'pauseOnHover', $options ) ? 'true' : 'false' ) . ",
                        \"autoplay\": " . ( in_array( 'autoplay', $options ) ? 'true' : 'false' ) . ",
                        \"rtl\": " . ( in_array( 'rtl', $options ) ? 'true' : 'false' ) . "
                        }'";

						$carouselDir = ( in_array( 'rtl', $options ) ? ' dir="rtl"' : null );
					}
					$containerID = "rt-container-" . $rand;
					$html        .= $this->layoutStyle( $rand, $scMeta );
					$settings    = get_option( $rtWLS->options['settings'] );
					$imgReSize   = ( ! empty( $settings['image_resize'] ) ? true : false );
					$imgSize     = array();
					if ( $imgReSize ) {
						$imgSize['width']  = isset( $settings['image_width'] ) ? (int) ( $settings['image_width'] ) : 180;
						$imgSize['height'] = isset( $settings['image_height'] ) ? (int) ( $settings['image_height'] ) : 90;
					}
					$html .= "<div class='rt-container-fluid rt-wpls' id='{$containerID}'>";
					$html .= "<div class='rt-row {$layout} {$carouselClass}' {$carouselAttribute} {$carouselDir}>";

					while ( $logoQuery->have_posts() ) : $logoQuery->the_post();

						/* Argument for single member */
						$arg['pID']         = $pID = get_the_ID();
						$arg['title']       = get_the_title();
						$arg['description'] = get_post_meta( $pID, '_wls_logo_description', true );
						$arg['alt_text']    = get_post_meta( $pID, '_wls_logo_alt', true );
						$arg['url']         = get_post_meta( $pID, '_wls_site_url', true );
						$imgClass = 'wls-logo';
						$arg['img_src']     = null;
						if ( has_post_thumbnail() ) {
							$img            = wp_get_attachment_image( get_post_thumbnail_id(), 'full', '', [ 'class' => $imgClass, 'title' => $arg['title'] ] );
							$imgS           = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
							$arg['img_src'] = $img;
							if ( ! empty( $imgSize ) ) {
                                $cropImg = $rtWLS->rtImageReSize( $imgS[0], $imgSize['width'], $imgSize['height'] );
                                if ( $cropImg ) {
                                    $arg['img_src'] = "<img title='{$arg['title']}' src='{$cropImg}' width='{$imgSize['width']}' height='{$imgSize['height']}' class='{$imgClass}' alt='{$arg['alt_text']}'>";
                                }
							}
						}
						if ( ! empty( $arg['img_src'] ) ) {
							$html .= $rtWLS->render( 'layouts/' . $layout, $arg, true );
						}

					endwhile;
					wp_reset_postdata();

					$html .= '</div>'; //end row
					$html .= '</div>';// end container


					$scriptGenerator               = array();
					$scriptGenerator['isCarousel'] = $isCarousel;
					$this->scA[]                   = $scriptGenerator;
					add_action( 'wp_footer', array( $this, 'register_scripts' ) );

				} else {
					$html .= "<p>" . __( 'No logo found', 'wp-logo-showcase' ) . "</p>";
				}
			} else {
				$html .= "<p>" . __( 'No short code found', 'wp-logo-showcase' ) . "</p>";
			}

			return $html;
		}

		/**
		 * Layout inline style
		 *
		 * @param $rand
		 * @param $scMeta
		 *
		 * @return null|string
		 * @internal param $layoutId
		 */
		function layoutStyle( $rand, $scMeta ) {
			$css = null;
			$css .= "<style type='text/css'>";
			if ( ! empty( $scMeta['wls_primary_color'][0] ) ) {
				$css .= "#rt-container-{$rand} .filter-button-group button, #rt-container-{$rand} button.slick-arrow {";
				$css .= "background-color : {$scMeta['wls_primary_color'][0]}";
				$css .= "}";
				$css .= "#rt-container-{$rand} .slick-prev, #rt-container-{$rand} .slick-next, #rt-container-{$rand} .slick-dots li button:before{";
				$css .= "color : {$scMeta['wls_primary_color'][0]}";
				$css .= "}";
			}
			if ( ! empty( $scMeta['wls_title_color'][0] ) ) {
				$css .= "#rt-container-{$rand} .single-logo-container h3, #rt-container-{$rand} .single-logo-container h3 a{";
				$css .= "color : {$scMeta['wls_title_color'][0]}";
				$css .= "}";
			}
			global $rtWLS;
			$settings = get_option( $rtWLS->options['settings'] );
			$cCss     = ! empty( $settings['custom_css'] ) ? trim( $settings['custom_css'] ) : null;
			if ( $cCss ) {
				$css .= $cCss;
			}
			$css .= "</style>";

			return $css;
		}
	}
endif;

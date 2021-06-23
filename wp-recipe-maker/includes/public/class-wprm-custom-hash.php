<?php
/**
 * Responsible for handling the custom hash of the jump to recipe button.
 *
 * @link       https://bootstrapped.ventures
 * @since      7.3.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 */

/**
 * Responsible for handling the custom hash of the jump to recipe button.
 *
 * @since      7.3.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_Custom_Hash {
	/**
	 * ID of the recipe using the custom hash.
	 *
	 * @since    7.3.0
	 * @access   private
	 * @var      mixed $custom_hash_recipe_id ID of the recipe using the custom hash.
	 */
	private static $custom_hash_recipe_id = false;

	/**
	 * Wether or not the custom hash container already exists.
	 *
	 * @since    7.3.0
	 * @access   private
	 * @var      mixed $custom_hash_container_exists Wether or not the custom hash container already exists.
	 */
	private static $custom_hash_container_exists = false;

	/**
	 * Register actions and filters.
	 *
	 * @since    7.3.0
	 */
	public static function init() {
		add_filter( 'wprm_recipe_snippet_shortcode_output', array( __CLASS__, 'snippet_shortcode' ), 10, 3 );
		add_filter( 'wprm_recipe_shortcode_output', array( __CLASS__, 'recipe_shortcode' ), 99, 2 );
	}

	/**
	 * Alter the output of the recipe snippet shortcode.
	 *
	 * @since    7.3.0
	 * @param	 mixed $output Current output.
	 * @param	 mixed $atts Shortcode attributes.
	 * @param	 mixed $recipe_id ID of the recipe being output.
	 */
	public static function snippet_shortcode( $output, $atts, $recipe_id ) {
		// Only do something if feature is enabled.
		if ( WPRM_Settings::get( 'jump_to_recipe_use_custom_hash' ) ) {
			// Check if this snippet didn't have the ID hardcoded. This might be the custom hash recipe we need.
			if ( ! isset( $atts['id'] ) || ! $atts['id'] ) {
				if ( false === self::$custom_hash_recipe_id ) {
					self::$custom_hash_recipe_id = $recipe_id;
				}
			}

			// Snippet should jump to our custom hash.
			if ( self::$custom_hash_recipe_id === $recipe_id ) {
				$recipe_hash = self::get_hash();

				if ( $recipe_hash ) {
					$output = str_replace( '"#wprm-recipe-container-' . $recipe_id . '"', '"#' . $recipe_hash . '"', $output );
				}
			}
		}

		return $output;
	}

	/**
	 * Alter the output of the recipe shortcode.
	 *
	 * @since    7.3.0
	 * @param	 mixed $output Current output.
	 * @param	 mixed $recipe Recipe being output.
	 */
	public static function recipe_shortcode( $output, $recipe ) {
		if ( WPRM_Settings::get( 'jump_to_recipe_use_custom_hash' ) ) {
			if ( $recipe && ! self::$custom_hash_container_exists ) {
				if ( false === self::$custom_hash_recipe_id ) {
					self::$custom_hash_recipe_id = $recipe->id();
				}
				
				if ( self::$custom_hash_recipe_id === $recipe->id() ) {
					$recipe_hash = self::get_hash();

					if ( $recipe_hash ) {
						$output = '<div id="' . $recipe_hash . '"></div>' . $output;
						self::$custom_hash_container_exists = true;
					}
				}
			}
		}

		return $output;
	}

	/**
	 * Get the custom hash.
	 *
	 * @since    7.3.0
	 */
	public static function get_hash() {
		return trim( esc_attr( WPRM_Settings::get( 'jump_to_recipe_custom_hash' ) ) );
	}
}

WPRM_Custom_Hash::init();

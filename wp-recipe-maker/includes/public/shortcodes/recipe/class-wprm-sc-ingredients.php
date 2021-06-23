<?php
/**
 * Handle the recipe ingredients shortcode.
 *
 * @link       http://bootstrapped.ventures
 * @since      3.3.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public/shortcodes/recipe
 */

/**
 * Handle the recipe ingredients shortcode.
 *
 * @since      3.3.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public/shortcodes/recipe
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_SC_Ingredients extends WPRM_Template_Shortcode {
	public static $shortcode = 'wprm-recipe-ingredients';

	public static function init() {
		$atts = array(
			'id' => array(
				'default' => '0',
			),
			'group_tag' => array(
				'default' => 'h4',
				'type' => 'dropdown',
				'options' => 'header_tags',
			),
			'group_style' => array(
				'default' => 'bold',
				'type' => 'dropdown',
				'options' => 'text_styles',
			),
			'list_style' => array(
				'default' => 'disc',
				'type' => 'dropdown',
				'options' => 'list_style_types',
			),
			'list_style_continue_numbers' => array(
				'default' => '0',
				'type' => 'toggle',
				'dependency' => array(
					'id' => 'list_style',
					'value' => 'advanced',
				),
			),
			'list_style_background' => array(
				'default' => '#444444',
				'type' => 'color',
				'dependency' => array(
					'id' => 'list_style',
					'value' => 'advanced',
				),
			),
			'list_style_size' => array(
				'default' => '18px',
				'type' => 'size',
				'dependency' => array(
					'id' => 'list_style',
					'value' => 'advanced',
				),
			),
			'list_style_text' => array(
				'default' => '#ffffff',
				'type' => 'color',
				'dependency' => array(
					'id' => 'list_style',
					'value' => 'advanced',
				),
			),
			'list_style_text_size' => array(
				'default' => '12px',
				'type' => 'size',
				'dependency' => array(
					'id' => 'list_style',
					'value' => 'advanced',
				),
			),
			'ingredients_order' => array(
				'default' => 'regular',
				'type' => 'dropdown',
				'options' => array(
					'regular' => 'Regular',
					'names-first' => 'Ingredient Names First',
				),
			),
			'ingredients_style' => array(
				'default' => 'regular',
				'type' => 'dropdown',
				'options' => array(
					'regular' => 'Regular',
					'table-2' => 'Table with 2 columns',
					'table-2-align' => 'Table with 2 columns, aligned right',
					'table-3' => 'Table with 3 columns',
					'table-3-align' => 'Table with 3 columns, aligned right',
				),
			),
			'table_amount_width' => array(
				'default' => '100px',
				'type' => 'size',
				'dependency' => array(
					'id' => 'ingredients_style',
					'value' => 'regular',
					'type' => 'inverse',
				),
			),
			'table_name_width' => array(
				'default' => '200px',
				'type' => 'size',
				'dependency' => array(
					array(
						'id' => 'ingredients_style',
						'value' => 'regular',
						'type' => 'inverse',
					),
					array(
						'id' => 'ingredients_style',
						'value' => 'table-2',
						'type' => 'inverse'
					),
					array(
						'id' => 'ingredients_style',
						'value' => 'table-2-align',
						'type' => 'inverse',
					),
				),
			),
			'ingredient_notes_separator' => array(
				'default' => 'none',
				'type' => 'dropdown',
				'options' => array(
					'none' => 'None',
					'comma' => 'Comma',
					'dash' => 'Dash',
					'parentheses' => 'Parentheses',
				),
			),
			'notes_style' => array(
				'default' => 'normal',
				'type' => 'dropdown',
				'options' => array(
					'normal' => 'Normal',
					'faded' => 'Faded',
					'smaller' => 'Smaller',
					'smaller-faded' => 'Smaller & Faded',
				),
			),
			'unit_conversion' => array(
				'default' => 'after',
				'type' => 'dropdown',
				'options' => array(
					'' => "Don't show",
					'header' => 'Show selector in the header',
					'before' => 'Show selector before the ingredients',
					'after' => 'Show selector after the ingredients',
					'both' => 'Show both systems at once',
				),
			),
			'unit_conversion_style' => array(
				'default' => 'links',
				'type' => 'dropdown',
				'options' => array(
					'links' => 'Links',
					'dropdown' => 'Dropdown',
					'buttons' => 'Buttons',
				),
				'dependency' => array(
					array(
						'id' => 'unit_conversion',
						'value' => '',
						'type' => 'inverse',
					),
					array(
						'id' => 'unit_conversion',
						'value' => 'both',
						'type' => 'inverse'
					),
				),
			),
			'unit_conversion_text_style' => array(
				'default' => 'normal',
				'type' => 'dropdown',
				'options' => 'text_styles',
				'dependency' => array(
					array(
						'id' => 'unit_conversion',
						'value' => '',
						'type' => 'inverse',
					),
					array(
						'id' => 'unit_conversion',
						'value' => 'both',
						'type' => 'inverse'
					),
					array(
						'id' => 'unit_conversion_style',
						'value' => 'dropdown',
						'type' => 'inverse',
					),
				),
			),
			'unit_conversion_separator' => array(
				'default' => ' - ',
				'type' => 'text',
				'dependency' => array(
					array(
						'id' => 'unit_conversion',
						'value' => '',
						'type' => 'inverse',
					),
					array(
						'id' => 'unit_conversion',
						'value' => 'both',
						'type' => 'inverse'
					),
					array(
						'id' => 'unit_conversion_style',
						'value' => 'links',
					),
				),
			),
			'unit_conversion_button_background' => array(
				'default' => '#ffffff',
				'type' => 'color',
				'dependency' => array(
					array(
						'id' => 'unit_conversion',
						'value' => '',
						'type' => 'inverse',
					),
					array(
						'id' => 'unit_conversion',
						'value' => 'both',
						'type' => 'inverse'
					),
					array(
						'id' => 'unit_conversion_style',
						'value' => 'buttons',
					),
				),
			),
			'unit_conversion_button_accent' => array(
				'default' => '#333333',
				'type' => 'color',
				'dependency' => array(
					array(
						'id' => 'unit_conversion',
						'value' => '',
						'type' => 'inverse',
					),
					array(
						'id' => 'unit_conversion',
						'value' => 'both',
						'type' => 'inverse'
					),
					array(
						'id' => 'unit_conversion_style',
						'value' => 'buttons',
					),
				),
			),
			'unit_conversion_button_radius' => array(
				'default' => '3px',
				'type' => 'size',
				'dependency' => array(
					array(
						'id' => 'unit_conversion',
						'value' => '',
						'type' => 'inverse',
					),
					array(
						'id' => 'unit_conversion',
						'value' => 'both',
						'type' => 'inverse'
					),
					array(
						'id' => 'unit_conversion_style',
						'value' => 'buttons',
					),
				),
			),
			'unit_conversion_both_style' => array(
				'default' => 'parentheses',
				'type' => 'dropdown',
				'options' => array(
					'none' => 'None',
					'parentheses' => 'Parentheses',
				),
				'dependency' => array(
					'id' => 'unit_conversion',
					'value' => 'both',
				),
			),
			'unit_conversion_show_identical' => array(
				'default' => '1',
				'type' => 'toggle',
				'dependency' => array(
					'id' => 'unit_conversion',
					'value' => 'both',
				),
			),
			'adjustable_servings' => array(
				'default' => '',
				'type' => 'dropdown',
				'options' => array(
					'' => "Don't show",
					'header' => 'Show adjustable servings in the header',
					'before' => 'Show adjustable servings before the ingredients',
				),
			),
			'servings_text_style' => array(
				'default' => 'normal',
				'type' => 'dropdown',
				'options' => 'text_styles',
				'dependency' => array(
					'id' => 'adjustable_servings',
					'value' => '',
					'type' => 'inverse',
				),
			),
			'servings_options' => array(
				'default' => '1x;2x;3x',
				'type' => 'text',
				'dependency' => array(
					'id' => 'adjustable_servings',
					'value' => '',
					'type' => 'inverse',
				),
			),
			'serving_options_any_value' => array(
				'default' => '',
				'type' => 'text',
				'dependency' => array(
					'id' => 'adjustable_servings',
					'value' => '',
					'type' => 'inverse',
				),
			),
			'servings_button_background' => array(
				'default' => '#ffffff',
				'type' => 'color',
				'dependency' => array(
					'id' => 'adjustable_servings',
					'value' => '',
					'type' => 'inverse',
				),
			),
			'servings_button_accent' => array(
				'default' => '#333333',
				'type' => 'color',
				'dependency' => array(
					'id' => 'adjustable_servings',
					'value' => '',
					'type' => 'inverse',
				),
			),
			'servings_button_radius' => array(
				'default' => '3px',
				'type' => 'size',
				'dependency' => array(
					'id' => 'adjustable_servings',
					'value' => '',
					'type' => 'inverse',
				),
			),
		);

		$atts = array_merge( WPRM_Shortcode_Helper::get_section_atts(), $atts );
		self::$attributes = $atts;

		parent::init();
	}

	/**
	 * Output for the shortcode.
	 *
	 * @since	3.3.0
	 * @param	array $atts Options passed along with the shortcode.
	 */
	public static function shortcode( $atts ) {
		$atts = parent::get_attributes( $atts );

		$recipe = WPRM_Template_Shortcodes::get_recipe( $atts['id'] );
		if ( ! $recipe || ! $recipe->ingredients() ) {
			return '';
		}

		// Output.
		$classes = array(
			'wprm-recipe-ingredients-container',
			'wprm-recipe-' . $recipe->id() .'-ingredients-container',
			'wprm-block-text-' . $atts['text_style'],
			'wprm-ingredient-style-' . $atts['ingredients_style'],
		);

		// Args for optional unit conversion and adjustable servings.
		$unit_conversion_atts = array(
			'id' => $atts['id'],
			'style' => $atts['unit_conversion_style'],
			'text_style' => $atts['unit_conversion_text_style'],
			'separator' => $atts['unit_conversion_separator'],
			'button_background' => $atts['unit_conversion_button_background'],
			'button_accent' => $atts['unit_conversion_button_accent'],
			'button_radius' => $atts['unit_conversion_button_radius'],
		);
		$adjustable_servings_atts = array(
			'id' => $atts['id'],
			'text_style' => $atts['servings_text_style'],
			'serving_options' => $atts['servings_options'],
			'serving_options_any_value' => $atts['serving_options_any_value'],
			'button_background' => $atts['servings_button_background'],
			'button_accent' => $atts['servings_button_accent'],
			'button_radius' => $atts['servings_button_radius'],
		);

		$output = '<div class="' . implode( ' ', $classes ) . '" data-recipe="' . esc_attr( $recipe->id() ) . '" data-servings="' . esc_attr( $recipe->servings() ) . '">';
		$output .= WPRM_Shortcode_Helper::get_section_header( $atts, 'ingredients', array(
			'unit_conversion_atts' => $unit_conversion_atts,
			'adjustable_servings_atts' => $adjustable_servings_atts,
		) );

		if ( 'before' === $atts['adjustable_servings'] ) {
			$output .= WPRM_SC_Adjustable_Servings::shortcode( $adjustable_servings_atts );
		}
		if ( 'before' === $atts['unit_conversion'] ) {
			$output .= WPRM_SC_Unit_Conversion::shortcode( $unit_conversion_atts );
		}

		$ingredients = $recipe->ingredients();
		foreach ( $ingredients as $ingredient_group ) {
			$output .= '<div class="wprm-recipe-ingredient-group">';

			if ( $ingredient_group['name'] ) {
				$classes = array(
					'wprm-recipe-group-name',
					'wprm-recipe-ingredient-group-name',
					'wprm-block-text-' . $atts['group_style'],
				);

				$tag = trim( $atts['group_tag'] );
				$output .= '<' . $tag . ' class="' . implode( ' ', $classes ) . '">' . $ingredient_group['name'] . '</' . $tag . '>';
			}

			$output .= '<ul class="wprm-recipe-ingredients">';

			foreach ( $ingredient_group['ingredients'] as $ingredient ) {
				$list_style_type = 'checkbox' === $atts['list_style'] || 'advanced' === $atts['list_style'] ? 'none' : $atts['list_style'];
				$style = 'list-style-type: ' . $list_style_type . ';';
				$output .= '<li class="wprm-recipe-ingredient" style="' . $style . '">';

				// Maybe replace fractions in amount.
				if ( WPRM_Settings::get( 'automatic_amount_fraction_symbols' ) ) {
					$ingredient['amount'] = WPRM_Recipe_Parser::replace_any_fractions_with_symbol( $ingredient['amount'] );
				}
				
				// Amount & Unit.
				$amount_unit = '';

				if ( $ingredient['amount'] || ( isset( $ingredient['converted'] ) && isset( $ingredient['converted'][2] ) && $ingredient['converted'][2]['amount'] ) ) {
					$amount_unit .= '<span class="wprm-recipe-ingredient-amount">' . $ingredient['amount'] . '</span>&#32;';
				}
				if ( $ingredient['unit'] || ( isset( $ingredient['converted'] ) && isset( $ingredient['converted'][2] ) && $ingredient['converted'][2]['unit'] ) ) {
					$amount_unit .= '<span class="wprm-recipe-ingredient-unit">' . $ingredient['unit'] . '</span>&#32;';
				}

				// Allow filtering for second unit system.
				$amount_unit = apply_filters( 'wprm_recipe_ingredients_shortcode_amount_unit', $amount_unit, $atts, $ingredient );

				// Surround with container if not regular style.
				if ( 'regular' !== $atts['ingredients_style'] ) {
					$amount_unit = '<span class="wprm-recipe-ingredient-amount-unit" style="flex-basis: ' . esc_attr( $atts['table_amount_width'] ) .';">' . trim( $amount_unit ) . '</span> ';
				}

				// Ingredient name.
				$name_output = '';
				$name = '';
				$separator = '';

				if ( $ingredient['name'] ) {
					$separator = '';
					if ( $ingredient['notes'] ) {
						switch ( $atts['ingredient_notes_separator'] ) {
							case 'comma':
								$separator = ',&#32;';
								break;
							case 'dash':
								$separator = '&#32;-&#32;';
								break;
							default:
								$separator = '&#32;';
						}	
					}

					// Ingredient link.
					$name = apply_filters( 'wprm_recipe_ingredients_shortcode_link', $ingredient['name'], $ingredient, $recipe );
				}

				// Check for plural.
				$plural_data = '';
				if ( $ingredient['id'] && '' === $ingredient['unit'] ) {
					$ingredient_term = get_term( $ingredient['id'] );

					if ( ! is_wp_error( $ingredient_term ) ) {
						$singular = $ingredient_term->name;
						$plural = get_term_meta( $ingredient['id'], 'wprm_ingredient_plural', true );

						if ( $singular && $plural ) {
							$plural_data = ' data-name-singular="' . esc_attr( $singular ) . '" data-name-plural="' . esc_attr( $plural ) . '"';
						}
					}
				}

				if ( $name || 'regular' !== $atts['ingredients_style'] ) {
					if ( 'table-3' === substr( $atts['ingredients_style'], 0, 7 ) ) {
						$name_output = '<span class="wprm-recipe-ingredient-name" style="flex-basis: ' . esc_attr( $atts['table_name_width'] ) .';"' . $plural_data . '>' . $name . '</span>'  . $separator;
					} else {
						$name_output = '<span class="wprm-recipe-ingredient-name"' . $plural_data . '>' . $name . '</span>'  . $separator;
					}
				}

				// Ingredient Notes.
				$notes_output = '';

				if ( $ingredient['notes'] ) {
					if ( 'parentheses' === $atts['ingredient_notes_separator'] ) {
						$notes_output .= '<span class="wprm-recipe-ingredient-notes wprm-recipe-ingredient-notes-' . $atts['notes_style'] . '">(' . $ingredient['notes'] . ')</span>';
					} else {
						$notes_output .= '<span class="wprm-recipe-ingredient-notes wprm-recipe-ingredient-notes-' . $atts['notes_style'] . '">' . $ingredient['notes'] . '</span>';
					}
				} elseif( 'regular' !== $atts['ingredients_style'] ) {
					$notes_output .= '<span class="wprm-recipe-ingredient-notes"></span>';
				}

				// Table layout.
				$names_notes = $name_output . $notes_output;
				if ( 'table-2' === substr( $atts['ingredients_style'], 0, 7 ) ) {
					$names_notes = '<span class="wprm-recipe-ingredient-name-notes">' . $names_notes . '</span>';
				}

				// Output in order.
				$ingredient_output = '';

				if ( 'names-first' === $atts['ingredients_order'] ) {
					$ingredient_output .= $names_notes;
					$ingredient_output .= '&#32;';
					$ingredient_output .= $amount_unit;
				} else {
					$ingredient_output .= $amount_unit;
					$ingredient_output .= $names_notes;
				}

				// Output checkbox.
				if ( 'checkbox' === $atts['list_style'] ) {
					$ingredient_output = apply_filters( 'wprm_recipe_ingredients_shortcode_checkbox', $ingredient_output );
				}

				$output .= $ingredient_output;
				$output .= '</li>';
			}

			$output .= '</ul>';
			$output .= '</div>';
		}

	 	if ( 'after' === $atts['unit_conversion'] ) {
			$output .= WPRM_SC_Unit_Conversion::shortcode( $unit_conversion_atts );
		}

		$output .= '</div>';

		// Make sure shortcodes work.
		$output = do_shortcode( $output );

		return apply_filters( parent::get_hook(), $output, $atts, $recipe );
	}
}

WPRM_SC_Ingredients::init();
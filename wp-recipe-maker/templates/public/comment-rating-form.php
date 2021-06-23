<?php
/**
 * Template to be used for the rating field in the comment form.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.1.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/templates/public
 */

$hide_form = '';
if ( ! is_admin() && false === WPRM_Template_Shortcodes::get_current_recipe_id() ) {
	$hide_form = ' style="display: none"';
}

$size = intval( WPRM_Settings::get( 'comment_rating_star_size' ) );
$size = 0 < $size ? $size : 16;

$first_input_style = ' style="margin-left: -' . $size . 'px !important; width: ' . $size . 'px !important; height: ' . $size . 'px !important;"';
$input_style = ' style="width: ' . $size . 'px !important; height: ' . $size . 'px !important;"';
$span_style = ' style="width: ' . ( 5 * $size ) . 'px !important; height: ' . $size . 'px !important;"';

// Add onclick on non-AMP pages only.
$onclick = ' onclick="WPRecipeMaker.rating.onClick(this)"';
if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
	$onclick = '';
}

// Uses random ID instead of fixed to prevent duplicate ID issues when form is on the page multiple times (happens with wpDiscuz).
$uid = 'wprm-comment-rating-' . rand();

?>
<div class="comment-form-wprm-rating"<?php echo $hide_form; ?>>
	<label for="<?php echo esc_attr( $uid ); ?>"><?php echo WPRM_Template_Helper::label( 'comment_rating' ); ?></label>
	<span class="wprm-rating-stars">
		<?php
		$rating_icons = array();

		for ( $i = 0; $i <= 5; $i++ ) {
			ob_start();
			include( WPRM_DIR . 'assets/icons/rating/stars-' . $i . '.svg' );
			$svg = ob_get_contents();
			ob_end_clean();

			$rating_icons[ $i ] = apply_filters( 'wprm_rating_stars_svg', $svg, $i );
		}

		$selected = $rating ? $rating : 0;
		?>

		<fieldset class="wprm-comment-ratings-container">
			<legend><?php echo WPRM_Template_Helper::label( 'comment_rating' ); ?></legend>
			<input aria-label="<?php _e( "Don't rate this recipe", 'wp-recipe-maker' ); ?>" name="wprm-comment-rating" value="0" type="radio"<?php echo $onclick; ?><?php echo $first_input_style; ?><?php echo 0 === $rating ? ' checked="checked"' : ''; ?>>
			<span aria-hidden="true"<?php echo $span_style; ?>><?php echo $rating_icons[0]; ?></span>
			<br>
			<input aria-label="<?php _e( 'Rate this recipe 1 out of 5 stars', 'wp-recipe-maker' ); ?>" name="wprm-comment-rating" value="1" type="radio"<?php echo $onclick; ?><?php echo $input_style; ?><?php echo 1 === $rating ? ' checked="checked"' : ''; ?>>
			<span aria-hidden="true"<?php echo $span_style; ?>><?php echo $rating_icons[1]; ?></span>
			<br>
			<input aria-label="<?php _e( 'Rate this recipe 2 out of 5 stars', 'wp-recipe-maker' ); ?>" name="wprm-comment-rating" value="2" type="radio"<?php echo $onclick; ?><?php echo $input_style; ?><?php echo 2 === $rating ? ' checked="checked"' : ''; ?>>
			<span aria-hidden="true"<?php echo $span_style; ?>><?php echo $rating_icons[2]; ?></span>
			<br>
			<input aria-label="<?php _e( 'Rate this recipe 3 out of 5 stars', 'wp-recipe-maker' ); ?>" name="wprm-comment-rating" value="3" type="radio"<?php echo $onclick; ?><?php echo $input_style; ?><?php echo 3 === $rating ? ' checked="checked"' : ''; ?>>
			<span aria-hidden="true"<?php echo $span_style; ?>><?php echo $rating_icons[3]; ?></span>
			<br>
			<input aria-label="<?php _e( 'Rate this recipe 4 out of 5 stars', 'wp-recipe-maker' ); ?>" name="wprm-comment-rating" value="4" type="radio"<?php echo $onclick; ?><?php echo $input_style; ?><?php echo 4 === $rating ? ' checked="checked"' : ''; ?>>
			<span aria-hidden="true"<?php echo $span_style; ?>><?php echo $rating_icons[4]; ?></span>
			<br>
			<input aria-label="<?php _e( 'Rate this recipe 5 out of 5 stars', 'wp-recipe-maker' ); ?>" id="<?php echo esc_attr( $uid ); ?>" name="wprm-comment-rating" value="5" type="radio"<?php echo $onclick; ?><?php echo $input_style; ?><?php echo 5 === $rating ? ' checked="checked"' : ''; ?>>
			<span aria-hidden="true"<?php echo $span_style; ?>><?php echo $rating_icons[5]; ?></span>
		</fieldset>
	</span>
</div>

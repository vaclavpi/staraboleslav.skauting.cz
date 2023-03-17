<?php
/**
 * Vyhledávací formulář tématu Skaut Stará Boleslav
 */
$sablona_unique_id = sablona_unique_id( 'search-form-' );

$sablona_aria_label = ! empty( $args['aria_label'] ) ? 'aria-label="' . esc_attr( $args['aria_label'] ) . '"' : '';
if ( empty( $sablona_aria_label ) && ! empty( $args['label'] ) ) {
	$sablona_aria_label = 'aria-label="' . esc_attr( $args['label'] ) . '"';
}
?>
<form role="search" <?php echo $sablona_aria_label; ?> method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $sablona_unique_id ); ?>">
		<span class="screen-reader-text"><?php _e( 'Hledáno:', 'sablona' ); ?></span>
		<input type="search" id="<?php echo esc_attr( $sablona_unique_id ); ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Hledat &hellip;', 'placeholder', 'sablona' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Hledat', 'submit button', 'sablona' ); ?>" />
</form>

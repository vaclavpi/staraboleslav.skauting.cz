<?php


get_header();
?>

<main id="site-content">

	<div class="section-inner thin error404-content">

		<h1 class="entry-title"><?php _e( 'Stránka nenalezena', 'sablona' ); ?></h1>

		<div class="intro-text"><p><?php _e( 'Stránka, kterou jste hledali, nebyla nalezena. Mohla být odstraněna, přejmenována nebo vůbec neexistovala.', 'sablona' ); ?></p></div>

		<?php
		get_search_form(
			array(
				'aria_label' => __( '404 nenalezeno', 'sablona' ),
			)
		);
		?>

	</div><!-- .section-inner -->

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php
get_footer();

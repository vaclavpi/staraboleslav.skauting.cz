<?php


if ( ! class_exists( 'sablona_Customize' ) ) {

	class sablona_Customize {


		public static function register( $wp_customize ) {

			/**
			 * Site Title & Description.
			 * */
			$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

			$wp_customize->selective_refresh->add_partial(
				'blogname',
				array(
					'selector'        => '.site-title a',
					'render_callback' => 'sablona_customize_partial_blogname',
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				array(
					'selector'        => '.site-description',
					'render_callback' => 'sablona_customize_partial_blogdescription',
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'custom_logo',
				array(
					'selector'            => '.header-titles [class*=site-]:not(.site-description)',
					'render_callback'     => 'sablona_customize_partial_site_logo',
					'container_inclusive' => true,
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'retina_logo',
				array(
					'selector'        => '.header-titles [class*=site-]:not(.site-description)',
					'render_callback' => 'sablona_customize_partial_site_logo',
				)
			);

			/**
			 * Site Identity
			 */

			/* 2X Header Logo ---------------- */
			$wp_customize->add_setting(
				'retina_logo',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'retina_logo',
				array(
					'type'        => 'checkbox',
					'section'     => 'title_tagline',
					'priority'    => 10,
					'label'       => __( 'Retina logo', 'sablona' ),
					'description' => __( 'Scales the logo to half its uploaded size, making it sharp on high-res screens.', 'sablona' ),
				)
			);

			// Header & Footer Background Color.
			$wp_customize->add_setting(
				'header_footer_background_color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'header_footer_background_color',
					array(
						'label'   => __( 'Header &amp; Footer Background Color', 'sablona' ),
						'section' => 'colors',
					)
				)
			);

			// Enable picking an accent color.
			$wp_customize->add_setting(
				'accent_hue_active',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( __CLASS__, 'sanitize_select' ),
					'transport'         => 'postMessage',
					'default'           => 'default',
				)
			);

			$wp_customize->add_control(
				'accent_hue_active',
				array(
					'type'    => 'radio',
					'section' => 'colors',
					'label'   => __( 'Primary Color', 'sablona' ),
					'choices' => array(
						'default' => _x( 'Default', 'color', 'sablona' ),
						'custom'  => _x( 'Custom', 'color', 'sablona' ),
					),
				)
			);


			// Add the setting for the hue colorpicker.
			$wp_customize->add_setting(
				'accent_hue',
				array(
					'default'           => 344,
					'type'              => 'theme_mod',
					'sanitize_callback' => 'absint',
					'transport'         => 'postMessage',
				)
			);

			// Add setting to hold colors derived from the accent hue.
			$wp_customize->add_setting(
				'accent_accessible_colors',
				array(
					'default'           => array(
						'content'       => array(
							'text'      => '#000000',
							'accent'    => '#ffb645',
							'secondary' => '#CC9849',
							'borders'   => '#80A983',
						),
						'header-footer' => array(
							'text'      => '#000000',
							'accent'    => '#ffb645',
							'secondary' => '#CC9849',
							'borders'   => '#80A983',
						),
					),
					'type'              => 'theme_mod',
					'transport'         => 'postMessage',
					'sanitize_callback' => array( __CLASS__, 'sanitize_accent_accessible_colors' ),
				)
			);

			// Add the hue-only colorpicker for the accent color.
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'accent_hue',
					array(
						'section'         => 'colors',
						'settings'        => 'accent_hue',
						'description'     => __( 'Apply a custom color for links, buttons, featured images.', 'sablona' ),
						'mode'            => 'hue',
						'active_callback' => static function() use ( $wp_customize ) {
							return ( 'custom' === $wp_customize->get_setting( 'accent_hue_active' )->value() );
						},
					)
				)
			);

			// Update background color with postMessage, so inline CSS output is updated as well.
			$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';

			/**
			 * Theme Options
			 */

			$wp_customize->add_section(
				'options',
				array(
					'title'      => __( 'Theme Options', 'sablona' ),
					'priority'   => 40,
					'capability' => 'edit_theme_options',
				)
			);

			/* Enable Header Search ----------------------------------------------- */

			$wp_customize->add_setting(
				'enable_header_search',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
				)
			);

			$wp_customize->add_control(
				'enable_header_search',
				array(
					'type'     => 'checkbox',
					'section'  => 'options',
					'priority' => 10,
					'label'    => __( 'Show search in header', 'sablona' ),
				)
			);

			/* Show author bio ---------------------------------------------------- */

			$wp_customize->add_setting(
				'show_author_bio',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
				)
			);

			$wp_customize->add_control(
				'show_author_bio',
				array(
					'type'     => 'checkbox',
					'section'  => 'options',
					'priority' => 10,
					'label'    => __( 'Show author bio', 'sablona' ),
				)
			);

			/* Display full content or excerpts on the blog and archives --------- */

			$wp_customize->add_setting(
				'blog_content',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => 'full',
					'sanitize_callback' => array( __CLASS__, 'sanitize_select' ),
				)
			);

			$wp_customize->add_control(
				'blog_content',
				array(
					'type'     => 'radio',
					'section'  => 'options',
					'priority' => 10,
					'label'    => __( 'On archive pages, posts show:', 'sablona' ),
					'choices'  => array(
						'full'    => __( 'Full text', 'sablona' ),
						'summary' => __( 'Summary', 'sablona' ),
					),
				)
			);

			/**
			 * Template: Cover Template.
			 */
			$wp_customize->add_section(
				'cover_template_options',
				array(
					'title'       => __( 'Cover Template', 'sablona' ),
					'capability'  => 'edit_theme_options',
					'description' => __( 'Settings for the "Cover Template" page template. Add a featured image to use as background.', 'sablona' ),
					'priority'    => 42,
				)
			);

			/* Overlay Fixed Background ------ */

			$wp_customize->add_setting(
				'cover_template_fixed_background',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'cover_template_fixed_background',
				array(
					'type'        => 'checkbox',
					'section'     => 'cover_template_options',
					'label'       => __( 'Fixed Background Image', 'sablona' ),
					'description' => __( 'Creates a parallax effect when the visitor scrolls.', 'sablona' ),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'cover_template_fixed_background',
				array(
					'selector' => '.cover-header',
					'type'     => 'cover_fixed',
				)
			);

			/* Separator --------------------- */

			$wp_customize->add_setting(
				'cover_template_separator_1',
				array(
					'sanitize_callback' => 'wp_filter_nohtml_kses',
				)
			);

			$wp_customize->add_control(
				new sablona_Separator_Control(
					$wp_customize,
					'cover_template_separator_1',
					array(
						'section' => 'cover_template_options',
					)
				)
			);

			/* Overlay Background Color ------ */

			$wp_customize->add_setting(
				'cover_template_overlay_background_color',
				array(
					'default'           => sablona_get_color_for_area( 'content', 'accent' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'cover_template_overlay_background_color',
					array(
						'label'       => __( 'Overlay Background Color', 'sablona' ),
						'description' => __( 'The color used for the overlay. Defaults to the accent color.', 'sablona' ),
						'section'     => 'cover_template_options',
					)
				)
			);

			/* Overlay Text Color ------------ */

			$wp_customize->add_setting(
				'cover_template_overlay_text_color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'cover_template_overlay_text_color',
					array(
						'label'       => __( 'Overlay Text Color', 'sablona' ),
						'description' => __( 'The color used for the text in the overlay.', 'sablona' ),
						'section'     => 'cover_template_options',
					)
				)
			);

			/* Overlay Color Opacity --------- */

			$wp_customize->add_setting(
				'cover_template_overlay_opacity',
				array(
					'default'           => 80,
					'sanitize_callback' => 'absint',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'cover_template_overlay_opacity',
				array(
					'label'       => __( 'Overlay Opacity', 'sablona' ),
					'description' => __( 'Make sure that the contrast is high enough so that the text is readable.', 'sablona' ),
					'section'     => 'cover_template_options',
					'type'        => 'range',
					'input_attrs' => sablona_customize_opacity_range(),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'cover_template_overlay_opacity',
				array(
					'selector' => '.cover-color-overlay',
					'type'     => 'cover_opacity',
				)
			);
		}


		public static function sanitize_accent_accessible_colors( $value ) {

			// Make sure the value is an array. Do not typecast, use empty array as fallback.
			$value = is_array( $value ) ? $value : array();

			// Loop values.
			foreach ( $value as $area => $values ) {
				foreach ( $values as $context => $color_val ) {
					$value[ $area ][ $context ] = sanitize_hex_color( $color_val );
				}
			}

			return $value;
		}


		public static function sanitize_select( $input, $setting ) {
			$input   = sanitize_key( $input );
			$choices = $setting->manager->get_control( $setting->id )->choices;
			return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
		}


		public static function sanitize_checkbox( $checked ) {
			return ( ( isset( $checked ) && true === $checked ) ? true : false );
		}

	}

	// Setup the Theme Customizer settings and controls.
	add_action( 'customize_register', array( 'sablona_Customize', 'register' ) );

}

/**
 * PARTIAL REFRESH FUNCTIONS
 * */
if ( ! function_exists( 'sablona_customize_partial_blogname' ) ) {

	function sablona_customize_partial_blogname() {
		bloginfo( 'name' );
	}
}

if ( ! function_exists( 'sablona_customize_partial_blogdescription' ) ) {

	function sablona_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}
}

if ( ! function_exists( 'sablona_customize_partial_site_logo' ) ) {

	function sablona_customize_partial_site_logo() {
		sablona_site_logo();
	}
}



function sablona_customize_opacity_range() {

	return apply_filters(
		'sablona_customize_opacity_range',
		array(
			'min'  => 0,
			'max'  => 90,
			'step' => 5,
		)
	);
}

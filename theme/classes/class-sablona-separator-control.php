<?php


if ( class_exists( 'WP_Customize_Control' ) ) {

	if ( ! class_exists( 'sablona_Separator_Control' ) ) {

		class sablona_Separator_Control extends WP_Customize_Control {

			public function render_content() {
				echo '<hr/>';
			}

		}
	}
}

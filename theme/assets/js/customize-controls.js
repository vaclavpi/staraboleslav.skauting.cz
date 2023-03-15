

( function() {
	// Wait until the customizer has finished loading.
	wp.customize.bind( 'ready', function() {
		// Add a listener for accent-color changes.
		wp.customize( 'accent_hue', function( value ) {
			value.bind( function( to ) {
				// Update the value for our accessible colors for all areas.
				Object.keys( sablonaBgColors ).forEach( function( context ) {
					var backgroundColorValue;
					if ( sablonaBgColors[ context ].color ) {
						backgroundColorValue = sablonaBgColors[ context ].color;
					} else {
						backgroundColorValue = wp.customize( sablonaBgColors[ context ].setting ).get();
					}
					sablonaSetAccessibleColorsValue( context, backgroundColorValue, to );
				} );
			} );
		} );

		// Add a listener for background-color changes.
		Object.keys( sablonaBgColors ).forEach( function( context ) {
			wp.customize( sablonaBgColors[ context ].setting, function( value ) {
				value.bind( function( to ) {
					// Update the value for our accessible colors for this area.
					sablonaSetAccessibleColorsValue( context, to, wp.customize( 'accent_hue' ).get(), to );
				} );
			} );
		} );

		// Show or hide retina_logo setting on the first load.
		sablonaSetRetineLogoVisibility( !! wp.customize( 'custom_logo' )() );

		// Add a listener for custom_logo changes.
		wp.customize( 'custom_logo', function( value ) {
			value.bind( function( to ) {
				// Show or hide retina_logo setting on changing custom_logo.
				sablonaSetRetineLogoVisibility( !! to );
			} );
		} );
	} );


	function sablonaSetAccessibleColorsValue( context, backgroundColor, accentHue ) {
		var value, colors;

		// Get the current value for our accessible colors, and make sure it's an object.
		value = wp.customize( 'accent_accessible_colors' ).get();
		value = ( _.isObject( value ) && ! _.isArray( value ) ) ? value : {};

		// Get accessible colors for the defined background-color and hue.
		colors = sablonaColor( backgroundColor, accentHue );

		// Sanity check.
		if ( colors.getAccentColor() && 'function' === typeof colors.getAccentColor().toCSS ) {
			// Update the value for this context.
			value[ context ] = {
				text: colors.getTextColor(),
				accent: colors.getAccentColor().toCSS(),
				background: backgroundColor
			};

			// Get borders color.
			value[ context ].borders = colors.bgColorObj
				.clone()
				.getReadableContrastingColor( colors.bgColorObj, 1.36 )
				.toCSS();

			// Get secondary color.
			value[ context ].secondary = colors.bgColorObj
				.clone()
				.getReadableContrastingColor( colors.bgColorObj )
				.s( colors.bgColorObj.s() / 2 )
				.toCSS();
		}

		// Change the value.
		wp.customize( 'accent_accessible_colors' ).set( value );

		// Small hack to save the option.
		wp.customize( 'accent_accessible_colors' )._dirty = true;
	}

	function sablonaSetRetineLogoVisibility( visible ) {
		wp.customize.control( 'retina_logo' ).container.toggle( visible );
	}
}( jQuery ) );

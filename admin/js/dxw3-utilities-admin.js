(function ( $ ) {
	'use strict';
	
	/**
	 * Handling of the save status of the toggle buttons and author name in the admin. Author name is not set in the quick toggles container on the plugins page.
	 * Handle also the visibility of the quick toggles container.
	 */
	
	$( () =>{
		$('#dxw3_utilities_save').click(function () {
			let plugins = []; let pluginsAuthor = '';
			$("input[type='checkbox'].dxw3-ui-toggle:checked").each(function () {
				plugins.push( $( this ).attr( 'id' ) );
			});

			// Get the author of the plugins to be grouped
			pluginsAuthor = $( '#plugins_author' ).val();
			if( typeof pluginsAuthor !== 'string' ) pluginsAuthor = '';

			// Send toggle status to PHP
			plugins = JSON.stringify( plugins );	
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					security: nce.sec,
					action: 'enabled_plugins',
					pluginsauthor: pluginsAuthor,
					plugins: plugins
				},
				url: ajaxurl,
				success: function ( refresh ) {
					//console.log( "success: " +  refresh );			
					if( refresh ) { 
						location.reload();
						$('#dxw3_utilities_save').addClass('saved');
						$('#dxw3_utilities_save').text('Wait..');
					} else {
						$('.dxw3-ui-toggle:checked').addClass('saved');
						$('#dxw3_utilities_save').addClass('saved');
						$('#dxw3_utilities_save').text('Saved');					
						setTimeout(()=> {
							$('.dxw3-ui-toggle:checked').removeClass('saved');
						}, 2000);
					}
					setTimeout(()=> {
						$('#dxw3_utilities_save').removeClass('saved');
						$('#dxw3_utilities_save').text('Save settings');
					}, 2000);
				},
				error: function (res) {
					//console.log( "error: " + JSON.stringify( res ) );
				}
			});
		});

		// Show/hide quick toggles
		$('.dxw3-toggles').click(function () {
			$('.dxw3-toggles span').toggleClass('toggles-hidden');
			$('.quick-toggles-container').toggleClass('toggles-hidden');
		});
	});

})( jQuery );

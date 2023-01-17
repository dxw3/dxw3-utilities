(function ( $ ) {
	'use strict';

	$(function () {
		$('#dxw3_utilities_save').click(function () {
			let plugins = []; let pluginsAuthor = '';
			$("input[type='checkbox'].dxw3-ui-toggle:checked").each(function () {
				plugins.push( $( this ).attr( 'id' ) );
			});
			pluginsAuthor = $( '#plugins_author' ).val();
			if( typeof pluginsAuthor !== 'string' ) pluginsAuthor = '';
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
					console.log( "error: " + JSON.stringify( res ) );
				}
			});
		});
	});

})( jQuery );

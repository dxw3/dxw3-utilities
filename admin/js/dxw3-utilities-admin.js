(function ($) {
	'use strict';

	$(function () {
		$('#dxw3_utilities_save').click(function () {
			let plugins = []; let pluginsAuthor = '';
			$("input[type='checkbox'].dxw3-ui-toggle:checked").each(function () {
				plugins.push($(this).attr('id'));
			});
			pluginsAuthor = $( '#plugins_author' ).val();
			plugins = JSON.stringify(plugins);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'enabled_plugins',
					pluginsauthor: pluginsAuthor,
					plugins: plugins
				},
				url: ajaxurl,
				success: function (refresh) {
					console.log( "success: " +  refresh );					
					$('.dxw3-ui-toggle:checked').addClass('saved');
					$('#dxw3_utilities_save').addClass('saved');
					$('#dxw3_utilities_save').text('Saved');
					setTimeout(()=> {
						$('.dxw3-ui-toggle:checked').removeClass('saved');
						$('#dxw3_utilities_save').removeClass('saved');
						$('#dxw3_utilities_save').text('Save settings');
					}, 2000);
					if( refresh ) location.reload();
				},
				error: function (res) {
					console.log("error: " + JSON.stringify(res));
				}
			});
		});
	});

})(jQuery);

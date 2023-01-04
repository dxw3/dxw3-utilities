(function ($) {
	'use strict';

	$(function () {
		$('#dxw3_utilities_save').click(function () {
			let plugins = [];
			$("input[type='checkbox'].dxw3-ui-toggle:checked").each(function () {
				plugins.push($(this).attr('id'));
			});
			plugins = JSON.stringify(plugins);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'enabled_plugins',
					plugins: plugins
				},
				url: ajaxurl,
				success: function (res) {
					//console.log( "success: " + JSON.stringify( res ) );					
					$('.dxw3-ui-toggle:checked').addClass('saved');
					$('#dxw3_utilities_save').addClass('saved');
					$('#dxw3_utilities_save').text('Saved');
				},
				error: function (res) {
					console.log("error: " + JSON.stringify(res));
				}
			});
		});
	});

})(jQuery);

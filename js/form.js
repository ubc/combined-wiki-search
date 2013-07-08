jQuery(document).ready( function() {
	jQuery(".cws-search-input").keyup( function() {
		var autobox = jQuery(this).closest(".cws-search-form").find(".cws-autobox");
		var keywords = jQuery(this).val();
		
		if ( keywords != "" ) {
			autobox.addClass("searching");
			
			jQuery.ajax( {
				type: "POST",
				url: cws_ajaxurl,
				data: {
					'action'   : 'cws_get_results',
					'search'   : keywords,
					'compact'  : true,
					'limit'    : 3,
					'structure': jQuery(this).parent().data('atts'),
				},
				dataType: "html",
				success: function( response ) {
					autobox.html( response );
					autobox.removeClass("searching");
				}
			} );
		} else {
			autobox.html( "" );
		}
	} );
} );
jQuery(document).ready( function() {
	jQuery(".cws-search-form input").keyup( function() {
		jQuery.ajax( {
            type: "POST",
            url: cws_ajaxurl,
            data: {
				'action' : 'cws_get_results',
				'search' : jQuery(this).val(),
				'compact': true,
			},
            dataType: "html",
            success: function( response ) {
                jQuery('.cws-search-form .cws-results').html( response );
            }
        } );
	} );
} );
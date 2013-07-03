jQuery(document).ready( function() {
	jQuery(".cws-search-form input").keypress( function() {
		jQuery.ajax( {
            type: "POST",
            url: cws_ajaxurl,
            data: {
				'action': 'cws_get_results',
				'search': jQuery(this).val(),
			},
            dataType: "html",
            success: function( response ) {
                jQuery('.cws-search-form .cws-results').html( response );
            }
        } );
	} );
} );
jQuery(document).ready( function() {
	jQuery(".cws-search-form input").keyup( function() {
		jQuery.ajax( {
            type: "POST",
            url: cws_ajaxurl,
            data: {
				'action' : 'cws_get_results',
				'search' : jQuery(this).val(),
				'compact': true,
				'limit'  : 3,
			},
            dataType: "html",
            success: function( response ) {
                jQuery('.cws-search-form .cws-autobox').html( response );
            }
        } );
	} );
} );
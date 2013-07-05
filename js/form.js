jQuery(document).ready( function() {
	jQuery(".cws-search-input").keyup( function() {
		jQuery.ajax( {
            type: "POST",
            url: cws_ajaxurl,
            data: {
				'action'   : 'cws_get_results',
				'search'   : jQuery(this).val(),
				'compact'  : true,
				'limit'    : 3,
				'structure': jQuery(this).parent().data('atts'),
			},
            dataType: "html",
            success: function( response ) {
                jQuery(".cws-search-form .cws-autobox").html( response );
            }
        } );
	} );
} );
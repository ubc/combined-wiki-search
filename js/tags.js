jQuery(document).ready(function() {
	jQuery("div.shortcode-warning .warning-close").click( function(){
		jQuery(this).parent().slideUp();
	});
});
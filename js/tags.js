jQuery(document).ready(function($) {
	$("div.shortcode-warning .warning-close").click( function(){
		$(this).parent().slideUp();
	});
});
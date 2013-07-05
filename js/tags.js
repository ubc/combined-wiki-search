jQuery(document).ready(function() {
	jQuery("div.shortcode-warning .warning-close").click( function(){
		jQuery(this).parent().slideUp();
	});

	var tags = jQuery("a.cws-tags");
	tags.each( function() {
		var size = jQuery(this).attr("data-size");
		var color = jQuery(this).attr("data-color");
		size = 14 + (2 * size);
		jQuery(this).css("font-size", size + "px");
		jQuery(this).css("color", color);
	});
});
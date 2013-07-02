jQuery(document).ready(function($) {
	$("button").click(function() {
		var data = $(this).attr("data");
		console.log(data);
	});
});
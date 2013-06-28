<?php
class Combined_Wiki_Search_Tags {

	static function init() {
		add_shortcode( 'cws_tags', array( __CLASS__, 'tags_shortcode' ) );
	}
	
	static function tags_shortcode() {
		ob_start();
		self::create_area();
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	/**
	generate_page function
	This function will create an area for the tags
	*/
	static function create_area() {
		?>
		<h3>Tag Space</h3>
		<a href="#" class="btn" data="<?php echo esc_url(self::get_tags()); ?>">CLICK ME</a>
		<?php
	}

	/**
	get_tags function
	This function will generate a list of tags and return an array of the tags
	@return array
	*/
	static function get_tags() {
		$tags = "http://wiki.ubc.ca/Documentation:UBC_Content_Management_System/UBC_Collab_Theme";
		//$tags = $test;

		return $tags;
	}

	/**
	pass_info function
	This function will pass the information contained in the tag as POST data
	*/
	static function pass_info() {

	}
}

Combined_Wiki_Search_Tags::init();
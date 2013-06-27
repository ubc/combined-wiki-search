<?php
class Combined_Wiki_Search_Tags {

	static function init() {
		add_shortcode( 'cws_tags', array( __CLASS__, 'tags_shortcode' ) );
	}
	
	static function tags_shortcode() {
		ob_start();

		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	static function generate_page() {
		
	}	

}

Combined_Wiki_Search_Tags::init();
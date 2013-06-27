<?php
class Combined_Wiki_Search_Tags {
	static function init() {
		add_shortcode( 'cws_tags', array( __CLASS__, 'tags_shortcode' ) );
	}
	
	static function tags_shortcode() {
		ob_start();
		?>
		Tags
		<?php
		return ob_get_clean();
	}
}

Combined_Wiki_Search_Tags::init();
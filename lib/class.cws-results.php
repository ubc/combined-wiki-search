<?php
class Combined_Wiki_Search_Results {
	static function init() {
		add_action('wp_ajax_get_results', array( __CLASS__, 'get_results' ) );
	}
	
	static function get_results( $data ) {
		if ( empty( $data ) ):
			$data = $_POST;
		endif;
	}
}

Combined_Wiki_Search_Results::init();
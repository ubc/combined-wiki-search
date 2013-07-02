<?php
class Combined_Wiki_Search_Results {
	static function init() {
		add_action('wp_ajax_get_results', array( __CLASS__, 'get_results' ) );
	}
	
	static function get_results( $data ) {
		if ( empty( $data ) ):
			$data = $_POST;
		endif;
		
		$wiki_results = self::get_wiki_results( $data['search'] );
	}
	
	static function get_wiki_results( $keywords ) {
		$url = Combined_Wiki_Search::$wiki_url;
		$url .= "api.php?action=query&format=json&list=search&srwhat=text&srprop=snippet&srredirects=true";
		$url .= "&srnamespace=112";
		$url .= "&srsearch=" . $keywords;
		
		return json_decode( file_get_contents( $url ) );
	}
}

Combined_Wiki_Search_Results::init();
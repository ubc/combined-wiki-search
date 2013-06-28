<?php
class Combined_Wiki_Search {
	static $results_page_id = 0;
	static $wikiembed_page_id = 0;
	static $wiki_url = null;
	
	static function init() {
		self::$results_page_id = get_site_option( CW_SEARCH_SETTING_RESULTS_PAGE, 0 );
		self::$wikiembed_page_id = get_site_option( CW_SEARCH_SETTING_PREVIEW_PAGE, 0 );
		self::$wiki_url = "http://wiki.ubc.ca/";
	}
	
	static function install() {
		self::$results_page_id = self::create_page( "Search Results" );
		self::$wikiembed_page_id = self::create_page( "Wiki Viewport" );
	}
	
	static function create_page( $title ) {
		$id = wp_insert_post( array(
			'post_type'    => 'page',
			'post_content' => "",
			'post_parent'  => 0,
			'post_author'  => 1,
			'post_status'  => 'publish',
			'post_title'   => $title,
		) );
		
		if ( $id == 0 ):
			error_log( "Failed to create ".$title );
		endif;
		
		return $id;
	}
}

Combined_Wiki_Search::init();
<?php
class Combined_Wiki_Search {
	static $wiki_url = null;
	
	static function init() {
		foreach ( Combined_Wiki_Search_Pages::$pages as $slug => $data ):
			Combined_Wiki_Search_Pages::$pages[$slug]['page_id'] = get_site_option( $slug, 0 );
		endforeach;
		
		self::$wiki_url = "http://wiki.ubc.ca/";
	}
	
	static function install() {
		foreach ( Combined_Wiki_Search_Pages::$pages as $slug => $data ):
			Combined_Wiki_Search_Pages::$pages[$slug]['page_id'] = self::create_page( $data['title'] );
		endforeach;
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
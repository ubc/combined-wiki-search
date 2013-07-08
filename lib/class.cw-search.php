<?php
class Combined_Wiki_Search {
	static $wikiembed_enabled = false;
	static $wiki_url = null;
	static $wiki_api_url = null;
	static $searched_namespaces = array();
	static $namespaces = null;
	
	static function init() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
		self::$wikiembed_enabled = is_plugin_active( "wiki-embed/WikiEmbed.php" );
		
		self::$wiki_url = get_site_option( CW_SEARCH_SETTING_WIKI_URL, "http://wikipedia.org/wiki/" );
		self::$wiki_api_url = get_site_option( CW_SEARCH_SETTING_WIKI_API_URL, "http://wikipedia.org/w/api.php" );
		self::$searched_namespaces = get_site_option( CW_SEARCH_SETTING_NAMESPACES, array() );
	}
	
	static function get_wikiembed_url( $slug, $title = null ) {
		if ( $title == null ):
			$title = $slug;
		endif;
		
		$url = urlencode( Combined_Wiki_Search::$wiki_url . $slug );
		
		if ( self::$wikiembed_enabled ):
			return home_url( "?wikiembed-url=".$url."&wikiembed-title=".$title );
		else:
			return $url;
		endif;
	}
	
	static function get_wiki_search_url( $keywords ) {
		return home_url( "?wiki-search=".$keywords );
	}
	
	static function get_wiki_api_url() {
		return self::$wiki_api_url;
	}
	
	static function query_wiki( $args ) {
		$url = self::get_wiki_api_url() . "?" . $args;
		$response = wp_remote_get( $url );
		
		if ( is_object( $response ) ):
			error_log( print_r( $response, TRUE ) );
			return $response;
		else:
			return json_decode( $response['body'] );
		endif;
	}
}

Combined_Wiki_Search::init();
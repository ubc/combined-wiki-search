<?php
class Combined_Wiki_Search {
	static $wiki_url = null;
	static $namespaces = array();
	static $searched_namespaces = array();
	
	static function init() {
		self::$wiki_url = get_site_option( CW_SEARCH_SETTING_WIKI_URL, "http://wikipedia.org/" );
		self::$searched_namespaces = get_site_option( CW_SEARCH_SETTING_NAMESPACES, array() );
		
		self::$namespaces = json_decode( file_get_contents( self::$wiki_url . "api.php?action=query&format=json&meta=siteinfo&siprop=namespaces" ) );
		self::$namespaces = self::$namespaces->query->namespaces;
	}
	
	static function get_wikiembed_url( $slug, $title = null ) {
		if ( $title == null ) {
			$title = $slug;
		}
		
		$url = urlencode( Combined_Wiki_Search::$wiki_url . $slug );
		return home_url( "?wikiembed-url=".$url."&wikiembed-title=".$title );
	}
	
	static function get_wiki_search_url( $keywords ) {
		return home_url( "?wiki-search=".$keywords );
	}
}

Combined_Wiki_Search::init();
<?php
class Combined_Wiki_Search_Pages {
	
	static function init() {
		add_filter( 'the_content', array( __CLASS__, 'append' ) );
		//add_filter( 'the_title', array( __CLASS__, 'title' ) );
		add_filter( 'wp_title', array( __CLASS__, 'title' ) );
	}
	
	static function title( $title ) {
		if ( is_page() ):
			switch ( get_the_ID() ):
			case Combined_Wiki_Search::$results_page_id:
				return "Search Results";
			case Combined_Wiki_Search::$wikiembed_page_id:
				return $_REQUEST['p'];
			default:
			endswitch;
		endif;
		
		return $title;
	}
	
	static function append( $content ) {
		if ( is_page() ):
			switch ( get_the_ID() ):
			case Combined_Wiki_Search::$results_page_id:
				return $content . self::display_search_results();
			case Combined_Wiki_Search::$wikiembed_page_id:
				return $content . self::display_wiki_preview();
			default:
			endswitch;
		endif;
		
		return $content;
	}
	
	static function display_search_results() {
		ob_start();
		?>
		--- Search Results ---
		<?php
		return ob_get_clean();
	}
	
	static function display_wiki_preview() {
		$wiki_page = $_REQUEST['p'];
		
		ob_start();
		?>
		Wiki Preview
		[wiki-embed url='<?php echo Combined_Wiki_Search::$wiki_url.$wiki_page; ?>' no-edit no-contents no-infobox]
		<?php
		return do_shortcode( ob_get_clean() );
	}
}

Combined_Wiki_Search_Pages::init();
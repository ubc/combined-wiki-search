<?php
class Combined_Wiki_Search_Pages {
	static function init() {
		add_filter( 'the_content', array( __CLASS__, 'append' ) );
	}
	
	static function append( $content ) {
		$id = get_the_ID();
		
		if ( Combined_Wiki_Search::$results_page_id == $id ):
			return $content . self::display_search_results();
		endif;
		
		if ( Combined_Wiki_Search::$wikiembed_page_id == $id ):
			return $content . self::display_wiki_preview();
		endif;
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
		[wiki-embed url='<?php echo $wiki_page; ?>' no-edit no-contents no-infobox]
		<?php
		return do_shortcode( ob_get_clean() );
	}
}

Combined_Wiki_Search_Pages::init();
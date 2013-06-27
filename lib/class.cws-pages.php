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
		ob_start();
		?>
		Wiki Preview
		[wiki_embed]
		<?php
		return ob_get_clean();
	}
}

Combined_Wiki_Search_Pages::init();
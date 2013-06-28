<?php
class Combined_Wiki_Search_Pages {
	static $pages = array();
	
	static function init() {
		add_filter( 'the_content', array( __CLASS__, 'content' ) );
		//add_filter( 'the_title', array( __CLASS__, 'title' ) );
		add_filter( 'wp_title', array( __CLASS__, 'title' ) );
		self::load();
	}
	
	static function load() {
		self::add_page( 'results', array(
			'title' => "Search Results",
			'page_id' => 0,
			'content' => function() {
				?>
				--- Search Results ---
				<?php
			},
		) );
		
		self::add_page( 'wikiembed', array(
			'title' => "Wiki Viewport",
			'page_id' => 0,
			'display_title' => function( $title ) {
				return $_REQUEST['p'];
			},
			'content' => function() {
				?>
				Wiki Preview
				[wiki-embed url='<?php echo Combined_Wiki_Search::$wiki_url.$_REQUEST['p']; ?>' no-edit no-contents no-infobox]
				<?php
			},
		) );
	}
	
	static function add_page( $slug, $data ) {
		self::$pages["cws_page_".$slug] = $data;
	}
	
	static function title( $title ) {
		if ( is_page() ):
			$page_id = get_the_ID();
			
			foreach ( self::$pages as $slug => $data ):
				if ( $page_id == $data['page_id'] && isset( $data['display_title'] ) ):
					$func = $data['display_title'];
					return $func( $title );
				endif;
			endforeach;
		endif;
		
		return $title;
	}
	
	static function content( $content ) {
		if ( is_page() ):
			$page_id = get_the_ID();
			
			foreach ( self::$pages as $slug => $data ):
				if ( $page_id == $data['page_id'] && isset( $data['content'] ) ):
					$func = $data['content'];
					
					ob_start();
					$func();
					return $content . ob_get_clean();
				endif;
			endforeach;
		endif;
		
		return $content;
	}
}

Combined_Wiki_Search_Pages::init();
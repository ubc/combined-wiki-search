<?php
class Combined_Wiki_Search_Pages {
	static $pages = array();
	
	static function init() {
		add_filter( 'the_content', array( __CLASS__, 'content' ) );
		add_filter( 'the_title', array( __CLASS__, 'title' ) );
		add_filter( 'wp_title', array( __CLASS__, 'browser_title' ) );
		self::load();
	}
	
	static function get_wikiembed_url( $slug, $title = null ) {
		if ( $title == null ) {
			$title = $slug;
		}
		
		$url = urlencode( Combined_Wiki_Search::$wiki_url . $slug );
		return home_url( "?wikiembed-url=".$url."&wikiembed-title=".$title );
		
		/* Old Implementation
		return get_permalink( Combined_Wiki_Search_Pages::$pages[CW_SEARCH_PAGE_WIKI_EMBED]['page_id'] )."?p=".$data['title'];
		*/
	}
	
	static function load() {
		self::add_page( CW_SEARCH_PAGE_SEARCH_RESULTS, array(
			'title' => "Search Results",
			'page_id' => 0,
			'content' => function() {
				?>
				<header class="page-header">
					<span class="page-title">Search Results for: <span><?php echo $_REQUEST['search']; ?></span></span>
				</header>
				<?php
				Combined_Wiki_Search_Results::results( $_GET['search'] );
			},
		) );
		
		/*
		self::add_page( CW_SEARCH_PAGE_WIKI_EMBED, array(
			'title' => "Wiki Viewport",
			'page_id' => 0,
			'browser_title' => function( $title ) {
				$new_title = $_REQUEST['p'];
				$new_title = str_replace( "_", " ", $new_title );
				$new_title = explode( ":", $new_title, 2 );
				
				if ( count( $new_title ) > 1 ):
					$new_title = $new_title[1];
				else:
					$new_title = $new_title[0];
				endif;
				
				return $new_title;
			},
			'display_title' => function( $title ) {
				$new_title = $_REQUEST['p'];
				$new_title = str_replace( "_", " ", $new_title );
				$new_title = explode( ":", $new_title, 2 );
				
				if ( count( $new_title ) > 1 ):
					$new_title = '<strong>['.$new_title[0].']</strong> '.$new_title[1];
				else:
					$new_title = $new_title[0];
				endif;
				
				return $new_title;
			},
			'content' => function() {
				?>
				Wiki Preview
				[wiki-embed url='<?php echo Combined_Wiki_Search::$wiki_url.$_REQUEST['p']; ?>' no-edit no-contents no-infobox]
				<?php
			},
		) );
		*/
	}
	
	static function add_page( $slug, $data ) {
		self::$pages["cws_page_".$slug] = $data;
	}
	
	static function browser_title( $title ) {
		if ( is_page() ):
			$page_id = get_the_ID();
			
			foreach ( self::$pages as $slug => $data ):
				if ( $page_id == $data['page_id'] && isset( $data['display_title'] ) ):
					$func = $data['browser_title'];
					$new_title = $func( $title );
				endif;
			endforeach;
		endif;
		
		if ( ! empty( $new_title ) ):
			$title = $new_title;
		endif;
		
		return $title;
	}
	
	static function title( $title ) {
		if ( is_page() ):
			global $post;
			$page_id = get_the_ID();
			
			foreach ( self::$pages as $slug => $data ):
				if ( $page_id == $data['page_id'] && isset( $data['display_title'] ) && $title == $post->post_title ):
					$func = $data['display_title'];
					$new_title = $func( $title );
				endif;
			endforeach;
		endif;
		
		if ( ! empty( $new_title ) ):
			$title = $new_title;
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
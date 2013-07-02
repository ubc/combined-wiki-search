<?php
class Combined_Wiki_Search_Results {
	static function init() {
		add_action( 'wp_ajax_get_results', array( __CLASS__, 'get_results' ) );
		
		add_action( 'init', array( __CLASS__, 'register_style' ) );
	}
	
	static function register_style() {
		wp_register_style( 'cws-results' , trailingslashit( CW_SEARCH_DIR_URL ) . 'css/results.css' );
	}
	
	static function results( $data = null, $echo = true ) {
		wp_enqueue_style( 'cws-results' );
		
		if ( empty( $data ) ):
			$data = $_REQUEST;
		endif;
		
		$wiki_results = self::get_wiki_results( $data['search'] );
		$wp_results = query_posts( array(
			's' => $data['search'],
		) );
		
		//print_r($wp_results);
		?>
		<div class="cws-results">
		<?php
			foreach ( $wiki_results->query->search as $data ):
				$split = explode( ":", $data->title, 2 );
				
				self::result_single( array(
					'title' => $split[1],
					'snippet' => $data->snippet,
					'timestamp' => $data->timestamp,
					'type' => "Wiki",
					'category' => Combined_Wiki_Search::$namespaces->{$data->ns}->canonical,
				) );
			endforeach;
			
			foreach ( $wp_results as $data ):
				self::result_single( array(
					'title' => $data->post_title,
					'snippet' => $data->post_excerpt, // Currently this is empty??
					'timestamp' => $data->post_modified,
					'type' => $data->post_type,
					'category' => "???",
				) );
			endforeach;
		?>
		</div>
		<?php
	}
	
	static function result_single( $data ) {
		$embed_url = get_permalink( Combined_Wiki_Search_Pages::$pages[CW_SEARCH_PAGE_WIKI_EMBED]['page_id'] );
		$embed_url .= "?p=" . $data['title'];
		
		$original_url = Combined_Wiki_Search::$wiki_url . "index.php?title=" . $data['title'];
		?>
		<article>
			<div class="entry-header">
				<span class="entry-title">
					<a href="<?php echo $embed_url; ?>" title="Permalink to <?php echo $data['title']; ?>" rel="bookmark">
						<?php echo $data['title']; ?>
					</a>
				</span>
			</div>
			<div class="entry-content"><?php echo $data['snippet']; ?></div>
			<footer class="entry-meta">
				<?php echo $data['type']; ?> | 
				This entry was posted in <?php echo $data['category']; ?>, and last modified on 
				<a href="<?php echo $original_url . "&action=history"; ?>" title="<?php echo date( "g:ia", strtotime( $data->timestamp ) ); ?>" rel="bookmark"><time class="entry-date" datetime="<?php echo $data['timestamp']; ?>"><?php echo date( "F j, Y", strtotime( $data['timestamp'] ) ); ?></time></a>.
			</footer>
		</article>
		<?php
	}
	
	static function get_wiki_results( $keywords ) {
		$url = Combined_Wiki_Search::$wiki_url;
		$url .= "api.php?action=query&format=json&list=search&srwhat=text&srprop=snippet|timestamp&srredirects=true";
		$url .= "&srnamespace=112";
		$url .= "&srsearch=" . $keywords;
		
		return json_decode( file_get_contents( $url ) );
	}
}

Combined_Wiki_Search_Results::init();
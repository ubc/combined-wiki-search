<?php
class Combined_Wiki_Search_Results {
	static function init() {
		add_action( 'wp_ajax_cws_get_results', array( __CLASS__, 'ajax_results' ) );
		
		add_action( 'init', array( __CLASS__, 'register_style' ) );
	}
	
	static function register_style() {
		wp_register_style( 'cws-results', trailingslashit( CW_SEARCH_DIR_URL ) . 'css/results.css' );
	}
	
	static function ajax_results() {
		self::display( $_POST );
		die();
	}
	
	static function results( $args ) {
		wp_enqueue_style( 'cws-results' );
		self::display( $args );
	}
	
	static function display( $args ) {
		$keywords = $args['search'];
		$compact = ( empty( $args['compact'] ) ? true : $args['compact'] );
		$limit = $args['limit'];
		
		$wiki_results = self::get_wiki_results( $keywords, $limit );
		$wp_results = self::get_wp_results( $keywords, $limit );
		
		?>
		<div class="cws-results<?php echo ( $compact ? " compact" : "" ); ?>">
			<ul class="cws-wiki-results cws-section">
				<li class="cws-section-title">WIKI</li>
				<?php
					foreach ( $wiki_results as $data ):
						?>
						<li class="cws-link">
							<?php self::result_single( $data, $compact ); ?>
						</li>
						<?php
					endforeach;
				?>
				<li class="cws-results-more cws-link">
					<a href="">
						see more...
					</a>
				</li>
			</ul>
			<ul class="cws-wp-results cws-section">
				<li class="cws-section-title">WORDPRESS</li>
				<?php
					foreach ( $wp_results as $data ):
						?>
						<li class="cws-link">
							<?php self::result_single( $data, $compact ); ?>
						</li>
						<?php
					endforeach;
				?>
				<li class="cws-results-more cws-link">
					<a href="">
						see more...
					</a>
				</li>
			</ul>
			<ul class="cws-section">
				
			</ul>
		</div>
		<?php
	}
	
	static function result_single( $data ) {
		?>
		<article class="<?php echo $data['type']; ?>">
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
	
	static function get_wiki_results( $keywords, $limit = null ) {
		$url = Combined_Wiki_Search::$wiki_url;
		$url .= "api.php?action=query&format=json&list=search&srwhat=text&srprop=snippet|timestamp&srredirects=true";
		$url .= "&srnamespace=112";
		$url .= "&srsearch=" . $keywords;
		
		if ( ! empty( $limit ) ):
			$url .= "&srlimit=" . $limit;
		endif;
		
		$response = wp_remote_get( $url );
		$response = json_decode( $response['body'] );
		$results = array();
		
		foreach ( $response->query->search as $data ):
			$split = explode( ":", $data->title, 2 );
			
			$results[] = array(
				'title'     => $split[1],
				'snippet'   => $data->snippet,
				'timestamp' => $data->timestamp,
				'type'      => "wiki",
				'category'  => Combined_Wiki_Search::$namespaces->{$data->ns}->canonical,
				'url'       => Combined_Wiki_Search_Pages::get_wikiembed_url( $data->title ),
				'permalink' => Combined_Wiki_Search::$wiki_url . "index.php?title=" . $data->title
			);
		endforeach;
		
		return $results;
	}
	
	static function get_wp_results( $keywords, $limit = -1 ) {
		if ( empty( $limit ) ):
			$limit = -1;
		endif;
		
		$response = query_posts( array(
			's' => $keywords,
			'posts_per_page' => $limit,
		) );
		$results = array();
		
		foreach ( $response as $data ):
			$results[] = array(
				'title'     => $data->post_title,
				'snippet'   => $data->post_excerpt, // Currently this is empty??
				'timestamp' => $data->post_modified,
				'type'      => $data->post_type,
				'category'  => "???",
				'url'       => $data->permalink,
				'permalink' => $data->permalink,
			);
		endforeach;
		
		return $results;
	}
}

Combined_Wiki_Search_Results::init();
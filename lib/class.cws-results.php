<?php
class Combined_Wiki_Search_Results {
	static function init() {
		add_action( 'wp_ajax_cws_get_results', array( __CLASS__, 'ajax_results' ) );
		add_action( 'wp_ajax_nopriv_cws_get_results', array( __CLASS__, 'ajax_results' ) );
		add_action( 'init', array( __CLASS__, 'register_style' ) );
		add_action( 'template_redirect', array( __CLASS__, 'results_page' ) );
	}
	
	static function register_style() {
		wp_register_style( 'cws-results', trailingslashit( CW_SEARCH_DIR_URL ) . 'css/results.css' );
	}
	
	static function ajax_results() {
		error_log("Ajax");
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
					<a href="<?php echo home_url( "search/".$keywords ); ?>">
						see more...
					</a>
				</li>
			</ul>
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
					<a href="<?php home_url( "search/".$keywords ); ?>">
						see more...
					</a>
				</li>
			</ul>
			<ul class="cws-section">
				<li class="cws-link">
					<a href="">
						Can't find your question? Ask it.
					</a>
				</li>
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
			$snippet = $data->post_excerpt;
			
			if ( empty( $snippet ) ):
				$snippet = strip_shortcodes( strip_tags( $data->post_content ) );
			endif;
			
			if ( strlen($snippet) > 100 ):
				$snippet = substr( $snippet, 0, 97 ) . "...";
			endif;
			
			$results[] = array(
				'title'     => $data->post_title,
				'snippet'   => $snippet,
				'timestamp' => $data->post_modified,
				'type'      => $data->post_type,
				'category'  => "???",
				'url'       => $data->permalink,
				'permalink' => $data->permalink,
			);
		endforeach;
		
		return $results;
	}
	
	static function results_page() {
		global $wp_query;
		
		if ( ! empty( $_GET['wiki-search'] ) ):
			print_r( "WIKI SEARCH RESULTS" );
			$results = self::get_wiki_results( $_GET['wiki-search'] );
			
			$wp_query->is_home = false;
			$wp_query->is_page = false;
			$wp_query->is_search = true;
			
			$wp_query->post_count = count( $results );
			$list = array();
			
			foreach ( $results as $index => $data ):
				$post = (object) null;
				$post->ID = 0;
				$post->post_title = $data['title'];
				$post->guid = get_site_url()."?wikiembed-url=".urlencode( $data['url'] )."&wikiembed-title=".urlencode( $data['title'] );
				$post->post_content = $data['snippet'];
				$post->post_status = "published";
				$post->comment_status = "closed";
				$post->post_modified = $data['time_stamp'];
				$post->post_excerpt = $data['snippet'];
				$post->post_parent = 0;
				$post->post_type = $data['type'];
				$post->post_date = $data['time_stamp'];
				$post->post_author = $user->ID; // newly created posts are set as if they are created by the admin user
				
				$list[] = $post;
			endforeach;
			
			$wp_query->posts = array( $list );
			
			print_r( $wp_query );
		endif;
	}
}

Combined_Wiki_Search_Results::init();
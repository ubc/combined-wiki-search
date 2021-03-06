<?php
class Combined_Wiki_Search_Results {
	static $is_wiki_search = false;
	
	static function init() {
		add_action( 'wp_ajax_cws_get_results', array( __CLASS__, 'ajax_results' ) );
		add_action( 'wp_ajax_nopriv_cws_get_results', array( __CLASS__, 'ajax_results' ) );
		
		add_action( 'template_redirect', array( __CLASS__, 'results_page' ) );
		add_filter( 'the_permalink',     array( __CLASS__, 'results_page_post_link' ) );
		add_filter( 'get_permalink',     array( __CLASS__, 'results_page_post_link' ) );
		
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
			<div class="cws-results-meta">
				<span><strong>SEARCH RESULTS FOR:</strong> <?php echo $args['search']; ?></span>
			</div>
			<?php
				foreach ( $args['structure'] as $data ):
					self::display_component( $data, $args );
				endforeach;
			?>
		</div>
		<?php
	}
	
	static function display_component( $args, $atts ) {
		switch ( $args['code'] ):
		case 'section':
			?>
			<ul class="cws-section">
				<li class="cws-section-title"><? echo $args['title']; ?></li>
				<?php
				foreach ( $args['content'] as $data ):
					self::display_component( $data, $atts );
				endforeach;
				?>
			</ul>
			<?php
			break;
		case 'results':
			$keywords = $atts['search'];
			
			switch ( $args['type'] ):
			case 'wordpress':
				$results = self::get_wp_results( $keywords, 3 );
				$url = get_home_url( null, "search/".$keywords );
				break;
			case 'wiki':
				$results = self::get_wiki_results( $keywords, 3 );
				$url = get_home_url( null, "?wiki-search=".$keywords );
				break;
			default:
				return;
			endswitch;
			
			if ( ! empty( $results ) ):
				foreach ( $results as $data ):
					?>
					<li class="cws-link">
						<?php self::result_single( $data, $compact ); ?>
					</li>
					<?php
				endforeach;
				?>
				<li class="cws-link">
					<a href="<?php echo $url; ?>">
						see more...
					</a>
				</li>
				<?php
			else:
				?>
				<li><small>No Matches</small></li>
				<?php
			endif;
			break;
		case 'link':
			?>
			<li class="cws-link">
				<a href="<?php echo $args['url']; ?>">
					<?php echo $args['title']; ?>
				</a>
			</li>
			<?php
			break;
		default:
			break;
		endswitch;
	}
	
	static function result_single( $data ) {
		?>
		<article class="<?php echo $data['type']; ?> type-<?php echo $data['type']; ?> status-published hentry">
			<header class="entry-header">
				<h1 class="entry-title">
					<a href="<?php echo $data['url']; ?>" title="Permalink to <?php echo $data['title']; ?>" rel="bookmark">
						<?php echo $data['title']; ?>
					</a>
				</h1>
			</header><!-- .entry-header -->
			<div class="entry-summary">
				<p><?php echo $data['snippet']; ?></p>
			</div><!-- .entry-summary -->
			<footer class="entry-meta">
				This entry was last modified on <a href="<?php echo $data['permalink'] . "&action=history"; ?>" title="<?php echo date( "g:ia", strtotime( $data->timestamp ) ); ?>" rel="bookmark"><time class="entry-date" datetime="<?php echo $data['timestamp']; ?>"><?php echo date( "F j, Y", strtotime( $data['timestamp'] ) ); ?></time></a>.
			</footer><!-- .entry-meta -->
		</article><!-- #post -->
		<?php
	}
	
	static function get_wiki_results( $keywords, $limit = null ) {
		if ( empty( $keywords ) ):
			return array();
		endif;
		
		$url .= "action=query&format=json&list=search&srwhat=text&srprop=snippet|timestamp&srredirects=true";
		$url .= "&srnamespace=112";
		$url .= "&srsearch=" . $keywords;
		
		if ( ! empty( $limit ) ):
			$url .= "&srlimit=" . $limit;
		endif;
		
		$response = Combined_Wiki_Search::query_wiki( $url );
		$results = array();
		
		foreach ( $response->query->search as $data ):
			$split = explode( ":", $data->title, 2 );
			$slug = str_replace( ' ', '_', $data->title );
			
			$results[] = array(
				'title'     => $split[1],
				'snippet'   => $data->snippet,
				'timestamp' => $data->timestamp,
				'type'      => "cws_wiki",
				'category'  => Combined_Wiki_Search::$namespaces->{$data->ns}->canonical,
				'url'       => Combined_Wiki_Search::get_wikiembed_url( $slug ),
				'permalink' => Combined_Wiki_Search::$wiki_url . "index.php?title=" . $slug,
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
			
			if ( strlen($snippet) > 200 ):
				$snippet = substr( $snippet, 0, 197 ) . "...";
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
			self::$is_wiki_search = true;
			$results = self::get_wiki_results( $_GET['wiki-search'] );
			
			// Old Implementation
			$wp_query->is_home = false;
			$wp_query->is_page = false;
			$wp_query->is_search = true;
			$wp_query->s = $_GET['wiki-search'];
			
			$wp_query->post_count = count( $results );
			$list = array();
			
			foreach ( $results as $index => $data ):
				$post = (object) null;
				$post->ID = 0;
				$post->post_name = $data['url'];
				$post->post_title = $data['title'];
				$post->wiki_url = $data['url'];
				$post->post_content = $data['snippet'];
				$post->post_status = "published";
				$post->comment_status = "closed";
				$post->post_modified = $data['timestamp'];
				$post->post_excerpt = $data['snippet'];
				$post->post_parent = 0;
				$post->post_type = $data['type'];
				$post->post_date = $data['timestamp'];
				
				$list[] = $post;
			endforeach;
			
			$wp_query->posts = $list;
			
			
			/* Newer Implementation
			ob_start();
			foreach ( $results as $index => $data ):
				self::result_single( $data );
			endforeach;
			$content = ob_get_clean();
			
			$wp_query->is_home = false;
			$wp_query->is_page = true;
			$wp_query->post_count = 1;
			
			$post = (object) null;
			$post->ID = 0;
			$post->title = "Search Results for: <span>".$_GET['wiki-search']."</span>";
			$post->guid = get_home_url()."?wiki-search=".urlencode( $_GET['wiki-search'] );
			$post->post_content = $content;
			$post->post_status = "published";
			$post->comment_status = "closed";
			$post->post_parent = 0;
			
			$wp_query->posts = array( $post );
			$wp_query->queried_object = $post;
			*/
		endif;
	}
	
	static function results_page_post_link( $url ) {
		global $post;
		
		if ( $post->post_type == "cws_wiki" ):
			$url = $post->wiki_url;
		endif;
		error_log( $url );
		
		return $url;
	}
}

Combined_Wiki_Search_Results::init();
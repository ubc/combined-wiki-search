<?php
class Combined_Wiki_Search_Tags {

	static function init() {
		add_shortcode( 'cws_tags', array( __CLASS__, 'tags_shortcode' ) );
		add_action( 'init', array( __CLASS__, 'register_scripts' ) );
		add_action( 'wp_footer', array( __CLASS__, 'enqueue_scripts' ) );
	}
	
	/**
	tags_shortcode function
	This is the controlling function called in the constructor that will handle
	the functionality for the shortcode
	@return buffer
	*/
	static function tags_shortcode( $atts ) {
		extract( shortcode_atts( array( 'page_title' => '', 'namespace' => '', 'tag_name' => '' ), $atts ) );
		ob_start();
		self::create_area( $page_title, $namespace, $tag_name );
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	/**
	register_scripts function
	This function will register the handles for the javascript files needed
	*/
	static function register_scripts() {
		wp_register_script( 'cws-tags' , trailingslashit( CW_SEARCH_DIR_URL ) . 'js/tags.js', array( 'jquery' ), '1.0', true );
	}

	/**
	enqueue_scripts function
	This function will enqueue the registered scripts
	*/
	static function enqueue_scripts() {
		wp_enqueue_script( 'cws-tags' );
	}

	/**
	generate_page function
	This function will create an area for the tags
	*/
	static function create_area( $title, $namespace, $tag_name ) {
		//$tags_arr = self::grab_tags();
		//$api_list = self::mediawiki_api_builder( $namespace, $title );
		?>
		<h3></h3>
		<h3>Tag Space</h3>
		<?php /*foreach( $tags_arr as $tag ): ?>
			<a class="btn cws-tags" href="<?php echo self::make_url( $tag ); ?>"><?php echo self::get_tag_name( $tag ); ?></a><br/>
		<?php endforeach;*/
		echo "<p>page_title=" . $title . "<br/>namespace=" . $namespace . "<br/>tag_name=" . $tag_name . "</p>";

		$title = explode( ':', $title );
		$title = $title[1];
		$yolo = self::mediawiki_api_builder( 112, $title );
	}

	/**
	mediawiki_api_builder function
	This function takes 2 parameters and checks to see if they are set, then it
	returns a JSON decoded array of the results of the mediawiki url api
	@param int, string
	@return array
	*/
	private static function mediawiki_api_builder( $namespace, $prefix ) {
		// use JSON for mediawiki api results
		// want to grab 10 pages in documentation and ubc cms?
		//$mediawiki_api = "api.php?format=json&action=query&generator=allpages&gapnamespace=112&gapprefix=UBC_Content_Management_System&prop=info&inprop=counter";
		// api.php?action=query&list=allpages&apprefix=UBC_Content_Management_System&apnamespace=112
		// for testing purposes, site will be hardcoded
		//$mediawiki_site = Combined_Wiki_Search::$wiki_url;
		$url = Combined_Wiki_Search::$wiki_url;
		$url .= "api.php?format=json&action=query&list=allpages&aplimit=100";
		$url .= isset( $namespace ) && is_int( $namespace ) ? "&apnamespace=" . $namespace : "";
		$url .= isset( $prefix ) ? "&apprefix=" . $prefix : "";
		$url .= "&prop=info";

		echo '<p>' . $url . '</p>';
		return json_decode( file_get_contents( $url ) );
	}

	/**
	grab_tags function
	This function will generate a list of tags and return an array of the tags
	@return array
	*/
	static function grab_tags() {
		$tags = array(
			"Documentation:UBC_Content_Management_System/UBC_Collab_Theme", 
			"Documentation:UBC_Content_Management_System/CLF_Theme"
		);

		return $tags;
	}

	/**
	make_url function
	This function will create a url and put it into the GET parameters based on the page name passed into it
	if an empty page name is passed, nothing is returned
	@param string
	@return string
	*/
	static function make_url( $page_name ) {
		// set the url for the tag
		if( isset( $page_name ) ):
			$url_redirect = get_permalink( Combined_Wiki_Search_Pages::$pages['cws_page_' . CW_SEARCH_PAGE_WIKI_EMBED]['page_id'] );
			$url_redirect = add_query_arg('p', $page_name, $url_redirect );
			return $url_redirect;
		endif;
		return;
	}

	/**
	get_tag_name function
	This function will get the name of the wiki page and return it as a string
	@param
	@return string
	*/
	static function get_tag_name( $tag ) {
		$broken_tag = explode( '/', $tag );
		$tag_name = str_replace( '_', ' ', $broken_tag[sizeof( $broken_tag ) - 1] );
		return $tag_name;
	}

}

Combined_Wiki_Search_Tags::init();
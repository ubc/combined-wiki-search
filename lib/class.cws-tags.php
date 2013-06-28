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
	static function tags_shortcode() {
		ob_start();
		self::create_area();
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
	static function create_area() {
		$tags_arr = self::get_tags();
		?>
		<h3>Tag Space</h3>
		<?php foreach( $tags_arr as $tag ): ?>
			<a class="btn cws-tags" href="<?php echo self::make_url( $tag ); ?>"><?php echo self::get_tag_name( $tag ); ?></a>
		<?php endforeach;
	}

	/**
	get_tags function
	This function will generate a list of tags and return an array of the tags
	@return array
	*/
	static function get_tags() {
		$tags = array(
			"Documentation:UBC_Content_Management_System/UBC_Collab_Theme", 
			"Documentation:UBC_Content_Management_System/CLF_Theme"
		);
		
		return $tags;
	}

	/**
	tag_space_generator function
	This function will generate the area for the tag space and style it accordingly
	@param array
	@return void
	*/
	static function tag_space_generator( $tags ) {
		foreach( $tags as $tag ): ?>
			<button href="#" class="btn cws-tags" data="<?php esc_url($tag); ?>"><?php echo $tag; ?></button>
		<?php endforeach;
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
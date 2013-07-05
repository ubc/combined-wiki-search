<?php
class Combined_Wiki_Search_Tags {

	static function init() {
		add_shortcode( 'cws_tags', array( __CLASS__, 'tags_shortcode' ) );
        add_shortcode( 'cws_tag', array( __CLASS__, 'nested_tags' ) );
		add_action( 'init', array( __CLASS__, 'register_scripts_styles' ) );
		add_action( 'wp_footer', array( __CLASS__, 'enqueue_scripts_styles' ) );
	}
	
	/**
	tags_shortcode function
	This is the controlling function called in the constructor that will handle
	the functionality for the shortcode
	@return buffer
	*/
	static function tags_shortcode( $atts, $content = null ) {
		ob_start();
		echo do_shortcode( $content );
		return ob_get_clean();
	}

	static function nested_tags( $atts ) {
		extract( shortcode_atts( array(
			'title'	=> null,
			'name'	=> null,
			'size'	=> 0,
			'color'	=> "#000"
			), $atts )
		);
		ob_start();
		self::create_tag( $title, $name, $size, $color );
		return ob_get_clean();
	}

	/**
	register_scripts function
	This function will register the handles for the javascript files needed
	*/
	static function register_scripts_styles() {
		wp_register_script( 'cws-tags-js' , trailingslashit( CW_SEARCH_DIR_URL ) . 'js/tags.js', array( 'jquery' ), '1.0', true );
		wp_register_style( 'cws-tags-css' , trailingslashit( CW_SEARCH_DIR_URL ) . 'css/tags.css' );
	}

	/**
	enqueue_scripts function
	This function will enqueue the registered scripts
	*/
	static function enqueue_scripts_styles() {
		wp_enqueue_script( 'cws-tags-js' );
		wp_enqueue_style( 'cws-tags-css' );
	}

	/**
	create_tag function
	This function will create an area for the tags
	*/
	static function create_tag( $title = null, $tag_name = null, $size = 0, $color = "#000" ) {
		$mod_title = explode( ':', $title );
		$slug = str_replace( ' ', '_', $title );
		$mod_title = $mod_title[1];
		empty( $tag_name ) ? $tag_name = self::get_tag_name( $mod_title ) : $tag_name;
		if( !is_numeric( $size ) || ($size > 12 || $size < 0) ):
		?>
			<div class="shortcode-warning">
				<button href="#" class="warning-close" data="warning-close">&times;</button>
				<div class="warning-text">
					You have entered <strong>"<?php echo $size; ?>"</strong> as the <strong>size</strong> parameter in the <strong>"<?php echo $tag_name; ?>"</strong> tag shortcode, 
					please consider entering a <strong>number <?php echo is_numeric( $size ) && ($size > 12 || $size < 0) ? "between 0 and 12" : ""; ?></strong> 
					as the size will have a defualt value of 0 (12px).
				</div>
			</div>
		<?php
		endif;
		empty( $size ) ? $size = 0 : !is_numeric( $size ) ? $size = 0 : ($size > 12 || $size < 0) ? $size = 0 : $size;
		// first checks if the $size parameter is empty, if it is empty, $size is assigned 1
		// if it is not empty, check if $size is an integer, if yes, leave it alone, otherwise cast it to an int
		?>
		<a class="btn cws-tags" href="<?php echo self::make_url( $slug, $mod_title ); ?>" data-size="<?php echo $size; ?>" data-color="<?php echo $color; ?>"><?php echo $tag_name; ?></a>
		<?php

	}

	/**
	mediawiki_api_builder function
	This function takes 2 parameters and checks to see if they are set, then it
	returns a JSON decoded array of the results of the mediawiki url api
	@param int, string
	@return array
	*/
	/*private static function mediawiki_api_builder( $namespace, $prefix ) {
		// use JSON for mediawiki api results
		$url = Combined_Wiki_Search::$wiki_url;
		$url .= "api.php?format=json&action=query&list=allpages&aplimit=100";
		$url .= isset( $namespace ) && is_int( $namespace ) ? "&apnamespace=" . $namespace : "";
		$url .= isset( $prefix ) ? "&apprefix=" . $prefix : "";
		$url .= "&prop=info";

		echo '<p>' . $url . '</p>';
		return json_decode( file_get_contents( $url ) );
	}*/

	/**
	grab_tags function
	This function will generate a list of tags and return an array of the tags
	@return array
	*/
	/*static function grab_tags() {
		$tags = array(
			"Documentation:UBC_Content_Management_System/UBC_Collab_Theme", 
			"Documentation:UBC_Content_Management_System/CLF_Theme"
		);

		return $tags;
	}*/

	/**
	make_url function
	This function will create a url and put it into the GET parameters based on the page name passed into it
	if an empty page name is passed, nothing is returned
	@param string
	@return string
	*/
	static function make_url( $slug, $title ) {
		// set the url for the tag
		if( isset( $slug ) ):
			$url_redirect = Combined_Wiki_Search::get_wikiembed_url( $slug, $title );
			//$url_redirect = get_permalink( Combined_Wiki_Search::$pages['cws_page_' . CW_SEARCH_PAGE_WIKI_EMBED]['page_id'] );
			//$url_redirect = add_query_arg('p', $page_name, $url_redirect );
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

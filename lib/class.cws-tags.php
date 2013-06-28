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
		?>
		<h3>Tag Space</h3>
		<button href="#" class="btn cws-tags" data="<?php echo esc_url(self::get_tags()); ?>">CLICK ME</button>
		<?php
	}

	/**
	get_tags function
	This function will generate a list of tags and return an array of the tags
	@return array
	*/
	static function get_tags() {
		$tags = "http://wiki.ubc.ca/Documentation:UBC_Content_Management_System/UBC_Collab_Theme";
		//$tags = $test;

		return $tags;
	}

	/**
	pass_info function
	This function will pass the information contained in the tag as POST data
	*/
	static function pass_info() {

	}
}

Combined_Wiki_Search_Tags::init();
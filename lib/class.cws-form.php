<?php
class Combined_Wiki_Search_Form {
	static $in_form = false;
	static $form_atts = null;
	static $current = null;
	
	static function init() {
		add_shortcode( 'cws_form', array( __CLASS__, 'form_shortcode' ) );
		add_shortcode( 'cws_section', array( __CLASS__, 'section_shortcode' ) );
		add_shortcode( 'cws_results', array( __CLASS__, 'results_shortcode' ) );
		add_shortcode( 'cws_link', array( __CLASS__, 'link_shortcode' ) );
		
		add_action( 'init', array( __CLASS__, 'register_script' ) );
	}
	
	static function register_script() {
		wp_register_script( 'cws-form' , trailingslashit( CW_SEARCH_DIR_URL ) . 'js/form.js', array( 'jquery' ), '1.0', true );
		wp_register_style( 'cws-form' , trailingslashit( CW_SEARCH_DIR_URL ) . 'css/form.css' );
		
		wp_localize_script( 'cws-form', 'cws_ajaxurl', admin_url( 'admin-ajax.php' ) );
	}
	
	static function form_shortcode( $atts, $content ) {
		wp_enqueue_script( 'cws-form' );
		wp_enqueue_style( 'cws-form' );
		wp_enqueue_style( 'cws-results' );
		
		ob_start();
		self::$in_form = true;
		self::$current = array();
		do_shortcode( $content );
		self::$form_atts = self::$current;
		?>
		<div class="cws-search-form" data-atts='<?php echo json_encode( self::$form_atts ); ?>'>
			<input class="cws-search-input" name="search" placeholder="Ask a question..." />
			<div class="cws-autobox"></div>
		</div>
		<?php
		self::$in_form = false;
		return ob_get_clean();
	}
	
	static function section_shortcode( $atts, $content ) {
		$section = shortcode_atts( array(
			'code' => 'section',
			'title' => "",
		), $atts );
		$parent = self::$current;
		self::$current = array();
		
		do_shortcode( $content );
		
		$section['content'] = self::$current;
		$parent[] = $section;
		self::$current = $parent;
	}
	
	static function results_shortcode( $atts ) {
		self::$current[] = shortcode_atts( array(
			'code' => 'results',
			'type' => 'wordpress',
		), $atts );
	}
	
	static function link_shortcode( $atts ) {
		self::$current[] = shortcode_atts( array(
			'code' => 'link',
			'title' => "Link",
			'url'   => home_url(),
		), $atts );
	}
}

Combined_Wiki_Search_Form::init();
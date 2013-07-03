<?php
class Combined_Wiki_Search_Form {
	static function init() {
		add_shortcode( 'cws_form', array( __CLASS__, 'form_shortcode' ) );
		
		add_action( 'init', array( __CLASS__, 'register_script' ) );
	}
	
	static function register_script() {
		wp_register_script( 'cws-form' , trailingslashit( CW_SEARCH_DIR_URL ) . 'js/form.js', array( 'jquery' ), '1.0', true );
		wp_register_style( 'cws-form' , trailingslashit( CW_SEARCH_DIR_URL ) . 'css/form.css' );
	}
	
	static function form_shortcode( $atts ) {
		wp_enqueue_script( 'cws-form' );
		wp_enqueue_style( 'cws-form' );
		
		ob_start();
		?>
		<form class="cws-search-form">
			<input name="search" placeholder="Ask a question..." />
		</form>
		<?php
		return ob_get_clean();
	}
}

Combined_Wiki_Search_Form::init();
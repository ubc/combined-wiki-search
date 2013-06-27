<?php
class Combined_Wiki_Search_Form {
	static function init() {
		add_shortcode( 'cws_form', array( __CLASS__, 'form_shortcode' ) );
		
		add_action( 'init', array( __CLASS__, 'register_script' ) );
	}
	
	static function register_script() {
		wp_register_script( 'cws-form' , plugins_url( 'js/form.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	}
	
	static function form_shortcode() {
		wp_enqueue_script( 'cws-form' );
		
		ob_start();
		?>
		<form class="cws-search-form">
			Search Form
			<input name="search" />
		</form>
		<?php
		return ob_get_clean();
	}
}

Combined_Wiki_Search_Form::init();
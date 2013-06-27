<?php
class Combined_Wiki_Search {
	static function init() {
		add_shortcode( 'cws_form', array( __CLASS__, 'form_shortcode' ) );
	}
	
	static function form_shortcode() {
		ob_start();
		?>
		<form class="cws-search-form">
			
		</form>
		<?php
		return ob_get_clean();
	}
}

CTLT_KB_Search::init();
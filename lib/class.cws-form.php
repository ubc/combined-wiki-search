<?php
class Combined_Wiki_Search_Form {
	static function init() {
		add_shortcode( 'cws_form', array( __CLASS__, 'form_shortcode' ) );
	}
	
	static function form_shortcode() {
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
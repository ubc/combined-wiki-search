<?php
class Combined_Wiki_Search_Admin {
	static function init() {
		add_action( 'admin_init', array( __CLASS__, 'load' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
	}
	
	static function load() {
		register_setting( 'cws_options', 'cws_results_page');
		register_setting( 'cws_options', 'cws_wiki_preview_page');
		
		add_settings_section( 'cws_main', 'Main Settings', array( __CLASS__, 'setting_section_main' ), 'cws_settings' );
		
		foreach ( Combined_Wiki_Search_Pages::$pages as $slug => $data ):
			add_settings_field( $slug, $data['title'], array( __CLASS__, 'setting_page' ), 'cws_settings', 'cws_main', $slug );
		endforeach;
	}
	
	static function admin_menu() {
		add_options_page( 'Combined Wiki Search', 'CW Search', 'manage_options', 'cwsearch_settings', array( __CLASS__,  'admin_page' ) );
	}
		
	static function setting_section_main() {
		?>
		<!-- Main Settings -->
		<?php
	}
	
	static function setting_page( $slug ) {
		wp_dropdown_pages( array(
			'selected' => Combined_Wiki_Search_Pages::$pages[$slug]['page_id'],
			'name' => $slug,
			'show_option_none' => "None",
			'option_none_value' => 0,
		) );
	}
	
	static function admin_page() {
		if ( ! empty( $_POST ) ):
			foreach ( Combined_Wiki_Search_Pages::$pages as $slug => $data ):
				$value = ( isset( $_POST[$slug] ) ? $_POST[$slug] : 0 );
				Combined_Wiki_Search_Pages::$pages[$slug]['page_id'] = $value;
				update_site_option( $slug, $value );
			endforeach;
		endif;
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br /></div>
			<h2>Combined Wiki Search Settings</h2>
			<form id="cws-options" method="post" <?php echo $action; ?>>
				<?php settings_fields( 'cws_options' ); ?>
				<?php do_settings_sections( 'cws_settings' ); ?>
				<br />
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</form>
		</div>
		<?php
	}
}

Combined_Wiki_Search_Admin::init();
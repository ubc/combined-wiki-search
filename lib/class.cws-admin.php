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
		add_settings_field( CW_SEARCH_SETTING_RESULTS_PAGE, 'Results Page', array( __CLASS__, 'setting_results_page' ), 'cws_settings', 'cws_main' );
		add_settings_field( CW_SEARCH_SETTING_PREVIEW_PAGE, 'Wiki Preview Page', array( __CLASS__, 'setting_wiki_preview_page' ), 'cws_settings', 'cws_main' );
	}
	
	static function admin_menu() {
		add_options_page( 'Combined Wiki Search', 'CW Search', 'manage_options', 'cwsearch_settings', array( __CLASS__,  'admin_page' ) );
	}
		
	static function setting_section_main() {
		?>
		<!-- Main Settings -->
		<?php
	}
	
	static function setting_results_page() {
		wp_dropdown_pages( array(
			'selected' => Combined_Wiki_Search::$results_page_id,
			'name' => CW_SEARCH_SETTING_RESULTS_PAGE,
			'show_option_none' => "None",
			'option_none_value' => 0,
		) );
	}
	
	static function setting_wiki_preview_page() {
		wp_dropdown_pages( array(
			'selected' => Combined_Wiki_Search::$wikiembed_page_id,
			'name' => CW_SEARCH_SETTING_PREVIEW_PAGE,
			'show_option_none' => "None",
			'option_none_value' => 0,
		) );
	}
	
	static function admin_page() {
		if ( ! empty( $_POST ) ):
			Combined_Wiki_Search::$results_page_id = ( isset( $_POST[CW_SEARCH_SETTING_RESULTS_PAGE] ) ? $_POST[CW_SEARCH_SETTING_RESULTS_PAGE] : 0 );
			Combined_Wiki_Search::$wikiembed_page_id = ( isset( $_POST[CW_SEARCH_SETTING_PREVIEW_PAGE] ) ? $_POST[CW_SEARCH_SETTING_PREVIEW_PAGE] : 0 );
			
			update_site_option( CW_SEARCH_SETTING_RESULTS_PAGE, Combined_Wiki_Search::$results_page_id );
			update_site_option( CW_SEARCH_SETTING_PREVIEW_PAGE, Combined_Wiki_Search::$wikiembed_page_id );
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
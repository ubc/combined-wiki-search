<?php
class Combined_Wiki_Search_Admin {
	static $wikiembed_enabled = false;
	
	static function init() {
		add_action( 'admin_init', array( __CLASS__, 'load' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'init', array( __CLASS__, 'register_style' ) );
	}
	
	static function load() {
		self::$wikiembed_enabled = is_plugin_active( "wiki-embed/WikiEmbed.php" );
		
		add_settings_section( 'cws_main', 'Main Settings', array( __CLASS__, 'setting_section_main' ), 'cws_settings' );
		add_settings_field( CW_SEARCH_SETTING_WIKI_URL, "Wiki URL", array( __CLASS__, 'setting_wiki_url' ), 'cws_settings', 'cws_main' );
		add_settings_field( CW_SEARCH_SETTING_NAMESPACES, "Namespaces", array( __CLASS__, 'setting_namespaces' ), 'cws_settings', 'cws_main' );
		
		add_settings_section( 'cws_plugins', 'Plugin Integration Status', array( __CLASS__, 'setting_section_plugins' ), 'cws_settings' );
		add_settings_field( 'wikiembed_found', 'Wiki Embed plugin', array( __CLASS__, 'setting_wikiembed_plugin' ), 'cws_settings', 'cws_plugins' );
	}
	
	static function register_style() {
		wp_register_style( 'cws-admin', trailingslashit( CW_SEARCH_DIR_URL ) . 'css/admin.css' );
	}
	
	static function admin_menu() {
		add_options_page( 'Combined Wiki Search', 'CW Search', 'manage_options', 'cwsearch_settings', array( __CLASS__,  'admin_page' ) );
	}
	
	static function setting_section_main() {
		?>
		<!-- Main Settings -->
		<?php
	}
	
	static function setting_wiki_url( $slug ) {
		?>
		<input type="text" name="<?php echo CW_SEARCH_SETTING_WIKI_URL; ?>" value="<?php echo Combined_Wiki_Search::$wiki_url; ?>" />
		<?php
	}
	
	static function setting_namespaces( $slug ) {
		Combined_Wiki_Search::$namespaces = json_decode( file_get_contents( Combined_Wiki_Search::$wiki_url . "api.php?action=query&format=json&meta=siteinfo&siprop=namespaces" ) );
		?>
		<small>
			Choose which sections of your Wiki you want to this plugin to search.
		</small>
		<div class="cws-namespaces">
			<?php
			foreach ( Combined_Wiki_Search_Admin::$namespaces->query->namespaces as $id => $data ):
				?>
				<label>
					<input type="checkbox" name="<?php echo CW_SEARCH_SETTING_NAMESPACES; ?>[]" value="<?php echo $id; ?>" <?php checked( in_array( $id, Combined_Wiki_Search::$searched_namespaces ) ); ?>/>
					<?php echo ( empty( $data->canonical ) ? "Main" : $data->canonical ); ?>
				</label>
				<?php
			endforeach;
			?>
		</div>
		<?php
	}
	
	public static function setting_section_plugins() {
		?>
		Integration for the Wiki Embed plugin.
		<?php
	}
	
	public static function setting_wikiembed_plugin() {
		if ( self::$wikiembed_enabled ): ?>
			<div style="color: green">Enabled</div>
		<?php else: ?>
			<div style="color: red">Not Found</div>
		<?php endif;
	}
	
	static function admin_page() {
		wp_enqueue_style( 'cws-admin' );
		
		if ( ! empty( $_POST ) ):
			Combined_Wiki_Search::$wiki_url = trailingslashit( $_POST[CW_SEARCH_SETTING_WIKI_URL] );
			Combined_Wiki_Search::$searched_namespaces = $_POST[CW_SEARCH_SETTING_NAMESPACES];
			update_site_option( CW_SEARCH_SETTING_WIKI_URL, Combined_Wiki_Search::$wiki_url );
			update_site_option( CW_SEARCH_SETTING_NAMESPACES, Combined_Wiki_Search::$searched_namespaces );
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
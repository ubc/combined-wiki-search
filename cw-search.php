<?php
/**
  Plugin Name: Combined Wiki Search
  Plugin URI: https://github.com/ubc/combined-wiki-search
  Version: 0.1
  Description: 
  Author: Julien Law, Devindra Payment, CTLT, UBC
  Author URI: http://ctlt.ubc.ca
  License: GPLv2
 */

if ( ! defined('ABSPATH') )
	die('-1');

define( 'CW_SEARCH_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'CW_SEARCH_BASENAME', plugin_basename( __FILE__ ) );
define( 'CW_SEARCH_DIR_URL',  plugins_url('', CW_SEARCH_BASENAME) );
define( 'CW_SEARCH_VERSION',  0.1 );

define( 'CW_SEARCH_SETTING_WIKI_URL', "cws_wiki_url" );
define( 'CW_SEARCH_SETTING_NAMESPACES', "cws_namespaces" );
define( 'CW_SEARCH_PAGE_SEARCH_RESULTS', "results" );
define( 'CW_SEARCH_PAGE_WIKI_EMBED', "wikiembed" );

// Don't mess with the ordering of these unless you know what you are doing.
require_once( 'lib/class.cws-pages.php' );
require_once( 'lib/class.cw-search.php' );
require_once( 'lib/class.cws-results.php' );
require_once( 'lib/class.cws-admin.php' );
require_once( 'lib/class.cws-form.php' );
require_once( 'lib/class.cws-tags.php' );

// Register the activation hooks for the plugin
register_activation_hook( __FILE__, array( 'Combined_Wiki_Search', 'install' ) );
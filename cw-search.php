<?php
/**
  Plugin Name: Combined Wiki Search
  Plugin URI: https://github.com/ubc/combined-wiki-search
  Version: 1.0
  Description: 
  Author: Julien Law, Devindra Payment, CTLT, UBC
  Author URI: http://ctlt.ubc.ca
  License: GPLv2
 */

if ( ! defined('ABSPATH') )
	die('-1');

define( 'CW_SEARCH_DIR_PATH', plugin_dir_path(__FILE__) );
define( 'CW_SEARCH_BASENAME', plugin_basename(__FILE__) );
define( 'CW_SEARCH_DIR_URL',  plugins_url('', PULSE_CPT_BASENAME) );
define( 'CW_SEARCH_VERSION',  0.1 );

require_once( 'lib/class.cws-admin.php' );
require_once( 'lib/class.cws-form.php' );
require_once( 'lib/class.cws-tags.php' );
require_once( 'lib/class.cws-pages.php' );

<?php 
/*
 * Plugin Name: Shoptranslate.com - Translation & SEO for multilingual webshops
 * Plugin URI: https://wordpress.org/plugins/shoptranslation-seo-multilingual-webshops
 * Description: Shoptranslate.com will help you build & maintain a crossborder webshop. Don't worry about your translations any more. Be flexible, profit from our SEO knowledge, and develop pricing and stock strategies. This Shoptranslate.com plugin is needed for your native language shop.
 * Version: 1.0.3
 * Author: Shoptranslate.com
 * Author URI: http://www.shoptranslate.com
 * Text Domain: wwttran
 * Domain Path: languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions 
 * 
 * @package wwt Translate
 * @since 1.0.0
 */
if( !defined( 'WWT_TRAN_DIR' ) ) {
  define( 'WWT_TRAN_DIR', dirname( __FILE__ ) );      // Plugin dir
}
if( !defined( 'WWT_TRAN_VERSION' ) ) {
  define( 'WWT_TRAN_VERSION', '1.0.3' );      // Plugin Version
}
if( !defined( 'WWT_TRAN_URL' ) ) {
  define( 'WWT_TRAN_URL', plugin_dir_url( __FILE__ ) );   // Plugin url
}
if( !defined( 'WWT_TRAN_INC_DIR' ) ) {
  define( 'WWT_TRAN_INC_DIR', WWT_TRAN_DIR.'/includes' );   // Plugin include dir
}
if( !defined( 'WWT_TRAN_INC_URL' ) ) {
  define( 'WWT_TRAN_INC_URL', WWT_TRAN_URL.'includes' );    // Plugin include url
}
if( !defined( 'WWT_TRAN_ADMIN_DIR' ) ) {
  define( 'WWT_TRAN_ADMIN_DIR', WWT_TRAN_INC_DIR.'/admin' );  // Plugin admin dir
}
if( !defined( 'WWT_TRAN_LOG_POST_TYPE' ) ) {
  define( 'WWT_TRAN_LOG_POST_TYPE', 'translate_log' ); // Plugin Prefix
}
if( !defined('WWT_TRAN_PREFIX') ) {
  define( 'WWT_TRAN_PREFIX', '_wwt_tran_'); // Plugin Prefix
}
if(!defined('WWT_TRAN_VAR_PREFIX')) {
  define('WWT_TRAN_VAR_PREFIX', '_wwt_tran_'); // Variable Prefix
}
if(!defined('WWT_TRAN_TEXT_DOMAIN')) {
  define('WWT_TRAN_TEXT_DOMAIN', 'wwttran'); //Text Domain
}
if( !defined( 'WWT_TRAN_REQUEST_PT' ) ) {
  define( 'WWT_TRAN_REQUEST_PT', 'shop_request_list' );
}
if( !defined( 'HOSTNAME' ) ) {
	define ('HOSTNAME', 'http://shoptranslate.com/');
}
if( !defined( 'CALLBACK_URL' ) ) {
	define ('CALLBACK_URL', add_query_arg( array( 'wc_textmasterapi' => 2 ), get_site_url() ) );
}

//For Cron work better
define('ALTERNATE_WP_CRON', true);

global $wwt_tran_options;

//Get events settings
$wwt_tran_options = wwt_tran_get_settings();

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 *
 * @package Wwt Translate
 * @since 1.0.0
 */
load_plugin_textdomain( WWT_TRAN_TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/**
 * Activation Hook
 *
 * Register plugin activation hook.
 *
 * @package wwt Translate
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wwt_tran_install' );

function wwt_tran_install(){

	if( function_exists( 'wwt_tran_register_post_types' ) ) {
		//register post type
		wwt_tran_register_post_types();
	}
	// Cron jobs
	wp_clear_scheduled_hook( 'wwt_tran_data_scheduled_cron' );
}

/**
 * Deactivation Hook
 *
 * Register plugin deactivation hook.
 *
 * @package wwt Translate
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wwt_tran_uninstall');

function wwt_tran_uninstall(){
  
}

/**
 * Get Settings From Option Page
 * 
 * Handles to return all settings value
 * 
 * @package wwt Translate
 * @since 1.0.0
 */
function wwt_tran_get_settings() {

	$settings = is_array(get_option('wwt_tran_options')) ? get_option('wwt_tran_options') 	: array();
	return $settings;
}

// Global variables
global $wwt_tran_scripts, $wwt_tran_model, $wwt_tran_admin;


include_once( WWT_TRAN_INC_DIR.'/stsmw-post-types.php' );

// Script class handles most of script functionalities of plugin
include_once( WWT_TRAN_INC_DIR.'/class-stsmw-scripts.php' );
$wwt_tran_scripts = new Wwt_Tran_Scripts();
$wwt_tran_scripts->add_hooks();

// Model class handles most of model functionalities of plugin
include_once( WWT_TRAN_INC_DIR.'/class-stsmw-model.php' );
$wwt_tran_model = new Wwt_Tran_Model();


// Admin class handles most of admin panel functionalities of plugin
include_once( WWT_TRAN_ADMIN_DIR.'/class-stsmw-admin.php' );
$wwt_tran_admin = new Wwt_Tran_Admin();
$wwt_tran_admin->add_hooks();

//Text Master Api Call
include_once( WWT_TRAN_INC_DIR.'/class-stsmw-tm-api.php' );
?>
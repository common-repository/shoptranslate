<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register Post Type
 *
 * Register Custom Post Type for Banners
 *
 *@package WWT Translate 
 * @since 1.0.0
 */ 
function wwt_tran_register_post_types() {
 
	//Arguments for Roles log admin side
	$labels = array(
				    'name'				=> __('Pricing Option Logs',WWT_TRAN_TEXT_DOMAIN),
				    'singular_name' 	=> __('pricing-option-log',WWT_TRAN_TEXT_DOMAIN),
				    'add_new' 			=> __('Add New',WWT_TRAN_TEXT_DOMAIN),
				    'add_new_item' 		=> __('Add New Pricing Option log',WWT_TRAN_TEXT_DOMAIN),
				    'edit_item' 		=> __('Edit Pricing Option log',WWT_TRAN_TEXT_DOMAIN),
				    'new_item' 			=> __('New Pricing Option log',WWT_TRAN_TEXT_DOMAIN),
				    'all_items' 		=> __('All Pricing Option log',WWT_TRAN_TEXT_DOMAIN),
				    'view_item' 		=> __('View Pricing Option log',WWT_TRAN_TEXT_DOMAIN),
				    'search_items' 		=> __('Search Pricing Option log',WWT_TRAN_TEXT_DOMAIN),
				    'not_found' 		=> __('No Pricing Option log found',WWT_TRAN_TEXT_DOMAIN),
				    'not_found_in_trash'=> __('No Pricing Option log found in Trash',WWT_TRAN_TEXT_DOMAIN),
				    'parent_item_colon' => '',
				    'menu_name' 		=> __('Pricing Option log',WWT_TRAN_TEXT_DOMAIN),
					);

	$args = array(
				    'labels' 			  => $labels,
				    'query_var' 		  => false,//true,
			    	'exclude_from_search' => true,
				    'rewrite' 			  => false,
				    'capability_type' 	  => 'post',
					'map_meta_cap'        => true,
				    'hierarchical' 		  => false,
				    'supports' 			  => array( 'title' , 'editor')
				    );
	//Apply Filter to modify the arguments of  Roles logs post type
	$translate_log_args = apply_filters( 'wwt_tran_log_post_type_args', $args );

	
	//Register Roles post type
	register_post_type( WWT_TRAN_LOG_POST_TYPE, $translate_log_args );

	//Arguments for request list
	$shop_request_labels = array(
				    'name'				=> __('Shop Request Logs',WWT_TRAN_TEXT_DOMAIN),
				    'singular_name' 	=> __('shop-request-log',WWT_TRAN_TEXT_DOMAIN),
				    'add_new' 			=> __('Add New',WWT_TRAN_TEXT_DOMAIN),
				    'add_new_item' 		=> __('Add New Shop Request Log',WWT_TRAN_TEXT_DOMAIN),
				    'edit_item' 		=> __('Edit Shop Request Log',WWT_TRAN_TEXT_DOMAIN),
				    'new_item' 			=> __('New Shop Request Log',WWT_TRAN_TEXT_DOMAIN),
				    'all_items' 		=> __('All Shop Request Log',WWT_TRAN_TEXT_DOMAIN),
				    'view_item' 		=> __('View Shop Request Log',WWT_TRAN_TEXT_DOMAIN),
				    'search_items' 		=> __('Search Shop Request Log',WWT_TRAN_TEXT_DOMAIN),
				    'not_found' 		=> __('No Shop Request Log Found',WWT_TRAN_TEXT_DOMAIN),
				    'not_found_in_trash'=> __('No Shop Request Log Found In Trash',WWT_TRAN_TEXT_DOMAIN),
				    'parent_item_colon' => '',
				    'menu_name' 		=> __('Shop Request Log',WWT_TRAN_TEXT_DOMAIN),
					);

	$shop_request_args = array(
				    'labels' 			  => $shop_request_labels,
				    'query_var' 		  => false,//true,
			    	'exclude_from_search' => true,
				    'rewrite' 			  => false,
				   // 'show_ui' 			  => true,
				    'capability_type' 	  => 'post',
					'map_meta_cap'        => true,
				    'hierarchical' 		  => false,
				    'supports' 			  => array( 'title' , 'editor')
				    );

	//Apply Filter to modify the arguments of banner logs post type
	$shop_request_args = apply_filters( 'wwt_tran_log_post_type_args', $shop_request_args );

	//Register banner logs post type
	register_post_type( WWT_TRAN_REQUEST_PT, $shop_request_args );

}

//creating custom post type
add_action( 'init', 'wwt_tran_register_post_types' );

?>
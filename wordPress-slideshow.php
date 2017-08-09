<?php
/**
 * Plugin Name: Wordpress Slideshow
 * Description: This plugin to make a slideshow module for wordpress website using short code.
 * Admin can reorder of slides and remove slides using ajax
 * Author: Harsiddhi Thakkar
 * Author Url:https://github.com/harsiddhi
 * version:1.0
 * License: GPL-2.0+
 **/
require_once 'admin/admin-slideshow-settings.php';
require_once 'frontend/frontend-slideshow.php';

// include all scripts and style.
add_action( 'admin_enqueue_scripts', 'slider_backend_enqueue_script' );
add_action( 'wp_enqueue_scripts', 'slider_assets_enqueue_script' );

/*Add action for slide order and slide remove*/

add_action( 'wp_ajax_remove_current_slide', 'remove_current_slide_callback' );
add_action( 'wp_ajax_new_slide_order_ajax', 'new_slide_order_ajax_callback' );

// Add Backend scripts.
function slider_backend_enqueue_script() {
	wp_enqueue_script( 'slideshow-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( "jquery" ) );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_style( 'admin-style', plugins_url( 'assets/css/admin.css', __FILE__ ) );


	wp_localize_script( 'slideshow-admin-script', 'admin_ajax_object',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		)
	);
}

// Add Assets scripts.
function slider_assets_enqueue_script() {
	wp_enqueue_script( 'slideshow-script', plugins_url( 'lib/flexslider.js', __FILE__ ), array( "jquery" ) );
	wp_enqueue_style( 'slideshow-style', plugins_url( 'lib/slider.css', __FILE__ ) );
}

<?php
/**
 * Theme Setup
 * This file is loaded using 'after_setup_theme' hook at priority 10
 *
 * @package    Nevark
 * @subpackage Theme
 */


/* === WordPress === */


// Automatically add <title> to head.
add_theme_support( 'title-tag' );

// Adds core WordPress HTML5 support.
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

// Add theme support for WordPress Custom Logo
add_theme_support( 'custom-logo' );

// Add theme support for WordPress Custom Background
add_theme_support( 'custom-background', array(
	'default-color'      => nevark_default_style( 'site_background' ),
	'default-image'      => hoot_data()->template_uri . '/images/header.jpg',
	'default-repeat'     => 'no-repeat',
	'default-position-x' => 'center',
	'default-position-y' => 'top',
	'default-size'       => 'cover',
	'default-attachment' => 'fixed',
) );

// Adds theme support for WordPress 'featured images'.
add_theme_support( 'post-thumbnails' );

// Automatically add feed links to <head>.
add_theme_support( 'automatic-feed-links' );

// WordPress Jetpack
add_theme_support( 'infinite-scroll', array(
	'type' => apply_filters( 'nevark_jetpack_infinitescroll_type', 'click' ), // scroll or click
	'container' => apply_filters( 'nevark_jetpack_infinitescroll_container', 'content' ),
	'footer' => false,
	'wrapper' => true,
	'render' => apply_filters( 'nevark_jetpack_infinitescroll_render', 'nevark_jetpack_infinitescroll_render' ),
) );


/* === WooCommerce Plugin === */


// Woocommerce support and init load theme woo functions
if ( class_exists( 'WooCommerce' ) ) {
	add_theme_support( 'woocommerce' );
	if ( file_exists( hoot_data()->template_dir . 'woocommerce/functions.php' ) )
		include_once( hoot_data()->template_dir . 'woocommerce/functions.php' );
}


/* === Hootkit Plugin === */


// Load theme's Hootkit functions if plugin is active
if ( class_exists( 'HootKit' ) && file_exists( hoot_data()->template_dir . 'hootkit/functions.php' ) )
	include_once( hoot_data()->template_dir . 'hootkit/functions.php' );


/* === Theme Hooks === */


/**
 * Handle content width for embeds and images.
 * Hooked into 'init' so that we can pull custom content width from theme options
 *
 * @since 1.0
 * @return void
 */
function nevark_set_content_width() {
	$width = intval( hoot_get_mod( 'site_width' ) );
	$width = !empty( $width ) ? $width : 1260;
	$GLOBALS['content_width'] = absint( $width );
}
add_action( 'init', 'nevark_set_content_width', 10 );

/**
 * Modify the '[...]' Read More Text
 *
 * @since 1.0
 * @return string
 */
function nevark_readmoretext( $more ) {
	$read_more = esc_html( hoot_get_mod('read_more') );
	/* Translators: %s is the HTML &rarr; symbol */
	// $read_more = ( empty( $read_more ) ) ? sprintf( __( 'Continue Reading %s', 'nevark' ), '&rarr;' ) : $read_more;
	$read_more = ( empty( $read_more ) ) ? __( 'Continue Reading', 'nevark' ) : $read_more;
	return $read_more;
}
add_filter( 'hoot_readmoretext', 'nevark_readmoretext' );

/**
 * Modify the exceprt length.
 * Make sure to set the priority correctly such as 999, else the default WordPress filter on this function will run last and override settng here.
 *
 * @since 1.0
 * @return void
 */
function nevark_custom_excerpt_length( $length ) {
	if ( is_admin() )
		return $length;

	$excerpt_length = intval( hoot_get_mod('excerpt_length') );
	if ( !empty( $excerpt_length ) )
		return $excerpt_length;
	return 50;
}
add_filter( 'excerpt_length', 'nevark_custom_excerpt_length', 999 );

/**
 * Register recommended plugins via TGMPA
 *
 * @since 1.0
 * @return void
 */
function nevark_tgmpa_plugins() {
	// Array of plugin arrays. Required keys are name and slug.
	// Since source is from the .org repo, it is not required.
	$plugins = apply_filters( 'nevark_tgmpa_plugins', array(
		array(
			'name'     => __( '(HootKit) Nevark Sliders, Widgets, 1-Click Installation', 'nevark' ),
			'slug'     => 'hootkit',
			'required' => false,
		),
	) );
	// Array of configuration settings.
	$config = array(
		'is_automatic' => true,
	);
	// Register plugins with TGM_Plugin_Activation class
	tgmpa( $plugins, $config );
}
add_filter( 'tgmpa_register', 'nevark_tgmpa_plugins' );
<?php
/**
 * This file contains functions and hooks for styling Hootkit plugin
 *   Hootkit is a free plugin released under GPL license and hosted on wordpress.org.
 *   It is recommended to the user via wp-admin using TGMPA class
 *
 * This file is loaded at 'after_setup_theme' action @priority 10 ONLY IF hootkit plugin is active
 *
 * @package    Nevark
 * @subpackage HootKit
 */

// Register HootKit
add_filter( 'hootkit_register', 'nevark_register_hootkit', 5 );

// Set data for theme scripts localization. hootData is actually localized at priority 11, so populate data before that at priority 9
add_action( 'wp_enqueue_scripts', 'nevark_localize_hootkit', 9 );
// Add hootkit styles. Set priority to @11 (unlike other scripts/styles @10)
// However we set stylesheet dependency to main stylesheet so hootkit css is loaded afterwards.
// Hootkit plugin loads its styles at default @10 (we skip this using config 'theme_css')
// The theme's main style is loaded @12 (Hence dynamic styles are loaded after -> now hooked to hootkit)
// The theme's main script is loaded @11
add_action( 'wp_enqueue_scripts', 'nevark_enqueue_hootkit', 11 );
// Set dynamic css handle to hootkit
add_filter( 'hoot_style_builder_inline_style_handle', 'nevark_dynamic_css_hootkit_handle', 5 );

// Add dynamic CSS for hootkit
add_action( 'hoot_dynamic_cssrules', 'nevark_hootkit_dynamic_cssrules' );

/**
 * Register Hootkit
 *
 * @since 1.0
 * @param array $config
 * @return string
 */
if ( !function_exists( 'nevark_register_hootkit' ) ) :
function nevark_register_hootkit( $config ) {
	// Array of configuration settings.
	$config = array(
		'nohoot'    => false,
		'theme_css' => true,
		'modules'   => array(
			'sliders' => array( 'image', 'postimage' ),
			'widgets' => array( 'announce', 'content-blocks', 'content-posts-blocks', 'cta', 'icon', 'post-grid', 'post-list', 'social-icons', 'ticker', 'profile' ),
		),
		'supports'  => array( 'cta-styles', 'content-blocks-style5', 'content-blocks-style6' ),
	);
	if ( apply_filters( 'nevark_support_ocdi', true ) ) {
		$config['modules']['importer'] = array( array(
				'import_file_name'           => __( 'Nevark Demo', 'nevark' ),
				'import_file_url'            => 'https://demo.wphoot.com/downloads/nevark-content.xml',
				'import_widget_file_url'     => 'https://demo.wphoot.com/downloads/nevark-widgets-free.wie',
				'import_customizer_file_url' => 'https://demo.wphoot.com/downloads/nevark-customize-free.dat',
				'import_preview_image_url'   => hoot_data()->template_uri . 'screenshot.jpg',
				/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
				'import_notice'              => sprintf( esc_html__( 'You are using the free version of the theme.%1$sSome features (available only in the premium version) will not get imported - You may see %2$s"Could not import"%3$s message for these features in the log once the installation is finished. You can safely ignore these messages.', 'nevark' ), '<br />', '<em>', '</em>' ),
				'preview_url'                => 'https://demo.wphoot.com/nevark/',
			), );
	}
	return $config;
}
endif;

/**
 * Enqueue Scripts and Styles
 *
 * @since 2.7
 * @access public
 * @return void
 */
if ( !function_exists( 'nevark_localize_hootkit' ) ) :
function nevark_localize_hootkit() {
	$scriptdata = hoot_data( 'scriptdata' );
	if ( empty( $scriptdata ) )
		$scriptdata = array();
	$scriptdata['contentblockhover'] = 'enable'; // This needs to be explicitly enabled by supporting themes
	$scriptdata['contentBlockTopAdjust'] = 5; // 4 pixel for bottom padding, 1px bottom border
	$scriptdata['contentBlockTitleMargin'] = 8; // reintroduce bottom margin for title before making $text visible to prevent jerk on hover out
	hoot_set_data( 'scriptdata', $scriptdata );
}
endif;

/**
 * Enqueue Scripts and Styles
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'nevark_enqueue_hootkit' ) ) :
function nevark_enqueue_hootkit() {

	/* Load Hootkit Style - Add dependency so that hotkit is loaded after */
	$style_uri = hoot_locate_style( 'hootkit/hootkit' );
	wp_enqueue_style( 'nevark-hootkit', $style_uri, array( 'hoot-style' ), hoot_data()->template_version );

	/* Load Hootkit Javascript */
	// $script_uri = hoot_locate_script( 'hootkit/hootkit' );
	// wp_enqueue_script( 'nevark-hootkit', $script_uri, array( 'jquery' ), hoot_data()->template_version, true );

}
endif;

/**
 * Set dynamic css handle to hootkit
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'nevark_dynamic_css_hootkit_handle' ) ) :
function nevark_dynamic_css_hootkit_handle( $handle ) {
	return 'nevark-hootkit';
}
endif;

/**
 * Custom CSS built from user theme options for hootkit features
 * For proper sanitization, always use functions from library/sanitization.php
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'nevark_hootkit_dynamic_cssrules' ) ) :
function nevark_hootkit_dynamic_cssrules() {

	// Get user based style values
	$styles = nevark_user_style(); // echo '<!-- '; print_r($styles); echo ' -->';
	extract( $styles );

	/*** Add Dynamic CSS ***/

	/* Light Slider */

	hoot_add_css_rule( array(
						'selector'  => '.lSSlideOuter ul.lSPager.lSpg > li:hover a, .lSSlideOuter ul.lSPager.lSpg > li.active a',
						'property'  => 'background-color',
						'value'     => $accent_color,
						'idtag'     => 'accent_color',
					) );
	hoot_add_css_rule( array(
						'selector'  => '.lSSlideOuter ul.lSPager.lSpg > li a',
						'property'  => 'border-color',
						'value'     => $accent_color,
						'idtag'     => 'accent_color',
					) );

	hoot_add_css_rule( array(
						'selector'  => '.wrap-light-on-dark .hootkitslide-head, .wrap-dark-on-light .hootkitslide-head',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background' => array( $accent_color, 'accent_color' ),
							'color'      => array( $accent_font, 'accent_font' ),
							),
					) );


	/* Sidebars and Widgets */

	hoot_add_css_rule( array(
						'selector'  => '.widget .view-all a:hover',
						'property'  => 'color',
						'value'     => $accent_color,
						'idtag'     => 'accent_color',
					) ); // Overridden in premium

	if ( !empty( $widgetmargin ) ) :
		hoot_add_css_rule( array(
						'selector'  => '.bottomborder-line:after' . ',' . '.bottomborder-shadow:after',
						'property'  => 'margin-top',
						'value'     => $widgetmargin,
						'idtag'     => 'widgetmargin',
					) );
		hoot_add_css_rule( array(
						'selector'  => '.topborder-line:before' . ',' . '.topborder-shadow:before',
						'property'  => 'margin-bottom',
						'value'     => $widgetmargin,
						'idtag'     => 'widgetmargin',
					) );
	endif;
	if ( !empty( $smallwidgetmargin ) ) :
		hoot_add_css_rule( array(
						'selector'  => '.content-block-row' . ',' . '.vcard-row',
						'property'  => 'margin-bottom',
						'value'     => $smallwidgetmargin,
						'idtag'     => 'widgetmargin',
					) );
	endif;

	hoot_add_css_rule( array(
						'selector'  => '.cta-subtitle',
						'property'  => 'color',
						'value'     => $accent_color,
						'idtag'     => 'accent_color',
					) );

	hoot_add_css_rule( array(
						'selector'  => '.social-icons-icon',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background' => array( $accent_color, 'accent_color' ),
							'color'      => array( $accent_font, 'accent_font' ),
							),
					) );

	hoot_add_css_rule( array(
						'selector' => '.content-block-icon i',
						'property' => 'color',
						'value'    => $accent_color,
						'idtag'    => 'accent_color',
					) );

	hoot_add_css_rule( array(
						'selector' => '.icon-style-circle' .',' . '.icon-style-square',
						'property' => 'border-color',
						'value'    => $accent_color,
						'idtag'    => 'accent_color',
					) );

	hoot_add_css_rule( array(
						'selector'  => '.content-block-style3 .content-block-icon',
						'property'  => 'background',
						'value'     => $content_bg_color,
					) );

}
endif;

/**
 * Modify Content Block settings
 *
 * @since 2.7
 * @param array $settings
 * @return string
 */
function nevark_content_blocks_widget_settings( $settings ) {
	if ( isset( $settings['form_options']['style']['std'] ) )
		$settings['form_options']['style']['std'] = 'style5';
	if ( isset( $settings['form_options']['boxes']['fields']['link']['std'] ) )
		$settings['form_options']['boxes']['fields']['link']['std'] = __( 'Continue Reading', 'nevark' );
	return $settings;
}
add_filter( 'hootkit_content_blocks_widget_settings', 'nevark_content_blocks_widget_settings', 5 );

/**
 * Modify Content Block settings
 *
 * @since 2.7
 * @param array $settings
 * @return string
 */
function nevark_content_posts_blocks_widget_settings( $settings ) {
	if ( isset( $settings['form_options']['style']['std'] ) )
		$settings['form_options']['style']['std'] = 'style5';
	return $settings;
}
add_filter( 'hootkit_content_posts_blocks_widget_settings', 'nevark_content_posts_blocks_widget_settings', 5 );
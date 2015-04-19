<?php

// Tell the main theme that a child theme is running. Do not remove this.
$GLOBALS['builder_child_theme_loaded'] = true;

// Load translations
load_theme_textdomain( 'it-l10n-Builder-Everett', get_stylesheet_directory() . '/lang' );


// Theme Support Features
add_theme_support( 'builder-3.0' );
add_theme_support( 'builder-responsive' );
add_theme_support( 'builder-full-width-modules' );


// Enqueuing and Using Custom Javascript/Jquery
function custom_load_custom_scripts() {
if ( file_exists( get_stylesheet_directory() . '/js/custom_jquery_additions.js' ) )
    $url = get_stylesheet_directory_uri() . '/js/custom_jquery_additions.js';
else if ( file_exists( get_template_directory() . '/js/custom_jquery_additions.js' ) )
    $url = get_template_directory_uri() . '/js/custom_jquery_additions.js';
if ( ! empty( $url ) )
    wp_enqueue_script( 'custom_jquery_additions', $url, array('jquery'), false, true );
}
add_action( 'wp_enqueue_scripts', 'custom_load_custom_scripts' );


// Tag Cloud Widget functionality
function custom_tag_cloud_widget($args) {
	$args['number'] = 0; // adding a 0 will display all tags
	$args['largest'] = 22; // largest tag
	$args['smallest'] = 12; // smallest tag
	$args['unit'] = 'px'; // tag font unit
	$args['format'] = 'flat';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'custom_tag_cloud_widget' );


// Add Support for Alternate Module Styles
if ( ! function_exists( 'it_builder_loaded' ) ) {
	function it_builder_loaded() {
		builder_register_module_style( 'image', 'No Spacing', 'image-no-spacing' );
		builder_register_module_style( 'image', 'Full Window', 'image-full-window' );
		builder_register_module_style( 'html', 'Callout', 'html-callout' );
	}
}
add_action( 'it_libraries_loaded', 'it_builder_loaded' );


// registering post thumbnail sizes
if ( function_exists( 'add_image_size' ) ) {
		add_image_size( 'index_thumbnail', 0, 0, true );
}

// 38solutions - customization to change default button text from "Buy Now" to "Donate" 

function my_translated_text_strings( $translated_text, $untranslated_text, $domain ) {

            $translated_text = $untranslated_text;

            if ( 'LION' === $domain || 'it-l10n-ithemes-exchange' === $domain ) {

                            switch ( $untranslated_text ) {
                                            case 'Buy Now' :
                                                            $translated_text = 'Donate Now';
                                                            break;
                            }
            }

            return $translated_text;
}
add_filter( 'gettext', 'my_translated_text_strings', 10, 3 );
add_filter( 'ngettext', 'my_translated_text_strings', 10, 3 );

// 38solutions - remove Exchange js from loading on every page (which slows down the site)

function my_stripe_enqueue_scripts() {
        
        if ( !it_exchange_is_page() ||  !function_exists( 'it_exchange_stripe_addon_enqueue_script' ) ) {
                
                wp_dequeue_script( 'stripe' );
                wp_dequeue_script( 'stripe-addon-js' );
        }
}
add_action( 'wp_enqueue_scripts', 'my_stripe_enqueue_scripts', 99);

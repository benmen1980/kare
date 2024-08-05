<?php
/**
 * Enqueue scripts and styles.
 */

$theme_data = wp_get_theme();
define( 'THEME_VERSION', $theme_data->Version );


function kare_custom_scripts() {
    wp_enqueue_style( 'kare-style-min', get_template_directory_uri() . '/dist/css/style.min.css' );
    wp_enqueue_style( 'kare-style', get_template_directory_uri() . '/dist/css/style.min.css' );
    wp_enqueue_style( 'slick-css', get_template_directory_uri() . '/dist/css/slick.css' );
	wp_enqueue_style( 'slick-theme-css', get_template_directory_uri() . '/dist/css/slick-theme.css' );
    wp_enqueue_style('swiper-css', get_template_directory_uri() . '/dist/css/swiper-bundle.min.css');

    wp_enqueue_script('jquery', get_template_directory_uri() . '/js/jquery-3.7.1.min.js', false, '3.7.1',true);
    wp_enqueue_script( 'kare-scripts', get_template_directory_uri() . '/dist/js/scripts.js', array(), '1.0.1' );
    wp_enqueue_script( 'kare-slick', get_template_directory_uri() . '/dist/js/slick.min.js', array(), '', false );
    wp_enqueue_script('swiper-js', get_template_directory_uri() . '/dist/js/swiper-bundle.min.js', array('jquery'), '', true);

}

add_action( 'wp_enqueue_scripts', 'kare_custom_scripts' );

/**
 * Enqueue  Admin Scripts
 */
function gant_admin_script( $hook ) {
    wp_enqueue_style( 'admin_styles', get_template_directory_uri() . '/dist/css/admin.css', false, '1.0' );
}
add_action( 'admin_enqueue_scripts', 'gant_admin_script' );
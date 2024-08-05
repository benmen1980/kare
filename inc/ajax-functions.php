<?php 

function kare_ajax_enqueue() {
	// Enqueue javascript on the frontend.
    wp_enqueue_script('kare-ajax-scripts', get_template_directory_uri() . '/dist/js/ajax-scripts.js', array('jquery'));
    // The wp_localize_script allows us to output the ajax_url path for our script to use.
	wp_localize_script('kare-ajax-scripts', 'ajax_obj', array( 
        'ajax_url' => admin_url( 'admin-ajax.php' ),
    ));
}

add_action( 'wp_enqueue_scripts', 'kare_ajax_enqueue' );
<?php 

function kare_ajax_enqueue() {
	// Enqueue javascript on the frontend.
    wp_enqueue_script('kare-ajax-scripts', get_template_directory_uri() . '/dist/js/ajax-scripts.js', array('jquery'));
    // The wp_localize_script allows us to output the ajax_url path for our script to use.
	wp_localize_script('kare-ajax-scripts', 'ajax_obj', array( 
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
        'woo_shop_url' => get_permalink( wc_get_page_id( 'cart' ) )
    ));
}

add_action( 'wp_enqueue_scripts', 'kare_ajax_enqueue' );


// function auto_update_cart_on_quantity_change() {
//     wp_enqueue_script( 'custom-cart-quantity-update', get_template_directory_uri() . '/js/cart-quantity-update.js', array( 'jquery' ), '1.0', true );
//     wp_localize_script( 'custom-cart-quantity-update', 'woocommerce_params', array(
//         'ajax_url' => WC_AJAX::get_endpoint( '%%endpoint%%' )
//     ));
// }
// add_action( 'wp_enqueue_scripts', 'auto_update_cart_on_quantity_change' );

add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
        
function woocommerce_ajax_add_to_cart() {

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

        do_action('woocommerce_ajax_added_to_cart', $product_id);

        // if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
        wc_add_to_cart_message(array($product_id => $quantity), true);
        // }

        $data =  wc_print_notices(true); 
        wp_send_json_success($data);
        wc_clear_notices();

        WC_AJAX :: get_refreshed_fragments();
    } else {

        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id),
            'error_msg' => wc_print_notices(true)); 

        wp_send_json_success($data);
        wc_clear_notices();
    }

    wp_die();
}
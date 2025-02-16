<?php 

function kare_ajax_enqueue() {
	// Enqueue javascript on the frontend.
    wp_enqueue_script('kare-ajax-scripts', get_template_directory_uri() . '/dist/js/ajax-scripts.js', array('jquery'));
    // The wp_localize_script allows us to output the ajax_url path for our script to use.
	wp_localize_script('kare-ajax-scripts', 'ajax_obj', array( 
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
        'woo_shop_url' => get_permalink( wc_get_page_id( 'cart' ) ),
        'lang'     => apply_filters('wpml_current_language', null),
        'product_added_message' => __('Product added to cart successfully!', 'kare')
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
    $variation_id =  isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
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

add_action('wp_enqueue_scripts', 'enqueue_city_autocomplete_script');

function enqueue_city_autocomplete_script() {
    if (is_checkout()) { // Only load on checkout page
        wp_enqueue_script('autocomplete_script', get_template_directory_uri() . '/dist/js/ajax-scripts.js', array('jquery', 'jquery-ui-autocomplete'), null, true);
        wp_localize_script('autocomplete_script', 'ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }
}

/**
 * Get cities from databse 
 */
add_action( 'wp_ajax_autocomplete_city', 'autocomplete_city' );
// for non-logged in users:
add_action( 'wp_ajax_nopriv_autocomplete_city', 'autocomplete_city' );

function autocomplete_city(){
    $aa = $_REQUEST['city'];
    if( empty(trim($_REQUEST['city']) ) ) {
        wp_send_json(array(
            'results' => [],
            'success' =>  true
        ));
        wp_die();
    }

    global $wpdb;
    $table_cities_and_settelments = $wpdb->prefix . 'list_of_cities_and_towns';

    $search_term = $wpdb->esc_like(trim($_REQUEST['city']));

    $current_locale = get_locale();
    $search_column = ($current_locale === 'he_IL') ? 'name_he' : 'name_en';


    // search for any city starting with the string
    $results = $wpdb->get_results( $wpdb->prepare(
        "
        SELECT id, $search_column as value
        FROM `$table_cities_and_settelments` 
        WHERE $search_column LIKE %s
        ORDER BY $search_column
        LIMIT 10
        ",
        ($search_term . '%')
    ) );

    // if we didn't find any city, try searching for any city containing the string
    if( !count($results) ) {
        $results = $wpdb->get_results( $wpdb->prepare(
            "
            SELECT id, $search_column as value
            FROM `$table_cities_and_settelments` 
            WHERE $search_column LIKE %s
            ORDER BY name_en
            LIMIT 10
            ",
            ('%' . $search_term . '%')
        ) );
    }

    wp_send_json(array(
        'results' => $results,
        'success' =>  true
    ));
    wp_die();
}

/**
 * Update shipping cost when selecting a city 
 */

/*add_action('wp_ajax_get_shipping_cost', 'get_shipping_cost');
add_action('wp_ajax_nopriv_get_shipping_cost', 'get_shipping_cost');

function get_shipping_cost() {
    $city_id = intval($_POST['city_id']);
    $cities_cost_list = get_field('list_cities_cost', 'option'); 

    $default_shipping_cost = 0.0;

    if ($cities_cost_list) {
        foreach ($cities_cost_list as $city) {
            // Check if the entered city name matches any city name in the array
            if (strcasecmp($city['city_name'], $city_name) == 0) { // Case-insensitive comparison
                $default_shipping_cost = $city['cost']; // Return the matching city's shipping cost
            }
        }
    }

    wp_send_json_success(array('shipping_cost' => $shipping_cost));
}*/


/*add_action( 'wp_ajax_update_checkout_fields', 'update_checkout_fields_based_on_shipping' );
add_action( 'wp_ajax_nopriv_update_checkout_fields', 'update_checkout_fields_based_on_shipping' );

function update_checkout_fields_based_on_shipping() {
    // Fetch the checkout fields with the custom logic applied
    $selected_shipping_method = isset($_POST['ship_method']) ? sanitize_text_field($_POST['ship_method']) : '';

    // קבלת השדות המותאמים
    $fields = WC()->checkout()->get_checkout_fields();
        
    // בדיקה אם המשלוח שנבחר כולל את "local_pickup"
    if (strpos($selected_shipping_method, 'local_pickup') !== false) {
        unset($fields['billing']['billing_country']);
        unset($fields['billing']['billing_address_1']);
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['billing_postcode']);
        unset($fields['billing']['billing_city']);
        unset($fields['shipping']);
        add_filter('woocommerce_cart_needs_shipping_address', '__return_false');
    }
    // Send the updated fields back to JavaScript
    wp_send_json_success( $fields );
}*/

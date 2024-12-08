<?php

/**
 * Changes to the category page
 */

add_filter("woocommerce_get_stock_html", function(){ return ""; });

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Changes to the archive product page
 */
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);


/*// Filtering in breadcrumbs
add_filter('woocommerce_get_breadcrumb', 'custom_clickable_last_breadcrumb');
function custom_clickable_last_breadcrumb($crumbs) {

    // hide the current category/product/page in breadcrumbs
    array_pop($crumbs); // Remove the last breadcrumb (current item)
    return $crumbs;
}*/


/**
 * Changes to the cart page
 */

// remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );

add_filter( 'woocommerce_coupon_error', 'custom_coupon_error_message', 10, 3 );

function custom_coupon_error_message( $err, $err_code, $coupon ) {
    if ( $err_code == WC_Coupon::E_WC_COUPON_NOT_EXIST ) {
        $err = 'The code you entered does not exist - please enter it again.';
    }

    return $err;
}


/**
 * @snippet       Add to Cart Quantity drop-down - WooCommerce
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 5.0
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
 function woocommerce_quantity_input( $args = array(), $product = null, $echo = true ) {
  
    if ( is_null( $product ) ) {
       $product = $GLOBALS['product'];
    }

    $product_id = $product ? $product->get_id() : null;
    $pack_quantity = get_post_meta( $product_id, 'quantity_product_order', true );
  
    $defaults = array(
       'input_id' => uniqid( 'quantity_' ),
       'input_name' => 'quantity',
       'input_value' => '1',
       'classes' => apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text' ), $product ),
       'max_value' => apply_filters( 'woocommerce_quantity_input_max', -1, $product ),
    //    'min_value' => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
       'min_value' => isset($pack_quantity) && $pack_quantity >= 1 ? $pack_quantity : apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
       'step'        => isset($pack_quantity) && $pack_quantity > 1 ? $pack_quantity : apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
       'pattern' => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
       'inputmode' => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
       'product_name' => $product ? $product->get_title() : '',
    );
  
    $args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );

    // Apply sanity to min/max args - min cannot be lower than 0.
    $args['min_value'] = isset($pack_quantity) && $pack_quantity >= 1 ? $pack_quantity : max( $args['min_value'], 1 );

    // Note: change 20 to whatever you like
    $args['max_value'] = 24 > $args['max_value'] ? $args['max_value'] : 24;
  
    // Max cannot be lower than min if defined.
    if ( '' !== $args['max_value'] && $args['max_value'] < $args['min_value'] ) {
       $args['max_value'] = $args['min_value'];
    }
   
    $options = '';
     
    for ( $count = $args['min_value']; $count <= $args['max_value']; $count = $count + $args['step'] ) {
  
       // Cart item quantity defined?
       if ( '' !== $args['input_value'] && $args['input_value'] >= 1 && $count == $args['input_value'] ) {
         $selected = 'selected';      
       } else $selected = '';
  
       $options .= '<option value="' . $count . '"' . $selected . '>' . $count . '</option>';
  
    }
      
    $string = '<div class="quantity">
        <select class="qty" name="' . $args['input_name'] . '">' . $options . '</select>
        <span class="cart_product_quantity_icon">' . file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-down.svg') . '</span>
    </div>';
    // $string = '<div class="quantity">
    //     <select class="qty" name="' . $args['input_name'] . '">' . $options . '</select>
    // </div>';
  
    if ( $echo ) {
       echo $string;
    } else {
       return $string;
    }
   
}

//Update the max quantity according to the available stock or according to the sand stock
add_filter('woocommerce_quantity_input_args', 'custom_quantity_input_args', 10, 2);
function custom_quantity_input_args($args, $product) {
    $kare_stock = get_post_meta($product->get_id(), 'kare_general_stock', true);
    $stock_available = get_post_meta($product->get_id(), '_stock', true);
    $backorders_status = get_post_meta($product->get_id(), '_backorders', true);

    $max_quantity = $product->get_max_purchase_quantity();
    if ($stock_available <= 0) {
        if ( $backorders_status === 'yes' ) {
            $max_quantity = !empty($kare_stock) ? intval($kare_stock) : 0;
        }  else {
            $max_quantity = 0; 
        }
    } else {
        $max_quantity = apply_filters( 'woocommerce_quantity_input_max', $max_quantity, $product );
    }
    $args['max_value'] = $max_quantity;
    return $args;
}
/**
 * @snippet       Automatically Update Cart on Quantity Change - WooCommerce
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 8
 * @community     https://businessbloomer.com/club/
 */
 
 add_action( 'wp_footer', 'bbloomer_cart_refresh_update_qty' ); 
 
 function bbloomer_cart_refresh_update_qty() {
    if ( is_cart() || ( is_cart() && is_checkout() ) ) {
       wc_enqueue_js( "
        $('div.woocommerce').on('change', 'select.qty', function(){
            $('[name=\'update_cart\']').trigger('click');
        });
       " );
    }
 }

/**
 * Changes to the checkout page
 */

 //Removes the coupon code form from the WooCommerce checkout page.
 function remove_checkout_coupon_form() {
    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
}
add_action( 'woocommerce_before_checkout_form', 'remove_checkout_coupon_form', 9 );

//Adjust fields in the checkout form
add_filter( 'woocommerce_checkout_fields', 'remove_checkout_fields_placeholder' );
function remove_checkout_fields_placeholder( $fields ) {
    
    //Removes fields from the WooCommerce checkout page.
    unset( $fields['billing']['billing_state_field'] );
    // unset( $fields['billing']['billing_address_2'] );
    // unset( $fields['shipping']['shipping_address_2'] );

    $fields['billing']['billing_city'] = array(
        'type'        => 'text', // Keep it as text for autocomplete
        'label'       => __('City', 'woocommerce'), // Set the label
        'required'    => true,
        'autocomplete' => 'none', // Prevent browser autocomplete
    );

    $fields['shipping']['shipping_city'] = array(
        'type'        => 'text', // Keep it as text for autocomplete
        'label'       => __('City', 'woocommerce'), // Set the label
        'required'    => true,
        'autocomplete' => 'none', // Prevent browser autocomplete
    );

    $chosen_methods = WC()->session->get('chosen_shipping_methods');
    if (!empty($chosen_methods)) {
        foreach ($chosen_methods as $method) {
            if ( strpos( $method, 'local_pickup' ) !== false ) {
                // print_r(' 11 ');
                unset( $fields['billing']['billing_country'] );
                unset( $fields['billing']['billing_address_1'] );
                unset( $fields['billing']['billing_address_2'] );
                unset( $fields['billing']['billing_postcode'] );
                unset( $fields['billing']['billing_city'] );
                unset( $fields['shipping'] );
                add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false' );

            }
        }
    }

    return $fields;
}

// Make the postcode field optional
add_filter( 'woocommerce_default_address_fields', 'update_address_fields_func' );
function update_address_fields_func( $fields ) {
    $fields['postcode']['required'] = false;
    return $fields;
}

//Calculating Shipping cost per product by class-shipping
add_filter('woocommerce_package_rates', 'custom_shipping_cost_per_product_class', 10, 2);
function custom_shipping_cost_per_product_class($rates, $package) {

    $shipping_cost = 0.0;

    foreach ($package['contents'] as $item_id => $values) {

        // Get the product object
        $product = $values['data'];
        $quantity = $values['quantity'];

        // Get the shipping class
        $shipping_class = $product->get_shipping_class();

        // Define shipping cost based on shipping class
        switch ($shipping_class) {
            case 'small':
                $shipping_cost += (float) get_field('small_shipping_cost', 'option') * $quantity;
                break;
            case 'medium':
                $shipping_cost += (float) get_field('medium_shipping_cost', 'option') * $quantity;
                break;
            case 'large1':
                $shipping_cost += (float) get_field('large1_shipping_cost', 'option');
                break;
            case 'large2':
                $shipping_cost += (float) get_field('large2_shipping_cost', 'option');
                break;
            case 'large3':
                $shipping_cost += (float) get_field('large3_shipping_cost', 'option');
                break;
            default:
                $shipping_cost += 0; // Default cost
                break;
        }
    }

    // Retrieve the city for shipping or billing
    $shipping_city = WC()->customer->get_shipping_city();
    $billing_city = WC()->customer->get_billing_city();
    // Determine which city to use
    $city_name = !empty($shipping_city) ? $shipping_city : $billing_city;
    // print_r( $city_name );

    if (!empty($city_name)) {
        // $city_shipping_cost = get_shipping_cost_by_city($entered_city);
        $cities_cost_list = get_field('list_cities_cost', 'option'); 

        if ($cities_cost_list) {
            foreach ($cities_cost_list as $city) {
                if (strcasecmp($city['city_name'], $city_name) == 0) { 
                    $shipping_cost += $city['cost'];
                }
            }
        }
    }

    $order_total = WC()->cart->cart_contents_total;
    $max_sum_shipping = get_field('max_sum_shippping', 'option');
    if ($max_sum_shipping && $order_total >= $max_sum_shipping) {
        $shipping_cost = 0; // Set the shipping cost for orders under $50
    }   

    // Here you can set the cost to the shipping method or modify it
    foreach ($rates as $rate_key => $rate) {
        // Assuming you want to add this cost to a specific shipping method
        if ($rate->method_id === 'flat_rate') {
            $rates[$rate_key]->cost = $shipping_cost;
        }
    }       
    
    return $rates;
}

/*function get_shipping_cost_by_city($city_name) {
    $cities_cost_list = get_field('list_cities_cost', 'option'); 

    // Default cost if no match is found
    $default_shipping_cost = 0;

    if ($cities_cost_list) {
        foreach ($cities_cost_list as $city) {
            if (strcasecmp($city['city_name'], $city_name) == 0) { 
                return $city['cost']; // Return the matching city's shipping cost
            }
        }
    }

    return $default_shipping_cost;
}*/

// remove cost in the method name
add_filter( 'woocommerce_cart_shipping_method_full_label', 'custom_shipping_method_name_only', 10, 2 );
function custom_shipping_method_name_only( $label, $method ) {
    return $method->get_label();
}

// Remove the charge from the diplot position
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );

// Add the payment after the address fields
add_action( 'woocommerce_after_order_notes', 'woocommerce_checkout_payment' );

//Filter out the matching zone notice that shipping debug creates.
add_filter('woocommerce_add_message', 'wc_check_notices');
function wc_check_notices($message) {
    if (strpos($message, 'Customer matched zone') !== false) 
        return;
    else 
        return $message; 
}

function update_mini_cart_fragment( $fragments ) {
    ob_start();
    ?>
    <div class="mini-cart-wrapper">
        <?php woocommerce_mini_cart(); ?>
    </div>
    <?php
    $fragments['div.mini-cart-wrapper'] = ob_get_clean();
    
    return $fragments;
}
// add_filter( 'woocommerce_add_to_cart_fragments', 'update_mini_cart_fragment' );


/**
 * Search products of woocommerce
 */
// Only search in products
function filter_woocommerce_search( $query ) {
    if ( ! is_admin() && $query->is_search() && $query->is_main_query() ) {
        $query->set( 'post_type', 'product' ); 
    }
}
add_action( 'pre_get_posts', 'filter_woocommerce_search' );

//Search by Partial Product Name or SKU
function custom_search_by_name_or_sku( $search, $wp_query ) {
    global $wpdb;

    // Check if it's a search query and the main query
    if ( ! $wp_query->is_search() || ! $wp_query->is_main_query() || is_admin() ) {
        return $search;
    }

    // Get the search term entered by the user
    $search_term = $wp_query->get( 's' );
    if ( empty( $search_term ) ) {
        return $search;
    }

    // Use the wpdb esc_like method to escape the search term properly
    $search_term = $wpdb->esc_like( $search_term );

    // Build the custom search query to search in product titles and SKUs
    $search = "
        AND (
            {$wpdb->posts}.post_title LIKE '%{$search_term}%'
            OR (
                {$wpdb->postmeta}.meta_key = '_sku'
                AND {$wpdb->postmeta}.meta_value LIKE '%{$search_term}%'
            )
        )
    ";

    return $search;
}
add_filter( 'posts_search', 'custom_search_by_name_or_sku', 10, 2 );

function custom_join_for_sku_search( $join, $wp_query ) {
    global $wpdb;

    // Ensure it's only for search and the main query
    if ( ! $wp_query->is_search() || ! $wp_query->is_main_query() || is_admin() ) {
        return $join;
    }

    // Add the JOIN clause for postmeta to search SKUs
    $join .= " LEFT JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id ";

    return $join;
}
add_filter( 'posts_join', 'custom_join_for_sku_search', 10, 2 );

/**
 * Changes to the order-details page
 */
remove_action( 'woocommerce_order_details_before_order_table', 'woocommerce_order_details_table', 10 );

add_filter('woocommerce_is_purchasable', 'allow_purchase_if_acf_stock', 10, 2);

function allow_purchase_if_acf_stock($is_purchasable, $product) {
    // בדיקת המלאי בשדה ה-ACF
    $kare_stock = get_post_meta($product->get_id(), 'kare_general_stock', true);

    // אם המלאי בשדה ACF גדול מאפס, מאפשרים רכישה
    if (!empty($kare_stock) || $kare_stock > 0) {
        return true;
    }

    return $is_purchasable;
}
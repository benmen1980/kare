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
    $args['max_value'] = 0 > $args['max_value'] ? $args['max_value'] : 24;
  
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
* @snippet       Tiered Shipping Rates | WooCommerce
* @how-to        Get CustomizeWoo.com FREE
* @author        Rodolfo Melogli
* @testedwith    WooCommerce 5.0
* @community     https://businessbloomer.com/club/
*/
 
add_filter('woocommerce_package_rates', 'custom_shipping_price_based_on_order_amount', 10, 2);
function custom_shipping_price_based_on_order_amount($rates, $package) {
    // Get the order total amount
    $order_total = WC()->cart->cart_contents_total;
    $shipping_amount  = get_field('shipping_cost','option');
    $max_sum_shippping = get_field('max_sum_shippping', 'option');
    // Define the shipping rate based on the order amount
    if ($order_total <= $max_sum_shippping) {
        $shipping_cost = $shipping_amount; // Set the shipping cost for orders under $50
    } else {
        $shipping_cost = 0; // Set free shipping for orders of $50 or more
    }
    // Loop through the shipping rates
    foreach ($rates as $rate_key => $rate) {
        // Update the shipping cost for the specific shipping method
        if ($rate->method_id === 'flat_rate') {
            $rates[$rate_key]->cost = $shipping_cost;
            if($rates[$rate_key]->cost == 0){
                $rates[ $rate_key ]->label .= ' (free)';
            }
        }
    }

    return $rates;
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

    return $fields;
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

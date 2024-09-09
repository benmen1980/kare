<?php

/**
 * Changes to the category page
 */

add_filter("woocommerce_get_stock_html", function(){ return ""; });

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

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
       'min_value' => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
       'step'        => isset($pack_quantity) && $pack_quantity > 1 ? $pack_quantity : apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
       'pattern' => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
       'inputmode' => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
       'product_name' => $product ? $product->get_title() : '',
    );
  
    $args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );
   
    // Apply sanity to min/max args - min cannot be lower than 0.
    $args['min_value'] = max( $args['min_value'], 1 );
    // Note: change 20 to whatever you like
    $args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : 24;
  
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
 
add_filter( 'woocommerce_package_rates', 'bbloomer_woocommerce_tiered_shipping', 10, 2 );
 
function bbloomer_woocommerce_tiered_shipping( $rates, $package ) {
   $threshold = 1800;
   if ( WC()->cart->subtotal < $threshold ) {
      unset( $rates['flat_rate:1'] );
   } else {
      unset( $rates['flat_rate:2'] );
   }
   return $rates;
}
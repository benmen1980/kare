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

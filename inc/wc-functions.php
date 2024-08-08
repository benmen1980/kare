<?php

/**
 * Changes to the category page
 */

 add_filter("woocommerce_get_stock_html", function(){ return ""; });

 remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );



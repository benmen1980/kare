<?php 

function admin_bar(){

    if(is_user_logged_in()){
      add_filter( 'show_admin_bar', '__return_true' , 1000 );
    }
  }
  add_action('init', 'admin_bar' );

//Modify the account menu Label and Add SVG Icons
function custom_woocommerce_account_menu_items_svg_icons_and_labels( $items ) {

  //print_r($items);

  unset($items['downloads']);
  unset( $items['dashboard'] );

  $items['edit-account'] = __( 'Personal Data', 'kare' );
  $items['edit-address'] = __( 'Address Details', 'kare' );

  // Add the Wishlist link
  $items['wishlist'] = __('Wishlist', 'kare');
    
  $newitems = array(
    'edit-account'    => __( 'Personal Data', 'kare' ),
    'edit-address'    => _n( 'Addresses', 'Address', (int) wc_shipping_enabled(), 'woocommerce' ),
    'orders'          => __( 'Orders', 'woocommerce' ),
    'wishlist'          => __( 'Wishlist', 'woocommerce' ),
    'customer-logout' => __( 'Logout', 'woocommerce' ),
 ); 
 return $newitems;


}
add_filter( 'woocommerce_account_menu_items', 'custom_woocommerce_account_menu_items_svg_icons_and_labels' );

//add the wishlist endpoint to account menu
add_action('woocommerce_account_wishlist_endpoint', 'wishlist_endpoint_content');
function wishlist_endpoint_content() {
    if (shortcode_exists('yith_wcwl_wishlist')) {
        echo do_shortcode('[yith_wcwl_wishlist]');
    } else {
        echo '<p>' . __('Wishlist content is not available.', 'your-text-domain') . '</p>';
    }
}

// Add the endpoint to WooCommerce
add_action('init', 'add_wishlist_endpoint');
function add_wishlist_endpoint() {
    add_rewrite_endpoint('wishlist', EP_PAGES);
}

// Ensure the endpoint is added to the WooCommerce query vars
add_filter('query_vars', 'wishlist_query_vars');
function wishlist_query_vars($vars) {
    $vars[] = 'wishlist';
    return $vars;
}

// Flush rewrite rules on plugin/theme activation
add_action('activated_plugin', 'flush_rewrite_rules_on_activation');
function flush_rewrite_rules_on_activation() {
    flush_rewrite_rules();
}

add_action('after_switch_theme', 'flush_rewrite_rules_on_theme_switch');
function flush_rewrite_rules_on_theme_switch() {
    flush_rewrite_rules();
}

//Redirect Unauthenticated Users from login page to home with account popup open
add_action('template_redirect', 'redirect_if_not_logged_in');
function redirect_if_not_logged_in() {
    if (is_account_page() && !is_user_logged_in()) {
        // Redirect to the home page with a query parameter
        $redirect_url = add_query_arg('panel', 'account', home_url());
        wp_redirect($redirect_url);
        exit;
    }
}

// on logout redirect to home page
add_action('wp_logout','auto_redirect_after_logout');

function auto_redirect_after_logout(){

  wp_redirect( home_url() );
  exit();

}
// on login redirect to home page
add_filter('woocommerce_login_redirect', 'redirect_to_home_after_login', 10, 3);

function redirect_to_home_after_login($redirect_to) {
    // Always redirect to the home page after login
    return home_url();
}



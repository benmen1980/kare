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

// Add the "Full Name" field to the registration form
//add_action('woocommerce_register_form_start', 'add_full_name_field_to_registration_form');
function add_full_name_field_to_registration_form() {
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" placeholder="" name="full_name" id="reg_full_name" value="<?php if (!empty($_POST['full_name'])) echo esc_attr(wp_unslash($_POST['full_name'])); ?>" />
        <label for="reg_full_name"><?php esc_html_e('First Name Last Name', 'kare'); ?>&nbsp;<span class="required">*</span></label>   
    </p>
    <?php
}

// Validate Full Name Field
function validate_woocommerce_registration_full_name_field( $username, $email, $validation_errors ) {
    if ( isset( $_POST['full_name'] ) && empty( $_POST['full_name'] ) ) {
        $validation_errors->add( 'full_name_error', __( 'Please enter full name', 'woocommerce' ) );
    }

    return $validation_errors;
}
add_action( 'woocommerce_register_post', 'validate_woocommerce_registration_full_name_field', 10, 3 );

// Save Full Name Field as First Name and Last Name
function save_woocommerce_registration_full_name_field( $customer_id ) {
    if ( isset( $_POST['full_name'] ) ) {
        $full_name = sanitize_text_field( $_POST['full_name'] );
        
        // Split the full name by the first space encountered
        $name_parts = explode( ' ', $full_name, 2 );
        $first_name = $name_parts[0];
        $last_name = ( isset( $name_parts[1] ) ) ? $name_parts[1] : '';

        // Save the names
        update_user_meta( $customer_id, 'first_name', $first_name );
        update_user_meta( $customer_id, 'last_name', $last_name );
    }
}
add_action( 'woocommerce_created_customer', 'save_woocommerce_registration_full_name_field' );


//after registration, redirect to home page
add_filter( 'woocommerce_registration_redirect', 'custom_redirection_after_registration', 10, 1 );
function custom_redirection_after_registration( $redirection_url ){
    // Change the redirection Url
    $redirection_url = get_home_url(); // Home page

    return $redirection_url; // Always return something
}
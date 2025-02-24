<?php 
// add_filter('show_admin_bar', '__return_false');

function admin_bar(){

    if(is_user_logged_in()){
      add_filter( 'show_admin_bar', '__return_true' , 1000 );
    }
  }
//   add_action('init', 'admin_bar' );

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
        echo '<p>' . __('Wishlist content is not available.', 'kare') . '</p>';
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
        if ( is_wc_endpoint_url( 'lost-password' ) ) {
            if(isset( $_GET['reset-link-sent'] ) && $_GET['reset-link-sent'] === 'true'){
                $redirect_url = add_query_arg('panel', 'reset-link-sent', apply_filters('wpml_home_url', home_url()));
            }
            // Allow access to the "lost-password" endpoint without redirecting
            else{
                return;
            }
        }
        else{
            $redirect_url = add_query_arg('panel', 'account', apply_filters('wpml_home_url', home_url()));
        }
        wp_redirect($redirect_url);
        exit;
    }
}


add_action( 'woocommerce_login_failed', 'set_login_error_flag' );
function set_login_error_flag( $username ) {
    // Start session if not already started
    if ( ! session_id() ) {
        session_start();
    }
    $_SESSION['login_error'] = true;
}

// Clear the flag on successful login
add_action( 'wp_login', 'clear_login_error_flag' );
function clear_login_error_flag() {
    if ( ! session_id() ) {
        session_start();
    }
    unset( $_SESSION['login_error'] );
}





// on logout redirect to home page
//add_action('wp_logout','auto_redirect_after_logout');

function auto_redirect_after_logout(){

  wp_redirect( home_url() );
  exit();

}
// on login redirect to home page
add_filter('woocommerce_login_redirect', 'redirect_to_home_after_login', 10, 2);

function redirect_to_home_after_login($redirect_to) {
     // Always redirect to the home page after login
    return apply_filters('wpml_home_url', apply_filters('wpml_home_url', home_url()));
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

    if (!empty($_POST['birth_date']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['birth_date'])) {
        $validation_errors->add('birth_date_error', __('Invalid date format. Please use dd-mm-yyyy.', 'woocommerce'));
    }
    if (!empty($_POST['checkbox_club']) && isset($_POST['checkbox_club']) && empty($_POST['birth_date'])) {
        $validation_errors->add('birthday_error', __('Birthday is required to join the KARE Friends.', 'kare'));
    }
    if (!empty($_POST['checkbox_club']) && isset($_POST['checkbox_club']) && empty($_POST['sex_selection'])) {
        $validation_errors->add('gender_error', __('Gender is required to join the KARE Friends.', 'kare'));
    }
    if (empty($_POST['password'])) {
        $validation_errors->add('pswd_error', __('Passord is required.', 'kare'));
    }
    if ( empty( $_POST['reg_phone'] ) ) {
        $validation_errors->add( 'reg_phone_error', __( 'Phone number is required.', 'woocommerce' ) );
    } else {
        $phone = sanitize_text_field( $_POST['reg_phone'] );
        if ( ! preg_match( '/^05\d{8}$/', $phone ) ) {
            $validation_errors->add( 'reg_phone_error', __( 'Please enter a valid Israeli mobile phone number (e.g., 0501234567).', 'woocommerce' ) );
        }
    }

    return $validation_errors;
}
add_action( 'woocommerce_register_post', 'validate_woocommerce_registration_full_name_field', 10, 3 );

// Save full name field as first name and last name and also additional acf fields
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

    if (isset($_POST['birth_date'])) {
        update_user_meta($customer_id, 'birth_date', sanitize_text_field($_POST['birth_date']));
    }

    if (isset($_POST['user_arrived_choice'])) {
        update_user_meta($customer_id, 'user_arrived_choice', sanitize_text_field($_POST['user_arrived_choice']));
    }
    if (isset($_POST['sex_selection'])) {
        update_user_meta($customer_id, 'sex_selection', sanitize_text_field($_POST['sex_selection']));
    }
    if (isset($_POST['checkbox_club'])) {
        update_user_meta($customer_id, 'checkbox_club', 1);
    }
    if ( isset( $_POST['reg_phone'] ) ) {
        $phone = sanitize_text_field( $_POST['reg_phone'] );
        update_user_meta( $customer_id, 'billing_phone', $phone );
    }
}
add_action( 'woocommerce_created_customer', 'save_woocommerce_registration_full_name_field' );


//after registration, redirect to home page
add_filter( 'woocommerce_registration_redirect', 'custom_redirection_after_registration', 10, 1 );
function custom_redirection_after_registration( $redirect ){
    // Change the redirection Url
    if ( isset( $_GET['redirect_to'] ) && 'checkout' === $_GET['redirect_to'] ) {
        return wc_get_checkout_url(); //checkout page
    }
    return apply_filters('wpml_home_url', home_url()); // Home page
}

//use of the plugin is 'advanced-dynamic-pricing-woocommerce-pro' together with the Priority plugin
add_filter('adp_is_to_compensate_trd_party_adj_for_fixed_price', '__return_false');


function set_html_direction_attribute() {
    // Check if the body has the 'rtl' class
    if (is_rtl()) {
        echo ' dir="rtl"';
    } else {
        echo ' dir="ltr"';
    }
}
add_filter('language_attributes', 'set_html_direction_attribute');



/*
// upload list of city name to the DB - one size
function upload_city_name_file() {
    global $wpdb;

    // Define the table name
    $table_cities_and_settelments = $wpdb->prefix . 'list_of_cities_and_towns';

    // SQL query to create the table if it doesn't exist
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_cities_and_settelments (
        id INT AUTO_INCREMENT,
        name_he VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
        name_en VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Path to your CSV file
    $file_name = 'cities_name.csv';
    $upload_dir = wp_upload_dir();
    $csv_file_path = $upload_dir['path'] . '/' . $file_name;  

    if (file_exists($csv_file_path)) {

        // Open the CSV file for reading
        if (($handle = fopen($csv_file_path, 'r')) !== false) {
            // Skip the header row (optional)
            fgetcsv($handle);

            // Prepare the SQL insert statement with placeholders
            $insert_query = "INSERT INTO $table_cities_and_settelments (name_he, name_en) VALUES (%s, %s)";

            // Read each line in the CSV file
            while (($data = fgetcsv($handle)) !== false) {
                // Trim spaces from the city names
                $name_he = trim($data[0]);
                $name_en = trim($data[1]);

                // Insert the row into the table
                $wpdb->query($wpdb->prepare($insert_query, $name_he, $name_en));
            }
            fclose($handle);
        }
    }

}

add_action('upload_city_name_file_hook', 'upload_city_name_file');
if (!wp_next_scheduled('upload_city_name_file_hook')) {

    $res = wp_schedule_event(time(), 'none', 'upload_city_name_file_hook');

}*/


add_filter( 'woocommerce_order_received_verify_known_shoppers', '__return_false' );



function get_products_without_main_image() {
    // Arguments for the WP_Query
    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => [
            [
                'key'     => '_thumbnail_id', // Key for the main image
                'compare' => 'NOT EXISTS',   // No thumbnail set
            ],
        ],
    ];

    // Execute the query
    $query = new WP_Query($args);

    $products_without_image = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            // Get the product ID
            $product_id = get_the_ID();

            // Get the product object
            $product = wc_get_product($product_id);

            // Add the SKU to the result array if available
            if ($product) {
                $products_without_image[] = $product->get_sku();
            }
        }
    }

    // Reset the query
    wp_reset_postdata();

    return $products_without_image;
}

function get_products_in_hebrew_with_acf_field() {
    // Get the current language
    $current_language = apply_filters('wpml_current_language', null);

    // Check if we are in Hebrew
    if ($current_language !== 'he') {
        return 'This function only runs in Hebrew.';
    }

    // Arguments for WP_Query
    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'suppress_filters' => false, // Required for WPML to filter by language
    ];

    // Execute the query
    $query = new WP_Query($args);

    $products_with_field = [];
    $skus = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $product_id = get_the_ID();
            $product = wc_get_product($product_id);
            if ( get_field('product_details', $product_id) ):  
				$product_details = get_field('product_details', $product_id); // Getting the main set of fields

				// getting the fields from the internal field group 'pdt_information'
				$pdt_information = $product_details['pdt_information'];
				$material_details = $pdt_information['material_details'];



                // Check if the field is in English (e.g., string contains only English characters)
                if (preg_match('/^[A-Za-z0-9\s]+$/', $material_details)) {
                    $original_product_id = apply_filters('wpml_object_id', $product_id, 'product', true, 'en');

                    // Get the original product title
                    $original_title = get_the_title($original_product_id);
                    $products_with_field[] = [
                        'ID'        => $product_id,
                        'sku'       => $product->get_sku(),
                        'title'     => get_the_title($product_id),
                        'original_title'   => $original_title,
                        'field_value' => $material_details,
                    ];
                    $sku = $product ? $product->get_sku() : null;

                    if ($sku) {
                        $skus[] = $sku;
                    }
                }
            endif;
            
        }
    }

    // Reset the query
    wp_reset_postdata();

    //return $products_with_field;
    return $products_with_field;
}

//Example usage
// add_action('wp', function () {
//     if (isset($_GET['check_products'])) {
//         $products = get_products_in_hebrew_with_acf_field();
//         //echo implode(',', $products);
//         echo '<pre>';
//         print_r($products);
//         echo '</pre>';
//         exit; // Stop further processing
//     }
// });


// add_action('wp', function () {
//     // Check if the 'check' GET parameter is present in the URL
//     if (isset($_GET['check'])) {
//         $products = get_products_without_main_image();

//         // Check if products exist
//         if (!empty($products)) {
//             echo '<pre>';
//             print_r($products); // Output the products array
//             echo '</pre>';
//         } else {
//             echo 'No products without a main image were found.';
//         }

//         // Exit to prevent the rest of the page from rendering (optional)
//         exit;
//     }
// });

function complete_wpml_translation_fully( $translated_product_ids ) {
    // וודא ש-WPML פעיל
    if ( ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
        echo "WPML is not active. Exiting.\n";
        return;
    }

    foreach ( $translated_product_ids as $translated_product_id ) {
        // בדוק אם הפוסט המתורגם קיים
        $translated_post = get_post( $translated_product_id );
        if ( ! $translated_post || $translated_post->post_type !== 'product' ) {
            echo "Skipping invalid or non-product post ID: {$translated_product_id}.\n";
            continue;
        }

        // קבל את מזהה הפוסט המקורי
        $original_post_id = apply_filters( 'wpml_object_id', $translated_product_id, 'product', true );
        if ( ! $original_post_id ) {
            echo "No original product found for translated ID {$translated_product_id}.\n";
            continue;
        }

        // טען את עורך התרגום של WPML
        do_action( 'wpml_pro_translation_completed', $translated_product_id );

        // ודא שהתוכן המתורגם מתעדכן
        $translated_content = apply_filters( 'wpml_translate_single_string', $translated_post->post_content, 'woocommerce', 'product description', apply_filters( 'wpml_current_language', null ) );
        wp_update_post( [
            'ID'           => $translated_product_id,
            'post_content' => $translated_content,
        ] );

        echo "Translation fully completed and updated for Product ID {$translated_product_id}.\n";
    }
}




// קריאה לפונקציה עם מערך של מזהי מוצרים
//$product_ids = [105720]; // הכנס כאן את מזהי המוצרים
//complete_wpml_translation_fully( $product_ids );


// add_filter( 'wpml_sync_post_title', function( $sync, $post_id, $lang ) {
//     $post_type = get_post_type( $post_id );
//     if ( $post_type === 'product' ) {
//         error_log( "Preventing title sync for Product ID: {$post_id}" );
//         return false; // מנע סנכרון שם המוצר
//     }
//     return $sync;
// }, 10, 3 );



function wpml_floating_language_switcher() {
    if ( function_exists( 'icl_get_languages' ) ) {
        $languages = icl_get_languages('skip_missing=0&orderby=code');
        if (!empty($languages)) {
            echo '<div class="floating-lang-switcher">';
            foreach ($languages as $lang) {
                echo '<a href="' . $lang['url'] . '" class="lang-flag" title="' . $lang['native_name'] . '">
                        <img src="' . $lang['country_flag_url'] . '" alt="' . $lang['language_code'] . '">
                      </a>';
            }
            echo '</div>';
        }
    }
}
add_action('wp_footer', 'wpml_floating_language_switcher');


function add_product_query_custom_filter($q) {
    if (is_admin()) {
        return;
    }

    $tax_query = array('relation' => 'AND'); // Ensure all conditions between different filters are met
    $meta_query = array('relation' => 'AND'); // Ensure stock conditions are also enforced

    // **Filter by Product Category (OR within categories, AND with other filters)**
    if (!empty($_REQUEST['product_category'])) {
        $tax_query[] = array(
            'relation' => 'OR', // Multiple selected categories should use OR
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $_REQUEST['product_category'],
                'operator' => 'IN' // Products must belong to one of the selected categories
            )
        );
    }

    // **Filter by Color Attribute (OR within colors, AND with other filters)**
    if (!empty($_REQUEST['product_color'])) {
        $tax_query[] = array(
            'relation' => 'OR', // Multiple selected colors should use OR
            array(
                'taxonomy' => 'pa_color',
                'field'    => 'slug',
                'terms'    => $_REQUEST['product_color'],
                'operator' => 'IN' // Products must match at least one selected color
            )
        );
    }

    // **Filter by Delivery Time (Radio Button - AND condition remains)**
    if (!empty($_REQUEST['product_delivery'])) {
        $delivery_filter = $_REQUEST['product_delivery']; // Now a single value

        if ($delivery_filter == 'instock') {
            $meta_query[] = array(
                'key'     => '_stock',
                'value'   => '0',
                'compare' => '>', // Products with stock greater than 0
                'type' => 'NUMERIC'
            );
        }

        if ($delivery_filter == 'general_stock') {
            $meta_query[] = array(
                'relation' => 'AND',
                array(
                    'key'     => '_stock',
                    'value'   => 0,
                    'compare' => '<=', // Stock must be 0 or less
                    'type' => 'NUMERIC'
                ),
                array(
                    'key'     => 'kare_general_stock',
                    'value'   => '', // Field exists but is empty
                    'compare' => '!=', // Ensure empty values are also excluded
                )
            );
        }

        if ($delivery_filter == 'coming_soon') {
            $meta_query[] = array(
                'relation' => 'AND',
                array(
                    'key'     => '_stock',
                    'value'   => 0,
                    'compare' => '<=', // Stock must be 0 or less
                    'type' => 'NUMERIC'
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'kare_general_stock',
                        'compare' => 'NOT EXISTS', // Field does not exist at all
                    ),
                    array(
                        'key'     => 'kare_general_stock',
                        'value'   => '', // Field exists but is empty
                        'compare' => '=', // Ensure empty values are also excluded
                    ),
                    array( // NEW CONDITION: Check for empty field explicitly
                        'key'     => 'kare_general_stock',
                        'value'   => ' ',
                        'compare' => '=',
                    )
                ),
            );
        }
    }

    // **Apply Tax Query (Category & Color)**
    if (!empty($tax_query) && count($tax_query) > 1) {
        $q->set('tax_query', $tax_query);
    }

    // **Apply Meta Query (Delivery Time)**
    if (!empty($meta_query) && count($meta_query) > 1) {
        $q->set('meta_query', $meta_query);
    }

    // echo "<pre>WooCommerce Query Variables:\n";
    // print_r($q->query_vars);
    // echo "</pre>";
    // exit();

}

add_action('woocommerce_product_query', 'add_product_query_custom_filter'); 







function translate_category_names_with_wpml($categories) {
    if (function_exists('wpml_translate_single_string')) {
        foreach ($categories as &$category) {
            $category['name'] = wpml_translate_single_string($category['name'], 'Categories', $category['name']);
        }
    }
    return $categories;
}

function translate_colors_with_wpml($color_data) {
    if (function_exists('wpml_translate_single_string')) {
        foreach ($color_data as &$color) {
            $color['name'] = wpml_translate_single_string($color['name'], 'Colors', $color['name']);
        }
    }
    return $color_data;
}

function syncCatFilterAndPdtFilter() {
    $categories = get_terms(array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
    ));

    foreach ($categories as $term) {
        $cat_id = $term->term_id;
        $parent_id = $term->parent;
        
        // Get child categories
        $child_categories = get_terms(array(
            'taxonomy'   => 'product_cat',
            'parent'     => $cat_id,
            'hide_empty' => false,
        ));

        $cat_data = array();
        if (!empty($child_categories)) {
            foreach ($child_categories as $child) {
                $cat_data[] = array(
                    'slug' => $child->slug,
                    'name' => $child->name
                );
            }
        }

        // Get products in this category
        $product_ids = get_posts(array(
            'post_type'   => 'product',
            'numberposts' => -1,
            'fields'      => 'ids',
            'tax_query'   => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $cat_id,
                ),
            ),
        ));

        // Get colors for products
        $color_data = array();
        if (!empty($product_ids)) {
            $color_terms = get_terms(array(
                'taxonomy'   => 'pa_color',
                'hide_empty' => false,
                'object_ids' => $product_ids,
            ));

            if (!empty($color_terms) && !is_wp_error($color_terms)) {
                foreach ($color_terms as $color) {
                    $color_data[] = array(
                        'slug' => $color->slug,
                        'name' => $color->name
                    );
                }
            }
        }

        // **Update ACF for Original Category**
        update_field('child_categories_list', $cat_data, 'product_cat_' . $cat_id);
        update_field('product_colors_list', $color_data, 'product_cat_' . $cat_id);

        // **🔹 WPML: Update Translated Categories with Matching Values**
        if (function_exists('wpml_object_id')) {
            global $sitepress;
            $languages = $sitepress->get_active_languages();

            
            $translated_term_id = apply_filters('wpml_object_id', $cat_id, 'product_cat', true, 'en');
            if ($translated_term_id && $translated_term_id != $cat_id) {
                
                $translated_cat_data = array();
                foreach ($cat_data as $child) {
                    $translated_child_id = apply_filters('wpml_object_id', $child['slug'], 'product_cat', true, 'en');
                    if ($translated_child_id) {
                        $translated_term = get_term($translated_child_id, 'product_cat');
                        if ($translated_term) {
                            $translated_cat_data[] = array(
                                'slug' => $translated_term->slug,
                                'name' => $translated_term->name
                            );
                        }
                    }
                }

                $translated_color_data = array();
                foreach ($color_data as $color) {
                    $translated_color_id = apply_filters('wpml_object_id', $color['slug'], 'pa_color', true, 'en');
                    if ($translated_color_id) {
                        $translated_color_term = get_term($translated_color_id, 'pa_color');
                        if ($translated_color_term) {
                            $translated_color_data[] = array(
                                'slug' => $translated_color_term->slug,
                                'name' => $translated_color_term->name
                            );
                        }
                    }
                }

                update_field('child_categories_list', $translated_cat_data, 'product_cat_' . $translated_term_id);
                update_field('product_colors_list', $translated_color_data, 'product_cat_' . $translated_term_id);
            }
            
        }

    }
}

// Hook into a cron job
add_action('sync_filter_cron_hook', 'syncCatFilterAndPdtFilter');











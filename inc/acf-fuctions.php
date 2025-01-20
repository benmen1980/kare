<?php

/**
 * Register Options Page
 */
//add_action('acf/init', 'my_acf_op_init');
function my_acf_op_init() {
    // Check function exists.
    if( function_exists('acf_add_options_page') ) {

        // Add parent.
        $parent = acf_add_options_page(array(
            'page_title'  => __('kare settings', 'kare'),
            'menu_title'  => __('kare settings', 'kare'),
            'menu_slug' => 'acf-options-theme-settings',
            'redirect'    => false,
        ));
    }

}

// Register a custom field in the menu items
add_filter('wp_setup_nav_menu_item', 'add_acf_menu_fields');
function add_acf_menu_fields($menu_item) {
    $menu_item->acf = get_fields($menu_item);
    return $menu_item;
}

// Display the custom field in the menu items
add_filter('walker_nav_menu_start_el', 'display_acf_menu_fields', 10, 4);
function display_acf_menu_fields($item_output, $item, $depth, $args) {
    if (isset($item->acf['menu_icon']) && !empty($item->acf['menu_icon'])) {
        $icon_id = $item->acf['menu_icon']['ID'];
        
        // אם זה שדה תמונה
        $icon = wp_get_attachment_image($icon_id, 'thumbnail', false, array('class' => 'menu-icon-class'));

        // if it's a text field (for example with the HTML code of an icon)
        // $icon = '<span class="menu-icon">' . esc_html($item->acf['menu_icon']) . '</span>';

        $item_output = $icon . $item_output;
    }

    return $item_output;
}

// Filter to fill select field in drum options "faq_tabs"
add_filter('acf/load_field/name=selected_tab', 'populate_faq_tabs');
function populate_faq_tabs($field) {
    $field['choices'] = [];

    if (have_rows('faq_tabs', 'option')) {
        while (have_rows('faq_tabs', 'option')) {
            the_row();
            $tab_slug = get_sub_field('faq_title');
            $tab_title = get_sub_field('faq_title');
            $field['choices'][$tab_slug] = $tab_title;
        }
    }

    return $field;
}

//Adding a search by SKU in the query of "post object" fields
add_filter('acf/fields/post_object/query', 'acf_post_object_search_by_sku_and_status', 10, 3);

function acf_post_object_search_by_sku_and_status($args, $field, $post_id) {
    // Ensure the query includes only published products
    $args['post_status'] = 'publish';

    // Modify the WP_Query arguments for SKU search
    add_filter('posts_where', function($where, $wp_query) {
        global $wpdb;

        // Get the search term from the query
        $search_term = $wp_query->get('s');

        // Add SKU search logic
        if (!empty($search_term)) {
            $where .= $wpdb->prepare(
                " OR ({$wpdb->postmeta}.meta_key = '_sku' AND {$wpdb->postmeta}.meta_value LIKE %s)",
                '%' . $wpdb->esc_like($search_term) . '%'
            );
        }

        return $where;
    }, 10, 2);

    // Join postmeta table for SKU search
    add_filter('posts_join', function($join, $wp_query) {
        global $wpdb;

        if ($wp_query->get('s')) {
            $join .= " LEFT JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id";
        }

        return $join;
    }, 10, 2);

    // Prevent duplicate results
    add_filter('posts_groupby', function($groupby, $wp_query) {
        global $wpdb;

        if ($wp_query->get('s')) {
            $groupby = "{$wpdb->posts}.ID";
        }

        return $groupby;
    }, 10, 2);

    return $args;
}
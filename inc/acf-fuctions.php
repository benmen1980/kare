<?php

/**
 * Register Options Page
 */
add_action('acf/init', 'my_acf_op_init');
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
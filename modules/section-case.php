<?php
if(is_category() || is_product_category()) {
    // var_dump('1');
    $category = get_queried_object();
    $cat_id = $category->term_id;
    $choose_module = get_field('choose_module' ,'category_'. $cat_id .'');
    $post_id = 'category_'. $cat_id;
}
else{
    //var_dump('2');
    $choose_module = get_field('choose_module');
    $post_id = get_the_ID();
} 

if($choose_module): 
    //var_dump('3');
    while(the_repeater_field('choose_module',  $post_id)): 
        $mod = get_sub_field('module_list');
        $mod = explode(':', $mod);
        switch ($mod[0]) {
            case 'module_banner_w_txt':
                get_template_part('modules/section','module_banner_w_txt');
                break;
            case 'module_design_banner':
                get_template_part('modules/section','module_design_banner');
                break;
            case 'module_50_50':
                get_template_part('modules/section','module_50_50');
                break;
            case 'module_slider_pdts':
                get_template_part('modules/section','module_slider_pdts');
                break;
            case 'module_slider_categories':
                get_template_part('modules/section','module_slider_categories');
                break;
            case 'module_50_50_with_pdts':
                get_template_part('modules/section','module_50_50_with_pdts');
                break;
            case 'module_slider_4_cubes':
                get_template_part('modules/section','module_slider_4_cubes');
                break;
            case 'module_banner_promotion':
                get_template_part('modules/section','module_banner_promotion');
                break;
            case 'module_register_newsletter':
                get_template_part('modules/section','module_register_newsletter');
                break;
            case 'module_shop_area':
                get_template_part('modules/section','module_shop_area');
                break;
            case 'module_text_container':
                get_template_part('modules/section','module_text_container');
                break;
            case 'module_banner_celebrity':
                get_template_part('modules/section','module_banner_celebrity');
                break;
            case 'module_banner_celebrity_sayings':
                get_template_part('modules/section','module_banner_celebrity_sayings');
                break;
        }
    endwhile;
endif;
//var_dump('3');
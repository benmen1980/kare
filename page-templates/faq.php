<?php
/*
Template Name: Faq

*/

get_header();

$post_id = get_the_ID();

$is_main_faq_page = false;
if ( wp_get_post_parent_id( get_the_ID() ) == 0 ) {
    $is_main_faq_page = true;
    $main_faq_id = get_the_ID();
} else {
    $main_faq_id = wp_get_post_parent_id($post_id);
}

$main_tabs = get_field('selected_tab', $main_faq_id); 

$array_tabs = get_field('selected_tab', $post_id); 

?>

<div class="faq-page">
    <div class="container-w-gutter">

        <section class="name-page container">	
            <h1 class="w-header-title"><?php echo get_the_title(); ?></h1>
        </section>

        <div class="breadcrumb_page">
            <div class="woocommerce-breadcrumb">
                <?php
                if ( function_exists( 'woocommerce_breadcrumb' ) ) {
                    woocommerce_breadcrumb(array(
                        'delimiter'   => '&nbsp;&bull;&nbsp;',
                        'wrap_before' => '<nav class="w-breadcrumb">',
                        'wrap_after'  => '</nav>',
                        'before'      => '',
                        'after'       => '',
                        'home'        => _x( 'Home Page', 'breadcrumb', 'woocommerce' ),
                    ));
                }
                ?>
            </div>
        </div>

        <?php 
        if ($array_tabs && have_rows('faq_tabs', 'option')) : ?>
            <div class="faq-tabs-menu">
                <a href="<?php echo esc_url(get_permalink($main_faq_id)); ?>" aria-label="link" class="link-menu <?php echo ($is_main_faq_page) ? 'active' : ''; ?>">
                    <span class="text"><?php echo esc_html('FAQ - Help', 'kare'); ?></span>
                </a>

                <?php

                $current_url = get_permalink();

                while (have_rows('faq_tabs', 'option')) : the_row();
                    $tab_title = get_sub_field('faq_title');
                    $tab_url = get_sub_field('faq_page_url');
                    $active_class = ($current_url === $tab_url) ? 'active' : '';

                    if (in_array($tab_title, $main_tabs)) : 
                    ?>
                    
                        <a href="<?php echo esc_url($tab_url); ?>" aria-label="link" class="link-menu <?php echo $active_class; ?>">
                            <span class="text"><?php echo esc_html($tab_title); ?></span>
                        </a>
                    <?php endif;
                endwhile; ?>
            </div>
        <?php endif; ?>

        <div class="faq-page-content">

            <?php if ( get_field('image', $post_id) ) : ?>
                <div class="image-page container">
                    <img calss="img-bg" src="<?php echo esc_url(get_field('image', $post_id)); ?>" alt="image kare" />
                </div>
            <?php endif; ?>

            <?php if (have_rows('faq_tabs', 'option')) : 
                $tabs_to_display = $is_main_faq_page ? get_field('faq_tabs', 'option') : $array_tabs;
                $show_h2 = (count($tabs_to_display) > 1);
                
                while (have_rows('faq_tabs', 'option')) : the_row();
                    $tab_title = get_sub_field('faq_title');

                    if ($is_main_faq_page || in_array($tab_title, $tabs_to_display)) : ?>

                        <div class="faq-items-content">
                            <div class="container">
                                
                                <?php if ($show_h2): ?>
                                    <h2 class="title-text"><?php echo esc_html($tab_title); ?></h2>
                                <?php endif; ?>

                                <div class="faq-items-wrapper">

                                    <?php if (have_rows('faq_items')): ?>
                                        <?php while (have_rows('faq_items')): the_row(); ?>
                                            <div class="accordion-faq-item">
                                                <div class="faq-item-title">
                                                    <button type="button" aria-label="button" class="faq-question">
                                                        <span class="title"><?php echo esc_html(get_sub_field('faq_question')); ?></span>
                                                        <?php echo file_get_contents( get_template_directory_uri() . '/dist/images/svg/arrow-down-circle.svg');?>
                                                    </button>
                                                </div>
                                                <div class="faq-item-content">
                                                    <div class="faq_answer">
                                                        <div class="text">
                                                                <?php echo wp_kses_post(get_sub_field('faq_answer')); ?>
                                                        </div>
                                                        <?php if ( get_sub_field('faq_button_txt') ) : ?>
                                                            <div class="button">
                                                                <a href="<?php echo esc_html(get_sub_field('faq_link')); ?>" aria-label="link" class="link">
                                                                    <span class="text"><?php echo esc_html(get_sub_field('faq_button_txt')); ?></span>
                                                                </a>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        <?php endwhile; 
                                    endif; ?>
                                    
                                </div>
                            </div>
                        </div>
                    <?php endif; 
                endwhile; ?>

            <?php endif; ?>

            <div class="moduls_container">
                <div class="section_modules_wrapper">
                    <?php get_template_part('modules/section','module_banner_promotion'); ?>
                </div>
            </div>
            
        </div>
            
    </div>
</div>

<?php
get_footer();
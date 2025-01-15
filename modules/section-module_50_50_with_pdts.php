<section class="section_wrap module_half_half_with_pdts">
    <?php if(get_sub_field('module_50_50_with_pdts')): 
            $half_with_pdts = get_sub_field('module_50_50_with_pdts');
            $img_position = $half_with_pdts['img_position'];
            $banner_img = $half_with_pdts['half_moudle_pdts_link_img'];
            $title = $half_with_pdts['half_moudle_pdts_title'];
            $text = $half_with_pdts['half_moudle_pdts_text'];
            $link_category = $half_with_pdts['half_moudle_pdts_link'];
            $btn_text = $half_with_pdts['half_moudle_pdts_text_btn'];
            $radio_selected = $half_with_pdts['select_cat_or_pdt'];

            $title_no_p = wp_kses_post($title);
            $title = preg_replace('/<p[^>]*>(.*?)<\/p>/i', '$1', $title_no_p); // Remove <p> tags
    endif; ?>

    <div class="module_50_50_with_pdts module_50_50" style="<?php echo ($img_position == "right") ? 'flex-direction:row-reverse':'flex-direction:row'?>;">
        <div class="w-1-2 first">
            <div class="visible_mobile">
                <!-- <a class="link_content" href="<?php //echo $link_category ?>"> -->
                <h2 class="title_hero_content" style="<?php echo ($img_position == "right") ? 'text-align: var(--dir-right);':'text-align: var(--dir-left);'?>">
                    <?php echo $title ?>
                </h2>
                <!-- </a> -->
            </div>
            <div class="hero_img_background">
                <a href="<?php echo $btn_text; ?>" class="img_block">
                    <img src="<?php echo $banner_img ?>" alt="image">
                </a>
            </div>
        </div>
        <div class="w-1-2 second">
            <div class="hero_content" style= "<?php echo ($img_position == "right") ? 'padding-inline-end: 16px; align-items: flex-end; text-align: var(--dir-right);':'padding-inline-start: 16px; align-items: flex-start; text-align: var(--dir-left);'?>">
                <!-- <a class="link_content" href="<?php //echo $link_category ?>"> -->
                <h2 class="title_hero_content" style= "<?php echo ($img_position == "right") ? 'text-align: var(--dir-right);':'text-align: var(--dir-left);'?>">
                    <?php echo $title ?>
                </h2>
                <!-- </a> -->
                <div class="text_area_content">
                    <?php echo $text ?>
                </div>
                <?php if ( $btn_text ) : ?>
                    <div class="button_hero_content">
                        <button aria-label="link" type="button" class="btn_black_hover">
                            <a href="<?php echo $link_category; ?>" class="header-button "><?php echo $btn_text; ?></a>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="slider_pdts_content" style="max-height: 260px;">

                <?php if($radio_selected == 'select_pdts') :
                    $featured_pdts =  $half_with_pdts['select_pdts_slider'];
                else :
                    $selected_cat =  $half_with_pdts['select_category_slider'];
                    $term = get_term( $selected_cat, 'product_cat' );
                    $slug = $term->slug;

                    $args_cat = array(
                        'post_type' =>  array('product', 'product_variation'),
                        'post_status' => array('publish'),
                        'product_cat' => $slug,
                        'posts_per_page' => 10,
                        'meta_query' => array(
                            array(
                                'key' => '_stock_status',
                                'value' => 'instock',
                                'compare' => '=',
                            ),
                        )
                    );
                    $featured_pdts = get_posts( $args_cat );
                endif;
                if( $featured_pdts ): ?>
                        <div class="tabs_wrapper">
                            <div class="swiper_slide_pdts swiper-container">
                                <div id="module_pdts_content" class="slider_products_content swiper-wrapper">
                                    <?php
                                        foreach( $featured_pdts as $product ):
                                            setup_postdata( $product );
                                            get_template_part('page-templates/box-product'); 
                                            wp_reset_postdata(); 
                                        endforeach;
                                    ?>
                                </div>

                                <!-- arrows -->
                                <div class="swiper-nav swiper-nav-prev swiper-button-disabled">
                                    <button aria-label="vorherige" type="button" class="w-btn w-color w-color-white">
                                        <?php echo str_replace('<svg', '<svg class="w-dir-left"', file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-right-sm.svg')); ?>
                                    </button>
                                </div>
                                <div class="swiper-nav swiper-nav-next">
                                    <button aria-label="button" type="button" class="w-btn w-color w-color-white">
                                        <?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-right-sm.svg'); ?>
                                    </button>
                                </div>
                                <!-- scrollbar -->
                                <div class="swiper-scrollbar"></div>
                            
                            </div>
                            <?php endif; ?>

                        </div>
                </div>

                
            </div>
        </div>
    </div>
    
</section>
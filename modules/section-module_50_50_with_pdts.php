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

<div class="module_50_50_with_pdts module_50_50" style="padding: 20px 0; <?php echo ($img_position == "right") ? 'flex-direction:row-reverse':'flex-direction:row'?>;">
    <div class="w-1-2 first">
        <div class="hero_img_background">
            <a href="<?php echo $btn_text; ?>" class="img_block">
                <img src="<?php echo $banner_img ?>" alt=" image">
            </a>
        </div>
    </div>
    <div class="w-1-2 second">
        <div class="hero_content" style= "<?php echo ($img_position == "right") ? 'padding-right: 1rem; align-items: flex-end;':'padding-left: 1rem; align-items: flex-start;'?>">
            <h2 class="title_hero_content">
                <a class="link_content" href="<?php echo $link_category ?>"><?php echo $title ?></a>
            </h2>
            <div class="text_area_content">
                <?php echo $text ?>
            </div>
            <div class="button_hero_content">
                <button aria-label="link" type="button" class="btn_black_hover">
                    <a href="<?php echo $link_category; ?>" class="header-button "><?php echo $btn_text; ?></a>
                </button>
            </div>

            <div class="slider_pdts_content" style="max-height: 260px;">

            <?php if($radio_selected == 'select_pdts_slider') :
                $featured_pdts =  $half_with_pdts['select_pdts'];
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
                                    <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" class="svg-icon sprite-icons w-dir-left"><use href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-arrow-right-sm" xlink:href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-arrow-right-sm"></use></svg>
                                </button>
                            </div>
                            <div class="swiper-nav swiper-nav-next">
                                <button aria-label="button" type="button" class="w-btn w-color w-color-white">
                                    <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" class="svg-icon sprite-icons"><use href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-arrow-right-sm" xlink:href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-arrow-right-sm"></use></svg>
                                </button>
                            </div>
                           
                        </div>
                        <?php endif; ?>

                    </div>
            </div>

            
        </div>
    </div>
</div>
    
</section>
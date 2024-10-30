<?php if(get_sub_field('slider_pdts')): ?>
<section class="section_wrap section_slider_pdts" style="padding-bottom: 20px;">
    <?php  
        $slider_pdts = get_sub_field('slider_pdts');
        $pdts_name = $slider_pdts['pdts_title'];
        $radio_selected = $slider_pdts['select_cat_or_pdt'];

        $pdts_name_no_p = wp_kses_post($pdts_name);
        $pdts_name = preg_replace('/<p[^>]*>(.*?)<\/p>/i', '$1', $pdts_name_no_p); // Remove <p> tags
        
    endif;?>
    <div class="section_slider_pdts_wrap">
        <?php if (!empty($pdts_name)) : ?>
            <h2><?php echo $pdts_name; ?></h2>
        <?php endif;?>
        <?php if($radio_selected == 'select_pdts') :
		    $featured_pdts =  $slider_pdts['select_pdts'];
        else :
            $selected_cat =  $slider_pdts['select_category'];
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
                <div id="module_slider_pdts" class="slider_products swiper-wrapper">
                    <!-- <pre style="font-size:12px; width: 100%;">
                    <?php 
                        // print_r($featured_pdts); ?>
                    </pre> -->
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
                <!-- dropping points-->
                <div class="swiper-pagination-wrapper">
                    <div class="swiper-pagination swiper-pagination-black swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-bullets-dynamic">
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>

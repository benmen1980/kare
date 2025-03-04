
<?php if(get_sub_field('slider_categories')): ?>
    
<section class="section_wrap section_slider_categories">
    <?php  
        $slider_cat = get_sub_field('slider_categories');
        $cat_name = $slider_cat['cat_title'];
        $btn_type = $slider_cat['select_design'];
        $categories = $slider_cat['select_category'];

        $cat_name_no_p = wp_kses_post($cat_name);
        $cat_name = preg_replace('/<p[^>]*>(.*?)<\/p>/i', '$1', $cat_name_no_p); // Remove <p> tags
        
        ?>
        <div class="section_slider_cat_wrap">
            <?php if(!empty($cat_name)) : ?>
                <div class="section_header">
                    <?php if($btn_type == "simple") : ?>
                        <h2><?php echo $cat_name; ?></h2>
                    <?php else : ?>
                        <h5><?php echo $cat_name; ?></h5>
                    <?php endif; ?>              
                </div>
            <?php endif; ?>
            <div class="tabs_wrapper swiper <?php echo $btn_type; ?>">
                <div class="swiper_slide_cat swiper-container categoty-slider">
                    <div id="<?php echo $btn_type; ?>" class="slider_categories swiper-wrapper">
                        <?php foreach($categories as $key => $cat){ 
                            $cat_name = $cat->name; 
                            $cat_slug = get_term_link($cat->term_id);
                            $cat_img_link = get_field('img_link_cat', 'product_cat_' . $cat->term_id); 
                        ?>   
                        <div class="swiper-slide">
                            <a href="<?php echo $cat_slug; ?>" class="image_btn" title="<?php echo $cat_name;?>" aria-lable="link">
                                <div class="w-card-category-wrapper" >
                                    <img src="<?php echo !empty($cat_img_link) ? $cat_img_link : ''?>" alt="<?php echo $cat_name;?>" width="150" height="150"/>
                                </div>
                                <?php if($btn_type == "simple") : ?>
                                    <h3><?php echo $cat_name;?></h3>
                                <?php else : ?>  
                                    <span><?php echo $cat_name;?></span>  
                                <?php endif;?>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- arrows -->
                    <div class="swiper-nav swiper-nav-prev swiper-button-disabled">
                        <button aria-label="prev" type="button" class="w-btn w-color w-color-white">
                            <?php echo str_replace('<svg', '<svg class="w-dir-left"', file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-right-sm.svg')); ?>
                        </button>
                    </div>
                    <div class="swiper-nav swiper-nav-next">
                        <button aria-label="next" type="button" class="w-btn w-color w-color-white">
                            <?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-right-sm.svg'); ?>
                        </button>
                    </div>
                    <!-- scrollbar -->
                    <div class="swiper-scrollbar"></div>
                    <!-- dropping points-->
                    <div class="swiper-pagination-wrapper">
                        <div class="swiper-pagination swiper-pagination-black swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-bullets-dynamic">
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<?php endif ?>
<?php if(get_sub_field('slider_4_cubes')): ?>
<section class="section_wrap section_slider_4_cubes">
    <?php  
        $slider_cubes = get_sub_field('slider_4_cubes');
        $title = $slider_cubes['main_title'];
        $cubes = $slider_cubes['repeat_image_text'];

        if (is_array($cubes)) {
            $counter_cubes = count($cubes);
        }
    ?>
    <div class="section_slider_cubes_wrap container">
        <?php if (!empty($title)) : ?>
            <h2><?php echo $title; ?></h2>
        <?php endif; ?>
  
        <div class="tabs_wrapper_cubes">
            <div class="swiper_slide_cube swiper-container">
                <div id="module_slide_cube" class="slider_cubes swiper-wrapper">
                    <?php foreach ($cubes as $cube) {
                        $image_link = $cube['image_link'];
                        $text = $cube['text_cube'];
                        $link = $cube['txt_link'];
                        $open_link = $cube['open_link_tab'];       
                    ?>
                    <div class="swiper-slide">
                        <img src="<?php echo $image_link; ?>" alt="<?php echo $text; ?>"/>
                        <h4>
                            <a href="<?php echo $link; ?>" aria-label="link" class="" target="<?php echo $open_link === "no" ? '_self' :'_blank'; ?>"><?php echo $text; ?></a>
                        </h4>
                    </div>
                    <?php }?>
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
<?php
endif; 
?>
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
                    <button aria-label="vorherige" type="button" class="w-btn w-color w-color-white">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" class="svg-icon sprite-icons w-dir-left"><use href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-arrow-right-sm" xlink:href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-arrow-right-sm"></use></svg>
                    </button>
                </div>
                <div class="swiper-nav swiper-nav-next">
                    <button aria-label="button" type="button" class="w-btn w-color w-color-white">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" class="svg-icon sprite-icons"><use href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-arrow-right-sm" xlink:href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-arrow-right-sm"></use></svg>
                    </button>
                </div>
                <!-- dropping points-->
                <div class="swiper-pagination-wrapper">
                    <div class="swiper-pagination swiper-pagination-black swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-bullets-dynamic" style="width: 100px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
endif; 
?>
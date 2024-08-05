<section class="section_wrap design_banner_wrapper">
    <?php if(get_sub_field('design_banner')): 
        $design_banner = get_sub_field('design_banner');
        $banner_img = $design_banner['img_link'];
        $button_link = $design_banner['design_banner_link'];
        $button_txt = $design_banner['design_banner_txt'];
    endif;?>
    <div class="section_image_inner">
        <img src="<?php echo $banner_img; ?>" alt="<?php echo $button_txt; ?>"/>
        <!-- <div> -->
        <button aria-label="" type="button" class="w-btn">
            <a href="<?php echo $button_link; ?>" class="header-button "><?php echo $button_txt; ?></a>
        </button>
        <!-- </div> -->
        
    </div>
</section>
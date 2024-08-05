<section class="section_wrap module_banner_promotion">
    <?php if(get_sub_field('banner_promotion')): 
        $promo_banner = get_sub_field('banner_promotion');
        $design_type = $promo_banner['full_banner'];
        $banner_img = $promo_banner['img'];
        $banner_txt = $promo_banner['title'];
        $banner_sub_txt = $promo_banner['sub_title'];
        $banner_sub_txt = wp_kses_post($banner_sub_txt);
        $banner_sub_txt = preg_replace('/<p[^>]*>(.*?)<\/p>/i', '$1', $banner_sub_txt);
        $button_link = $promo_banner['cat_link'];
        $button_txt = $promo_banner['btn_txt'];
        $banner_bg = $promo_banner['bg_color'];

    endif;?>

    <div class="banner_promotion_wrapper <?php echo ($design_type['value'] == "full_width") ? "full_width" : "container";?>" style="background-color: <?php echo $banner_bg;?>;">
        <?php if ($design_type['value'] == "full_width") : ?>  
            <div class="container">    
        <?php endif; ?>
                <div class="img_wrapper">
                    <img src="<?php echo $banner_img; ?>" alt="<?php echo $banner_txt;?>"/>
                </div>
                <div class="txt_wrapper">
                    <h2>
                        <a href="<?php echo $button_link;?>" title="<?php echo $banner_txt;?>">
                            <?php echo $banner_txt;?>
                        </a>
                    </h2>
                    <p><?php echo $banner_sub_txt; ?></p>
                </div>
                <div class="link_wrapper">
                    <button aria-label="link" type="button" class="btn_black_hover">
                        <a href="<?php echo $button_link; ?>" class="button_promotion "><?php echo $button_txt; ?></a>
                    </button>
                </div>
        <?php if ($design_type['value'] == "full_width") : ?>
            </div>
        <?php endif; ?>
    </div>
</section>

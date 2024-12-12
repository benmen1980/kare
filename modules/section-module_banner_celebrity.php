<section class="section_wrap module_banner_celebrity">
    <?php if(get_sub_field('banner_celebrity')): 
        $celeb_banner = get_sub_field('banner_celebrity');
        $img = $celeb_banner['img_celeb'];
        $title_celeb = $celeb_banner['title_celeb'];
        $desc_seleb = $celeb_banner['more_desc_seleb'];
    endif; ?>
    <div class="module_celebrity_content">
        <div class="container">
            <div class="celeb_img_text">
                <div class="l_side w-1-2">
                    <div class="celeb_image_area">
                        <img src="<?php echo $img; ?>" alt="celebrity"/>
                    </div>
                </div>
                <div class="r_side w-1-2">
                    <h2><?php echo $title_celeb ?></h2>
                    <div class="module_description"><?php echo $desc_seleb; ?></div>
                </div>

            </div>
        </div>
    </div>
</section>
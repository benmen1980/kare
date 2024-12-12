<section class="section_wrap module_shop_area">
    <?php if(get_sub_field('shop_area')):  
        $banner_shop_area = get_sub_field('shop_area');
        $img_position = $banner_shop_area['img_position'];
        $store_img = $banner_shop_area['store_img_link'];
        $store_title = $banner_shop_area['store_title'];
        $store_desc = $banner_shop_area['store_desc'];
    endif; ?>
    <div class="module_shop_content module_50_50" style= "<?php echo ($img_position == "right") ? 'flex-direction:row-reverse':'flex-direction:row'?>;">
        <div class="w-1-2">
            <div class="hero_img_background">
                <!-- <a href="<?php // echo $button_txt; ?>" class="img_block"> -->
                    <img src="<?php echo $store_img ?>" alt="our store image">
                <!-- </a> -->
            </div>
        </div>

        <div class="w-1-2">
            <div class="hero_content" style= "<?php echo ($img_position == "right") ? 'padding-right: 16px; align-items: flex-end; text-align: right;':'padding-left: 16px; align-items: flex-start; text-align: left;'?>"> 
                <h4 class="title_hero_content" style= "<?php echo ($img_position == "right") ? 'text-align: right;':'text-align: left;'?>">
                    <?php echo $store_title; ?>
                </h4> 

                <div class="text_area_content">
                    <?php echo $store_desc ?>
                </div>
            </div>
        </div>

    </div>
</section>
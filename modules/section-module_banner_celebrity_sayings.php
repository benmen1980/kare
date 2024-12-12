<section class="section_wrap module_banner_celebrity_sayings">
    <?php if(get_sub_field('banner_celebrity_sayings')): 
        $celeb_sayings_banner = get_sub_field('banner_celebrity_sayings');
        $text = $celeb_sayings_banner['sayings_text'];
        $img = $celeb_sayings_banner['img_celeb_sayings'];
        $name = $celeb_sayings_banner['celeb_name'];
        $desc = $celeb_sayings_banner['celeb_desc'];
    endif; ?>
    <div class="celebrity_sayings_content">
        <div class="text_celeb">
            <?php echo $text; ?>
        </div>
        <img src="<?php echo $img; ?>" alt="<?php echo $name; ?>"/>
        <h4><?php echo $name ?></h4>
        <p><?php echo $desc ?></p>
    </div>
</section>
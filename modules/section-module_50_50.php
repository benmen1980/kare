<section class="section_wrap module_half_half">
    <?php if(get_sub_field('module_50_50')): while(the_repeater_field('module_50_50')):  
        $img_position = get_sub_field('img_position');
        $banner_img = get_sub_field('half_moudle_link_img');

        if(get_sub_field('repeat_text_button')): while(the_repeater_field('repeat_text_button')):

            $title_txt = get_sub_field('half_moudle_title');
            $title_txt_no_p = wp_kses_post($title_txt);
            $title_txt = preg_replace('/<p[^>]*>(.*?)<\/p>/i', '$1', $title_txt_no_p); // Remove 
            $link = get_sub_field('half_moudle_link');
        endwhile; endif;
    ?>
    <div class="module_50_50_content module_50_50" style= "<?php echo ($img_position == "right") ? 'flex-direction:row-reverse':'flex-direction:row'?>;">
        <div class="w-1-2">
            <div class="visible_mobile">
                <?php //if(!empty($link)) : ?>
                    <!-- <a class="link_content" href="<?php //echo $link ?>">
                        <h2 class="title_hero_content" style= "<?php //echo ($img_position == "right") ? 'text-align: right;':'text-align: left;'?>">
                            <?php //echo $title_txt; ?>
                        </h2>
                    </a> -->
                <?php //else : ?>
                <h2 class="title_hero_content" style="<?php echo ($img_position == "right") ? 'text-align: right;':'text-align: var(--dir-left);'?>">
                    <?php echo $title_txt; ?>
                </h2> 
                <?php //endif; ?>
            </div>
            <div class="hero_img_background">
                <a href="<?php echo (!empty($link)) ? $link : ''; ?>" class="img_block">
                    <img src="<?php echo $banner_img ?>" alt=" image">
                </a>
            </div>
        </div>

        <div class="w-1-2">
            <div class="hero_content" style= "<?php echo ($img_position == "right") ? 'padding-inline-end: 16px; align-items: flex-end; text-align: var(--dir-right);':'padding-inline-start: 16px; align-items: flex-start; text-align: var(--dir-left);'?>"> 
    
            <?php
                //repeat text and button
                if(get_sub_field('repeat_text_button')): while(the_repeater_field('repeat_text_button')):

                $title_txt = get_sub_field('half_moudle_title');
                $text_txt = get_sub_field('half_moudle_text');
                $link = get_sub_field('half_moudle_link');
                $button_txt = get_sub_field('half_moudle_text_btn');

                $title_txt_no_p = wp_kses_post($title_txt);
                $title_txt = preg_replace('/<p[^>]*>(.*?)<\/p>/i', '$1', $title_txt_no_p); // Remove <p> tags
        
                ?>
                 <?php //if(!empty($link)) : ?>
                    <!-- <a class="link_content" href="<?php //echo $link ?>">
                        <h2 class="title_hero_content" style= "<?php// echo ($img_position == "right") ? 'text-align: right;':'text-align: left;'?>">
                            <?php //echo $title_txt; ?>
                        </h2>
                    </a> -->
                <?php //else : ?>
                <h2 class="title_hero_content" style="<?php echo ($img_position == "right") ? 'text-align: var(--dir-right);':'text-align: var(--dir-left);'?>">
                    <?php echo $title_txt; ?>
                </h2> 
                <?php //endif; ?>
                <div class="text_area_content">
                    <?php echo $text_txt ?>
                </div>
                <?php if (!empty($button_txt)) : ?>
                    <div class="button_hero_content">
                        <button aria-label="link" type="button" class="btn_black_hover">
                            <a href="<?php echo $link; ?>" class="header-button "><?php echo $button_txt; ?></a>
                        </button>
                    </div>
                <?php endif; ?>                                 
            <?php 
                endwhile; endif; ?>
            </div>
        </div>

    </div>
    <?php
    endwhile; endif;
    ?>
</section>
<section class="section_wrap module_half_half">
    <?php if(get_sub_field('module_50_50')): while(the_repeater_field('module_50_50')):  
        $img_position = get_sub_field('img_position');
        $banner_img = get_sub_field('half_moudle_link_img');
    ?>
    <div class="module_50_50_content module_50_50" style= "padding-top: 30px; <?php echo ($img_position == "right") ? 'flex-direction:row-reverse':'flex-direction:row'?>;">
        <div class="w-1-2">
            <div class="hero_img_background">
                <a href="<?php echo $button_txt; ?>" class="img_block">
                    <img src="<?php echo $banner_img ?>" alt=" image">
                </a>
            </div>
        </div>

        <div class="w-1-2">
            <div class="hero_content" style= "<?php echo ($img_position == "right") ? 'padding-right: 1rem; align-items: flex-end; text-align: right;':'padding-left: 1rem; align-items: flex-start; text-align: left;'?>"> 
    
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
                    <h2 class="title_hero_content">
                        <?php if(!empty($link)) : ?>
                            <a class="link_content" href="<?php echo $link ?>"><?php echo $title_txt ?></a>
                        <?php else : 
                            echo $title_txt;
                        endif; ?>
                    </h2>
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
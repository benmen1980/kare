<section class="section_wrap module_accordion">
    <?php if(get_sub_field('accordion')): while(the_repeater_field('accordion')): 
        $title = get_sub_field('accordion_question');
        $select_field = get_sub_field('selecting_field_type');
        $text_answer = get_sub_field('text'); ?>

        <div class="module_accordion_content">
            <div class="accordion_item">
                <button type="button" aria-label="button" class="accordion_question">
                    <span class="accordion_title"><?php echo esc_html($title); ?></span>
                    <?php echo file_get_contents( get_template_directory_uri() . '/dist/images/svg/arrow-down.svg');?>
                </button>
            </div class="accordion_content">
                <?php if($select_field == 'text') : 
                    $align_text = $text_answer['text_align'];
                    $answer = $text_answer['text_answer'];
                    $txt_content_answer = $text_answer['text_area'];
                    $btn = $text_answer['button_txt'];
                    $link = $text_answer['button_url'];
                    ?>
                    <div class="text_information container">
                        <h3 class="title_ans"><?php echo esc_html($answer); ?></h3>
                        <?php echo esc_html($txt_content_answer); ?>
                        <?php if(!empty($btn)) :?>
                            <div class="button_hero_content">
                                <button aria-label="link" type="button" class="btn_white_hover">
                                    <a href="<?php echo $link; ?>" class="link_accordion"><?php echo $btn; ?></a>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php elseif($select_field == 'repeater_information') :
                    //repeat answer pdt information
                    if(get_sub_field('repeater_information')): while(the_repeater_field('repeater_information')):
                        $title_txt = get_sub_field('title_information');
                        $text_txt = get_sub_field('pdt_information');

                        if(get_sub_field('repeater_information')): while(the_repeater_field('repeater_information')):
                            $title_txt = get_sub_field('pdt_information_title');
                            $title = explode(':', $title_txt);
                            switch ($title[0]) {
                                case 'item_number':
                                    $txt_answer = get_sub_field('item_number');
                                    break;
                                case 'material_details':
                                    $txt_answer = get_sub_field('material_details');
                                    break;
                                case 'width':
                                    $txt_answer = get_sub_field('width');
                                    break;
                                case 'depth':
                                    $txt_answer = get_sub_field('depth');
                                    break;
                                case 'height':
                                    $txt_answer = get_sub_field('height');
                                    break;
                                case 'weight':
                                    $txt_answer = get_sub_field('weight');
                                    break;
                                case 'color':
                                    $txt_answer = get_sub_field('color');
                                    break;
                                    get_template_part('modules/section','module_accordion');
                                    break;
                            }
                        endwhile; endif;
                    endwhile; endif;?>


            <?php else : ?>
             <div class="text_information container"> </div>
           <?php endif; ?>
            <div>

            </div>
        </div>
    <?php
    endwhile; endif;?>
<p>hello</p>
</section>

<?php if( have_rows('accordion') ): ?>
    <div class="accordion">
        <?php while( have_rows('accordion') ): the_row(); 
            $title = get_sub_field('title');
            $question = get_sub_field('question');
            $answer = get_sub_field('answer');
        ?>
            <div class="accordion-item">
                <div class="accordion-title"><?php echo esc_html($title); ?></div>
                <div class="accordion-content">
                    <div class="accordion-question"><?php echo esc_html($question); ?></div>
                    <div class="accordion-answer"><?php echo wp_kses_post($answer); ?></div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

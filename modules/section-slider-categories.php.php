
<?php if(get_sub_field('slider_categories')): 
    $item_counter = count(get_sub_field('slider_categories'));?>
    <section class="section_wrap section_slider_categories <?php echo ($item_counter > 1) ? 'multiple_cat_tab' : '' ?>">
        <?php  
            echo 'hello';
            $cat_name = get_sub_field('cat_title');
            $btn_type = get_sub_field('select_design');
            $categories = get_sub_field('select_category');
            echo  $cat_name . ' ' .  $btn_type . ' ' . $categories;
            ?>
            <div class="section_slider_cat_wrap <?php echo ($item_counter > 1) ? 'half-width' : '' ?>">
                <?php if(!empty($cat_name)): ?>
                    <div class="section_header">
                        <h3><?php echo $cat_name; ?></h3>
                    </div>
                <?php endif;?>
                <div class="tabs_wrapper <?php echo $btn_type; ?>">
                    <div class="btn_holder">
                        <?php foreach($categories as $key => $cat){
                            $cat_name = $cat->name; 
                            $cat_slug = get_term_link($cat->term_id);
                        ?>   
                            <a href="<?php echo $cat_slug; ?>" class="rounded_btn" title="<?php echo $cat_name;?>">
                                <?php echo $cat_name;?>
                                <?php if(($btn_type == 'simple') && ($key !== array_key_last($categories)) ): 
                                    echo ',';
                                endif;?>
                            </a>
                        <?php }?>
                    </div>
                </div>
                
            </div>
    </section>
<?php endif ?>
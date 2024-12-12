<section class="section_wrap module_text_container">
    <?php if(get_sub_field('text_container')): 
        $text_banner = get_sub_field('text_container');
        $color_bg = $text_banner['color_bg'];
        $title = $text_banner['title'];
        $more_description = $text_banner['more_text'];
    endif; ?>
    <div class="module_text_content">
        <div class="background container" style="background-color: <?php echo $color_bg; ?>;">
            <h2><?php echo $title;?></h2>
            <div class="module_description">
                <?php echo $more_description;?>
            </div>
        </div>
    </div>
</section>
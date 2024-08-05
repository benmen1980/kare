<section class="section_wrap banner_register_newsletter">
    <div class="container register_newsletter_wrapper">
        <div class="newsletter_content">
            <h2><?php the_field('newsletter_heading','option'); ?></h2>
            <ul>
                <?php if( have_rows('newsletter_list_items','option') ): ?>
                    <?php while( have_rows('newsletter_list_items','option') ): the_row(); ?>
                        <li><?php the_sub_field('list_item'); ?></li>
                    <?php endwhile; ?>
                <?php endif; ?>
            </ul>
        </div>
        <div class="newsletter_form">
            <?php 
            $cf7_form_id = get_field('select_cf7_form','option'); // Get the selected form ID from ACF
            if( $cf7_form_id ) {
                echo do_shortcode('[contact-form-7 id="' . $cf7_form_id . '"]');
            }
            ?>
        </div>
    </div>
</section>
        
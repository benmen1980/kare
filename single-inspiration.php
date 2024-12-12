<?php
get_header();
?>

<main id="primary" class="site-main">
    <?php
    while ( have_posts() ) :
        the_post();
        
        get_template_part('template-parts/content', 'inspiration');
        
    endwhile;
    ?>
</main><!-- #main -->

<?php
get_footer();

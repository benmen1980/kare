<?php
/*
Template Name: Contact-us

*/

get_header();
?>

<div class="contact_page">
    <div class="container_w_gutter">
        <setion class="top-txt-img-contact">	
			<img calss="img-link-bg" src="<?php echo $img_cat_bg; ?>" alt="<?php echo woocommerce_page_title();?>"/>
			<h1 class="w-header-title"><?php woocommerce_page_title(); ?></h1>
		</section>
        <div class="top_txt_contact">
            <h1><?php echo get_field('title'); ?></h1>
            <div class="top_txt_desc">
                <?php echo get_field('description') ?>
            </div>
        </div>
        <div class="contact_form_wrapper">
            <?php echo do_shortcode('[contact-form-7 id="845" title="טופס יצירת קשר"]'); ?>
        </div>
    </div>

</div>

<div class="moduls_container">
    <div class="section_modules_wrapper">
        <?php get_template_part('modules/section','module_register_newsletter'); ?>
        <?php // get_template_part('modules/section','case'); ?>
    </div>
</div>

<?php
get_footer();
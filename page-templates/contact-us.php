<?php
/*
Template Name: Contact

*/

get_header();
?>

<div class="contact-page">
    <div class="container-w-gutter">
        <section class="top-txt-img-contact">	
			<img calss="img-link-bg" src="<?php echo get_field('img_link'); ?>" alt="The store photos"/>
			<h1 class="w-header-title"><?php echo get_field('title_header'); ?></h1>
		</section>

        <section class="breadcrumb_page">
            <div class="woocommerce-breadcrumb">
                <?php
                if ( function_exists( 'woocommerce_breadcrumb' ) ) {
                    woocommerce_breadcrumb(array(
                        'delimiter'   => '&nbsp;&bull;&nbsp;',
                        'wrap_before' => '<nav class="w-breadcrumb">',
                        'wrap_after'  => '</nav>',
                        'before'      => '',
                        'after'       => '',
                        'home'        => _x( 'Home Page', 'breadcrumb', 'woocommerce' ),
                    ));
                }
                ?>
            </div>
        </section>

        <section class="description-contact container">
            <h3 class="brand-store-title">
                <?php
                echo sprintf( 
                    __( 'Discover <b>%s</b>', 'kare' ), 
                    __( 'KARE Brand Stores', 'kare' ) 
                );
                ?>
            </h3>
            <div class="store-details-wrapper">
                <div class="l-side">
                    <h2 class="title-txt"><?php echo get_field('title_txt'); ?></h2>
                    <div class="txt-store-desc">
                        <?php echo get_field('store_desc') ?>
                    </div>
                </div>
                <div class="r-side">
                    <div class="container-border">
                        <img src="<?php echo get_template_directory_uri();?>/dist/images/logo-kare.png" alt="KARE - Buy KARE furniture" width="169" height="30" class="text-red">

                        <div class="store-details">
                            <?php echo get_field('description') ?>
                        </div>

                        <div class="branch-map-wrapper">
                            <?php echo get_field('branch_map_embed_code'); ?>
                        </div>
                    </div>
                </div>
            </div>

		</section>

    </div>
</div>

<?php
get_footer();
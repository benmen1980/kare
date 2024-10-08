<?php
/*
Template Name: Service

*/

get_header();
?>

<div class="service-page">
    <div class="container-w-gutter">
        <section class="name-page">	
			<h1 class="w-header-title"><?php echo get_the_title(); ?></h1>
		</section>
    </div>
    
    <div class="breadcrumb_page">
        <div class="woocommerce-breadcrumb">
            <?php
            if ( function_exists( 'woocommerce_breadcrumb' ) ) {
                woocommerce_breadcrumb(array(
                    'delimiter'   => '&nbsp;&bull;&nbsp;',
                    'wrap_before' => '<nav class="w-breadcrumb">',
                    'wrap_after'  => '</nav>',
                    'before'      => '',
                    'after'       => '',
                    'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
                ));
            }
            ?>
        </div>
    </div>

    <div class="service-page-content">
        
        <section class="service-container title-description-service container">
            <h2 class="title-service"><?php esc_html_e( 'KARE CUSTOMER SERVICE', 'kare' ); ?></h2>
            <div class="module-description"><?php echo get_field('description_service'); ?></div>
        </section>
        
        <section class="cube-wrapper container">
            <div class="service-wrapper">
                <?php 
                if(get_field('add_area_service')): while(the_repeater_field('add_area_service')):  
                    $img = get_sub_field('icon_img');
                    $title = get_sub_field('title_txt');
                    $description = get_sub_field('short_desc');
                    $link_txt = get_sub_field('link_txt');
                    $link = get_sub_field('link');
                ?>
                    <div class="service-cube-wrapper">
                        <img class="service-img icon-cube" src="<?php echo $img;?>" alt="<?php echo $title;?> image"/>
                        <h4 class="title-cube"><?php echo $title ?></h4>
                        <p class="desc-cube"><?php echo $description ?></p>
                        <a aria-label="link" class="link_content" href="<?php echo $link ?>" target="_blank">
                            <?php echo $link_txt ?>
                        </a>
                    </div>
                <?php 
                endwhile; endif;
                ?>
            </div>
        </section>

        <section class="service-container title-description-benefits container">
            <h2 class="title-benefits"><?php esc_html_e( 'KARE BENEFITS', 'kare' ); ?></h2>
            <div class="module-description"><?php echo get_field('description_benefits'); ?></div>
        </section>

        <section class="cube-wrapper container">
            <div class="benefits-wrapper">
                <?php 
                if(get_field('add_area_benefits')): while(the_repeater_field('add_area_benefits')):  
                    $img = get_sub_field('icon_img');
                    $title = get_sub_field('title_txt');
                    $description = get_sub_field('short_desc');
                ?>
                    <div class="benefits-cube-wrapper">
                        <img class="benefits-img icon-cube" src="<?php echo $img;?>" alt="<?php echo $title;?> image"/>
                        <h4 class="title-cube"><?php echo $title ?></h4>
                        <p class="desc-cube"><?php echo $description ?></p>
                    </div>
                <?php 
                endwhile; endif;
                ?>
            </div>
        </section>

        <section class="service-container return-wrapper">
            <div class="bg-return">
                <div class="container">
                    <div class="return-img-text">
                        <div class="l-side w-1-2">
                            <div class="image-retaurn-area">
                                <img src="<?php echo get_field('img_return'); ?>" alt="<?php echo get_field('title_return'); ?>"/>
                            </div>
                        </div>
                        <div class="r-side w-1-2">
                            <h2 class="title-return"><?php echo get_field('title_return'); ?></h2>
                            <div class="module-description"><?php echo get_field('description_return'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    
</div>

<?php
get_footer();
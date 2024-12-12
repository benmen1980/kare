<?php 
    $img_bg = get_field('trend_img',  get_the_ID() );
    $array_pdts = get_field('select_pdts',  get_the_ID() );
    // var_dump($array_pdts);

    if ( ! empty( $array_pdts ) && is_array( $array_pdts ) ) :
        $per_page = get_option('posts_per_page');
        $current_page = max(1, get_query_var('paged', 1));
        $total_products = count($array_pdts);
        $total_pages = ceil($total_products / $per_page);
    endif;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header <?php echo empty($img_cat_bg) ? 'empty' : '';?>">
        <?php if (!empty($img_bg)) : ?>
            <section class="section-image-text">	
                <img calss="img_bg" src="<?php echo $img_bg; ?>" alt="<?php echo the_title();?>"/>
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </section>
        <?php else : ?>
            <section class="section-text">
                <h1 class="entry-title only-text"><?php the_title(); ?></h1>
            </section>
        <?php endif; ?>
    </header>

    <section class="section_wrap breadcrumb_section">
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

    <section class="entry-content">
        <div class="moduls_container">
            <div class="section_modules_wrapper">
                <?php get_template_part('modules/section','case'); ?>
            </div>
        </div>

        <?php 
        $title_area = get_field('title_ques_ans_area',  get_the_ID() );

        if ( have_rows('add_ques_ans', get_the_ID() )) : ?>
            <div class="questions-container">
                <div class="container">
                    <?php
                    if ($title_area): ?>
                        <h2 class="title-text"><?php echo esc_html($title_area); ?></h2>
                    <?php endif;
                    while ( have_rows('add_ques_ans', get_the_ID()) ) : the_row(); ?>
                        <div class="accordion-faq-item">
                            <div class="faq-item-title">
                                <button type="button" aria-label="button" class="faq-question">
                                    <span class="title"><?php echo esc_html(get_sub_field('question_ins')); ?></span>
                                    <?php echo file_get_contents( get_template_directory_uri() . '/dist/images/svg/arrow-down-circle.svg');?>
                                </button>
                            </div>
                            <div class="faq-item-content">
                                <div class="faq_answer">
                                    <div class="text">
                                            <?php echo wp_kses_post(get_sub_field('answer_ins')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>

    </section>

    <?php if ( ! empty( $array_pdts ) && is_array( $array_pdts ) ) : ?>
        <section class="container-archive-product show-archive-product container">

            <div class="product_sorting_wrapper">
                <div class="hidden"></div>
                <div class="product-count">
                    <span class="number-pdts">
                        <?php echo sprintf(
                                    esc_html__('%s Products', 'kare'),
                                    $total_products
                                ); 
                        ?>
                    </span>
                    <span> | </span>
                    <span class="number-page">
                        <?php echo sprintf(
                                    esc_html__('%s of %s', 'kare'),
                                    $current_page,
                                    $total_pages
                                ); 
                        ?>
                    </span>
                </div>
                <div class="sorting-options">
                </div>
            </div>

            <div class="archive-product">
                <?php
                if ( ! empty( $array_pdts ) && is_array( $array_pdts ) ) :
                    foreach( $array_pdts as $product) :
                        setup_postdata( $product );
                        // Pass a custom variable to the template
                        set_query_var('is_archive_product_inspiration', false);
                        get_template_part('page-templates/box-product'); 
                        wp_reset_postdata(); 
                    endforeach;
                else :
                    echo '<p>No products found.</p>';
                endif;
                ?>
            </div>

            <!-- load more pages of same category -->
            <?php if ($total_pages > 1) : ?>
                <div class="load_more_pdts_wrapper">

                    <!-- Previous Button -->
                    <?php if ($current_page > 1): ?>
                        <a href="<?php echo get_pagenum_link($current_page - 1); ?>" class="pagination-prev btn_white_hover">
                            <?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-right-md.svg'); ?>
                            <span class="prev"><?php echo esc_html_e('Previous', 'kare'); ?></span>
                        </a>
                    <?php endif; ?>
                        
                    <!-- Dropdown Pagination -->
                    <div class="pagination-dropdown">
                        <button class="pagination-current">
                            <span><?php echo $current_page . ' of ' . $total_pages; ?></span>
                            <?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-down.svg'); ?>
                        </button>
                        <ul class="pagination-options">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li><a href="<?php echo get_pagenum_link($i); ?>" <?php echo ($i === $current_page) ? 'class="active"' : ''; ?>>
                                    <?php echo $i; ?>
                                </a></li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                        
                    <!-- Next Button -->
                    <?php if ($current_page < $total_pages): ?>
                        <a href="<?php echo get_pagenum_link($current_page + 1); ?>" class="pagination-next btn_white_hover">
                            <span class="next"><?php echo esc_html_e('Next', 'kare'); ?></span>
                            <?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-right-md.svg'); ?>
                        </a>
                    <?php endif; ?>
                        
                </div>	
            <?php endif; ?>

        </section>
    <?php endif; ?>
</article>
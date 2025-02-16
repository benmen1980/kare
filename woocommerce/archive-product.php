<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

// Get the current category object
$current_category = get_queried_object();

if ( $current_category ) {
	$taxonomy = $current_category->taxonomy;
	$current_term_name = $current_category->name;
	$category_id = $current_category->term_id;
}
else {
    $taxonomy = '';
    $current_term_name = '';
    $category_id = 0;
}

$img_cat_bg = get_field('img_link_bg', 'product_cat_' . $category_id);

$choose_module = get_field('choose_module', 'category_'. $category_id .'');
$post_id = 'category_'. $category_id;


$parent_category_id = $category_id;
$child_categories = get_terms( array(
    'taxonomy' => 'product_cat',
    'parent' => $parent_category_id,
    'hide_empty' => false, // can be set to true if you want to hide empty
) );

global $wp_query;

$total_products = $wp_query->found_posts;
$per_page = $wp_query->get('posts_per_page');
$current_page = max(1, $wp_query->get('paged'));
$total_pages = $wp_query->max_num_pages;

?>
<header class="woocommerce-products-header<?php echo empty($img_cat_bg) ? ' empty' : '';?>">
	<?php if (!empty($img_cat_bg)) : ?>
		<section class="section-image-text">	
			<img calss="img_bg" src="<?php echo $img_cat_bg; ?>" alt="<?php echo woocommerce_page_title();?>"/>
			<h1 class="woocommerce-products-header__title page-title text_image_background"><?php woocommerce_page_title(); ?></h1>
		</section>
	<?php else : ?>
		<section class="section-text">
			<h1 class="woocommerce-products-header__title page-title only_text"><?php woocommerce_page_title(); ?></h1>
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

<?php if ( !empty($child_categories) && ! is_wp_error( $child_categories ) ) : ?>
	<section class="section_wrap slider_cat_section">
		<div class="slider_cat container">
			<h5><?php echo sprintf( __( 'Categories in "%s"', 'kare' ), $current_term_name ); ?></h5>
			<div class="section_modules_wrapper">
				<div class="tabs_wrapper swiper rounded_btn">
					<div class="swiper_slide_cat swiper-container categoty-slider">
						<div id="rounded_btn" class="slider_categories swiper-wrapper">
							<?php foreach ($child_categories as $child_category) :
								$cat_name = esc_html($child_category->name); 
								$cat_link = esc_url( get_term_link($child_category->term_id ));
								$cat_img_link = get_field('img_link_cat', 'product_cat_' . $child_category->term_id); 
							?>   
							<div class="swiper-slide">
								<a href="<?php echo $cat_link; ?>" class="image_btn" title="<?php echo $cat_name;?>" aria-lable="link">
									<div class="w-card-category-wrapper" >
										<img src="<?php echo !empty($cat_img_link) ? $cat_img_link : ''?>" alt="<?php echo $cat_name;?>" width="150" height="150"/>
									</div>
									<span><?php echo $cat_name;?></span>  
								</a>
							</div>
							<?php endforeach; ?>
						</div>
						<!-- arrows -->
						<div class="swiper-nav swiper-nav-prev swiper-button-disabled">
							<button aria-label="prev" type="button" class="w-btn w-color w-color-white">
								<?php echo str_replace('<svg', '<svg class="w-dir-left"', file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-right-sm.svg')); ?>
							</button>
						</div>
						<div class="swiper-nav swiper-nav-next">
							<button aria-label="next" type="button" class="w-btn w-color w-color-white">
								<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-right-sm.svg'); ?>
							</button>
						</div>
						<!-- scrollbar -->
						<div class="swiper-scrollbar"></div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php
endif;
?>

<div class="container container-archive-product">
	<!-- <div class="filtering_wrapper">
		<button class="btn_filter_pdts">
			<span class="filter_name">delivery time</span>
			<svg></svg>
		</button>
		<button class="btn_filter_pdts">
			<span class="filter_name">Filter categories</span>
			<svg></svg>
		</button>
		<button class="btn_filter_pdts">
			<span class="filter_name">material</span>
			<svg></svg>
		</button>
		<button class="btn_filter_pdts">
			<span class="filter_name">Color</span>
			<svg></svg>
		</button>
		<button class="btn_filter_pdts">
			<span class="filter_name">All filters</span>
			<svg></svg>
		</button>
	</div> -->
	<div class="ambiance_filter">
		<button type="button" class="btn_active" id="pdt_img">
			<?php echo esc_html__('Product', 'kare') ?>
		</button>
		<button type="button" id="ambiance_img">
			<?php echo esc_html__('Ambiance', 'kare') ?>
		</button>
	</div>
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
			<?php woocommerce_catalog_ordering(); ?>
		</div>
	</div>
	<div class="child_category_wrapper" id="<?php echo 'term-'.$category_id;?>">	
		<?php
			if ( woocommerce_product_loop() ) {
				
				/**
				* Hook: woocommerce_before_shop_loop.
				*
				* @hooked woocommerce_output_all_notices - 10
				* @hooked woocommerce_result_count - 20
				* @hooked woocommerce_catalog_ordering - 30
				*/
				do_action( 'woocommerce_before_shop_loop' );

				woocommerce_product_loop_start();

				if ( wc_get_loop_prop( 'total' ) ) {
					$product_count = 0; 

					while ( have_posts() ) {
						the_post();

						/**
						* Hook: woocommerce_shop_loop.
						*/
						do_action( 'woocommerce_shop_loop' );

						get_template_part('page-templates/box-product');

						$product_count++;

						if ( $product_count == 3 ) {
							?>
							
							<?php
								if($choose_module): ?>
									<div class="section_modules_wrapper kkk">
										<?php while(the_repeater_field('choose_module',  $post_id)): 
											$mod = get_sub_field('module_list');
											$mod = explode(':', $mod);
											switch ($mod[0]) {
												case 'module_banner_promotion':
													get_template_part('modules/section','module_banner_promotion');
													break;
											}
											// break;
										endwhile; ?>
									</div>
								<?php endif; ?>
							
							<?php
						}

					}
				}

				woocommerce_product_loop_end();

				/**
				* Hook: woocommerce_after_shop_loop.
				*
				* @hooked woocommerce_pagination - 10
				*/
				do_action( 'woocommerce_after_shop_loop' );


			} else {
				/**
				* Hook: woocommerce_no_products_found.
				*
				* @hooked wc_no_products_found - 10
				*/
				do_action( 'woocommerce_no_products_found' );
			}
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

	<div class="module_half_wrapper">
	<?php
		if($choose_module): 
			while(the_repeater_field('choose_module',  $post_id)): 
				$mod = get_sub_field('module_list');
				$mod = explode(':', $mod);
				switch ($mod[0]) {
					case 'module_50_50':
						get_template_part('modules/section','module_50_50');
						break;
				}
			endwhile;
		endif;
	?>
	</div>
</div>

<?php

get_footer( 'shop' );
?>
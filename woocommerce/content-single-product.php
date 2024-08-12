<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

 defined( 'ABSPATH' ) || exit;

 global $product;
 
 /**
  * Hook: woocommerce_before_single_product.
  *
  * @hooked woocommerce_output_all_notices - 10
  */
 do_action( 'woocommerce_before_single_product' ); 
		
if ( function_exists( 'woocommerce_breadcrumb' ) ) {
	woocommerce_breadcrumb(array(
		'delimiter'   => '&nbsp;&bull;&nbsp;',
		'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">',
		'wrap_after'  => '</nav>',
		'before'      => '',
		'after'       => '',
		'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
	));
}

 if ( post_password_required() ) {
	 echo get_the_password_form(); // WPCS: XSS ok.
	 return;
 }

 $attachment_ids = $product->get_gallery_image_ids();

 ?>
 <div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
	<section class="top_details container">
		<div class="l_side">
			<div class="product-gallery">
				<div class="slider_gallery_wrapper">
					<div class="gallery-thumbnail-slider">
						<div class="gallery-thumbnail" id="main_slider_1">
							<!-- <a href="<?php// echo wp_get_attachment_url( $product->get_image_id() );?>"> -->
							<!-- <a href="<?php //echo get_permalink( $product->ID ).'#zoom_1'?>"> -->
								<img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" data-slide="1" alt="<?php echo $product->get_title();?>">
							<!-- </a> -->
						</div> 
					</div>
					
				</div>
				<?php if($product->get_image_id() != ''): ?>
					<div class="small_img_slider_wrapper tabs_wrapper">
						<div class="swiper_img_slider swiper-container">
							<div id="slider_img_gallery_small" class="img_gallery_small swiper-wrapper">
								<div class="swiper-slide">
									<div class="product_zoom_thumbnail_item">
										<!-- <a href="<?php// echo get_permalink( $product->ID ).'#zoom_1';?>" data_main_slider="main_slider_1" class="small_img_active"> -->
											<img class="thumbnail_img" src="<?php echo wp_get_attachment_url($product->get_image_id())?>"  alt="<?php echo $product->get_title();?>">
										<!-- </a>            		 -->
									</div> 
								</div> 
								<?php
								$key_thumb = 2;
								foreach ($attachment_ids as $image_id){ 
									$attach_url = wp_get_attachment_url( $image_id);
									//dont display thumb image
									if(strpos( $attach_url, 'thumb-fv-1') == false){?>
										<div class="swiper-slide">
											<div class="product_zoom_thumbnail_item">
												<!-- <a href="<?php //echo get_permalink( $product->ID ).'#zoom_'.$key_thumb;?>" data_main_slider="main_slider_<?php echo $key_thumb;?>"> -->
													<img class="thumbnail_img" src="<?php echo  wp_get_attachment_url( $image_id ); ?>" alt="<?php echo $product->get_title();?>">
												<!-- </a> -->
											</div>
										</div>
									<?php $key_thumb++;
									}
								} ?> 
							</div>

							<!-- arrows -->
							<div class="swiper-nav swiper-nav-prev swiper-button-disabled">
								<button aria-label="vorherige" type="button" class="w-btn w-color w-color-white">
									<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" class="svg-icon sprite-icons w-dir-left"><use href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-arrow-right-sm" xlink:href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-arrow-right-sm"></use></svg>
								</button>
							</div>
							<div class="swiper-nav swiper-nav-next">
								<button aria-label="button" type="button" class="w-btn w-color w-color-white">
									<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" class="svg-icon sprite-icons"><use href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-arrow-right-sm" xlink:href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-arrow-right-sm"></use></svg>
								</button>
							</div>
						</div>
					</div>
				<?php endif;?>
				<?php
				/**
				 * Hook: woocommerce_before_single_product_summary.
				*
				* @hooked woocommerce_show_product_sale_flash - 10
				* @hooked woocommerce_show_product_images - 20
				*/
				do_action( 'woocommerce_before_single_product_summary' );
				?>
			</div>
		</div>
		
		<div class="r_side">
			<div class="summary entry-summary product-details">
				<p class="product-sku"><?php echo 'Item no.:' . $product->get_sku(); ?></p>

				<?php
				/**
				 * Hook: woocommerce_single_product_summary.
				*
				* @hooked woocommerce_template_single_title - 5
				* @hooked woocommerce_template_single_rating - 10
				* @hooked woocommerce_template_single_price - 10
				* @hooked woocommerce_template_single_excerpt - 20
				* @hooked woocommerce_template_single_add_to_cart - 30
				* @hooked woocommerce_template_single_meta - 40
				* @hooked woocommerce_template_single_sharing - 50
				* @hooked WC_Structured_Data::generate_product_data() - 60
				*/
				//  do_action( 'woocommerce_single_product_summary' );

				woocommerce_template_single_title();

				//Custom Tag Below Title 

				$terms = get_the_terms( $product->get_id(), 'product_tag' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					?>
					<div class="wc_tags">
						<?php foreach ( $terms as $term ) { ?>
							<div class="text_tag"> <?php echo $term->name; ?></div>
						<?php }?>
					</div>
				<?php }

				woocommerce_template_single_rating();
				woocommerce_template_single_price(); 

				if ($product->get_stock_quantity() > 0){
					$stock = 'Immediately available';
				}

				if ($product->get_stock_quantity() <= 0){ 
					$stock = $product->get_stock_quantity() . ' weeks';
				}
				
				?>
				<div class="stock_shipping_availability">
					<p class="product-shipping ?>">Shipping in: </p>
					<p class="<?php echo ($product->get_stock_quantity() > 0) ? 'stock' : ''; ?>"><?php echo ' ' . $stock; ?></p>
				</div>
				<?php

				woocommerce_template_single_add_to_cart();
				
				woocommerce_template_single_sharing();
				?>
				<div class="about_product">
					<!-- List of qualities in the product -->
					<div class="list_qualities_pdts">
						<?php
						if( have_rows( 'list_item_product', $product->get_id() ) ):
							while( have_rows( 'list_item_product', $product->get_id() ) ): the_row();
							// Get sub field value.
							$list_item = get_sub_field('list_item',);
							?>

							<div class="item_pdt">
								<?php echo file_get_contents( get_template_directory_uri() . '/dist/images/svg/check.svg');?>
								<p class="text_item"><?php echo $list_item;?></p>
							</div>

							<?php					
							// End loop.
							endwhile;
						endif;
						?>
					</div>

					<h5 class="short_discreption_title">At a glance:</h5>
					<?php
					
					// add short description
					woocommerce_template_single_excerpt();

					?>
				</div>
			</div>
			<?php
			/**
			 * Hook: woocommerce_after_single_product_summary.
			*
			* @hooked woocommerce_output_product_data_tabs - 10
			* @hooked woocommerce_upsell_display - 15
			* @hooked woocommerce_output_related_products - 20
			*/
			// do_action( 'woocommerce_after_single_product_summary' );
			?>
		</div>
	</section>
	<section class="accordion_wrapper">
		<h2><b><?php echo esc_html_e('Product'); ?></b> <?php echo esc_html_e(' details'); ?></h2>
		<section class="accordion_product_details">
			<?php if(get_field('product_details')):  
				$product_details = get_field('product_details'); // Getting the main set of fields
				
				// getting the fields from the internal field group 'pdt_information'
				$pdt_information = $product_details['pdt_information'];
				$item_number = $pdt_information['item_number'];
				$material_details = $pdt_information['material_details'];
				$width = $pdt_information['width'];
				$depth = $pdt_information['depth'];
				$height = $pdt_information['height'];
				$weight = $pdt_information['weight'];
				$color = $pdt_information['color'];
				$series = $pdt_information['series'];

				// Getting additional fields from the main field group
				$video_url = $product_details['url_video'];
				$img_link = $product_details['img_link'];
				$title_description = $product_details['title_description'];
				$txt_description = $product_details['more_description'];
				$delivery_information = $product_details['delivery_information'];
				$file_download = $product_details['file_download'];

			endif; ?>

			<div class="accordion_item">
				<div class="accordion_title">
					<button type="button" aria-label="button" class="accordion_question">
						<span class="title"><?php echo esc_html_e( 'Technical data', 'kare' ); ?></span>
						<?php 
							$icon = file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-down.svg');
							$icon_with_class = str_replace('<svg', '<svg class="rotate180"', $icon);
							echo $icon_with_class;
						?>
					</button>
				</div>
				<div class="accordion_content active">
					<div class="accordion_answer data_wrapper">
						<h3><?php echo esc_html_e( 'Generally', 'kare' ); ?></h3>
						<ul class="information_product">
							<li>
								<span class="description_bold"><?php echo esc_html_e( 'Article number:', 'kare' ); ?></span>
								<span class="answer"><?php echo esc_html( $item_number ); ?></span>
							</li>
						</ul>
						<h3><?php echo esc_html_e( 'Details', 'kare' ); ?></h3>
						<ul class="information_product">
							<li>
								<span class="description_bold"><?php echo esc_html_e( 'Material Details:', 'kare' ); ?></span>
								<span class="answer"><?php echo esc_html( $material_details ); ?></span>
							</li>
						</ul>
						<h3><?php echo esc_html_e( 'Dimensions', 'kare' ); ?></h3>
						<ul class="information_product">
							<li>
								<span class="description_bold"><?php echo esc_html_e( 'Width (cm):', 'kare' ); ?></span>
								<span class="answer"><?php echo esc_html( $width ); ?></span>
							</li>
							<li>
								<span class="description_bold"><?php echo esc_html_e( 'Depth (cm):', 'kare' ); ?></span>
								<span class="answer"><?php echo esc_html( $depth ); ?></span>
							</li>
							<li>
								<span class="description_bold"><?php echo esc_html_e( 'Height (cm):', 'kare' ); ?></span>
								<span class="answer"><?php echo esc_html( $height ); ?></span>
							</li>
						</ul>
						<h3><?php echo esc_html_e( 'Miscellaneous', 'kare' ); ?></h3>
						<ul class="information_product">
							<li>
								<span class="description_bold"><?php echo esc_html_e( 'Weight (kg):', 'kare' ); ?></span>
								<span class="answer"><?php echo esc_html( $weight ); ?></span>
							</li>
							<li>
								<span class="description_bold"><?php echo esc_html_e( 'Color:', 'kare' ); ?></span>
								<span class="answer"><?php echo esc_html( $color ); ?></span>
							</li>
							<?php if(!empty($series)) : ?>
								<li>
									<span class="description_bold"><?php echo esc_html_e( 'Series:', 'kare' ); ?></span>
									<span class="answer"><?php echo esc_html( $series ); ?></span>
								</li>
							<?php endif?>
						</ul>
					</div>
				</div>
			</div>
			<?php if($video_url) : ?>
				<div class="accordion_item">
					<div class="accordion_title">
						<button type="button" aria-label="button" class="accordion_question">
							<span class="title"><?php echo esc_html_e( 'Video', 'kare' ); ?></span>
							<?php echo file_get_contents( get_template_directory_uri() . '/dist/images/svg/arrow-down.svg');?>
						</button>
					</div>
					<div class="accordion_content">
						<div class="accordion_answer video_wrapper">
							<div class="video_container">
								<iframe width="768" height="432" src="<?php echo $video_url; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
							</div>
						</div>
					</div>
				</div>
			<?php endif?>
			<?php if($img_link) : ?>
				<div class="accordion_item">
					<div class="accordion_title">
						<button type="button" aria-label="button" class="accordion_question">
							<span class="title"><?php echo esc_html_e( 'Dimensions', 'kare' ); ?></span>
							<?php echo file_get_contents( get_template_directory_uri() . '/dist/images/svg/arrow-down.svg');?>
						</button>
					</div>
					<div class="accordion_content">
						<div class="accordion_answer dimensions_wrapper">
							<div class="img_wrapper">
								<img src="<?php echo $img_link; ?>" alt="image with dimensions of the product"/>
							</div>
						</div>
					</div>
				</div>
			<?php endif?>
			<div class="accordion_item">
				<div class="accordion_title">
					<button type="button" aria-label="button" class="accordion_question">
						<span class="title"><?php echo esc_html_e( 'Description', 'kare' ); ?></span>
						<?php echo file_get_contents( get_template_directory_uri() . '/dist/images/svg/arrow-down.svg');?>
					</button>
				</div>
				<div class="accordion_content">
					<div class="accordion_answer container description_wrapper">
						<h3><?php echo esc_html( $title_description ); ?></h3>
						<span class="text_description"><?php echo esc_html($txt_description); ?></span>
					</div>
				</div>
			</div>
			<div class="accordion_item">
				<div class="accordion_title">
					<button type="button" aria-label="button" class="accordion_question">
						<span class="title"><?php echo esc_html_e( 'Delivery', 'kare' ); ?></span>
						<?php echo file_get_contents( get_template_directory_uri() . '/dist/images/svg/arrow-down.svg');?>
					</button>
				</div>
				<div class="accordion_content">
					<div class="accordion_answer container delivery_wrapper">
						<div class="text_delivery"><?php echo wp_kses_post($delivery_information); ?></div>
					</div>
				</div>
			</div>
			<div class="accordion_item">
				<div class="accordion_title">
					<button type="button" aria-label="button" class="accordion_question">
						<span class="title"><?php echo esc_html_e( 'Customer reviews', 'kare' ); ?></span>
						<?php echo file_get_contents( get_template_directory_uri() . '/dist/images/svg/arrow-down.svg');?>
					</button>
				</div>
				<div class="accordion_content">
					<div class="accordion_answer reviews_wrapper">
						<div class="customer_reviews">
							<?php if (!have_comments()) : ?>
								<button aria-label="button" type="button" class="open_account_button btn_white_hover">
									<a href="#" class="add_review_btn" ><?php echo esc_html_e( 'Add Review', 'kare' ); ?></a>
								</button>
								<div>
									<p><?php esc_html_e('No reviews for this search', 'kare'); ?></p>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php if($file_download) : ?>
				<div class="accordion_item">
					<div class="accordion_title">
						<button type="button" aria-label="button" class="accordion_question">
							<span class="title"><?php echo esc_html_e( 'Downloads', 'kare' ); ?></span>
							<?php echo file_get_contents( get_template_directory_uri() . '/dist/images/svg/arrow-down.svg');?>
						</button>
					</div>
					<div class="accordion_content">
						<div class="accordion_answer downloads_wrapper container">
							<h6><?php echo esc_html_e( 'Installation instructions', 'kare' ); ?></h6>
							<button aria-label="button" type="button" class="btn_white_hover">
								<a href="<?php echo $file_download; ?>" class="button_download" rel="noopener" target="_blank" download><?php echo esc_html_e( 'Download', 'kare' ); ?></a>
							</button>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</section>
	</section>
	<?php 
	$product_categories = get_the_terms( get_the_ID(), 'product_cat' );
	if ( !empty( $product_categories ) && !is_wp_error( $product_categories ) ) {
		$category = $product_categories[0];
	}
	?>
	<section class="related_products_section">
		<section class="appropriate_categories">
			<h2 class="title_wrapper"><?php echo esc_html_e('This fits:'); ?></h2>
			<div>
				<?php
				$categories_fits = get_field('add_this_fits', 'term_' . $category->term_id);

				if ( $categories_fits ) {
					foreach($categories_fits as $key => $cat){ 
						$cat_name = $cat->name; 
						$cat_link = get_term_link($cat->term_id);
						$cat_img_link = get_field('img_link_cat', 'product_cat_' . $cat->term_id); 
						?>

						<div class="swiper-slide more_categories_fits">
							<a href="<?php echo $cat_link; ?>" class="category_btn" title="<?php echo $cat_name;?>" aria-lable="link">
								<div class="card_category_wrapper" >
									<img src="<?php echo !empty($cat_img_link) ? $cat_img_link : ''?>" alt="<?php echo $cat_name;?>" width="50" height="50"/>
								</div>
								<p><?php echo $cat_name;?></p>
							</a>
						</div>
					<?php
					}
				}
				?>
			</div>
		</section>
		<section class="more_same_category">
			<h2 class="title_wrapper"><b><?php echo esc_html_e('More'); ?></b> <?php echo esc_html($category->name);?></h2>
			<div>
				<?php
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => -1,
					'tax_query' => array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $category->term_id,
						),
					),
					'post__not_in' => array( get_the_ID() ), // מונע הצגה של המוצר הנוכחי
				);
			
				$query = new WP_Query( $args );
			
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						get_template_part('page-templates/box-product');
					}
					wp_reset_postdata(); 
				} 			
				?>
			</div>
		</section>
		<section class="bestseller_same_category">
			<h2 class="title_wrapper"><b><?php echo esc_html_e('Bestseller'); ?></b> <?php echo esc_html($category->name);?></h2>
			<div>
				<?php
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => -1,
					'tax_query' => array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $category->term_id,
						),
						array(
							'taxonomy' => 'product_tag',
							'field'    => 'slug',
							'terms'    => 'bestseller',
						),
					),
					'post__not_in' => array( get_the_ID() ), // מונע הצגה של המוצר הנוכחי
				);
			
				$query = new WP_Query( $args );
			
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						get_template_part('page-templates/box-product');
					}
					wp_reset_postdata(); 
				} 			
				?>
			</div>
		</section>
		<section class="interesting_products">
			<h3 class="title_wrapper"><b><?php echo esc_html_e('Interesting products for you'); ?> </b></h3>
			<div>
				<a href="<?php echo get_term_link($category->term_id); ?>" class="interesting _category" title="<?php echo $category->name;?>" aria-lable="link">
					<?php echo $category->name;?>
				</a>
			</div>
		</section>
		<section class="bestseller_products">
			<h2 class="title_wrapper"><b><?php echo esc_html_e('Bestseller'); ?></b></h2>
			<div>
				<?php
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => -1,
					'tax_query' => array(
						array(
							'taxonomy' => 'product_tag',
							'field'    => 'slug',
							'terms'    => 'bestseller',
						),
					),
					'post__not_in' => array( get_the_ID() ), // מונע הצגה של המוצר הנוכחי
				);
			
				$query = new WP_Query( $args );
			
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						get_template_part('page-templates/box-product');
					}
					wp_reset_postdata(); 
				} 			
				?>
			</div>
		</section>
		
	</section>

 </div>
 
 <?php //do_action( 'woocommerce_after_single_product' ); ?>
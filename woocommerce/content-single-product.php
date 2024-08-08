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
		<h2><b>Product</b> details</h2>
		<section class="accordion_Product_details"></section>
	</section>
	<section class="pdts_related_wrapper">
	<h2><b>Product</b> details</h2>
	</section>
 </div>
 
 <?php //do_action( 'woocommerce_after_single_product' ); ?>
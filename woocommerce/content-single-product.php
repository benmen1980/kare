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
		'home'        => _x( 'Home Page', 'breadcrumb', 'woocommerce' ),
	));
}

 if ( post_password_required() ) {
	 echo get_the_password_form(); // WPCS: XSS ok.
	 return;
 }

 $attachment_ids = $product->get_gallery_image_ids();

 if ( $product->is_type( 'variable' ) ) {
	$regular_price = $product->get_variation_regular_price();
	$sale_price = ($regular_price != $product->get_variation_sale_price())? $product->get_variation_sale_price() : '';
 }
 else{
	$regular_price = $product->get_regular_price();
	$sale_price = $product->get_sale_price();
 }
 if(!empty($sale_price)){
	$percent = round((($regular_price - $sale_price)*100) / $regular_price) ;
 }

 ?>
 <div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
	<section class="top_details container">
		<div class="l_side">
			<div class="product-gallery">
				<div class="slider_gallery_wrapper">
					<!-- Large Gallery -->
					<div class="gallery-thumbnail-slider main-slider">
						<div class="swiper-wrapper">
							<div class="swiper-slide">
								<div class="gallery-large" id="main_slider_1">
									<img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" data-slide="1" alt="<?php echo $product->get_title();?>">
								</div>
							</div>
							<?php 
							$key_thumb = 2;
							foreach ($attachment_ids as $image_id) : ?>
								<div class="swiper-slide">
									<div class="gallery-large" id="main_slider_<?php echo $key_thumb; ?>">
										<img class="thumbnail_img" src="<?php echo  wp_get_attachment_url( $image_id ); ?>" alt="<?php echo $product->get_title();?>">
									</div>
								</div>
								<?php $key_thumb++; 
							endforeach; ?>								
						</div>
						<!-- arrows -->
						<div class="swiper-nav large-slider-nav-prev swiper-button-disabled">
							<button aria-label="prev" type="button" class="w-btn w-color w-color-white">
								<?php echo str_replace('<svg', '<svg class="w-dir-left"', file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-right-sm.svg')); ?>
							</button>
						</div>
						<div class="swiper-nav large-slider-nav-next">
							<button aria-label="next" type="button" class="w-btn w-color w-color-white">
								<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-right-sm.svg'); ?>
							</button>
						</div>
							<!-- <a href="<?php// echo wp_get_attachment_url( $product->get_image_id() );?>"> -->
							<!-- <a href="<?php //echo get_permalink( $product->ID ).'#zoom_1'?>"> -->
							<!-- </a> -->
					</div>
				</div>
				 <!-- Small Gallery -->
				<?php if($product->get_image_id() != '') : ?>
					<div class="small_img_slider_wrapper tabs_wrapper">
						<div class="swiper_img_slider thumb-slider">
							<div id="slider_img_gallery_small" class="img_gallery_small swiper-wrapper">
								<?php if ( $product_details = get_field('product_details') ){
									//$product_details = get_field('product_details'); // Getting the main set of fields
									$threed_url = $product_details['3d_url'];
									if($threed_url): ?>
										<div class="swiper-slide threed-slide">
											<div class="product_zoom_thumbnail_item threed_wrapper">
												<button type="button" class="threed_btn">
													<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/3d.svg'); ?>
												</button>
											</div>
										</div>
									<?php endif;
								} ?>
								<div class="swiper-slide active-small-thumbnail">
									<div class="product_zoom_thumbnail_item">
										<!-- <a href="<?php// echo get_permalink( $product->ID ).'#zoom_1';?>" data_main_slider="main_slider_1" class="small_img_active"> -->
											<img class="thumbnail_img" src="<?php echo wp_get_attachment_url($product->get_image_id())?>"  alt="<?php echo $product->get_title();?>">
										<!-- </a>            		 -->
									</div> 
								</div> 
								<?php
								$key_thumb = 2;
								foreach ($attachment_ids as $image_id) : ?>
									<div class="swiper-slide">
										<div class="product_zoom_thumbnail_item">
											<!-- <a href="<?php //echo get_permalink( $product->ID ).'#zoom_'.$key_thumb;?>" data_main_slider="main_slider_<?php echo $key_thumb;?>"> -->
												<img class="thumbnail_img" src="<?php echo  wp_get_attachment_url( $image_id ); ?>" alt="<?php echo $product->get_title();?>">
											<!-- </a> -->
										</div>
									</div>
									<?php $key_thumb++; 
								endforeach; ?>
							</div>
							<!-- arrows -->
							<div class="swiper-nav small-slider-nav-prev swiper-button-disabled">
								<button aria-label="prev" type="button" class="w-btn w-color w-color-white">
									<?php echo str_replace('<svg', '<svg class="w-dir-left"', file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-right-sm.svg')); ?>
								</button>
							</div>
							<div class="swiper-nav small-slider-nav-next">
								<button aria-label="next" type="button" class="w-btn w-color w-color-white">
									<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-right-sm.svg'); ?>
								</button>
							</div>
							<!-- scrollbar -->
							<div class="swiper-scrollbar"></div>
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
				<p class="product-sku"><?php echo esc_html__('Item no:', 'kare') .' '. $product->get_sku(); ?></p>

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

				$main_module = get_post_meta($product->get_id(), 'main_module', true);

				if ( $main_module ) : ?>

					<div class="similar_pdt_wrapper">
						<?php
							$query_args = array(
								'post_type' => 'product',
								'post_status' => array('publish', 'draft'),
								'post__not_in' => array($product->get_id()),
								'meta_query' => array(
									array(
										'key' => 'main_module',
										'value' => $main_module
									)
								),
								'posts_per_page' => 15
							);
							
							// The Query
							$the_query = new WP_Query( $query_args );
							$similar_ids = [$product->get_id()];
				
							// Loop through similar products
							if ( $the_query->have_posts() ) :
								while ( $the_query->have_posts() ) :
									$the_query->the_post();
									$similar_ids[] = get_the_ID();
								endwhile;
								/* Restore original Post Data */
								wp_reset_postdata();
							endif;
						?>
					
						<div class="swiper tabs_wrapper">
							<div class="similar-pdt-swiper">
								<div class="swiper-wrapper">
									<?php foreach($similar_ids as $pdt_id) :

										$similar_product = wc_get_product($pdt_id);
										if ($similar_product && $similar_product->get_status() === 'publish') :
											$image_url = wp_get_attachment_url($similar_product->get_image_id());
											$product_url = get_permalink($pdt_id);
											$product_name = $similar_product->get_name();
												
											if ($image_url) : ?>
												<div class="swiper-slide">
													<div class="similar-image-pdt">
														<a href="<?php echo esc_url($product_url); ?>" class="varition_image" title="<?php echo esc_attr($product_name); ?>" aria-label="link">
															<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product_name);?>" width="80" height="80"/>
														</a>
													</div>
												</div>									
											<?php endif;
										endif;
									endforeach; ?>
								</div>
							</div>
						</div>
					</div>

				<?php endif; 

				woocommerce_template_single_rating();
				?>

				<div class="pdt_price_wrapper">
					<?php if(!empty($sale_price)): ?>
						<div class="sale_tag">
							<b class="text_sale_percent"> <?php echo '-'.$percent.'%&nbsp;'; ?></b>
						</div>
						<p class="sale_price"> 
							<!-- <span>RRP*: &nbsp;</span> -->
							<?php woocommerce_template_single_price(); ?>
						</p>
					<?php else : ?>
						<b class="regular_price"><?php woocommerce_template_single_price(); ?></b>
					<?php endif; ?> 
				</div>

				<?php
					$stock = ($product->get_stock_quantity() > 0) ? __('Immediately available', 'kare') : __('60 business days', 'kare');
					$kare_stock = get_post_meta($product->get_id(), 'kare_general_stock', true);
					$stock_available = get_post_meta($product->get_id(), '_stock', true);
					$backorders_status = get_post_meta($product->get_id(), '_backorders', true);
				?>

				<?php if ( ( !empty($kare_stock) || $kare_stock > 0) || intval($stock_available) > 0 ) : ?>

					<div class="stock_shipping_availability">
						<p class="product-shipping"><?php echo __( 'Delivery within:', 'kare' ); ?></p>
						<p class="instock <?php echo ($product->get_stock_quantity() > 0) ? 'stock' : ''; ?>"><?php echo esc_html( $stock ); ?></p>
					</div>
				<?php else : ?>
					<div class="stock_coming_soon">
						<p class="coming_soon"><?php echo esc_html_e( 'coming soon', 'kare' ); ?></p>
					</div>
				<?php endif;

				// woocommerce_template_single_add_to_cart(); ?>
				<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

				<?php
				if ( ( !empty($kare_stock) || $kare_stock > 0) || intval($stock_available) > 0 ) :
				?>

					<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
						<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
				
						<?php
							do_action( 'woocommerce_before_add_to_cart_quantity' );
					
							$max_quantity = $product->get_max_purchase_quantity();
							if ($stock_available <= 0) {
								if ( $backorders_status === 'yes' ) {
									$max_quantity = !empty($kare_stock) ? intval($kare_stock) : 0;
								}
							} else {
								$max_quantity = apply_filters('woocommerce_quantity_input_max', $max_quantity, $product);
							}
							$min_quantity = apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product );
							$pack_quantity = get_post_meta($product->get_id(), 'quantity_product_order', true);
							$quantity = isset($pack_quantity) && $pack_quantity > 1 ? $pack_quantity : $min_quantity; 

							?>

							<div class="custom_select_wrapper">
								<button aria-label="button" type="button" class="btn_quantity_wrapper">
									<span class="selected_value"><?php echo esc_html($quantity); ?></span> <!-- ברירת מחדל: הספרה 1 -->
									<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-down.svg'); ?>
								</button>
								<ul class="custom_options">
									<?php for ($i = $quantity; $i <= min(24, $max_quantity); $i += $quantity) { ?>
										<li class="custom_option <?php echo $i == $quantity ? 'selected' : ''; ?>" data-value="<?php echo esc_attr($i); ?>">
											<?php echo esc_html($i); ?>
										</li>
									<?php } ?>
								</ul>
								<input type="hidden" name="quantity" class="custom_select_hidden" value="<?php echo esc_html($quantity); ?>"> <!-- ברירת מחדל: ערך 1 -->
							</div>

							<?php do_action( 'woocommerce_after_add_to_cart_quantity' );
						?>
				
						<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

						<button aria-label="link" type="button" title="Add to Wishlist" class="wishlist_btn">
							<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
						</button>
		
						<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
					</form>

					<?php else : ?>
					<section class="notification_content">
						<p><?php the_field('title_before_form','option'); ?></p>
						<div class="notification_and_btn">
							<div class="notification_form">
								<?php 
								$cf7_form_id = get_field('select_cf7_form_pdt','option'); // Get the selected form ID from ACF
								if( $cf7_form_id ) {
									echo do_shortcode('[contact-form-7 id="' . $cf7_form_id . '"]');
								}
								?>
							</div>
							<button aria-label="link" type="button" title="Add to Wishlist" class="wishlist_btn">
								<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
							</button>
						</div>
					</section>
							
				<?php endif; ?>
		
				<?php do_action( 'woocommerce_after_add_to_cart_form' ); 
				
				//get quantityto order of the product
				$quantity_product = get_post_meta($product->get_id(), 'quantity_product_order', true);

				if ($quantity_product && $quantity_product  > 1 ) :
				?>
					<section class="product_price_notification">
						<?php echo file_get_contents( get_template_directory_uri() . '/dist/images/svg/box.svg');?>
						<span class="product_price_notification_text">
							<p>
								<?php echo sprintf(esc_html__( 'Minimum order quantity: %d pieces', 'kare' ),intval( $quantity_product )); ?>
							</p>
							<p>
								<?php echo sprintf(esc_html__( 'This product is only available in a set of %d. Price is per set.', 'kare' ),intval( $quantity_product )); ?>
							</p>
						</span>
					</section>
				<?php
				endif;

				woocommerce_template_single_sharing();

				?>
				<div class="about_product">
					<!-- List of qualities in the product -->
					<div class="list_qualities_pdts">
						<?php
						if( have_rows( 'list_item_product', 'option' ) ):
							while( have_rows( 'list_item_product', 'option' ) ): the_row();
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
					<?php if($product->get_short_description()): ?>
					<h5 class="short_discreption_title"><?php _e( 'At a glance:', 'kare' ); ?></h5>
					<?php
					
					// add short description
					woocommerce_template_single_excerpt();
					endif;
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
		<h2><?php echo sprintf(__('<b>%s</b> details', 'kare'), __('Product', 'kare')); ?></h2>
		<section class="accordion_details_wrapper">
			<?php 
			if ( $product_details = get_field('product_details') ):  
				//$product_details = get_field('product_details'); // Getting the main set of fields

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
				
				$img_link = $product_details['img_dimensions_link'];
				$txt_description = $product_details['more_description'];
				$file_download = $product_details['file_download'];

			endif; 
			$delivery_information = get_field('delivery_information', 'option');;
			?>

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
								<span class="answer">
									<?php 
									//echo esc_html( $color );
									if (!empty($product->get_attribute('color'))) {
										echo $product->get_attribute('color');
									}
									// else{
									// 	echo esc_html( $color );
									// }
									?>
								</span>
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
			<?php if($video_url) : 
			    preg_match('/https?:\/\/(?:www\.)?vimeo\.com\/([0-9]+)/i', $video_url, $matches);
				$vimeo_id = $matches[1] ?? null;
				//echo 'video id'.$vimeo_id;
				if ($vimeo_id) { ?>
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
									<iframe width="768" height="432" src="https://player.vimeo.com/video/<?php echo $vimeo_id; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
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
						<h3><?php echo esc_html( $product->get_name() ); ?></h3>
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
			<!-- <div class="accordion_item">
				<div class="accordion_title">
					<button type="button" aria-label="button" class="accordion_question">
						<span class="title"><?php // echo esc_html_e( 'Customer reviews', 'kare' ); ?></span>
						<?php // echo file_get_contents( get_template_directory_uri() . '/dist/images/svg/arrow-down.svg');?>
					</button>
				</div>
				<div class="accordion_content">
					<div class="accordion_answer reviews_wrapper">
						<div class="customer_reviews">
							<?php // if (!have_comments()) : ?>
								<button aria-label="button" type="button" class="open_account_button btn_white_hover">
									<a href="#" class="add_review_btn" ><?php // echo esc_html_e( 'Add Review', 'kare' ); ?></a>
								</button>
								<div>
									<p><?php // esc_html_e('No reviews for this search', 'kare'); ?></p>
								</div>
							<?php // endif; ?>
						</div>
					</div>
				</div>
			</div> -->
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
	// echo "<pre>";
	// print_r($product_categories);
	// echo "</pre>";
	if ( !empty( $product_categories ) && !is_wp_error( $product_categories ) ) {
		$category = $product_categories[0];
		//$categories_fits = get_field('add_this_fits', 'term_' . $category->term_id);
		$categories_fits = get_field('add_this_fits', 'term_' . apply_filters( 'wpml_object_id', $category->term_id, 'product_cat',true,'en' ));
	}
	?>
	<section class="related_products_section container">
		<?php if ($categories_fits) : ?>
			<section class="appropriate_categories">
				<h2 class="title_wrapper"> <b><?php echo esc_html_e('This fits:', 'kare'); ?></b></h2>
				<div class="more_categories_wrapper tabs_wrapper swiper">
					<div class="swiper_more_same_cat">
						<div id="slide_same_cat" class="swiper_slide_more_cat swiper-wrapper">

							<?php

							if ( $categories_fits ) {
								foreach($categories_fits as $key => $cat){ 
									$cat_name = $cat->name; 
									$cat_link = get_term_link($cat->term_id);
									if( is_wp_error($cat_link) ) {
										
										continue;
									}
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
					</div>
				</div>
			</section>
		<?php endif; ?>
		<section class="more_same_category">
			<h2 class="title_wrapper"><b><?php echo esc_html_e('More', 'kare'); ?></b> <?php echo esc_html($category->name);?></h2>
			<div class="same_category_wrapper">
				<?php
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => 15,
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
			
				if ( $query->have_posts() ) { ?>
					<div class="tabs_wrapper">
                        <div class="swiper_slide_pdts swiper_slide_category swiper-container">
                            <div id="slide_same_cat_pdts" class="more_slide_products slider_products_content swiper-wrapper">
								<?php while ( $query->have_posts() ) {
									$query->the_post();
									$translated_product_id = apply_filters('wpml_object_id', get_the_ID(), 'product', true);
									$transient_name = 'product_slide_' . $translated_product_id;
									if ( false === ( $product_slide = get_transient( $transient_name ) ) ) {
										ob_start();
										get_template_part('page-templates/box-product');
										$product_slide = ob_get_clean();
										set_transient( $transient_name, $product_slide, 10 * 60 );
									}
									echo $product_slide;

									//get_template_part('page-templates/box-product');
								}
								wp_reset_postdata(); ?>
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
							<!-- dropping points-->
							<div class="swiper-pagination-wrapper">
								<div class="swiper-pagination swiper-pagination-black swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-bullets-dynamic" style="width: 100px;"></div>
							</div>
						</div>
					</div>
				<?php } 			
				?>
			</div>
		</section>
		<section class="bestseller_same_category">
			<?php 
			$args = array(
				'post_type' => 'product',
				'posts_per_page' => 15,
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
		
			if ( $query->have_posts() ) { ?>
				<h2 class="title_wrapper"><b><?php echo esc_html_e('Bestseller', 'kare'); ?></b> <?php echo esc_html($category->name);?></h2>
				<div class="same_category_wrapper">
					<div class="tabs_wrapper">
						<div class="swiper_slide_pdts swiper_slide_bestseller_category swiper-container">
							<div id="slide_bestseller_cat_pdts" class="more_slide_products slider_products_content swiper-wrapper">
								<?php while ( $query->have_posts() ) {
									$query->the_post();
									$translated_product_id = apply_filters('wpml_object_id', get_the_ID(), 'product', true);
									$transient_name = 'product_slide_' . $translated_product_id;
									if ( false === ( $product_slide = get_transient( $transient_name ) ) ) {
										ob_start();
										get_template_part('page-templates/box-product');
										$product_slide = ob_get_clean();
										set_transient( $transient_name, $product_slide, 10 * 60 );
									}
									echo $product_slide;
									//get_template_part('page-templates/box-product');
								}
								wp_reset_postdata(); ?>
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
							<!-- dropping points-->
							<div class="swiper-pagination-wrapper">
								<div class="swiper-pagination swiper-pagination-black swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-bullets-dynamic">
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } 			
			?>
		</section>
		<section class="interesting_products">
			<h3 class="title_wrapper"><?php echo esc_html_e('Interesting products for you', 'kare'); ?> </h3>
			<div class="interesting_category_wrapper">
				<a href="<?php echo get_term_link($category->term_id); ?>" class="interesting_category" title="<?php echo $category->name;?>" aria-lable="link">
					<?php echo $category->name;?>
				</a>
			</div>
		</section>
		<section class="bestseller_products">
			<h2 class="title_wrapper"><b><?php echo esc_html_e('Bestseller', 'kare'); ?></b></h2>
			<div class="same_category_wrapper">
				<?php
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => 15,
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
			
				if ( $query->have_posts() ) { ?>
					<div class="tabs_wrapper">
                        <div class="swiper_slide_pdts swiper_slide_bestseller_products swiper-container">
                            <div id="slide_bestseller_products" class="more_slide_products slider_products_content swiper-wrapper">
								<?php while ( $query->have_posts() ) {
									$query->the_post();
									$translated_product_id = apply_filters('wpml_object_id', get_the_ID(), 'product', true);
									$transient_name = 'product_slide_' . $translated_product_id;
									if ( false === ( $product_slide = get_transient( $transient_name ) ) ) {
										ob_start();
										get_template_part('page-templates/box-product');
										$product_slide = ob_get_clean();
										set_transient( $transient_name, $product_slide, 10 * 60 );
									}
									echo $product_slide;
									//get_template_part('page-templates/box-product');
								}
								wp_reset_postdata(); ?>
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
							<!-- dropping points-->
							<div class="swiper-pagination-wrapper">
								<div class="swiper-pagination swiper-pagination-black swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-bullets-dynamic">
								</div>
							</div>
						</div>
					</div>
				<?php } 			
				?>
			</div>
		</section>
		
	</section>

</div>
<?php if ( $product_details = get_field('product_details') ){
	//$product_details = get_field('product_details');
	$threed_url = $product_details['3d_url'];
	if($threed_url): ?>
		<div class="threed_popup" id="threed_pdt_<?php the_ID(); ?>">
			<button type="button" class="white-btn">
				<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/close.svg'); ?>
			</button>
			<iframe src="<?php echo $threed_url?>" width="100%" height="100%"   allow="fullscreen; autoplay; accelerometer; gyroscope">
			</iframe>
		</div>
	<?php endif;
} ?>
 
 <?php //do_action( 'woocommerce_after_single_product' ); ?>
<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit; 

// Get the cart object
$cart = WC()->cart;

// Calculate the total quantity of items in the cart
$total_quantity = $cart->get_cart_contents_count();
?>

<?php do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents shop_cart" cellspacing="0">

		<div class="comment_cart">
			<h4 class="qty_products"><?php echo esc_html_e('Shopping cart (' . $total_quantity . ' products)', 'kare'); ?></h4>
			<p class="comment_txt"><?php echo get_field('comment_txt','option'); ?></p>
		</div>
		<!-- <thead>
			<tr>
				<th class="product-remove"><span class="screen-reader-text"><?php// esc_html_e( 'Remove item', 'woocommerce' ); ?></span></th>
				<th class="product-thumbnail"><span class="screen-reader-text"><?php// esc_html_e( 'Thumbnail image', 'woocommerce' ); ?></span></th>
				<th class="product-name"><?php// esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-price"><?php// esc_html_e( 'Price', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php// esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="product-subtotal"><?php// esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			</tr>
		</thead> -->
		<!-- <tbody> -->
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : ?>
				<?php
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				$product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
				?>
			
				<div class="cart-product-item">
					<div class="cart-product-thumbnail">
						<?php
						$thumbnail = $_product->get_image();
						if ( ! $product_permalink ) {
							echo $thumbnail; // PHPCS: XSS ok.
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
						}
						?>
					</div>
					<div class="cart-product-details">
						<a href="<?php echo $product_permalink ?>" class="cart-product-name">
							<?php
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
							?>
						</a>
						<div class="cart-product-meta">
							<div class="remove_and_qty">
								<p><?php _e( 'Crowd:', 'woocommerce' ); ?></p>
								<div class="cart-product-quantity-remove">
									<a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="remove" aria-label="<?php esc_attr_e( 'Remove this item', 'woocommerce' ); ?>">
										<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/bin.svg'); ?>
									</a>
									<!-- <select class="quantity-dropdown" name="cart[<?php echo $cart_item_key; ?>][qty]" > -->
										<?php
										// $max_quantity = $_product->get_max_purchase_quantity();
										// $min_quantity = 1;
										// $pack_quantity = get_post_meta($product_id, 'quantity_product_order', true);
										// $quantity = isset($pack_quantity) && $pack_quantity > 1 ? $pack_quantity : $min_quantity; 

										
										$max_quantity = apply_filters('woocommerce_quantity_input_max', $_product->get_max_purchase_quantity(), $_product);
										$min_quantity = apply_filters('woocommerce_quantity_input_min', $_product->get_min_purchase_quantity(), $_product);
										$pack_quantity = get_post_meta($product_id, 'quantity_product_order', true);
										$quantity = isset($pack_quantity) && $pack_quantity > 1 ? $pack_quantity : $min_quantity;

										$selected_quantity = $cart_item['quantity'];
				
										// for ($i = $quantity; $i <= min(24, $max_quantity); $i += $quantity) {
										// 	echo '<option value="' . esc_attr($i) . '"' . selected($cart_item['quantity'], $i, false) . '>' . esc_html($i) . '</option>';
										// }				
										?>	
										<form class="update-cart-form" method="post" action="<?php echo esc_url( wc_get_cart_url() ); ?>">
											<div class="quantity-wrapper">
												<button aria-label="button" type="button" class="btn_quantity_wrapper">
													<span class="selected_value"><?php echo esc_html($selected_quantity); ?></span> <!-- ברירת מחדל: הספרה 1 -->
													<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-down.svg'); ?>
												</button>
												<ul class="custom_options">
													<?php for ($i = $quantity; $i <= min(24, $max_quantity); $i += $quantity) { ?>
														<li class="custom_option <?php echo $i == $selected_quantity ? 'selected' : ''; ?>" data-value="<?php echo esc_attr($i); ?>">
															<?php echo esc_html($i); ?>
														</li>
													<?php } ?>
												</ul>
												<input type="hidden" name="cart[<?php echo $cart_item_key; ?>][qty]" class="custom_select_hidden" value="<?php echo esc_html($selected_quantity); ?>">

											</div>
   											<!-- <button type="submit" class="button" name="update_cart" value="Update cart" style="display: none;">Update cart</button> -->
											<button type="submit" class="button <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" style="display:none;"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

										</form>
															
									<!-- </select> -->
								</div>
							</div>
							
							<?php
								$stock = ($_product->get_stock_quantity() > 0) ? 'Immediately available' : '60 business days';
							?>
							<div class="delivery_and_price">
								<div class="delivery_wrapper">
									<p><?php _e( 'Delivery time:', 'woocommerce' ); ?> </p>
									<p class="shipping_availability bold <?php echo ($_product->get_stock_quantity() > 0) ? 'stock' : ''; ?>"><?php echo esc_html( $stock ); ?></p>
								</div>
								<?php if ( empty($_product->get_sale_price()) ) : ?>
									<div class="price_wrapper">
										<p><?php _e( 'Unit price:', 'woocommerce' ); ?> </p>
										<p><?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?></p>
									</div>
								<?php else : ?>
									<div class="price_wrapper">
										<p><?php _e( 'Unit price:', 'woocommerce' ); ?> </p>
										<p><?php echo esc_html_e( 'RRP*: ', 'kare' ) . '<del>' . wc_price( $_product->get_regular_price() ) . '</del>'; ?></p>
									</div>
									<div class="price_discount_wrapper">
										<p><?php _e( 'Price after discount:', 'woocommerce' ); ?> </p>
										<p class="bold red"><?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?></p>

										
									</div>
								<?php endif; ?>
							</div>
						</div>
						<hr>
						<div class="cart-product-price">
							<p><?php _e( 'Total including VAT:', 'woocommerce' ); ?></p> 
							<?php 
							$quantity = $cart_item['quantity'];
							$regular_price = $_product->get_regular_price(); // מחיר רגיל
							$sale_price = $_product->get_sale_price(); // מחיר מבצע
							
							$subtotal = $sale_price ? $sale_price * $quantity : $regular_price * $quantity;
							$discounted_price_total = wc_get_price_to_display( $_product ) * $cart_item['quantity'];
							?>
							<p class="bold red"><?php echo wc_price( $discounted_price_total ); ?></p>
						</div>	
						<?php
							$regular_price = $_product->get_regular_price();
							$sale_price = $_product->get_sale_price();
							if ( $regular_price && $sale_price && $regular_price > $sale_price ) : ?>
								<div class="discount_message_wrapper">
									<p>
										<?php
											$savings = $regular_price - $sale_price;
											$percentage = round( ( $savings / $regular_price ) * 100 );
											
											$percentage_class = '<span class="discount-percentage">' . $percentage . '%</span>';
											echo sprintf( __( 'Congratulations! You have just saved %s, which is %s !', 'woocommerce' ), wc_price( $savings ), $percentage_class );
										?>
									</p>
								</div>	
							<?php endif;
						?>						
					</div>
				</div>
			<?php endforeach;						 
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<!-- <tr> -->
				<!-- <td colspan="6" class="actions"> -->
				<?php if ( wc_coupons_enabled() ) { ?>
					<div class="coupon">
						<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
						<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Enter voucher code here...', 'woocommerce' ); ?>" />
						<button type="submit" class="button disable coupon_button" name="apply_coupon" disabled value="<?php esc_attr_e( 'Activate voucher code', 'woocommerce' ); ?>">
							<?php esc_html_e( 'Activate voucher code', 'woocommerce' ); ?>
						</button>
						<?php do_action( 'woocommerce_cart_coupon' ); ?>
					</div>
				<?php } ?>

				<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

				<div class="cart-collaterals">
					<?php
						/**
						 * Cart collaterals hook.
						 *
						 * @hooked woocommerce_cross_sell_display
						 * @hooked woocommerce_cart_totals - 10
						 */
						do_action( 'woocommerce_cart_collaterals' );
					?>
				</div>


					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				<!-- </td> -->
			<!-- </tr> -->

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		<!-- </tbody> -->
	</div>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>

<!-- <div class="box_delivery container">
	<div class="title">+++ DELIVERY AREA +++</div>
	<p>Delivery area only Germany. Other countries: kare-design.com</p>
</div> -->

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

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<div class="cart_content_wrap">

		<div class="pdts_content">

			<div class="comment_cart white_bg">
				<h4 class="qty_products"><?php echo esc_html_e('Shopping cart (' . $total_quantity . ' products)', 'kare'); ?></h4>
				<p class="comment_txt"><?php echo get_field('comment_txt','option'); ?></p>
			</div>

			<?php do_action( 'woocommerce_before_cart_contents' ); ?>
			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				/**
				 * Filter the product name.
				 *
				 * @since 2.1.0
				 * @param string $product_name Name of the product in the cart.
				 * @param array $cart_item The product in the cart.
				 * @param string $cart_item_key Key for the product in the cart.
				 */
				$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				}?>
				<div class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<div class="cart-product-thumbnail">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );?>
						
						<?php if ( ! $product_permalink ) { 
							echo $thumbnail; // PHPCS: XSS ok.
						} else {
						printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
						?>
						<?php }
						?>
					</div>

					<div class="cart-product-details">
						<div class="product_name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
							<?php
							if ( ! $product_permalink ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
							} else {
								//echo $_product->get_name();
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
							}

							do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

							// Meta data.
							echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

							// Backorder notification.
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
							}
							?>
						</div>

						<div class="remove_and_qty cart_product_quantity_remove_wrapper">
							<p><?php _e( 'Crowd:', 'woocommerce' ); ?></p>
							<div class="cart-product-quantity-remove">

								<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="button_underline" aria-label="%s" data-product_id="%s" data-product_sku="%s"> %s</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_html__( 'Remove this item', 'woocommerce' ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() ),
											file_get_contents(get_template_directory() . '/dist/images/svg/bin.svg') // קובץ ה-SVG
										),
										$cart_item_key
									);
								?>

								<div class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
									<?php
									if ( $_product->is_sold_individually() ) {
										$min_quantity = 1;
										$max_quantity = 1;
									} else {
										$min_quantity = 0;
										$max_quantity = $_product->get_max_purchase_quantity();
									}

									$product_quantity = woocommerce_quantity_input(
										array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $max_quantity,
											'min_value'    => $min_quantity,
											'product_name' => $product_name,
										),
										$_product,
										false
									);

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
									?>
								</div>
							</div>
						</div>

						<?php
							$stock = ($_product->get_stock_quantity() > 0) ? 'Immediately available' : '60 business days';
						?>
						<div class="delivery_and_price_wrapper">
							<div class="product-delivery">
								<p><?php _e( 'Delivery time:', 'woocommerce' ); ?> </p>
								<p class="shipping_availability bold <?php echo ($_product->get_stock_quantity() > 0) ? 'stock' : ''; ?>"><?php echo esc_html( $stock ); ?></p>
							</div>

							<?php if ( $_product->is_on_sale() ) : ?>
								<div class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
									<p><?php _e( 'Unit price:', 'woocommerce' ); ?> </p>
									<p><?php _e( 'RRP*: ', 'woocommerce' ); ?> 
										<del> <?php echo wc_price( $_product->get_regular_price() ); ?> </del>
									</p>
								</div>
								<div class="product-price-discont" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
									<p><?php _e( 'Price after discount:', 'woocommerce' ); ?> </p>
									<p class="bold red">
										<?php
											echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
										?>
									</p>
								</div>
							<?php else : ?>
								<div class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
									<p><?php _e( 'Unit price:', 'woocommerce' ); ?> </p>
									<p><?php
										echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?></p>
								</div>
							<?php endif; ?>
						</div>

						<hr>
				
						<div class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
							<p><?php _e( 'Total including VAT:', 'woocommerce' ); ?></p>
							<p class="bold red"><?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?></p>
						</div>

						<?php if ( $_product->is_on_sale() ) : ?>
							<div class="product-discount-message">
								<p>
									<?php
										$regular_price = $_product->get_regular_price();
										$sale_price = $_product->get_sale_price();
										$savings = $regular_price - $sale_price;
										$percentage = round( ( $savings / $regular_price ) * 100 );
										
										$percentage_class = '<span class="discount-percentage">' . $percentage . '%</span>';
										echo sprintf( __( 'Congratulations! You have just saved %s, which is %s !', 'woocommerce' ), wc_price( $savings ), $percentage_class );
									?>
								</p>
							</div>	
						<?php endif; ?>
					</div>
				</div>
			<?php
			}
			
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<div class="delivery_cost_wrapper white_bg">
				<h5 class="title"><?php _e( 'Delivery costs', 'woocommerce' ); ?></h5>
				<div class="delivery_price">
					<p class="text">
						<?php _e( '** Detailed list in the next step', 'woocommerce' ); ?>
					</p>
					<p class="price">
						<span><?php _e( 'Price:', 'woocommerce' ); ?></span>
						<?php 
							$cart_total = WC()->cart->get_subtotal();
							$cost_delivery = $cart_total > 1800 ? 0 : 50.00;
							echo wc_price( $cost_delivery ); 
						?>
					</p>
				</div>
			</div>
			<div class="coupon_wrapper">
				<div colspan="6" class="actions">
				<h5 class="title"><?php _e( 'Voucher code', 'woocommerce' ); ?></h5>
					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon form-row">
							<label for="coupon_code" class="">
								<span> <?php esc_html_e( 'Enter voucher code here...', 'woocommerce' ); ?> </span>
							</label> 
							<input type="text" name="coupon_code" class="input-text coupon_code" id="coupon_code_a" value="" placeholder=" " /> 
							<button type="submit" class="coupon_button disable <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Activate voucher code', 'woocommerce' ); ?></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>

					<button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</div>
			</div>
			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			<?php do_action( 'woocommerce_after_cart_table' ); ?>

			<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
			<div class="cart_summary_inpdts_content">
				<div class="cart_summary_content">
					<?php
					/**
					 * Cart collaterals hook.
					 *
					 * @hooked woocommerce_cross_sell_display
					 * @hooked woocommerce_cart_totals - 10
					 */
					do_action( 'woocommerce_cart_collaterals' );
					?>
					<div class="desc_under_total">
						<?php echo get_field('desc_under_summary'); ?>
					</div>
				</div>
			</div>

			
			<?php
				$cart_total_before_discounts = WC()->cart->get_subtotal();
				$discount_total = WC()->cart->get_discount_total();
				$shipping_total = WC()->cart->get_shipping_total();
				$cart_total_after_discounts = WC()->cart->total;

				if ($cart_total_before_discounts > 0 && $discount_total > 0) { ?>
					<div class="product-discount-message">
						<p>
							<?php
								$discount_percentage = ($discount_total / $cart_total_before_discounts) * 100;
								$discount_percentage = round($discount_percentage, 2); 
								
								// הצגת ההודעה
								$discount_percentage = '<span class="discount-percentage">' . $discount_percentage . '%</span>';
								echo sprintf( __( 'Congratulations! You have just saved %s , which is %s !', 'woocommerce' ), wc_price( $discount_total ), $discount_percentage );			
							?>
						</p>
					</div>
				<?php } ?>	
		</div>
	</div>

	<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
	<div class="cart_summary">
		<div class="cart_summary_content">
			<?php
			/**
			 * Cart collaterals hook.
			 *
			 * @hooked woocommerce_cross_sell_display
			 * @hooked woocommerce_cart_totals - 10
			 */
			do_action( 'woocommerce_cart_collaterals' );
			?>
			<div class="coupon_wrapper">
				<div colspan="6" class="actions">
				<h5 class="title"><?php _e( 'Voucher code', 'woocommerce' ); ?></h5>
					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon form-row">
							<label for="coupon_code" class="">
								<span> <?php esc_html_e( 'Enter voucher code here...', 'woocommerce' ); ?> </span>
							</label> 
							<input type="text" name="coupon_code" class="input-text coupon_code" id="coupon_code_b" value="" placeholder=" " /> 
							<button type="submit" class="coupon_button disable <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Activate voucher code', 'woocommerce' ); ?></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>

					<button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</div>
			</div>
			<div class="another_data">
				<h5><?php _e( 'Do you need help?', 'woocommerce' ); ?></h5>
				<button class="contact_mail">
					<a href="mailto:<?php echo get_field('contact_email','option'); ?>"  target="_blank"><?php _e( 'Contact us', 'woocommerce' ); ?></a>
				</button>
			</div>
			<div class="desc_under_total">
				<?php echo get_field('desc_under_summary'); ?>
			</div>
		</div>
	</div>
</form>

<?php do_action( 'woocommerce_after_cart' ); ?>

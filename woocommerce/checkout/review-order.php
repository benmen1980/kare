<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */
defined( 'ABSPATH' ) || exit;


// Get the cart object
$cart = WC()->cart;

// Calculate the total quantity of items in the cart
$total_quantity = $cart->get_cart_contents_count();
?>
<div class="shop_table woocommerce-checkout-review-order-table">
	<section class="wc-cart-mini">
		<div class="cart-header">
			<h5 class="cart-title"><?php echo esc_html_e('Shopping cart (' . $total_quantity . ' products)', 'kare'); ?></h5>
			<div class="checkout-pdts">
				<button aria-label="button" type="button" alt="show checkout products" id="show_pdts" class="toggle-cart">
					<span><?php _e( 'Show products', 'kare' ); ?></span>
					<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-down.svg'); ?>
				</button>
				<div class="wc-cart-mini-wrapper">
					<div class="product">
						<?php
						do_action( 'woocommerce_review_order_before_cart_contents' );

						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

								?>
								<div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
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
									<div class="product-name">
										<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
										<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
									<?php
										$stock = ($_product->get_stock_quantity() > 0) ? 'Immediately available' : '60 business days';
									?>
									<div class="delivery_and_price_wrapper">
										<div class="product-delivery">
											<p><?php _e( 'Delivery time:', 'kare' ); ?> </p>
											<p class="shipping_availability bold <?php echo ($_product->get_stock_quantity() > 0) ? 'stock' : ''; ?>"><?php echo esc_html( $stock ); ?></p>
										</div>

										<?php if ( $_product->is_on_sale() ) : ?>
											<div class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
												<p><?php _e( 'Unit price:', 'kare' ); ?> </p>
												<p><?php _e( 'RRP*: ', 'kare' ); ?> 
													<del> <?php echo wc_price( $_product->get_regular_price() ); ?> </del>
												</p>
											</div>
											<div class="product-price-discont" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
												<p><?php _e( 'Price after discount:', 'kare' ); ?> </p>
												<p class="bold red">
													<?php
														echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
													?>
												</p>
											</div>
										<?php else : ?>
											<div class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
												<p><?php _e( 'Unit price:', 'kare' ); ?> </p>
												<p><?php
													echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
												?></p>
											</div>
										<?php endif; ?>
									</div>

									<hr>
						
									<div class="product-total" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
										<p><?php _e( 'Total including VAT:', 'kare' ); ?></p>
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
													echo sprintf( __( 'Congratulations! You have just saved %s, which is %s !', 'kare' ), wc_price( $savings ), $percentage_class );
												?>
											</p>
										</div>	
									<?php endif; ?>
								</div>
								<?php
							}
						}

						do_action( 'woocommerce_review_order_after_cart_contents' );
						?>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<!-- <thead>
		<tr>
			<th class="product-name"><?php // esc_html_e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-total"><?php // esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
		</tr>
	</thead> -->
	<!-- <div>
		<?php /*
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				*/?>
				<tr class="<?php //echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<td class="product-name">
						<?php //echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
						<?php //echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php //echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
					<td class="product-total">
						<?php //echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
				</tr>
				<?php
			//}
		//}

		//do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</div> -->
	<section class="summary-wrapper">
		
		<h5 class="summary"><?php echo esc_html_e('summary', 'kare'); ?></h5>
		<p class="comment_txt"><?php echo get_field('comment_txt_checkout','option'); ?></p>

		<div class="cart-subtotal">
			<p><?php esc_html_e( 'Order total:', 'kare' ); ?></p>
			<p><?php wc_cart_totals_subtotal_html(); ?></p>
		</div>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<p><?php wc_cart_totals_coupon_label( $coupon ); ?></p>
				<p><?php wc_cart_totals_coupon_html( $coupon ); ?></p>
		</div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php //wc_cart_totals_shipping_html(); ?>

			<div class="checkout-shipping-totals shipping">
				<p><?php esc_html_e( 'Shipping cost:', 'kare' ); ?></p>
				<p class="shipping-cost" data-title="<?php esc_attr_e( 'Shipping', 'kare' ); ?>">
					<?php echo wc_price( WC()->cart->get_shipping_total() ); ?>
				</p>
			</div>
			
			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="fee">
				<p><?php echo esc_html( $fee->name ); ?></p>
				<p><?php wc_cart_totals_fee_html( $fee ); ?></p>
			</div>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<p><?php echo esc_html( $tax->label ); ?></p>
						<p><?php echo wp_kses_post( $tax->formatted_amount ); ?></p>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="tax-total">
					<p><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></p>
					<p><?php wc_cart_totals_taxes_total_html(); ?></p>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
		<hr>
		<div class="order-total">
			<p><?php esc_html_e( 'Order total including VAT:', 'kare' ); ?></p>
			<p class="price-order-total"><?php wc_cart_totals_order_total_html(); ?></p>
		</div>



		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</section>
	<section class="another-data">
		<h5><?php _e( 'Do you need help?', 'kare' ); ?></h5>
		<button type="button" aria-label="link" class="contact_mail">
			<a aria-label="link" href="mailto:<?php echo get_field('contact_email','option'); ?>"  target="_blank"><?php _e( 'Contact us', 'kare' ); ?></a>
		</button>

		<hr>
		
		<h5><?php _e( 'Useful links', 'kare' ); ?></h5>
		<button type="button" aria-label="link" class="back_to back_to_shop">
			<a aria-label="link" href="<?php echo wc_get_cart_url(); ?>"  target=""><?php _e( 'Back to shopping cart', 'kare' ); ?></a>
		</button>
		<button type="button" aria-label="link" class="back_to back_to_home_page">
			<a aria-label="link" href="<?php echo home_url(); ?>"  target=""><?php _e( 'Back to home page', 'kare' ); ?></a>
		</button>
		<?php
			// $privacy_policy_page_id = get_option('wp_page_for_privacy_policy'); 
			// $privacy_policy_url = get_permalink($privacy_policy_page_id);
		?>
		<button type="button" aria-label="link" class="back_to back_to_privact">
			<a aria-label="link" href="<?php echo home_url(); ?>"  target=""><?php _e( 'Terms and Conditions', 'kare' ); ?></a>
		</button>
		<button type="button" aria-label="link" class="back_to back_to_faq">
			<a aria-label="link" href="<?php home_url(); ?>"  target=""><?php _e( 'Faq', 'kare' ); ?></a>
		</button>

	</section>
</div>

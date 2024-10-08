<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;

// Retrieve order ID from order confirmation page
$order_id = get_query_var('order-received'); 

// Get the order object
$order = wc_get_order($order_id); 
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

				<?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>

			</div>

			<h2 class="wc-order-title"> <?php _e( 'YOUR ORDER', 'woocommerce' ); ?></h2>


		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>

	<?php endif; ?>

</div>
<div class="woocommerce-review-order">
	<div class="woocommerce-review-order-wrapper">
		<section class="wc-order-mini">
			<div class="order-header">
				<h5 class="order-title"><?php esc_html_e( 'Order Summary', 'woocommerce' ); ?></h5>
				<div class="order-pdts">
					<button aria-label="button" type="button" alt="show checkout products" id="show_pdts_recived" class="toggle-cart">
						<span><?php _e( 'Show products', 'woocommerce' ); ?></span>
						<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-down.svg'); ?>
					</button>
					<div class="wc-order-mini-wrapper">
						<div class="product">
							<?php
							foreach ( $order->get_items() as $item_id => $item ) {
								$product = $item->get_product(); // Get the product object
								$quantity = $item->get_quantity();
								$product_price = $item->get_total() / $quantity;

								if ( $product && $product->exists() ) {
								$product_permalink = $product->is_visible() ? $product->get_permalink() : ''; ?>

									<div class="order-item">
										<div class="order-product-thumbnail">
											<?php
											$thumbnail = $product->get_image() ?>
											
											<?php if ( ! $product_permalink ) { 
												echo $thumbnail; // PHPCS: XSS ok.
											} else {
												printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
											}
											?>
										</div>
										<div class="product-name">
											<?php echo wp_kses_post( $product->get_name() ) . '&nbsp;<strong class="product-quantity">×' . $item->get_quantity() . '</strong>' ?>
										</div>
										<?php
											$stock = ($product->get_stock_quantity() > 0) ? 'Immediately available' : '60 business days';
										?>
										<div class="delivery_and_price_wrapper">
											<div class="product-delivery">
												<p><?php _e( 'Delivery time:', 'woocommerce' ); ?> </p>
												<p class="shipping_availability bold <?php echo ($product->get_stock_quantity() > 0) ? 'stock' : ''; ?>"><?php echo esc_html( $stock ); ?></p>
											</div>

											<?php if ( $product->is_on_sale() ) : ?>
												<div class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
													<p><?php _e( 'Unit price:', 'woocommerce' ); ?> </p>
													<p><?php _e( 'RRP*: ', 'woocommerce' ); ?> 
														<del> <?php echo wc_price( $product->get_regular_price() ); ?> </del>
													</p>
												</div>
												<div class="product-price-discont" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
													<p><?php _e( 'Price after discount:', 'woocommerce' ); ?> </p>
													<p class="bold red">
														<?php
															echo wc_price( $product_price ); // PHPCS: XSS ok.
														?>
													</p>
												</div>
											<?php else : ?>
												<div class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
													<p><?php _e( 'Unit price:', 'woocommerce' ); ?> </p>
													<p><?php
														echo wc_price( $product_price ); // PHPCS: XSS ok.
													?></p>
												</div>
											<?php endif; ?>
										</div>

										<hr>
							
										<div class="product-total" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
											<p><?php _e( 'Total including VAT:', 'woocommerce' ); ?></p>
											<p class="bold red"><?php
												echo wc_price( $item->get_total()); // PHPCS: XSS ok.
											?></p>
										</div>

										<?php if ( $product->is_on_sale() ) : ?>
											<div class="product-discount-message">
												<p>
													<?php
														$regular_price = $product->get_regular_price();
														$sale_price = $product->get_sale_price();
														$savings = $regular_price - $sale_price;
														$percentage = round( ( $savings / $regular_price ) * 100 );
														
														$percentage_class = '<span class="discount-percentage">' . $percentage . '%</span>';
														echo sprintf( __( 'Congratulations! You have just saved %s, which is %s !', 'woocommerce' ), wc_price( $savings ), $percentage_class );
													?>
												</p>
											</div>	
										<?php endif; ?>
									</div>
									<?php
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="wc-order-total">
			<h5 class="summary"><?php echo esc_html_e('summary', 'kare'); ?></h5>
			<p class="comment_txt"><?php echo get_field('comment_txt_checkout','option'); ?></p>
			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

				<li class="woocommerce-order-overview__order order">
					<?php esc_html_e( 'Order number:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<li class="woocommerce-order-overview__date date">
					<?php esc_html_e( 'Date:', 'woocommerce' ); ?>
					<strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<li class="woocommerce-order-overview__email email">
						<?php esc_html_e( 'Email:', 'woocommerce' ); ?>
						<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>
				<?php endif; ?>

				<li class="woocommerce-order-overview__total total">
					<?php esc_html_e( 'Total:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<?php if ( $order->get_payment_method_title() ) : ?>
					<li class="woocommerce-order-overview__payment-method method">
						<?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
						<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
					</li>
				<?php endif; ?>

			</ul>
		</section>
	</div>
</div>



<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h5><?php esc_html_e( 'Summary', 'woocommerce' ); ?></h5>

	<table cellspacing="0" class="shop_table shop_table_responsive">

		<?php 
			$cart_total_before_discounts = 0;

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$product = $cart_item['data'];
				$quantity = $cart_item['quantity'];

				$regular_price = $product->get_regular_price();

				$cart_total_before_discounts += $regular_price * $quantity;
			}
		?>

		<tr class="cart-subtotal">
			<th><?php esc_html_e( 'Order total:', 'woocommerce' ); ?></th>
			<td data-title="<?php esc_attr_e( 'Order total', 'woocommerce' ); ?>">
				<?php echo wc_price( $cart_total_before_discounts ); ?>
			</td>
		</tr>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php //wc_cart_totals_shipping_html(); ?>

			<tr class="cart-shipping-totals shipping">
				<th><?php esc_html_e( 'Shipping:', 'woocommerce' ); ?></th>
				<td class="shipping-message" data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>">
				<?php esc_html_e( 'Shipping cost calculation on the next page', 'woocommerce' ); ?>
				</td>
			</tr>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php //elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<!-- <tr class="shipping">
				<th><?php // esc_html_e( 'Delivery costs:', 'woocommerce' ); ?></th>
				<td data-title="<?php  // esc_attr_e( 'Delivery costs', 'woocommerce' ); ?>">
					<?php // woocommerce_shipping_calculator(); ?>
				</td>
			</tr> -->

		<?php endif; ?>

		<?php 
			$discount_total = 0;
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				$product = $cart_item['data'];
				$regular_price = $product->get_regular_price();  
				$sale_price = $product->get_sale_price();

				if ( $sale_price && $regular_price > $sale_price ) {
					$discount_total += ( $regular_price - $sale_price ) * $cart_item['quantity'];
				}
			}
		?>

		<?php if ( WC()->cart->get_discount_total() > 0 ) : ?>
            <tr class="discount">
                <th><?php _e( 'Discount:', 'woocommerce' ); ?></th>
                <td data-title="<?php esc_attr_e( 'Discount', 'woocommerce' ); ?>">
                    <?php echo '-' . wc_price( WC()->cart->get_discount_total() ); ?>
                </td>
            </tr>		
		<?php elseif ( $discount_total > 0 ) : ?>
			<tr class="discount">
				<th><?php _e( 'Discount:', 'woocommerce' ); ?></th>
				<td data-title="<?php esc_attr_e( 'Discount', 'woocommerce' ); ?>">
					<?php echo '-' . wc_price( $discount_total ); ?>
				</td>
			</tr>
        <?php endif; ?>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				/* translators: %s location. */
				$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			}

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
						<td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
					<td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr class="separator">
			<td class="hr"></td>
		</tr>

		<tr class="order-total">
			<th><?php esc_html_e( 'Order total including VAT:', 'woocommerce' ); ?></th>
			<td data-title="<?php esc_attr_e( 'Order total including VAT', 'woocommerce' ); ?>">
				<?php
					$cart_total = WC()->cart->get_total('edit');
					$shipping_total = WC()->cart->get_shipping_total();
					$total_without_shipping = floatval($cart_total) - floatval($shipping_total);
					echo wc_price($total_without_shipping);
				?>
			</td>
		</tr>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</table>
</div>

<div class="wc-proceed-to-checkout">
	<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
</div>

<?php do_action( 'woocommerce_after_cart_totals' ); ?>



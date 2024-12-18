<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 8.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = 'right';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();

?>
<div class="shipping_method_order" style="margin-bottom:40px;">
	<p><strong><?php echo 'סוג המשלוח:'; ?></strong></p>
	<span style="display: block;">
		<?php echo ($order->get_shipping_method() == 'Local pickup') ? 'איסוף עצמי ממשרדי החברה בתיאום מראש.' : 'משלוח ע"י שליח עד הבית.'; ?>
	</span>
</div>

<table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding:0;" border="0">
	<tr>
		<td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">
			<p><strong><?php echo 'פרטים לחיוב:'; ?></strong></p>

			<address class="address" style="padding: 0 !important; border: none; font-style: normal;">
				<?php if ($address) : ?>
					<?php if ( $order->get_billing_first_name() ) : ?>
						<p><strong><?php echo 'שם:'; ?></strong> <?php echo esc_html( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></p>
					<?php endif; ?>
					<?php if ( $order->get_billing_address_1() ) : ?>
						<p><strong><?php echo 'כתובת:'; ?></strong> <?php echo esc_html( $order->get_billing_address_1() ); ?></p>
					<?php endif; ?>
					<?php if ( $order->get_billing_city() ) : ?>
						<p><strong><?php echo 'עיר:'; ?></strong> <?php echo esc_html( $order->get_billing_city() ); ?></p>
					<?php endif; ?>
					<?php if ( $order->get_billing_phone() ) : ?>
						<p><strong><?php echo 'טלפון:'; ?></strong> <?php echo wc_make_phone_clickable( $order->get_billing_phone() ); ?></p>
					<?php endif; ?>
					<?php if ( $order->get_billing_email() ) : ?>
						<p><strong><?php echo 'אימייל:'; ?></strong> <?php echo esc_html( $order->get_billing_email() ); ?></p>
					<?php endif; ?>
				<?php else : 
					esc_html__( 'N/A', 'woocommerce' );
				endif; ?>
				<?php
				/**
				 * Fires after the core address fields in emails.
				 *
				 * @since 8.6.0
				 *
				 * @param string $type Address type. Either 'billing' or 'shipping'.
				 * @param WC_Order $order Order instance.
				 * @param bool $sent_to_admin If this email is being sent to the admin or not.
				 * @param bool $plain_text If this email is plain text or not.
				 */
				do_action( 'woocommerce_email_customer_address_section', 'billing', $order, $sent_to_admin, false );
				?>
			</address>
		</td>
		<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && $shipping ) : ?>
			<td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding:0;" valign="top" width="50%">
				<p><strong><?php echo 'כתובת למשלוח:'; ?></strong></p>

				<address class="address">
					<?php echo wp_kses_post( $shipping ); ?>
					<?php if ( $order->get_shipping_phone() ) : ?>
						<br /><?php echo wc_make_phone_clickable( $order->get_shipping_phone() ); ?>
					<?php endif; ?>
					<?php
					/**
					 * Fires after the core address fields in emails.
					 *
					 * @since 8.6.0
					 *
					 * @param string $type Address type. Either 'billing' or 'shipping'.
					 * @param WC_Order $order Order instance.
					 * @param bool $sent_to_admin If this email is being sent to the admin or not.
					 * @param bool $plain_text If this email is plain text or not.
					 */
					do_action( 'woocommerce_email_customer_address_section', 'shipping', $order, $sent_to_admin, false );
					?>
				</address>
			</td>
		<?php endif; ?>
	</tr>
</table>
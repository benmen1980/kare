<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p class="order_header">לקוח/ה יקר/ה,</p>

<p class="order_text">
	<p>תודה שבחרת לקנות ב-KARE ישראל!</p>
	<p>אנו שמחים להודיעך שהזמנתך התקבלה בהצלחה ומטופלת ברגעים אלו.</p>
</p>
<div class="order_details" style="margin-bottom:40px;">
	<p><strong>פרטי הזמנתך:</strong></p>
	<ul>
		<li style="margin-bottom: 4px;"><strong>מספר הזמנה:</strong> <?php echo $order->get_order_number(); ?></li>
		<li style="margin-bottom: 4px;"><strong>תאריך הזמנה:</strong> <?php echo wc_format_datetime( $order->get_date_created() ); ?></li>
		<li style="margin-bottom: 4px;"><strong>סך הכול:</strong> <?php echo $order->get_formatted_order_total(); ?></li>
	</ul>
</div>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

?>

<div class="additional_order_details" style="margin-bottom:40px;">
	<p><strong>מה הלאה?</strong></p>
	<ul>
		<li style="margin-bottom: 4px;">אנו נעדכן אותך ברגע שהזמנתך תצא לדרך ותשלח אליך.</li>
		<li style="margin-bottom: 4px;">בינתיים, אם יש לך שאלות או בקשות מיוחדות, אל תהסס/י לפנות אלינו.</li>
	</ul>
</div>
<div class="contact_details_order" style="margin-bottom:40px;">
	<p><strong>פרטי יצירת קשר:</strong></p>
	<span style="display: block; margin-bottom: 4px;">טלפון: 058-7997571</span>
	<span style="display: block; margin-bottom: 4px;">אימייל: <a href="mailto:cs@duke-gallery.com">cs@duke-gallery.com</a></span>
	<span style="display: block; margin-bottom: 4px;">כתובת אתר: <a href="https://www.kare.co.il">www.kare.co.il</a></span>
</div>

<?php

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
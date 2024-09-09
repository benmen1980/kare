var $=jQuery.noConflict();

jQuery(document).ready(function($){
    $('body').on('change', 'form.woocommerce-cart-form .qty', function() {
        var $this = $(this);

        // Update the cart
        var data = {
            action: 'woocommerce_update_cart',
            security: woocommerce_params.update_cart_nonce,
            cart: {}
        };

        $('[name="cart[qty][]"]').each(function() {
            var qty = $(this).val();
            var item_key = $(this).closest('.cart_item').find('.remove').data('product_id');
            data.cart[item_key] = qty;
        });

        $.post(woocommerce_params.ajax_url, data, function(response) {
            window.location.reload(); // Refresh the cart page after the update
        });
    });
});
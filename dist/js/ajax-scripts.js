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

        // Custom AJAX Add to Cart for Single Product Page
       /* $('body').on('click', '.ajax_add_to_cart', function(e) {
            e.preventDefault();
            var $button = $(this),
                product_id = $button.data('product_id');
            
            $.ajax({
                type: 'POST',
                url: wc_add_to_cart_params.ajax_url,
                data: {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: product_id
                },
                beforeSend: function() {
                    // You can show a loading spinner here if needed
                },
                success: function(response) {
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    }
                    // Update the mini cart, etc.
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
                }
            });
        });*/
    
    
});
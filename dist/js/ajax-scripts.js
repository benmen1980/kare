var $=jQuery.noConflict();

jQuery(document).ready(function($){
    // $('body').on('change', 'form.woocommerce-cart-form .qty', function() {
    //     var $this = $(this);

    //     // Update the cart
    //     var data = {
    //         action: 'woocommerce_update_cart',
    //         security: woocommerce_params.update_cart_nonce,
    //         cart: {}
    //     };

    //     $('[name="cart[qty][]"]').each(function() {
    //         var qty = $(this).val();
    //         var item_key = $(this).closest('.cart_item').find('.remove').data('product_id');
    //         data.cart[item_key] = qty;
    //     });

    //     $.post(woocommerce_params.ajax_url, data, function(response) {
    //         window.location.reload(); // Refresh the cart page after the update
    //     });
    // });


    // Custom AJAX Add to Cart for Single Product Page
    $('.single_add_to_cart_button').click(function(e) { 
        e.preventDefault();
        
        var $thisbutton = $(this),
            $form = $thisbutton.closest('form.cart'), // מוצא את הטופס שקשור לכפתור
            product_id = $form.find('button[name=add-to-cart]').val(), // מזהה את ה-id של המוצר
            product_qty = $form.find('input.custom_select_hidden').val() || 1; // כמות המוצר (או 1 אם לא נבחרה כמות)
            variation_id = $form.find('input[name=variation_id]').val() || 0;

        var data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: product_id,
            quantity: product_qty,
            variant_id: variation_id,
        };
        // טריגר לאירוע הוספת מוצר לסל
        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);
        
        // קריאת AJAX להוספת המוצר לסל
        $.ajax({
            type: 'post',
            url: ajax_obj.ajax_url,
            data: data,
            success: function(response) {
                if (response.error) {
                    console.log("error");
                    $('.woocommerce-notices-wrapper').html(response.error_msg);
                    $(document.body).trigger('wc_fragment_refresh');
                    //window.location = response.product_url;
                    return;
                } else {
                    console.log(response);
                    // $('.woocommerce-notices-wrapper').html(response);
                    $('#wc-add-product-notices').html(response.data);
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                    // $('#modal_mini_cart').toggleClass('is_modal_showing');

                    $('#modal_mini_cart').addClass('active');
                    if ($('#overlay').length === 0) {
                        $('body').append('<div id="overlay"></div>');
                        $('body').css('overflow', 'hidden');
                    }
                    $('#overlay').fadeIn(); // Show the overlay

                    // Activating the mini-cart refresh
                    $.ajax({
                        url: wc_add_to_cart_params.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'woocommerce_get_refreshed_fragments'
                        },
                        success: function(response) {
                            // עדכון המיני-קארט עם הפרגמנטים החדשים
                            if (response && response.fragments) {
                                $.each(response.fragments, function(key, value) {
                                    $(key).replaceWith(value);
                                });
                            }
                        }
                    });
                    
                    $(document.body).trigger('wc_fragment_refresh');

                    // alert('המוצר נוסף לעגלת הקניות!');
                }
            },
        });
    });
    
       
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
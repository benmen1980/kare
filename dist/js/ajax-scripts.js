var $=jQuery.noConflict();

jQuery(document).ready(function($){
    /*$('body').on('change', 'form.woocommerce-cart-form .qty', function() {
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
    });*/


    // Custom AJAX Add to Cart for Single Product Page
    $('.single_add_to_cart_button').click(function(e) { 
        e.preventDefault();
        
        var $thisbutton = $(this),
            $form = $thisbutton.closest('form.cart'), 
            product_id = $form.find('button[name=add-to-cart]').val(),
            product_qty = $form.find('input.custom_select_hidden').val() || 1; 
            variation_id = $form.find('input[name=variation_id]').val() || 0;

        var data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: product_id,
            quantity: product_qty,
            variant_id: variation_id,
        };
        // Triggering an "adding_to_cart" event
        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);
        
        // AJAX call to add the product to the car
        $.ajax({
            type: 'post',
            url: ajax_obj.ajax_url,
            data: data,
            success: function(response) {
                if (response.data && response.data.error) {
                    // Handle error case: Show error message and do not open mini-cart
                    console.log("Error adding product to cart:", response.data.error_msg);
                    $('.woocommerce-notices-wrapper').html(response.data.error_msg);
                    $(document.body).trigger('wc_fragment_refresh');
                    return; // Exit to prevent opening the mini-cart
                }

                // Success: Handle mini-cart logic
                console.log("Product added successfully:", response);
                $('#wc-add-product-notices').html(response.data);
                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                // $('#modal_mini_cart').toggleClass('is_modal_showing');

                $('#modal_mini_cart').addClass('active');
                if ($('#overlay').length === 0) {
                    $('body').append('<div id="overlay"></div>');
                    $('body').css('overflow', 'hidden');
                }
                $('#overlay').fadeIn(); // Show the overlay

                // Refresh mini-cart content
                $.ajax({
                    url: wc_add_to_cart_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'woocommerce_get_refreshed_fragments'
                    },
                    success: function(response) {
                        if (response && response.fragments) {
                            $.each(response.fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });
                        }
                    }
                });
                
                $(document.body).trigger('wc_fragment_refresh');
            },
            error: function(xhr, status, error) {
                // Handle unexpected errors gracefully
                console.error("Unexpected error during add-to-cart AJAX:", error);
                $('.woocommerce-notices-wrapper').html('<div class="woocommerce-error">An unexpected error occurred. Please try again.</div>');
            }
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

jQuery(document).ready(function($){
    setupCityAutocomplete('billing_city', 'billing_city_field');
    setupCityAutocomplete('shipping_city', 'shipping_city_field');

    function setupCityAutocomplete(fieldId, fieldWrapper) {
        $('#' + fieldId).autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: 'post',
                    url: ajax_obj.ajax_url,
                    data: {
                        'action': 'autocomplete_city',
                        city: request.term
                    },
                    success: function(data) {
                        response(data.results);
                    }
                });
            },
            create: function() {
                $(this).data('ui-autocomplete')._renderItem = function(ul, item) {
                    $(ul).addClass('for_city_autocomplete');
                    return $('<li class="ui-menu-item" data-id = "' + item.id + '"><div tabindex="-1" class="ui-menu-item-wrapper">' + item.value + '</div></li>').appendTo(ul);
                };
            },
            appendTo: "#" + fieldWrapper,
            minLength: 2,
            autofocus: true,
            select: function(event, ui) {
                event.stopPropagation();
                if (!ui.item) {
                    $(this).val('').attr('data-id', '');
                } else {
                    $(this).val(ui.item.value).attr('data-id', ui.item.id);
    
                    $('#' + fieldId).val(ui.item.value).attr('value', ui.item.value)
                    .attr('data-id', ui.item.id);
    
                    $('body').trigger('update_checkout');
                    $('form.checkout').trigger('change');
                }
                return false;
            },
            change: function(event, ui) {
                var selfInput = $(this);
                if (!ui.item) {
                    var writtenItem = new RegExp("^" + $.ui.autocomplete.escapeRegex($(this).val().toLowerCase()) + "$", "i"),
                        valid = false;
    
                    $('ul.for_city_autocomplete').children("li").each(function() {
                        if ($(this).text().toLowerCase().match(writtenItem)) {
                            this.selected = valid = true;
                            selfInput.val($(this).text());
                            selfInput.attr('data-id', $(this).data('id'));
                            return false;
                        }
                    });
    
                    if (!valid) {
                        $(this).val('').attr('data-id', '');
                    }
                }
            }
        });
    }
});
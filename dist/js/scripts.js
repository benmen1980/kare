
var $=jQuery.noConflict();

jQuery(document).on("ready", function(){

    // console.log(typeof jQuery);  
    
    //add class to label in login form for adding css 
    // On input focus
    $('.form-row .input-text').focus(function() {
        $(this).closest('.form-row').find('label').addClass('focused');
    });

    // On input blur
    $('.form-row .input-text').blur(function() {
        // Remove the class if the input is empty
        if ($(this).val() === "") {
            $(this).closest('.form-row').find('label').removeClass('focused');
        }
    });

    // Check on page load if inputs have text
    $('.form-row .input-text').each(function() {
        if ($(this).val() !== "") {
            $(this).closest('.form-row').find('label').addClass('focused');
        }
    });   

    //logout with popup
    $('.custom_logout a').on('click', function(e) {
        e.preventDefault();
        console.log('click!');
        $(".logout_popup").show();
        // Handle the OK button click
    
        $('#confirm_logout').on('click', function() {
            var logout_href = $('.woocommerce-MyAccount-navigation-link--customer-logout a').attr('href');
            //console.log("🚀 ~ $ ~ logout_href:", logout_href);
            window.location.href = logout_href;
        });
        
        // Handle the Cancel button click
        $('#cancel_logout').on('click', function() {
            $(".logout_popup").hide();
        });
    })

    //Listens to the scroll of the surfer to add a clickable button to the top of the page
    let scrollThreshold = 1500;

    //Limiting the function time to only run every 100ms
    function throttle(fn, wait) {
        let time = Date.now();
        return function() {
            if ((time + wait - Date.now()) < 0) {
                fn.apply(this, arguments); // call to the function with the correct context and arguments
                time = Date.now();
            }
        }
    }

    //Remove placeholder text in form fields in checkout page
    jQuery(document).ready(function($) {
        $('form.checkout .form-row input, form.checkout textarea, form.checkout select').each(function() {
            $(this).removeAttr('placeholder');
        });
    });

    $(window).on('scroll', throttle(function() {
        if (window.innerWidth >= 1024) {

            let st = $(this).scrollTop();
            let btn = $('#btnSkipArrow');

            if (st >= scrollThreshold) {
                if (!btn.hasClass('show')) {
                    btn.css('display', 'block'); // Show the element before starting the transition
                    setTimeout(function() {
                        btn.removeClass('hide');
                        btn.addClass('show');
                    }, 10); // Small delay to ensure the display change is applied
                }
            } else {
                btn.removeClass('show');
                btn.addClass('hide');
            }
        }
    }, 100)); // Executes the function every 100ms

    //Clicking the button scrolls to the top of the page
    $('#btnSkipArrow').on('click', function() {
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    });

    //checks if the current page is a single-product
    if (document.body.classList.contains('single-product')) {

        //add the selected quantity of the product to the cart
        const selectWrapper = document.querySelector('.custom_select_wrapper');
        const selectTrigger = selectWrapper.querySelector('.btn_quantity_wrapper');
        const options = selectWrapper.querySelector('.custom_options');
        const hiddenInput = selectWrapper.querySelector('.custom_select_hidden');
        const selectedValue = selectWrapper.querySelector('.selected_value');

        selectTrigger.addEventListener('click', function() {
            options.classList.toggle('open');
        });

        options.addEventListener('click', function(e) {
            if (e.target.classList.contains('custom_option')) {
                hiddenInput.value = e.target.getAttribute('data-value');
                selectedValue.textContent = e.target.textContent;
                options.classList.remove('open');
                document.querySelectorAll('.custom_option').forEach(option => {
                    option.classList.remove('selected');
                });
                e.target.classList.add('selected');
            }
        });

        // Initialize the large gallery (main-slider)
        const largeGallery = new Swiper('.main-slider', {
            slidesPerView: 1,
            spaceBetween: 0,
            initialSlide: 0,
            loop: false,
            navigation: {
                nextEl: '.large-slider-nav-next',
                prevEl: '.large-slider-nav-prev',
            },
        });
        //largeGallery.update(); // Forces Swiper to recalculate slides

        // Initialize the small gallery (thumb-slider)
        const smallGallery = new Swiper('.thumb-slider', {
            slidesPerView: 9.15,
            spaceBetween: 20,
            navigation: {
                nextEl: '.small-slider-nav-next',
                prevEl: '.small-slider-nav-prev',
            },
        });
        //smallGallery.update(); // Forces Swiper to recalculate slides

        // Sync the active thumbnail border when the large gallery changes
        largeGallery.on('slideChange', function () {
            let activeIndex = largeGallery.activeIndex;
            if (activeIndex === 0) {
                activeIndex = 1; // Adjust based on your needs
            }
            
            // Remove 'active-thumbnail' class from all slides in the small gallery
            smallGallery.slides.forEach(slide => {
                slide.classList.remove('active-small-thumbnail');
            });

            // Add 'active-thumbnail' class to the corresponding slide in the small gallery
            if (smallGallery.slides[activeIndex]) {
                smallGallery.slides[activeIndex].classList.add('active-small-thumbnail');
            }

            // Scroll the small gallery to the correct thumbnail
            smallGallery.slideTo(activeIndex);
        });

        // Manually set the red border for the small gallery active slide
        smallGallery.slides.forEach((slide, index) => {
            slide.addEventListener('click', function () {
                // Update the active thumbnail in the small gallery
                smallGallery.slides.forEach(slide => {
                    slide.classList.remove('active-small-thumbnail');
                });
                slide.classList.add('active-small-thumbnail');

                // Slide the large gallery to the clicked thumbnail's corresponding slide
                largeGallery.slideTo(index);
            });
        });
    }

    // General Swiper Initialization (for other galleries)

    // Updated function to calculate slidesPerView dynamically based on a given slide width
    // Function to calculate slidesPerView dynamically based on a fixed slide width
    function calculateSlidesPerView(containerWidth, slideWidth, spaceBetween) {
        return containerWidth / (slideWidth + spaceBetween);
    }

    // Define an object with IDs and their corresponding widths
    const slideWidths = {
        'rounded_btn': 144, 
        'module_slider_pdts': 266, 
        'module_pdts_content': 198,
        'module_slide_cube': 395,
        'slide_same_cat_pdts': 233,
        'slide_bestseller_cat_pdts': 233,
        'slide_bestseller_products': 233,
    };

    var defaultSwiperOptions = {
        slidesPerGroup: 1,
        spaceBetween: 3,
        centerInsufficientSlides: true,
        breakpoints: {
            1024: { 
                slidesPerView: 8.5,
                slidesPerGroup: 1,
            }
        },
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true,
            renderBullet: function (index, className) {
                return '<span class="' + className + '"></span>';
            },
        },
        navigation: {
            nextEl: '.swiper-nav-next',
            prevEl: '.swiper-nav-prev',
        },
        on: {
            init: function () {
                // Get the parent element's ID
                const parentId = this.el.querySelector('.swiper-wrapper').id; 
                let slideWidth = slideWidths[parentId] || 192; 
                const containerWidth = this.el.clientWidth;

                // Set slides per view based on width and space
                this.params.slidesPerView = calculateSlidesPerView(containerWidth, slideWidth, this.params.spaceBetween);

                this.update();
                updatePagination(this);  // Call pagination update
            },
            slideChange: function () {
                updatePagination(this); // Update pagination on slide change
            },
            resize: function () {
                const parentId = this.el.querySelector('.swiper-wrapper').id; 
                let slideWidth = slideWidths[parentId] || 192; // Defaults to 192 if ID not in slideWidths
                const containerWidth = this.el.clientWidth;

                // Recalculate slidesPerView on resize
                this.params.slidesPerView = calculateSlidesPerView(containerWidth, slideWidth, this.params.spaceBetween);

                this.update();
                updatePagination(this); // Update pagination on resize
            },
           
        }
    };

    var swiperContainers = document.querySelectorAll('.swiper-container');

    swiperContainers.forEach(function(container) {
        var swiperOptions = Object.assign({}, defaultSwiperOptions);

        if (container.querySelector('#rounded_btn')) {
            swiperOptions.spaceBetween = 8;
        } else if (container.querySelector('#module_slider_pdts')) {
            swiperOptions.spaceBetween = 8;
        } else if (container.querySelector('#module_pdts_content')) {
            swiperOptions.spaceBetween = 2;
            swiperOptions.centerInsufficientSlides = false;
        } else if (container.querySelector('#module_slide_cube')) {
            swiperOptions.spaceBetween = 16;
        } else if (container.querySelector('.more_slide_products')) {
            swiperOptions.spaceBetween = 2;
        }

        if (swiperOptions) {
            new Swiper(container, swiperOptions);
            console.log(container);
            console.log(swiperOptions);
        }
    });

    function updatePagination(swiper) {
        var paginationEl = swiper.pagination.el;
        if (Array.isArray(paginationEl)) {
            paginationEl = paginationEl[0];
        }
        if (!paginationEl || !paginationEl.querySelectorAll) {
            return;
        }
        var bullets = paginationEl.querySelectorAll('.swiper-pagination-bullet');
        var activeIndex = swiper.activeIndex;
        bullets.forEach((bullet, index) => {
            var distance = Math.abs(activeIndex - index);
            var size = 16 - (distance * 5);
            size = size < 4 ? 4 : size;
            bullet.style.width = size + 'px';
            bullet.style.height = size + 'px';
            bullet.style.opacity = 0.5 + (0.5 / (distance + 1));
        });
    }

    const swiper = new Swiper('.similar-pdt-swiper', {
        slidesPerView: 4,
        spaceBetween: 20,
    });

    //open account popup in header
    $('.open_account_popup').on('click', function() {
        $('#login_sidebar').addClass('active');
        if ($('#overlay').length === 0) {
            $('body').append('<div id="overlay"></div>');
            $('body').css('overflow', 'hidden');
        }
        $('#overlay').fadeIn(); // Show the overlay
    });

    $('#close_sidebar').on('click', function() {
        $('#login_sidebar').removeClass('active');
        $('#overlay').fadeOut(function() {
            $(this).remove(); // Remove overlay after fade out
        });
        $('body').css('overflow', '');
    });
    
    // Close the sidebar if clicked outside
    $(document).mouseup(function(e) {
        var container = $("#login_sidebar");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.removeClass('active');
            $('#overlay').fadeOut(function() {
                $(this).remove(); // Remove overlay after fade out
            });
            $('body').css('overflow', '');
        }
    });

    //open wishlist popup in header
    $('.open_wishlist_popup').on('click', function() {
        $('#wishlist_sidebar').addClass('active');
        if ($('#overlay').length === 0) {
            $('body').append('<div id="overlay"></div>');
            $('body').css('overflow', 'hidden');
        }
        $('#overlay').fadeIn(); // Show the overlay
    });

    $('#close_wishlist').on('click', function() {
        $('#wishlist_sidebar').removeClass('active');
        $('#overlay').fadeOut(function() {
            $(this).remove(); // Remove overlay after fade out
        });
        $('body').css('overflow', '');
    });

    // Close the wishlist sidebar if clicked outside
    $(document).mouseup(function(e) {
        var container = $("#wishlist_sidebar");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.removeClass('active');
            $('#overlay').fadeOut(function() {
                $(this).remove(); // Remove overlay after fade out
            });
            $('body').css('overflow', '');
        }
    });

    //open mobile menu
    if ($(window).width() < 1024) {
         $('button.menu-toggle').on('click', function() {
            $('#mobile_menu_sidebar').addClass('active_left');
            if ($('#overlay').length === 0) {
                $('body').append('<div id="overlay"></div>');
                $('body').css('overflow', 'hidden');
            }
            $('#overlay').fadeIn(); // Show the overlay
        });

        $('#close_menu').on('click', function() {
            $('#mobile_menu_sidebar').removeClass('active_left');
            $('#overlay').fadeOut(function() {
                $(this).remove(); // Remove overlay after fade out
            });
            $('body').css('overflow', '');
        });     

        // Close the wishlist sidebar if clicked outside
        $(document).mouseup(function(e) {
            var container = $("#mobile_menu_sidebar");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.removeClass('active_left');
                $('#overlay').fadeOut(function() {
                    $(this).remove(); // Remove overlay after fade out
                });
                $('body').css('overflow', '');
            }
        });

        // Open sub-menu on clicking main menu item
        $('#primary-menu > li > a').on('click', function(event) {
            event.preventDefault(); 

            const parentLi = $(this).parent();
            const submenu = parentLi.find('ul.sub-menu');
        
            if (parentLi.hasClass('active-nemu')) {

                submenu.stop().animate({ left: '500px', opacity: 0 }, 300, function() {
                    submenu.removeClass('sub-menu-active'); 
                });
                parentLi.removeClass('active-nemu');

            } else {
                $('#primary-menu > li.active-nemu').each(function() {
                    const activeSubmenu = $(this).find('ul.sub-menu.sub-menu-active');
                    $(this).removeClass('active-nemu');
                    activeSubmenu.stop().animate({ right: '-500px', opacity: 0 }, 300, function() {
                        activeSubmenu.removeClass('sub-menu-active');
                    });
                });
        
                // open sub-menu
                parentLi.addClass('active-nemu');
                $(this).css({ left: '-500px', opacity: 0 })
                       .stop().animate({ left: '0', opacity: 1 }, 300);
                
                submenu.addClass('sub-menu-active')
                       .css({ left: '-500px', opacity: 0 })
                       .stop().animate({ left: '0', opacity: 1 }, 300);
            }
        });

         // Close submenu on back arrow or close icon click
        $('#primary-menu > li > a::after', '#primary-menu > li > a::before').on('click', function() {
            const parentLi = $(this).closest('li');
            const submenu = parentLi.find('ul.sub-menu');

            submenu.css({ right: '0', opacity: 1 })
                .stop().animate({ right: '-500px', opacity: 0 }, 300, function() {
                submenu.removeClass('sub-menu-active');
            });
            parentLi.removeClass('active-nemu');
        });

        $('.visible-mobile').append('<p class="add_text">...or discover categories:</p>');
        $('.visible-mobile a').text('show all');
    }


    // Close mini-cart popup when clicking close button or clicking outside
    $('#close_mini_cart').on('click', function() {
        $('#modal_mini_cart').removeClass('active');
        $('#overlay').fadeOut(function() {
            $(this).remove(); // Remove overlay after fade out
        });
        $('body').css('overflow', '');
    });
    
    $(document).mouseup(function(e) {
        var miniCartContainer = $("#modal_mini_cart");

        if (!miniCartContainer.is(e.target) && miniCartContainer.has(e.target).length === 0) {
            miniCartContainer.removeClass('active');
            $('#overlay').fadeOut(function() {
                $(this).remove(); // Remove overlay after fade out
            });
        }
    });


    //open account popup to write product reviews
    $('.open_account_button').click(function(e) {
        e.preventDefault(); // Prevents the default action of the link
        $('.open_account_popup').trigger('click'); // Triggers the popup
    });

    //open account popup for url with panel=account
    function getQueryParam(param) {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Check if 'panel' parameter equals 'account'
    if (getQueryParam('panel') === 'account') {
        $('.open_account_popup').trigger('click');
        //remove panel=account from url
        var url = new URL(window.location.href);
        console.log("🚀 ~ jQuery ~ url:", url);
        url.searchParams.delete('panel');
        window.history.replaceState({}, document.title, url.pathname + url.search);
    }
    
    // opening and closing respectively of an accordion by clicking on the class "accordion_item" 
    $('.accordion_question').click(function() {
        var accordionItem = $(this).closest('.accordion_item');
        var accordionContent = accordionItem.find('.accordion_content');

        // Toggle the active class and the visibility of the content
        accordionContent.stop(true, true).slideToggle();
        accordionContent.toggleClass('active');     

        // rotation of the arrow 
       $(this).find('svg').toggleClass('rotate180');
    });

    //Added a class to wishlist_btn if it is in the list
    document.querySelectorAll('.delete_item').forEach(function(element) {
        element.closest('.wishlist_btn').classList.add('wishlist_btn_black');
    });

    $(document).on('click', '.yith-wcwl-add-button a', function(e) {
        e.preventDefault();
        
        var $wishlistButton = $(this).closest('.wishlist_btn');
    
        if ($wishlistButton.hasClass('wishlist_btn_black')) {
            $wishlistButton.removeClass('wishlist_btn_black');
        } else {
            $wishlistButton.addClass('wishlist_btn_black');
        }
    });

    // Capture the "Enter" keypress in the search input field
    $('#search-input').on('keypress', function(event) {
        if(event.which === 13) { // 13 is the Enter key
            event.preventDefault(); // Prevent the default form submission
            $('#search-button').click(); // Trigger the hidden button click
        }
    });

    $('.dropdown-toggle').on('click', function() {
        $(this).siblings().slideToggle();
        $(this).siblings().toggleClass('active-menu'); 

        $(this).toggleClass('rotate180');
    });

    // Check if the current page is the cart page
    if (window.location.href.indexOf("cart") > -1) {

        // disable the coupon button, if the input field is empty
        const couponInputs = document.querySelectorAll('.coupon_code'); // Select all coupon code input fields
        const applyButtons = document.querySelectorAll('.coupon_button'); // Select all related apply buttons

        couponInputs.forEach(function (couponInput, index) {
            applyButtons[index].classList.add('disable');

            couponInput.addEventListener('input', function () {
                if (couponInput.value.trim() === '') {
                    applyButtons[index].setAttribute('disabled', true);
                    applyButtons[index].classList.add('disable');
                } else {
                    applyButtons[index].removeAttribute('disabled');
                    applyButtons[index].classList.remove('disable');
                }
            });
        });
    }

    // Check if the current page is the checkout page
    if (window.location.href.indexOf('checkout') > -1) {
        //Open the product display in the checkout form
        function toggleCart() {
            $('#show_pdts').on('click', function() {
                $(this).siblings('.wc-cart-mini-wrapper').toggleClass('open-cart');
                
                // rotation of the arrow 
                $(this).find('svg').toggleClass('rotate180');
            });
        }
    
        toggleCart();
    
        $(document.body).on('updated_checkout', function() {
            toggleCart();
        });

    }

    // Check if the current page is the order recived page
    if (window.location.href.indexOf("order-received") !== -1) {
        console.log("This is the order confirmation page.");

        function toggleCartOrder() {
            $('#show_pdts_recived').on('click', function() {
                $(this).siblings('.wc-order-mini-wrapper').toggleClass('open-cart');
                
                // rotation of the arrow 
                $(this).find('svg').toggleClass('rotatesvg');
            });
        }

        toggleCartOrder();

        $(document.body).on('updated_checkout', function() {
            toggleCartOrder();
        });
    }

    document.querySelector('.share_wishlist_btn').addEventListener('click', function() {
        var copyTarget = document.querySelector('.copy-target').value;
        
        navigator.clipboard.writeText(copyTarget).then(function() {
            var notification = document.getElementById('copy-notification');
            notification.style.display = 'block';
            notification.style.opacity = '1';
            
            setTimeout(function() {
                notification.style.opacity = '0';
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 500); 
            }, 5000);
        });   
        
    });

});

// Management of a drop-down menu with a click, and automatic checking of the display location
$(document).ready(function() {
    const $dropdownButton = $('.pagination-current');
    const $dropdownMenu = $('.pagination-options');

    if ($dropdownButton.length && $dropdownMenu.length) {
        $dropdownButton.on('click', function(event) {
            event.stopPropagation();

            $dropdownMenu.toggleClass('open');

            const buttonRect = $dropdownButton[0].getBoundingClientRect();
            const dropdownHeight = $dropdownMenu.outerHeight();

            if (buttonRect.bottom + dropdownHeight > $(window).height()) {
                $dropdownMenu.addClass('open-up');
            } else {
                $dropdownMenu.removeClass('open-up');
            }
        });

        // Close the menu
        $(document).on('click', function(event) {
            if (!$dropdownButton.is(event.target) && !$dropdownMenu.is(event.target) && $dropdownMenu.has(event.target).length === 0) {
                $dropdownMenu.removeClass('open'); // הסרת הכלאס כאשר לוחצים מחוץ לרשימה
            }
        });
    }
});


/*// After loading Swiper, check the number of slides
document.addEventListener('DOMContentLoaded', function () {
    // Find all elements with same ID
    var swiperWrappers = document.querySelectorAll('#module_slider_pdts');

    // Iterate over each found element
    swiperWrappers.forEach(function(swiperWrapper) {
        var slides = swiperWrapper.querySelectorAll('.swiper-slide');

        if (slides.length < 6) {
            swiperWrapper.style.justifyContent = 'center';
        }
        if (slides.length >= 6) {
            swiperWrapper.style.justifyContent = 'flex-start';
        }
    });
});*/





var $=jQuery.noConflict();

console.log('hiii');
document.addEventListener('DOMContentLoaded', function () {
    if (!document.body.classList.contains('rtl')) {
        document.documentElement.setAttribute('dir', 'ltr');
    }
});

jQuery(document).on("ready", function(){
    //add class to label in login form for adding css 
    // On input focus
    $('.form-row .input-text').focus(function() {
        $(this).closest('.form-row').find('label').addClass('focused');
    });

    $('.form-row .input-text').each(function () {
        if ($(this).val()) {
            $(this).closest('.form-row').find('label').addClass('focused');
        }
    });

    // On input blur
    $('.form-row .input-text').blur(function() {
        if( $(this).val() ) {
            $(this).closest('.form-row').find('label').addClass('focused');
        }
    
        // Remove the class if the input is empty
        if ($(this).val() === "") {
            $(this).closest('.form-row').find('label').removeClass('focused');
        }
        else{
            $(this).closest('.form-row').find('label').addClass('focused');
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
    if (document.body.classList.contains('single-product') || document.body.classList.contains('page-template-module-in-content')) {

        //add the selected quantity of the product to the cart
        const selectWrapper = document.querySelector('.custom_select_wrapper');
        if (selectWrapper) {
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
        }


        //largeGallery.update(); // Forces Swiper to recalculate slides

        // Initialize the small gallery (thumb-slider)
        const smallGallery = new Swiper('.thumb-slider', {
            slidesPerView: 3.5,
            spaceBetween: 20,
            breakpoints: {
                800: { 
                    slidesPerView: 4,
                },
                1024: { 
                    slidesPerView: 6,
                },
                1440: { 
                    slidesPerView: 9.15,
                }
            },
            navigation: {
                nextEl: '.small-slider-nav-next',
                prevEl: '.small-slider-nav-prev',
            },
            watchSlidesProgress: true,
        });
        const hasThreedSlide = document.querySelector('.threed-slide') !== null;
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
            thumbs: {
                swiper: smallGallery,
            },
            on: {
                slideChange: function () {
                     // Sync small gallery to the active index of the main gallery
                    const targetIndex = hasThreedSlide ? this.activeIndex + 1 : this.activeIndex;
                    smallGallery.slideTo(targetIndex);
                },
            },

        });

        // Exclude the 3D button slide from affecting the main gallery if it exists
        smallGallery.on('click', function (swiper) {
            const clickedSlide = swiper.slides[swiper.clickedIndex];
            if (!clickedSlide.classList.contains('threed-slide')) {
                // Adjust the index for largeGallery based on whether threed-slide exists
                const targetIndex = hasThreedSlide ? swiper.clickedIndex - 1 : swiper.clickedIndex;
                largeGallery.slideTo(targetIndex);
            }
        });

        // Ensure small gallery slides to match large gallery changes
        largeGallery.on('slideChange', function () {
            const activeIndex = this.activeIndex;
            const targetIndex = hasThreedSlide ? activeIndex + 1 : activeIndex; // Adjust for threed-slide
            smallGallery.slideTo(targetIndex);
        });

        // Prevent the threed-slide from being treated as the active thumbnail
        largeGallery.on('slideChange', function () {
            const activeIndex = this.activeIndex;
            smallGallery.slides.forEach((slide, index) => {
                // Adjust active state, accounting for whether threed-slide exists
                const isActive = hasThreedSlide
                    ? index === activeIndex + 1
                    : index === activeIndex;
                slide.classList.toggle('swiper-slide-thumb-active', isActive && !slide.classList.contains('threed-slide'));
            });
        });

        //smallGallery.update(); // Forces Swiper to recalculate slides

        // Sync the active thumbnail border when the large gallery changes
        // largeGallery.on('slideChange', function () {
        //     let activeIndex = largeGallery.activeIndex;
        //     if (activeIndex === 0) {
        //         activeIndex = 1; // Adjust based on your needs
        //     }
            
        //     // Remove 'active-thumbnail' class from all slides in the small gallery
        //     smallGallery.slides.forEach(slide => {
        //         slide.classList.remove('active-small-thumbnail');
        //     });

        //     // Add 'active-thumbnail' class to the corresponding slide in the small gallery
        //     if (smallGallery.slides[activeIndex]) {
        //         smallGallery.slides[activeIndex].classList.add('active-small-thumbnail');
        //     }

        //     // Scroll the small gallery to the correct thumbnail
        //     smallGallery.slideTo(activeIndex);
        // });

        // // Manually set the red border for the small gallery active slide
        // smallGallery.slides.forEach((slide, index) => {
        //     slide.addEventListener('click', function () {
        //         // Update the active thumbnail in the small gallery
        //         smallGallery.slides.forEach(slide => {
        //             slide.classList.remove('active-small-thumbnail');
        //         });
        //         slide.classList.add('active-small-thumbnail');

        //         // Slide the large gallery to the clicked thumbnail's corresponding slide
        //         largeGallery.slideTo(index);
        //     });
        // });
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
        simulateTouch: true,
        breakpoints: {
            1024: { 
                slidesPerView: 8.5,
                slidesPerGroup: 1,
            },
        },
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true,
            dynamicBullets: true,
            dynamicMainBullets: 1,  
            renderBullet: function (index, className) {
                return '<span class="' + className + '"></span>';
            },
        },
        scrollbar: {
            el: '.swiper-scrollbar',
            draggable: true,
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

                if (parentId === 'rounded_btn' && window.innerWidth < 768) {
                    slideWidth = 100;
                }

                // Set slides per view based on width and space
                this.params.slidesPerView = calculateSlidesPerView(containerWidth, slideWidth, this.params.spaceBetween);

                this.update();
            },
            resize: function () {
                const parentId = this.el.querySelector('.swiper-wrapper').id; 
                let slideWidth = slideWidths[parentId] || 192; // Defaults to 192 if ID not in slideWidths
                const containerWidth = this.el.clientWidth;

                if (parentId === 'rounded_btn' && window.innerWidth < 768) {
                    slideWidth = 100;
                }

                // Recalculate slidesPerView on resize
                this.params.slidesPerView = calculateSlidesPerView(containerWidth, slideWidth, this.params.spaceBetween);

                this.update();
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

        container.addEventListener('wheel', (e) => {
            e.stopPropagation();
        });
    });

    const swiperSimilarPdt = new Swiper('.similar-pdt-swiper', {
        slidesPerView: 3.4,
        spaceBetween: 10,
        breakpoints: {
            1024: { 
                slidesPerView: 4,
                spaceBetween: 20
            }
        },
    });

    const swiperSameCat = new Swiper('.swiper_more_same_cat', {
        spaceBetween: 8,
        slidesPerView: 'auto',
        slidesPerGroup: 1,
        centerInsufficientSlides: true,
        // setWrapperSize: true,
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

    if($("#login_sidebar .woocommerce-error").length > 0){
        $('.open_account_popup').trigger('click');
    }
    var $loginForm = $(".bottom_sidebar");
    var $resetForm = $(".custom-reset-password-form");
    var $errors = $(".woocommerce-notices-wrapper .woocommerce-error");

    // Toggle forms based on the login error flag
    if (loginErrorData.loginError) {
        console.log('enter login error!');
        $loginForm.show();
        $resetForm.hide();
    } 
    else{
        
        // if ($errors.length && $errors.text().toLowerCase().includes("invalid username or email")) {
        //     console.log('enter invalid ');
        //     $loginForm.hide();
        //     $resetForm.show();
        // }
    
        if($("#login_sidebar .woocommerce-error").length > 0){
            console.log('pswd error!');
            $('#login_sidebar .bottom_sidebar:visible').hide();

            // Later in the script - Show it but only If it's not visible.  
            $('#login_sidebar .custom-reset-password-form:hidden').show();
        }
    }

    $('.btn_filter_pdts').on('click', function () {
        var targetSidebarId = '';
        var targetTitle = $(this).data('title');
        console.log("🚀 ~ targetTitle:", targetTitle);
    
        // Identify which button was clicked and map to the correct sidebar
        var sidebarMapping = {
            'cat_filter': '#cat_sidebar',
            'delivery_filter': '#delivery_sidebar',
            'material_filter': '#material_sidebar',
            'color_filter': '#color_sidebar'
        };
    
        var buttonId = $(this).attr('id');
    
        if (buttonId === 'mobile_filter') {
            // Mobile case: show all sidebars
            //$('.content_sidebar').show();
            $(".sidebar-content .top_sidebar h2").html("All Filters"); // Update title
        } else if (sidebarMapping[buttonId]) {
            targetSidebarId = sidebarMapping[buttonId];
    
            // Hide all sidebar-content divs first, then show the correct one
            $('.content_sidebar').hide();
            $(targetSidebarId).fadeIn();
    
            // Update sidebar title
            $(".sidebar-content .top_sidebar h2").html(targetTitle);
        }
    
        // Show the sidebar container
        $('.filter_sidebar').addClass('active');
        $("#main-site").css('overflow','hidden');
        $("#main-site").css('padding-bottom', '0');
        // Show overlay if not already present
        if ($('#overlay').length === 0) {
            $('body').append('<div id="overlay"></div>');
            $('body').css('overflow', 'hidden');
        }
        $('#overlay').fadeIn(); // Show the overlay
    });

    $('.btn_mobile_filter').on('click', function () {
        var targetSidebar = $(this).data('target');
        var $icon = $(this).find('svg'); 
        // If the clicked section is already open, close it
        if ($(targetSidebar).is(':visible')) {
            $(targetSidebar).slideUp();
            $icon.removeClass('rotated'); 
        } else {
            // Hide all other open filters
            $('.content_sidebar').slideUp();
            $icon.removeClass('rotated'); 
            
            // Show the selected filter section
            $(targetSidebar).slideDown();
            $icon.addClass('rotated'); 
        }
    });

    
    if( $('.filter_sidebar').hasClass('active')){
        if ($('#overlay').length === 0) {
            $('body').append('<div id="overlay"></div>');
            $('body').css('overflow', 'hidden');
        }
        $('#overlay').fadeIn(); // Show the overlay
        $("#main-site").css('overflow','hidden');
        $("#main-site").css('padding-bottom', '0');
    }
    $('.filter_sidebar .close,.filter_sidebar .close_filter').on('click', function() {
        $('.filter_sidebar').removeClass('active');
        $('#overlay').fadeOut(function() {
            $(this).remove(); // Remove overlay after fade out
        });
        $('body').css('overflow', '');
        $("#main-site").css('overflow','auto');
        $("#main-site").css('padding-bottom', '100');
    });

    $("#login_sidebar .lost_password a").on('click', function(e) {
        e.preventDefault();
        $("#login_sidebar .bottom_sidebar").hide();
        $("#login_sidebar .custom-reset-password-form").show();
    });
    $('#close_sidebar').on('click', function() {
        $('#login_sidebar').removeClass('active');
        $('#overlay').fadeOut(function() {
            $(this).remove(); // Remove overlay after fade out
        });
        $('body').css('overflow', '');
        $('#login_sidebar .custom-reset-password-form:visible').hide();
        $('#login_sidebar .bottom_sidebar:hidden').show();

    });

    // Click outside filter sidebar to close it
    $(document).on('click', '#overlay', function () {
        $('.filter_sidebar').removeClass('active'); // Remove active class
        $("#main-site").css('overflow','auto');
        $("#main-site").css('padding-bottom', '100');
        $('#overlay').fadeOut(function () {
            $(this).remove(); // Remove overlay after fading out
        });
        $('body').css('overflow', 'auto'); // Restore scrolling
    });

    // $( 'input', '.filter_sidebar .checkbox_wrapper' ).on( 'change', function() {
    //     console.log('enter change');
    //     $('#filter_form').trigger('submit');
    // });

    $('.reset_filter_item').on('click', function() {
        if ($('#overlay').length === 0) {
            $('body').append('<div id="overlay"></div>');
            $('body').css('overflow', 'hidden');
        }
        $('#overlay').fadeIn();

        // Handle category filter reset
        const category = $(this).data('category');
        if (category) {
            const $categoryCheckbox = $(`input[name="product_category[]"][value="${category}"]`);
            if ($categoryCheckbox.length) {
                $categoryCheckbox.prop('checked', false);
                $('#filter_form').submit();
                return;
            }
        }

        // Handle color filter reset
        const color = $(this).data('color');
        if (color) {
            const $colorCheckbox = $(`input[name="product_color[]"][value="${color}"]`);
            if ($colorCheckbox.length) {
                $colorCheckbox.prop('checked', false);
                $('#filter_form').submit();
                return;
            }
        }

        // Handle delivery filter reset
        const delivery = $(this).data('delivery');

        if (delivery) {

            // Find the checkbox with the extracted value
            const $deliveryRadio = $(`input[name="product_delivery"][value="${delivery}"]`);
    
            if ($deliveryRadio.length) {
                $deliveryRadio.prop('checked', false); // Uncheck the checkbox
                $('#filter_form').submit(); // Submit the form
            }
        }
    });

    $( '.reset-filters' ).on( 'click', function() {
        console.log('enter reset');
        $('#overlay').fadeIn();
        $('#filter_form').find( 'input[type="checkbox"]:checked,input[type="radio"]:checked' ).prop( 'checked', '' ).end().submit();
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

        // Check wishlist items and toggle the share button visibility
        const wishlistContainer = $('.wishlist_table');
        const shareButton = $('.share_wishlist_btn');

        if (wishlistContainer.length) {
            const wishlistWrapper = wishlistContainer.find('.wishlist-items-wrapper');

            if (wishlistWrapper.find('*[id*="yith-wcwl"]').length > 0) {
                shareButton.show(); // Show the button if there are products
            } else {
                shareButton.hide(); // Hide the button if there are no products
            }
        }

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
            console.log('mobile');
            $('#mobile_menu_sidebar').addClass('active_left');
            $("#mobile_menu_sidebar").addClass('child_menu_open');
            $("#main-site").css('overflow','hidden');
            $("#main-site").css('padding-bottom', '0');
            if ($('#overlay').length === 0) {
                $('body').append('<div id="overlay"></div>');
                $('body').css('overflow', 'hidden');
            }
            $('#overlay').fadeIn(); // Show the overlay
        });

        $('#close_menu').on('click', function() {
            console.log('enter close menu');
            $('#mobile_menu_sidebar').removeClass('active_left');
            $("#mobile_menu_sidebar").removeClass('child_menu_open');
            $("#main-site").css('overflow','auto');
            $("#main-site").css('padding-bottom', '100');
            $('#overlay').fadeOut(function() {
                $(this).remove(); // Remove overlay after fade out
            });
            $('body').css('overflow', '');
        });     

        $(document).on('click', '#primary-menu > li.menu-item-has-children.active-menu > a', function(event) {
            event.preventDefault(); 
            $("#mobile_menu_sidebar").removeClass('child_menu_open');
        });


        // Close the wishlist sidebar if clicked outside
        $(document).mouseup(function(e) {
            var container = $("#mobile_menu_sidebar");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.removeClass('active_left');
                $("#main-site").css('overflow','auto');
                $("#main-site").css('padding-bottom', '100');
                $('#overlay').fadeOut(function() {
                    $(this).remove(); // Remove overlay after fade out
                });
                $('body').css('overflow', '');
            }
        });

        // Open sub-menu on clicking main menu item
        $('#primary-menu > li.menu-item-has-children > a').on('click', function(event) {
            event.preventDefault(); 

            
            const parentLi = $(this).parent();
            const submenu = parentLi.find('ul.sub-menu');
        
            if (parentLi.hasClass('active-nemu')) {
                
                submenu.stop().animate({ left: '500px', opacity: 0 }, 300, function() {
                    submenu.removeClass('sub-menu-active'); 
                });
                parentLi.removeClass('active-nemu');
                $("#mobile_menu_sidebar").addClass('child_menu_open');
                $('body').css('overflow', 'hidden');

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
                $("#mobile_menu_sidebar").removeClass('child_menu_open');
                $('body').css('overflow', '');
            }
        });

        

        
         // Close submenu on back arrow or close icon click
        $('#primary-menu > li > a::after', '#primary-menu > li > a::before').on('click', function() {
            console.log('enter before and after');
            const parentLi = $(this).closest('li');
            const submenu = parentLi.find('ul.sub-menu');

            submenu.css({ right: '0', opacity: 1 })
                .stop().animate({ right: '-500px', opacity: 0 }, 300, function() {
                submenu.removeClass('sub-menu-active');
            });
            parentLi.removeClass('active-nemu');
        });

        // $('.visible-mobile').append('<p class="add_text">...or discover categories:</p>');
        // $('.visible-mobile a').text('show all');
        if ($("html").attr("lang") === "he" || $("html").attr("dir") === "rtl") {
            // Change the text for Hebrew
            $('.visible-mobile').append('<p class="add_text">או גלה קטגוריות:</p>');
            $('.visible-mobile a').text('הצג הכל');
        } else {
            // Default (English or other LTR languages)
            $('.visible-mobile').append('<p class="add_text">...or discover categories:</p>');
            $('.visible-mobile a').text('show all');
        }
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
    console.log('check cache!!!');
    // Check if URL contains `panel=reset-link-sent`
    if (getQueryParam('panel') === "reset-link-sent") {
        console.log('enter reset link!');
        // Create and display the message
        $("#lost-password-confirmation").fadeIn();

        // After 2 seconds, remove the message and update the URL
        setTimeout(function() {
            $("#lost-password-confirmation").fadeOut();
            
            // Remove `panel=reset-link-sent` from the URL
            var newUrl = window.location.href.replace(/[?&]panel=reset-link-sent/, '');
            history.replaceState(null, null, newUrl);
        }, 4000); // 2-second delay
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

    // Opening and closing respectively of accordion on the FAQ page
    $('.faq-question').click(function() {
        var accordionItem = $(this).closest('.accordion-faq-item');
        var accordionContent = accordionItem.find('.faq-item-content');
        var isActive = accordionContent.hasClass('active');

        $('.faq-item-content').stop(true, true).slideUp().removeClass('active');
        $('.faq-question svg').removeClass('rotate180');

        if (!isActive) {
            accordionContent.stop(true, true).slideDown().addClass('active');
            $(this).find('svg').addClass('rotate180');
        }
        
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
    if ($("body").hasClass("woocommerce-cart")) {

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
    if ($('body').hasClass('woocommerce-checkout')) {
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
    if ($("body").hasClass("woocommerce-order-received")) {
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

    const wishlistButton = document.querySelector('.share_wishlist_btn');

    if (wishlistButton) {
        wishlistButton.addEventListener('click', function() {
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
        
    };

    //open width search input
    if (window.matchMedia('(min-width: 1024px)').matches) {
        console.log('width search input');
        const searchInput = $('#dgwt-wcas-search-input-1'); 
        const parentDiv = $('#btn-header-search'); 
        const childDiv = $('.dgwt-wcas-search-wrapp');

        searchInput.on('focus', function() {
            childDiv.animate({ width: '500px' }, 500, function() {
                // childDiv.css('width', '100%'); 
            });
        });

        searchInput.on('blur', function() {
            childDiv.animate({ width: '250px' }, 500, function() {
                // childDiv.css('width', '250px'); 
            });
        });
    }

    /*//Opening the search input in the phone
    $(document).on('click', function(event) {
        if (!$(event.target).closest('#btn-header-search, .dgwt-wcas-search-wrapp').length) {
            $('#product-searchform').removeClass('open-search');
        }
    });

    $('#btn-header-search .search-svg-btn').on('click', function(event) {
        event.stopPropagation();
        $(this).find('.dgwt-wcas-search-wrapp.dgwt-wcas-has-submit').addClass('open-search');
    });*/

    //Calculation of shipping costs and display of fields on the checkout page
    if ($("body").hasClass("woocommerce-checkout") && !$("body").hasClass("woocommerce-order-received")) {
        function toggleAddressFields(selectedMethod) {
            const shippingMessage = $('.delivery-message').data('acf-shipping');
            const selfMessage = $('.delivery-message').data('acf-self');

            if (selectedMethod.includes('local_pickup')) {
                $('.delivery-message').html(selfMessage);

                // Hide the address fields for self-pickup
                $('#billing_address_1_field, #billing_address_2_field, #billing_city_field, #billing_postcode_field, #billing_country_field, #billing_state_field').hide();
                $('.woocommerce-shipping-fields').hide();

                // Trigger the change event on page load to set the initial state
                $('form.checkout').trigger('change');
            } else {
                $('.delivery-message').html(shippingMessage);

                // Show the address fields for delivery
                $('#billing_address_1_field, #billing_address_2_field, #billing_city_field, #billing_postcode_field, #billing_country_field, #billing_state_field').show();
                $('.woocommerce-shipping-fields').show();
            
                // Trigger the change event on page load to set the initial state
                $('form.checkout').trigger('change');
            }
        }

        toggleAddressFields($('#shipping_method input[type="radio"]:checked').val());

        // Listen for changes on the shipping method
        $('form.checkout').on('change', 'input[name="shipping_method[0]"]', function() {
            toggleAddressFields($(this).val());
        });

        // Trigger the change event on page load to set the initial state
        $('form.checkout').trigger('change');

        //Trigger to open the "Forgot Password" pop-up
        $(document).on('click', '.woocommerce-checkout .woocommerce form.woocommerce-form-login .lost_password a', function(event) {
            
            event.preventDefault();    
            // Triggers the popup     
            $('.open_account_popup').trigger('click'); 
            $("#login_sidebar .lost_password a").trigger('click');
        });
        // Add class to element
        $('.woocommerce-form-login-toggle').click(function() {
            $(this).toggleClass('rotate180');
        });
    
    }


    // Hiding the second delivery area when empty
    $('.woocommerce-shipping-fields').each(function() {
        if ($(this).text().trim() === '') {
            $(this).addClass('empty');
        }
    });

    //Closing the banner strip in the header by clicking the close button
    $('#close-banner').on('click', function () {
        $('.banner-strip').slideUp(300, function () {
            $(this).remove(); 
        });
    });
    //ambiance button swith between main image and ambiance image
    $('.ambiance_filter button').click(function() {
        if( $(this).hasClass('btn_active') ) {
            return;
        }
        handleKareLazy();
        // Remove 'btn_active' class from all buttons
        $('.ambiance_filter button').removeClass('btn_active');

        // Add 'btn_active' class to the clicked button
        $(this).addClass('btn_active');
       
        if ($(this).attr('id') === 'ambiance_img' && $(this).hasClass('btn_active')) {
            $('.image_with_tags').addClass('swap_img');
        }
        else if ($(this).attr('id') === 'pdt_img' && $(this).hasClass('btn_active')) {
            $('.image_with_tags').removeClass('swap_img');
        }

        $('.image_with_tags').each(function () {

            $(this).find('.image-pdts a img').css({
                'transition': 'opacity 0.3s ease-in-out, transform 0.3s ease-in-out'
            });
            const hoverImage = $(this).find('.thumbnail-hover a img');
            const thumbnailImage = $(this).find('.has-hover a img');
    
            // If 'swap_img' class exists, swap the images
            if ($(this).hasClass('swap_img')) {
                if (hoverImage.length > 0 && thumbnailImage.length > 0) {
                    // Swap the src attributes
                    const tempSrc = thumbnailImage.attr('src');
                    thumbnailImage.attr('src', hoverImage.attr('src'));
                    hoverImage.attr('src', tempSrc);

                }
            } else {
                console.log('no swap');
                // Reset the images to their original state
                if (hoverImage.length > 0 && thumbnailImage.length > 0) {
                    const originalSrc = hoverImage.attr('src');
                    const currentThumbnailSrc = thumbnailImage.attr('src');
    
                    // Restore original hover image
                    hoverImage.attr('src',currentThumbnailSrc );
    
                    // Store current thumbnail src for potential reuse
                    thumbnailImage.attr('src', originalSrc);

                }
            }
        })

  
    });

    
    // When the button with class 'threed_btn' is clicked
    $('.threed_btn').on('click', function(e) {
        e.stopPropagation();
        // Show the 3D popup
        $('.threed_popup').fadeIn();

        // Append the fader to the popup
        $('.threed_popup').after('<div class="fader"></div>');
    });

    // When the close button inside the 3D popup is clicked
    $('.threed_popup .white-btn').on('click', function() {
        // Hide the 3D popup
        $('.threed_popup').fadeOut();

        // Remove the fader
        $('.fader').remove();
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

jQuery(document).ready(function($) {
    // Function to move the notice to the WooCommerce wrapper
    function moveCouponNotice() {
        // Find the new coupon notice
        var $couponNotice = $('.coupon-error-notice');
        
        // Check if the notice exists and is visible
        if ($couponNotice.length && $couponNotice.text().trim() !== '') {
            // Get the content of the notice
            var noticeText = $couponNotice.text().trim();

            // Find the first WooCommerce notices wrapper
            var $firstNoticeWrapper = $('.woocommerce-notices-wrapper').first();
            // Check if the notice is already present in the first wrapper
            if ($firstNoticeWrapper.find('li').filter(function() {
                console.log('coupon');
                return $(this).text().trim() === noticeText;
            }).length > 0) {
                return;
            }
            
            // Determine if it's an error or success
            var noticeType = $couponNotice.hasClass('coupon-error-notice') ? 'woocommerce-error' : 'woocommerce-message';

            var $newNotice = $(
                '<ul class="' + noticeType + '" role="alert">' +
                    '<li>' + noticeText + '</li>' +
                '</ul>'
            );
            $('.woocommerce-notices-wrapper').append($newNotice);

            // Hide the original notice
            $couponNotice.hide();
        }
    }

    // Trigger the function after an AJAX action (WooCommerce uses these)
    $(document.body).on('applied_coupon removed_coupon updated_cart_totals', function() {
        moveCouponNotice();
    });
});


//Adding a ticker to the form as a hidden field
document.addEventListener("DOMContentLoaded", function () {
    if (document.body.classList.contains("single-product")) {
        const skuElement = document.querySelector(".product-sku");
        const hiddenField = document.querySelector('input[name="product-sku"]');

        if (skuElement && hiddenField) {
            const fullText = skuElement.textContent.trim();
            const sku = fullText.split(":")[1]?.trim();
            hiddenField.value = sku;
        }
    }

    Array.from( document.querySelectorAll( '.kare-lazy' ) ).forEach( (element, index) => {
        element.addEventListener('mouseover', (event) => {
            const el = event.target.closest('.box_product');
            const lazyImgEl = el.querySelector( '.image-pdts.thumbnail-hover img' );
            lazyImgEl.setAttribute( 'src', lazyImgEl.getAttribute('data-src') );
            el.classList.remove( 'kare-lazy' );
        }, {once : true});
    });
});

const handleKareLazy = () => {
    Array.from( document.querySelectorAll( '.kare-lazy' ) ).forEach( (element, index) => {
        element.dispatchEvent( new Event('mouseover') );
    });
};

window.addEventListener('load', (event) => {
    setTimeout(handleKareLazy, 2000);
});


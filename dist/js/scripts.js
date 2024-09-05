
var $=jQuery.noConflict();

jQuery(document).on("ready", function(){

    $(document).ready(function() {
        
        //add class to label in login form for adding css 
        // On input focus
        $('.form-row .input-text').focus(function() {
            console.log('enter focus!');
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

    $(window).on('scroll', throttle(function() {
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
    var defaultSwiperOptions = {
        slidesPerView: 8.5,
        slidesPerGroup: 1,
        spaceBetween: 3,
        pagination: {
            el: '.swiper-pagination',
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
                updatePagination(this);
            },
            slideChange: function () {
                updatePagination(this);
            }
        }
    };

    var swiperContainers = document.querySelectorAll('.swiper-container');

    swiperContainers.forEach(function(container) {
        var swiperOptions = defaultSwiperOptions;

        if (container.querySelector('#rounded_btn')) {
            swiperOptions = Object.assign({}, defaultSwiperOptions, {
                spaceBetween: 8,
                slidesPerView: 10
            });
        } else if (container.querySelector('#module_slider_pdts')) {
            swiperOptions = Object.assign({}, defaultSwiperOptions, {
                spaceBetween: 8,
                slidesPerView: 6
            });
        } else if (container.querySelector('#module_pdts_content')) {
            swiperOptions = Object.assign({}, defaultSwiperOptions, {
                spaceBetween: 2,
                slidesPerView: 4
            });
        } else if (container.querySelector('#module_slide_cube')) {
            swiperOptions = Object.assign({}, defaultSwiperOptions, {
                spaceBetween: 16,
                slidesPerView: 4
            });
        }

        if (swiperOptions) {
            new Swiper(container, swiperOptions);
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

    /*function updatePagination(swiper) {
        var paginationEl = swiper.pagination.el;

        // Verify if paginationEl is an array and take the first element
        if (Array.isArray(paginationEl)) {
            paginationEl = paginationEl[0];
        }

        // Verify if paginationEl is a valid DOM element
        if (!paginationEl || !paginationEl.querySelectorAll) {
            // console.error('Invalid pagination element');
            return;
        }

        var bullets = paginationEl.querySelectorAll('.swiper-pagination-bullet');

        var activeIndex = swiper.activeIndex;
        bullets.forEach((bullet, index) => {
            var distance = Math.abs(activeIndex - index);
            var size = 16 - (distance * 5); // Adjust formula as needed
            size = size < 4 ? 4 : size; // Set minimum size
            bullet.style.width = size + 'px';
            bullet.style.height = size + 'px';
            bullet.style.opacity = 0.5 + (0.5 / (distance + 1)); // Adjust opacity as needed
        });
    }

    var defaultSwiperOptions = {
        slidesPerView: 8.5,
        slidesPerGroup: 1,
        spaceBetween: 3,
        pagination: {
            el: '.swiper-pagination',
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
                updatePagination(this);
            },
            slideChange: function () {
                updatePagination(this);
            }
        }
    };

    var swiperContainers = document.querySelectorAll('.swiper-container');

    swiperContainers.forEach(function(container) {
        var swiperOptions;
        // Check if parent element has 'rounded_btn' ID
        if (container.querySelector('#rounded_btn')) {
            swiperOptions = Object.assign({}, defaultSwiperOptions, {
                spaceBetween: 8,
                slidesPerView: 10
            });
        }
        // Additional check for the element with ID's 
        if (container.querySelector('#module_slider_pdts')) {
            swiperOptions = Object.assign({}, defaultSwiperOptions, {
                spaceBetween: 8,
                slidesPerView: 6,
                on: { // Explicitly define the events here
                    init: function () {
                        updatePagination(this);
                    },
                    slideChange: function () {
                        updatePagination(this);
                    }
                }
            });
        } else if (container.querySelector('#module_pdts_content')) {
            swiperOptions = Object.assign({}, defaultSwiperOptions, {
                spaceBetween: 2,
                slidesPerView: 4,
                on: { // Explicitly define the events here
                    init: function () {
                        updatePagination(this);
                    },
                    slideChange: function () {
                        updatePagination(this);
                    }
                }
            });
        } else if (container.querySelector('#module_slide_cube')) {
            swiperOptions = Object.assign({}, defaultSwiperOptions, {
                spaceBetween: 16,
                slidesPerView: 4,
                on: { // Explicitly define the events here
                    init: function () {
                        updatePagination(this);
                    },
                    slideChange: function () {
                        updatePagination(this);
                    }
                }
            });
        } /*else if (container.querySelector('#slider_img_gallery_small')) {
            swiperOptions = Object.assign({}, defaultSwiperOptions, {
                spaceBetween: 20,
                slidesPerView: 9.15,
                on: { // Explicitly define the events here
                    init: function () {
                        updatePagination(this);
                    },
                    slideChange: function () {
                        updatePagination(this);
                    }
                }
            });
        } * else {
            swiperOptions = defaultSwiperOptions;
        };
        // Initialize your swiper instance here if swiperOptions is defined
        if (swiperOptions) {
            new Swiper(container, swiperOptions);
        }
    }); */

    //open wishlist popup in header
    $('.open_wishlist_popup').on('click', function() {
        $('#wishlist_sidebar').addClass('active');
        if ($('#overlay').length === 0) {
            $('body').append('<div id="overlay"></div>');
        }
        $('#overlay').fadeIn(); // Show the overlay
    });

    $('#close_wishlist').on('click', function() {
        $('#wishlist_sidebar').removeClass('active');
        $('#overlay').fadeOut(function() {
            $(this).remove(); // Remove overlay after fade out
        });
    });

    // Close the wishlist sidebar if clicked outside
    $(document).mouseup(function(e) {
        var container = $("#wishlist_sidebar");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.removeClass('active');
            $('#overlay').fadeOut(function() {
                $(this).remove(); // Remove overlay after fade out
            });
        }
    });

    //open account popup in header
    $('.open_account_popup').on('click', function() {
        $('#login_sidebar').addClass('active');
        if ($('#overlay').length === 0) {
            $('body').append('<div id="overlay"></div>');
        }
        $('#overlay').fadeIn(); // Show the overlay
    });

    $('#close_sidebar').on('click', function() {
        $('#login_sidebar').removeClass('active');
        $('#overlay').fadeOut(function() {
            $(this).remove(); // Remove overlay after fade out
        });
    });
    
    // Close the sidebar if clicked outside
    $(document).mouseup(function(e) {
        var container = $("#login_sidebar");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.removeClass('active');
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


    if (window.location.href.indexOf("cart") > -1) {
        const quantityWrapper = document.querySelectorAll('.quantity-wrapper');

        quantityWrapper.forEach(function(wrapper) {
            const button = wrapper.querySelector('.btn_quantity_wrapper');
            const options = wrapper.querySelector('.custom_options');
            const allOption = wrapper.querySelectorAll('.custom_option');
            const selectedValueSpan = wrapper.querySelector('.selected_value');
            const hiddenInput = wrapper.querySelector('.custom_select_hidden');
            const form = wrapper.closest('form');
    
            // הצגת הרשימה בעת לחיצה על ה-custom_select_wrapper
            button.addEventListener('click', function () {
                options.classList.toggle('open');
            });
    
            // בחירת כמות מתוך הרשימה
            allOption.forEach(function(option) {
                option.addEventListener('click', function () {
                    const selectedValue = option.getAttribute('data-value');
                    selectedValueSpan.textContent = selectedValue; // עדכון הכמות המוצגת
                    hiddenInput.value = selectedValue; // עדכון הכמות בשדה הנסתר
    
                    // סימון הכמות שנבחרה
                    allOption.forEach(function(opt) {
                        opt.classList.remove('selected');
                    });
                    option.classList.add('selected');
                    options.classList.remove('open');

                    // הגשת הטופס באופן אוטומטי לעדכון העגלה
                    const updateCartButton = form.querySelector('button[name="update_cart"]');
                    if (updateCartButton) {
                        // הסרת ה-disable אם הכפתור קיים
                        updateCartButton.removeAttribute('disabled');
                        // הגשת הטופס
                        form.submit();
                    } else {
                        console.error('הכפתור update_cart לא נמצא בטופס.');
                    }
                    

                });
            });
    
            // סגירת הרשימה אם לוחצים מחוץ ל-wrapper
            document.addEventListener('click', function(event) {
                if (!wrapper.contains(event.target)) {
                    options.classList.remove('open');
                }
            });
        });

        const couponInput = document.getElementById('coupon_code');
        const applyButton = document.querySelector('.coupon_button');

        // בדוק את השדה בכל פעם שהמשתמש מקליד משהו
        couponInput.addEventListener('input', function () {
            if (couponInput.value.trim() === '') {
                applyButton.setAttribute('disabled', true);
            } else {
                applyButton.removeAttribute('disabled');
                applyButton.classList.remove('disable');
            }
        });
    }

});

// After loading Swiper, check the number of slides
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
});

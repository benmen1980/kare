
var $=jQuery.noConflict();

jQuery(document).on("ready", function(){

    //logout with popup
    $('.custom_logout a').on('click', function(e) {
        e.preventDefault();
        console.log('click!');
        $(".logout_popup").show();
        // Handle the OK button click
    
        $('#confirm-logout').on('click', function() {
            var logout_href = $('.woocommerce-MyAccount-navigation-link--customer-logout a').attr('href');
            //console.log("🚀 ~ $ ~ logout_href:", logout_href);
            window.location.href = logout_href;
           
            
        

        });
        
        // Handle the Cancel button click
        $('#cancel-logout').on('click', function() {
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

    //open account popup forurl with panel=account
    
    function getQueryParam(param) {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Check if 'panel' parameter equals 'account'
    if (getQueryParam('panel') === 'account') {
        $('.open_account_popup').trigger('click');
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


;



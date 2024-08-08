
var $=jQuery.noConflict();

jQuery(document).on("ready", function(){
    
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

    // $('.slider_categories').slick({
    //     infinite: false,
    //     slidesToShow: 8.5,
    //     slidesToScroll: 1,
    //     arrows: true,
    //     dots: true,
    //     rtl: false,
    // });

    function updatePagination(swiper) {
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
        } else if (container.querySelector('#slider_img_gallery_small')) {
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
        } else {
            swiperOptions = defaultSwiperOptions;
        };
        // Initialize your swiper instance here if swiperOptions is defined
        if (swiperOptions) {
            new Swiper(container, swiperOptions);
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
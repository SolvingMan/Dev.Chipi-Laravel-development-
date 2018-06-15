//(function ($) {
// "use strict";
//aliexpress category page

function getCookie(name) {
    var dc = document.cookie,
        prefix = name + "=",
        begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    }
    else {
        begin += 2;
        var end = document.cookie.indexOf(";", begin);
        if (end == -1) {
            end = dc.length;
        }
    }
    return decodeURI(dc.substring(begin + prefix.length, end));
}

function setModes(mode) {
    if (mode === "grid") {
        $(".modes").html(
            '<strong  class="label">View as:</strong>' +
            '<strong  class="modes-mode active mode-grid" title="Grid">' +
            '<span>grid</span>' +
            '   </strong>' +
            '   <a href="#" id="list" title="List" class="modes-mode mode-list">' +
            '       <span>list</span>' +
            '   </a>'
        );
        $('#list-block').hide();
        $('#grid-block').show();
        document.mode = "grid";
    } else {
        $(".modes").html(
            '<strong class="label">View as:</strong>' +
            '<a href="#" id="grid" class="modes-mode  mode-grid" title="Grid">' +
            '<span>grid</span>' +
            '</a>' +
            '   <strong title="List" class="active modes-mode mode-list">' +
            '   <span>list</span>' +
            '   </strong>'
        );
        $('#list-block').show();
        $('#grid-block').hide();
        document.mode = "list";
    }
}

$(document).ready(function () {

    // order history switcher to order details
    $('.order-details').on('click', function () {
        var id = $(this).data('id'),
            wrap = $('.order_detail_wrapper');

        wrap.addClass('hidden');
        wrap.each(function () {
            var innerId = $(this).data('summary-id');
            console.log("In: " + innerId);
            console.log("Out: " + id);
            if (id === innerId) {
                $(this).removeClass("hidden");
                $("a[href$='#order']").trigger('click');
            }
        })
    });

    $('#sort_select').on('change', function () {
        $(this).closest('form').submit();
    });

    /*  [ testimonials -  owl ]
     - - - - - - - - - - - - - - - - - - - - */
    var sync1 = $('.testimonials-thumb'),
        sync2 = $('.testimonials-des'),
        duration = 300;

    sync1.on('changed.owl.carousel', function (event) {
        var item = event.item.index - 3;

        sync2.trigger('to.owl.carousel', [item, duration, true]);
    });

    //aliexpress category page
    setModes(getCookie("mode") == null ? "grid" : document.mode);
    var colMain = $(".col-main");
    colMain.on('click', "#list", function (e) {
        e.preventDefault();
        setModes("list");
    });

    colMain.on('click', "#grid", function (e) {
        e.preventDefault();
        setModes("grid");
    });

    /*  [ per page owl ]
     - - - - - - - - - - - - - - - - - - - - */
    var status1 = $("#callback-page1");

    function callback1(event) {

        var items = event.item.count,
            item = event.item.index + 1;

        updateResult1(".currentItem", item);
        updateResult1(".owlItems", items);
    }

    function updateResult1(pos, value) {
        status1.find(pos).find(".result").text(value);
    }

    function callback_bg(event) {
        var corlor = $($(".owl-carousel .active .item ")).data('background');
        $('.block-section-top').css('background', corlor);
    }
    /*  [ owl-carousel ]
     - - - - - - - - - - - - - - - - - - - - */
    $(".owl-carousel").each(function (index, el) {
        var config = $(this).data();
        config.navText = ['', ''];
        config.smartSpeed = "800";
        config.loop = "true";
        //config.rtl = "false";

        if ($(this).hasClass('dotsData')) {
            config.dotsData = "true";
        }
        if ($(this).hasClass('testimonials-des')) {
            config.animateOut = "fadeOutDown";
            config.animateIn = "fadeInDown";
        }
        if ($(this).hasClass('callback-page1')) {
            config.onChanged = callback1;
        }
        if ($(this).hasClass('data-bg')) {
            config.onChanged = callback_bg;
        }
        if ($(this).parents("html").hasClass('cms-rtl')) {
            config.rtl = "true";
        }
        $(this).owlCarousel(config);
    });
    /*  [Mobile Search ]
     - - - - - - - - - - - - - - - - - - - - */

    $(".block-search").find(".block-title").on('click', function () {
        $(this).parent().toggleClass('active');
        return false;
    });
    /*  [Mobile menu ]
     - - - - - - - - - - - - - - - - - - - - */

    $(".ui-menu").find(".toggle-submenu").on('click', function () {
        $(this).parent().toggleClass('open-submenu');
        return false;
    });

    $("[data-action='toggle-nav']").on('click', function () {
        $(this).toggleClass('active');
        $(".block-nav-menu").toggleClass("has-open");
        $("body").toggleClass("menu-open");
        return false;

    });
    $("[data-action='close-nav']").on('click', function () {
        $("[data-action='toggle-nav']").removeClass('active');
        $(".block-nav-menu").removeClass("has-open");
        $("body").removeClass("menu-open");
        return false;

    });
    /*  [Mobile categori ]- - - - - - - - - - - - - - - - - - - - */

    $(".ui-categori").find(".toggle-submenu").on('click', function () {
        $(this).parent().toggleClass('open-submenu');
        return false;
    });

    $("[data-action='close-cat']").on('click', function () {
        $(".block-nav-categori .block-title").removeClass('active');
        $(".block-nav-categori").removeClass("has-open");
        $("body").removeClass("categori-open");
        return false;

    });
    /*  [Mobile click service ]
     - - - - - - - - - - - - - - - - - - - - */
    $(".service-opt-1 .block-title").on('click', function () {
        $(this).parent().toggleClass('active');
        return false;
    });

    /*  [animate click -floor ]
     - - - - - - - - - - - - - - - - - - - - */
    $(".block-title .action ").on('click', function (e) {
        // prevent default anchor click behavior
        e.preventDefault();

        // store hash
        var hash = this.hash;

        // animate
        $('html, body').animate({
            scrollTop: $(hash).offset().top
        }, 500, function () {

            // when done, add hash to url
            // (default click behaviour)
            window.location.hash = hash;
        });

    });
    /*  [COUNT DOWN ]
     - - - - - - - - - - - - - - - - - - - - */
    $('[data-countdown]').each(function () {
        var $this = $(this), finalDate = $(this).data('countdown');

        $this.countdown(finalDate, function (event) {
            var fomat =
                '<div class="box-count box-secs"><div class="number">%S</div></div>' +
                '<div class="box-count box-min"><div class="number">%M</div></div>' +
                '<div class="box-count box-hours">' + '<div class="number">%H</div></div>' +
                '<div class="box-count box-days" style="display: none;">' + '<div class="number">%D</div>' + '</div>';

            $this.html(event.strftime(fomat));
        });
    });

    /*  [Button Filter Products  ]
     - - - - - - - - - - - - - - - - - - - - */
    //open filter

    $(".btn-filter-products").on('click', function () {
        $(this).toggleClass('active');
        $("#layered-filter-block").toggleClass('active');
        $("body").toggleClass('filter-active');
        return false;
    });
    //Close filter

    var layeredFilterBlock =$("#layered-filter-block");
    layeredFilterBlock.find(".close-filter-products").on('click', function () {
        $(".btn-filter-products").removeClass('active');
        $("#layered-filter-block").removeClass('active');
        $("body").removeClass('filter-active');
        return false;
    });
    //toggle filter options

    layeredFilterBlock.find(".filter-options-title").on('click', function () {
        $(this).toggleClass('active');
        $(this).parent().toggleClass('active');
        return false;
    });
    /* ------------------------------------------------
     Arctic modal
     ------------------------------------------------ */

    if ($.arcticmodal) {
        $.arcticmodal('setDefault', {
            type: 'ajax',
            ajax: {
                cache: false
            },
            afterOpen: function (obj) {

                var mw = $('.modal_window');

                mw.find('.custom_select').customSelect();

                mw.find('.product_preview .owl_carousel').owlCarousel({
                    margin: 10,
                    themeClass: 'thumbnails_carousel',
                    nav: true,
                    navText: [],
                    rtl: window.ISRTL ? true : false
                });

                Core.events.productPreview();

                addthis.toolbox('.addthis_toolbox');

            }
        });
    }

    /* ------------------------------------------------
     Fancybox
     ------------------------------------------------ */
    if ($.fancybox) {
        $.fancybox.defaults.direction = {
            next: 'left',
            prev: 'right'
        }
    }
    if ($('.fancybox_item').length) {
        $('.fancybox_item').fancybox({
            openEffect: 'elastic',
            closeEffect: 'elastic',
            helpers: {
                overlay: {
                    css: {
                        'background': 'rgba(0,0,0, .6)'
                    }
                },
                thumbs: {
                    width: 50,
                    height: 50
                }
            }
        });
    }
    if ($('.fancybox_item_media').length) {
        $('.fancybox_item_media').fancybox({
            openEffect: 'none',
            closeEffect: 'none',
            helpers: {
                media: {}
            }
        });
    }

    /* ------------------------------------------------
     Elevate Zoom
     ------------------------------------------------ */

    if ($('#img_zoom').length) {
        $('#img_zoom').elevateZoom({
            zoomType: "inner",
            gallery: 'thumbnails',
            galleryActiveClass: 'active',
            cursor: "crosshair",
            responsive: true,
            easing: true,
            zoomWindowFadeIn: 500,
            zoomWindowFadeOut: 500,
            lensFadeIn: 500,
            lensFadeOut: 500
        });

        $(".open_qv").on("click", function (e) {
            var ez = $(this).siblings('img').data('elevateZoom');
            $.fancybox(ez.getGalleryList());
            e.preventDefault();
        });

    }

    /*  [ input number ]
     - - - - - - - - - - - - - - - - - - - - */
    $('.btn-number').on('click', function (e) {
        e.preventDefault();
        var fieldName = $(this).attr('data-field'),
            type = $(this).attr('data-type'),
            input = $("input[name='" + fieldName + "']"),
            currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if (type == 'minus') {

                if (currentVal > input.attr('minlength')) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) == input.attr('minlength')) {
                    $(this).attr('disabled', true);
                }

            } else if (type == 'plus') {

                if (currentVal < input.attr('maxlength')) {
                    input.val(currentVal + 1).change();
                }
                if (parseInt(input.val()) == input.attr('maxlength')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }
    });

    /*  [ tab detail ]
     - - - - - - - - - - - - - - - - - - - - */

    $(".product-info-detailed").find(".block-title").on('click', function () {
        $(this).parent().toggleClass('has-active');
        return false;
    });
    
    /*  [ All Categorie ]
     - - - - - - - - - - - - - - - - - - - - */
    $(document).on('click', '.open-cate', function () {
        $(this).closest('.block-nav-categori').find('li.cat-link-orther').each(function () {
            $(this).slideDown();
        });
        $(this).addClass('colse-cate').removeClass('open-cate').html('Close');
        return false;
    });
    /* Close Categorie */
    $(document).on('click', '.colse-cate', function () {
        $(this).closest('.block-nav-categori').find('li.cat-link-orther').each(function () {
            $(this).slideUp();
        });
        $(this).addClass('open-cate').removeClass('colse-cate').html('All Categories');
        return false;
    });

    /*  [ All Categorie ]
     - - - - - - - - - - - - - - - - - - - - */
    $(document).on('click', '.col-categori .btn-show-cat', function () {
        $(this).closest('.col-categori').find('li.cat-orther').each(function () {
            $(this).slideDown();
        });
        $(this).addClass('btn-close-cat').removeClass('btn-show-cat').html('Close <i class="fa fa-angle-double-right" aria-hidden="true"></i>');
        $(this).parent().addClass('open');
        return false;
    });
    /* Close Categorie */
    $(document).on('click', '.col-categori .btn-close-cat', function () {
        $(this).closest('.col-categori').find('li.cat-orther').each(function () {
            $(this).slideUp();
        });
        $(this).parent().removeClass('open');
        $(this).addClass('btn-show-cat').removeClass('btn-close-cat').html('All Categories <i class="fa fa-angle-double-right" aria-hidden="true"></i>');
        return false;
    });

    /*  [ Banner top ]
     - - - - - - - - - - - - - - - - - - - - */

    $(".qc-top-site").find(".close").on('click', function () {
        $(this).parents(".qc-top-site").slideUp("slow");
        $(this).parents(".qc-top-site").addClass('close-bn');
        $(".qc-top-site ").css({"min-height": "0", "opacity": "0"});
        return false;
    });

    /*  [ chosen ]
     - - - - - - - - - - - - - - - - - - - - */

    if ($('.categori-search-option').length) {
        $(".categori-search-option").chosen({});
    }

    /*  [ brand ]
     - - - - - - - - - - - - - - - - - - - - */
    if ($('.slide-top-brand').length > 0) {
        $('.slide-top-brand').bxSlider({
            mode: 'vertical',
            minSlides: 4,
            maxSlides: 3,
            pager: false,
            useCSS: false,
            nextText: '',
            prevText: ''
        });
    }

    /*  [ brand -floor - home 2]
     - - - - - - - - - - - - - - - - - - - - */
    if ($('.block-floor-products .col-brand').length > 0) {
        $('.block-floor-products .col-brand .slide-brand').bxSlider({
            mode: 'vertical',
            minSlides: 8,
            maxSlides: 8,
            pager: false,
            useCSS: false,
            nextText: '',
            prevText: ''
        });
    }

    /*  [ popup - newsletter]
     - - - - - - - - - - - - - - - - - - - - */
    if ($('#popup-newsletter').length > 0) {
        $('#popup-newsletter').modal({
            keyboard: false
        })
    }
    setTimeout(function () {
        $('#preloader').fadeOut('slow', function () {
        });
    }, 1500);

    resizeMenu();
    resizeCategori();

    $('#successLoginModal').modal('show');
    $('.hidden-td').hide();
    if ($("#search-input").length) {
        $("#search-input").autocomplete({
            appendTo: ".ui-widget",
            minLength: 1,
            source: function (request, response) {
                $.ajax({
                    url: "/ajax/search",
                    dataType: "json",
                    data: {
                        keyword: request.term
                    },
                    success: function (data) {
                        response($.each(data, function (index, item) {
                            return item;
                        }));
                    }
                });
            }
        })
    }

    function updateShortcart() {
        $.ajax({
            url: "/cart/shortcart",
            success: function (response) {
                $('.minicart-content-wrapper').html(response);
            }
        })
    }

    updateShortcart();
    $(".minicart-content-wrapper").on("click", ".delete", function (e) {
        e.preventDefault();
        var url = "/cart/remove/" + $(this).data("id");
        $.ajax({
            url: url,
            success: function (response) {
                updateShortcart();
                $('.cart-items-count').text(response);
            }
        })
    });
    function setCookie (name, value, expires, path, domain, secure) {
        document.cookie = name + "=" + escape(value) +
            ((expires) ? "; expires=" + expires : "") +
            ((path) ? "; path=" + path : "") +
            ((domain) ? "; domain=" + domain : "") +
            ((secure) ? "; secure" : "");
    }

    function getCookie(name) {
        var cookie = " " + document.cookie;
        var search = " " + name + "=";
        var setStr = null;
        var offset = 0;
        var end = 0;
        if (cookie.length > 0) {
            offset = cookie.indexOf(search);
            if (offset != -1) {
                offset += search.length;
                end = cookie.indexOf(";", offset);
                if (end == -1) {
                    end = cookie.length;
                }
                setStr = unescape(cookie.substring(offset, end));
            }
        }
        return(setStr);
    }

    $(".chat-request").click(function (e) {
        e.preventDefault();
        $('.open-request').html('Matthew Townsen - TeamSupport').removeClass('open-request');
        $(this).html('<p>Name:  Matthew Townsen</p>' +
        '<p>Email:  mtownsen@teamsupport.com</p>' +
        '<p>Time:  2:47 PM</p>' +
        '<p>Message:  Its all broken</p>' +
        '<button class="btn btn-default">Accept</button>')
            .addClass('open-request');
    });
    $(".cart-button").click(function (e) {
        $("body").prepend('<div id="preloader"> <div class="loader"></div> </div>');
    });

    $(".search").click(function (e) {
        e.preventDefault();
        var value = $("#search-input").val();
        if (value != "") {
            $("body").prepend('<div id="preloader"> <div class="loader"></div> </div>');
            location.href = '/' + $(this).data("shop") + '/search/' + value + '/1';
        }
    });

    ////////////////////////////// Add loader  to product
    $(document).on('click', '.product-item', function (e) {
        var $this = $(this);
        $this.find('.loader-wrap').addClass('loader-product');
        $this.find('.category-fixed-size-image').addClass('img-loader');
    });

    $('.similar-categories').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        $this.siblings('.similar-categories-hide-block').toggleClass('hidden');
        $this.toggleClass('category-close');
        $this.toggleClass('category-open');
        $this.text(function (index, text) {
            return text == 'קטגוריות נוספות' ? 'סגור' : 'קטגוריות נוספות';
        });
    });

    /////////////Header banner

    /*if(!getCookie('banner')){
        $('.site-main').addClass('banner-margin');
        $('.site-header').addClass('header-banner-fixed');
    }*/

    $('#header-banner-button').click(function(){
        var $this = $(this);
        var now = new Date();
        var expire = new Date();
        expire.setFullYear(now.getFullYear());
        expire.setMonth(now.getMonth());
        expire.setDate(now.getDate()+1);
        expire.setHours(0);
        expire.setMinutes(0);
        expire.setSeconds(0);
        setCookie("banner", "hide", expire.toString(), "/");
        setTimeout(function(){
            $this.closest('.header-banner').remove();
        },1000);
        //$('.site-main').removeClass('banner-margin');
        //$('.site-header').removeClass('header-banner-fixed');
    });
});

/** #brand-showcase */
$(document).on('click', '.block-brand-tabs .nav-brand li', function () {
    var id = $(this).data('tab');
    $(this).closest('.block-brand-tabs').find('li').each(function () {
        $(this).removeClass('active');
    });
    $(this).closest('li').addClass('active');
    $('.block-brand-tabs').find('.tab-pane').each(function () {
        $(this).removeClass('active');
    });
    $('#' + id).addClass('active');
    return false;
});

$(window).resize(function () {
    resizeMenu();
    resizeCategori();
});

$(window).scroll(function () {
    resizeMenu();
    resizeCategori();
});

function resizeMenu() {
    var container = $('.container').innerWidth();
    var left_container = $('.container').offset().left;
    var w_window = $(window).width();


    $(".ui-menu .drop-menu").each(function (index, el) {


        var left_li = $(this).parent().offset().left;
        var w_li = $(this).parent().innerWidth();

        var w_megamenu = $(this).innerWidth();

        if (w_window > 991) {

            if ($("html").hasClass('cms-rtl')) {
                $(this).css({'right': "0", "left": "auto"});


                if ((left_li - left_container - 15) < (  w_megamenu - w_li )) {

                    $(this).css('margin-right', left_li - left_container - 15 - w_megamenu + w_li + "px");

                }
                else {

                }
            }

            else {

                $(this).css({'right': "auto", "left": "0"});


                if ((container + left_container - 15) < ( left_li + w_megamenu )) {

                    $(this).css('margin-left', container + left_container - 15 - left_li - w_megamenu + "px");

                }
                else {

                }
            }
        }
        else {
            $(this).removeAttr("style");
        }
    });
}

function resizeCategori() {
    var container = $('.container').innerWidth();
    $(".block-nav-categori .ui-categori").each(function (index, el) {

        var w_categori = $(this).innerWidth(),
            w_categori = parseInt($(this).actual('width'));

        if ($("html").hasClass('cms-rtl')) {
            $(".block-nav-categori .ui-categori .submenu").css({
                'right': w_categori + "px",
                "width": container - w_categori - 30 + "px"
            });

        }
        else {

            $(".block-nav-categori .ui-categori .submenu").css({
                'left': w_categori + "px",
                "width": container - w_categori - 30 + "px"
            });
        }
    });
}
//})(jQuery);

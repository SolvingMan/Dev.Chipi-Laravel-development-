$(document).ready(function (){
    $(document).on('click', '.nav-menu-title', function () {
        var width = $(window).width(),
            visibleIcom = $(this).find('.icon-bar').css('display');
        if (visibleIcom !== 'none'){
            $(this).toggleClass('active');
            $(this).parent().toggleClass('has-open');
            $("body").toggleClass("categori-open");
            if (width > 992) {
                $(this).closest('.block-nav-categori').find('.block-content').slideToggle();

            }
        }
        return false;
    });

    var elem = $('.site-header'),
        top = elem.offset().top;
    $(window).scroll(function () {
        //Scroll sticky header + category-menu

        var windowpos = $(window).scrollTop(),
            blockMenu = elem.find('.block-nav-categori'),
            iconMenu = blockMenu.find('.icon-bar'),
            width = $(window).width();

        if (windowpos > top) {
            elem.addClass('header-fixed');
            //console.log(windowpos);
            //console.log(top);
            //$(".header-top").hide();
            $(".header-top").addClass('header-top-display');
            if (blockMenu.hasClass('block-menu-open')){
                blockMenu.addClass('block-hover');
                iconMenu.addClass('icon-visible');
            }
        } else {
            elem.removeClass('header-fixed');
            $(".header-top").removeClass('header-top-display');
            if (blockMenu.hasClass('block-menu-open')){
                blockMenu.removeClass('block-hover');
                iconMenu.removeClass('icon-visible');
            }
        }
        blockMenu.click(function(){
            iconMenu.toggleClass('icon-bar-close');
        });

        if (width > 1200) {
            if ($('header').hasClass('cate-show') && $('body').hasClass('cms-index-index')) {
                if ($('#sticky-wrapper').hasClass('is-sticky')) {

                } else {
                    $('.header-nav .block-nav-categori').find('.block-content').hide();

                }
            }
        }
    });
});


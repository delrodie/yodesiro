
(function($) {
    "use strict";

    $(window).load(function(){
        mainBg();
        loader();
    });

    $(document).ready(function() {
        coloumnHeight();
        navigation();
        owlCarouselWidget();
        pageTransition();
        countdownClock();
        notifyMe();
    });

    $(window).resize(function() {
        mainBg();
        coloumnHeight();
        notifyMe();
    });

})(jQuery);

/*	COLOUMN HEIGHT
----------------------------*/
function loader(){
    $(".page-loader-wrapper").fadeOut(800);
}

/*	COLOUMN HEIGHT
----------------------------*/
function coloumnHeight(){
    var h = $(window).height();
    $(".coloumn").css({
        "min-height": h
    });
}

/*	MAIN-BACKGROUND
----------------------------*/
function mainBg(){
    var path = $(".bg-wrapper").attr("data-background");
    if($("body").hasClass("background-img")){
        $(".bg-wrapper").css({
            "background-image" : "url("+path+")"
        });
    }else{
        return false;
    }
}

/*	NAVIGATION
----------------------------*/
function navigation(){

    var menu_icon = $(".navigation-wrapper .menu-icon");
    var navigation_inner_wrapper = $(".navigation-wrapper .inner-wrapper");
    var navigation_li = $(".navigation li");
    var main_wrapper_overflow = $(".main-wrapper");
    var coloumn = $(".main-wrapper .coloumn");
    var coloumn_left = $(".main-wrapper .coloumn.left");
    var coloumn_right = $(".main-wrapper .coloumn.right");
    var menu_icon_convert = $(".navigation-wrapper .menu-icon .menu");

    // EFFECTS
    var coloumn_out = "easeInOutCubic";
    var coloumn_in = "easeInOutCubic";
    var navigation_in = "easeInOutCubic";
    var navigation_out = "easeInOutCubic";
    var menu_icon_unactive = "easeInOutCubic";
    var menu_icon_active = "easeInOutCubic";
    var nav_show = "easeInExpo";
    var nav_hide = "easeOutExpo";


    menu_icon.on("click", function(){
        if($("html").hasClass("mobile-v")){
            if(navigation_inner_wrapper.hasClass("open-menu") != true){
                navigation_inner_wrapper.show("clip",{direction: "horizontal"}, nav_show, 60).addClass("open-menu");
                main_wrapper_overflow.addClass("overflow");
                coloumn.animate({
                    opacity: 0.3
                }, 800, coloumn_out);
                menu_icon.addClass("close-icon");
                setTimeout(function(){
                    navigation_li.animate({
                        top: "0px",
                        opacity: 1
                    }, 800, navigation_in);
                    menu_icon_convert.addClass("example5");
                }, 400);
            }
            else{
                if(menu_icon.hasClass("close-icon")){
                    navigation_inner_wrapper.hide("clip",{direction: "horizontal"}, nav_hide, 60).removeClass("open-menu");
                    navigation_li.animate({
                        top: "-30px",
                        opacity: 0
                    }, 800, navigation_out);
                    coloumn.animate({
                        opacity: 1
                    }, 1000, coloumn_in, function(){
                        main_wrapper_overflow.removeClass("overflow");
                    });
                    menu_icon_convert.removeClass("example5");
                    menu_icon.removeClass("close-icon");
                }
            }
        }
        else{
            if(navigation_inner_wrapper.hasClass("open-menu") != true){
                navigation_inner_wrapper.show("clip",{direction: "horizontal"}, nav_show, 60).addClass("open-menu");
                main_wrapper_overflow.addClass("overflow");
                coloumn_left.animate({
                    left: "-100px",
                    opacity: 0.3
                }, 800, coloumn_out);
                coloumn_right.animate({
                    right: "-100px",
                    opacity: 0.3
                }, 800, coloumn_out);
                menu_icon.addClass("close-icon");
                setTimeout(function(){
                    menu_icon.animate({
                        top: "80px"
                    }, 800, menu_icon_active);
                    navigation_li.animate({
                        top: "0px",
                        opacity: 1
                    }, 800, navigation_in);
                    menu_icon_convert.addClass("example5");
                }, 400);
            }
            else{
                if(menu_icon.hasClass("close-icon")){
                    coloumn_left.animate({
                        left: "0px",
                        opacity: 1
                    }, 800, coloumn_in);
                    coloumn_right.animate({
                        right: "0px",
                        opacity: 1
                    }, 800, coloumn_in);
                    navigation_inner_wrapper.hide("clip",{direction: "horizontal"}, nav_hide, 60).removeClass("open-menu");
                    navigation_li.animate({
                        top: "-30px",
                        opacity: 0
                    }, 800, navigation_out);
                    menu_icon.animate({
                        top: "50%"
                    }, 800, menu_icon_unactive, function(){
                        main_wrapper_overflow.removeClass("overflow");
                    });
                    menu_icon_convert.removeClass("example5");
                    menu_icon.removeClass("close-icon");
                }
            }
        }
    });

    $(".navigation .navigation-link").on("click", function(){
        if($("html").hasClass("mobile-v")){
            if(navigation_inner_wrapper.hasClass("open-menu") == true){
                navigation_inner_wrapper.hide("clip",{direction: "horizontal"}, nav_hide, 60).removeClass("open-menu");
                navigation_li.animate({
                    top: "-30px",
                    opacity: 0
                }, 800, navigation_out);
                coloumn.animate({
                    opacity: 1
                }, 800, coloumn_in, function(){
                    main_wrapper_overflow.removeClass("overflow");
                });
                menu_icon_convert.removeClass("example5");
                menu_icon.removeClass("close-icon");
            }
        }
        else{
            if(navigation_inner_wrapper.hasClass("open-menu") == true){
                coloumn_left.animate({
                    left: "0px",
                    opacity: 1
                }, 800, coloumn_in);
                coloumn_right.animate({
                    right: "0px",
                    opacity: 1
                }, 800, coloumn_in);
                navigation_inner_wrapper.hide("clip",{direction: "horizontal"}, nav_hide, 60).removeClass("open-menu");
                navigation_li.animate({
                    top: "-30px",
                    opacity: 0
                }, 800, navigation_out);
                menu_icon.animate({
                    top: "50%"
                }, 800, menu_icon_unactive, function(){
                    main_wrapper_overflow.removeClass("overflow");
                });
                menu_icon_convert.removeClass("example5");
                menu_icon.removeClass("close-icon");
            }
        }
    });

}

/*	IMAGE POP-UP
----------------------------*/
function imgPopUp(){
    $('.img').magnificPopup({
        type: 'image',
        gallery:{
            enabled:true
        },
        zoom: {
            enabled: true
        }
    });
}

/*	OWL-CAROUSEL
----------------------------*/
function owlCarouselWidget(){
    function strtoArr(arr) {
        if (typeof(arr) == "string") {
            var t1 = arr.split('|');
            var t2 = [];
            $.each(t1, function(index, val) {
                var str = val;
                t2.push(str.split(','));
            });
            return t2;
        }else{
            return false;
        }
    }

    function dataCheck(val){
        return val && val == "true" ? true : false;
    }

    if ($(".carousel-widget").length > 0) {
        var carousel = 0;
        $('.carousel-widget').each(function(){

            // SET ID ON ALL OBJECTS
            carousel++;
            var createObj = 'owl'+carousel;
            $(this).css({opacity:0});
            $(this).attr("id", createObj);
            $(this).addClass(createObj);

            var pager = dataCheck($(this).attr('data-pager'));
            var itemsCustom = $(this).attr('data-itemrange') ? strtoArr($(this).attr('data-itemrange')) : [ [0, 1], [420, 2], [600, 3], [768, 3], [980, 4] ];
            var singleItem = dataCheck($(this).attr('data-singleitem'));
            var effect = dataCheck($(this).attr('data-effect'));


            $("."+createObj).animate({opacity:1}, 100, function(){

                $("."+createObj+ " .carousel .owl-carousel").owlCarousel({
                    itemsCustom : itemsCustom,
                    navigation : false,
                    pagination : pager,
                    responsiveBaseWidth: "."+createObj,
                    singleItem: singleItem,
                    scrollPerPage: 1,
                    transitionStyle : effect
                });

                var owl = $("."+createObj+ " .carousel .owl-carousel").data('owlCarousel');
                $("."+createObj).find('.carousel-btn .prev').on('click', function() { owl.prev(); });
                $("."+createObj).find('.carousel-btn .next').on('click', function() { owl.next(); });
            });
        });
        imgPopUp();
    }
}

/*	COUNTDOWN CLOCK
----------------------------*/
function countdownClock(){
    var day = parseInt($("#countdown_dashboard").attr("data-day"), 10);
    var month = parseInt($("#countdown_dashboard").attr("data-month"), 10);
    var year = parseInt($("#countdown_dashboard").attr("data-year"), 10);
    var hour = parseInt($("#countdown_dashboard").attr("data-hr"), 10);
    var min = parseInt($("#countdown_dashboard").attr("data-min"), 10);
    var sec = parseInt($("#countdown_dashboard").attr("data-sec"), 10);

    // DESKTOP CLOCK
    $('#countdown_dashboard').countDown({
        targetDate: {
            'day': 		day,
            'month': 	month,
            'year': 	year,
            'hour': 	hour,
            'min': 		min,
            'sec': 		sec
        },
        omitWeeks: true
    });
}

/*	NOTIFYME
----------------------------*/


/*	PAGE TRANSITION
----------------------------*/
function pageTransition(){
    var pageUrl;
    var navigation_links = ".navigation .navigation-link";
    var page_container = "#page-container";
    var ajax_page_wrapper = "#page-container .inner-container.ajax-pg";
    var ajax_page = "#page-container .inner-container.ajax-pg .page";
    var home_page = "#page-container .inner-container.home-pg .page";

    $(navigation_links).on("click", function(){

        pageUrl = $(this).attr("data-page");
        $(navigation_links).removeClass("active-nav");
        $(this).addClass("active-nav");

        if (pageUrl == "{{ path('app_accueil')}}") {
            setTimeout(function(){
                $(ajax_page).stop().animate({
                    top: "-30px",
                    opacity: 0
                }, 800, function(){
                    $(ajax_page_wrapper).html('');
                    $(page_container).addClass("home-page");
                    $(home_page).removeClass("display_none");
                    $(home_page).css("top", "-30px");
                    $(home_page).stop().animate({
                        top: "0px",
                        opacity: 1
                    }, 800);
                });
            }, 800);
        }
        else{
            if($(page_container).hasClass("home-page")){
                setTimeout(function(){
                    $(home_page).stop().animate({
                        top: "-30px",
                        opacity: 0
                    }, 800, function(){
                        $(home_page).addClass("display_none");
                        $(page_container).removeClass("home-page");
                        $.post(pageUrl, function(data){
                            $(ajax_page_wrapper).html(data);
                            owlCarouselWidget();
                            $(ajax_page).css("top", "-30px");
                            $(ajax_page).stop().animate({
                                top: "0px",
                                opacity: 1
                            }, 800);
                        });
                    });
                },800);
            }
            else{
                setTimeout(function(){
                    $(ajax_page).stop().animate({
                        top: "-30px",
                        opacity: 0
                    }, 800, function(){
                        $(ajax_page_wrapper).html('');
                        $.post(pageUrl, function(data){
                            $(ajax_page_wrapper).html(data);
                            owlCarouselWidget();
                            $(ajax_page).css("top", "-30px");
                            $(ajax_page).stop().animate({
                                top: "0px",
                                opacity: 1
                            }, 800);
                        });
                    });
                }, 800);
            }
        }
    });
}
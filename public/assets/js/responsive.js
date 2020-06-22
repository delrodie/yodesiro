(function($) {
    "use strict";

    if($("html").hasClass("ie")){
        return true;
    }else{
        $(document).ready(function(e) { mediaQuery(); });
        $(window).resize(function(e) { mediaQuery(); });
    }

})(jQuery);

function mediaQuery(){
    function mobileView(){
        if($("html").hasClass("mobile-v")){
            if($("#home .clock-wrp > *").length == 0){
                $("#countdown_dashboard").prependTo( $("#home .clock-wrp") );
                $("#home .clock-wrp").append($(".sec-ripple-wrp"));
            }
            else{
                return true;
            }

            if($(".navigation-wrapper .social-icon-wrp *").length == 0){
                $(".social-icon-wrp.desk .social-icon").prependTo( $(".social-icon-wrp.mob") );
            }
            else{
                return true;
            }
        }
        else{
            $("#home #countdown_dashboard").prependTo( $("#cover-page") );
            $("#cover-page").append($(".sec-ripple-wrp"));
            $(".social-icon-wrp.mob .social-icon").prependTo( $(".social-icon-wrp.desk") );
        }
    }
    enquire.register("only screen and (min-width: 1200px)", {
        match : function() {

        },
        unmatch : function() {

        }
    }).register("only screen and (min-width: 980px) and (max-width: 1199px)", {
        match : function() {

        },
        unmatch : function() {

        }
    }).register("only screen and (min-width: 768px) and (max-width: 979px)", {
        match : function() {
            $("html").addClass("mobile-v");
            mobileView();
        },
        unmatch : function() {
            $("html").removeClass("mobile-v");
            mobileView();
        }
    }).register("only screen and (min-width: 600px) and (max-width: 767px)", {
        match : function() {
            $("html").addClass("mobile-v");
            mobileView();
        },
        unmatch : function() {
            $("html").removeClass("mobile-v");
            mobileView();
        }
    }).register("only screen and (min-width: 480px) and (max-width: 599px)", {
        match : function() {
            $("html").addClass("mobile-v");
            mobileView();
        },
        unmatch : function() {
            $("html").removeClass("mobile-v");
            mobileView();
        }
    }).register("only screen and (min-width: 320px) and (max-width: 479px)", {
        match : function() {
            $("html").addClass("mobile-v");
            mobileView();
        },
        unmatch : function() {
            $("html").removeClass("mobile-v");
            mobileView();
        }
    });
}
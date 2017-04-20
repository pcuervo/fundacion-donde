var $=jQuery.noConflict();

(function($){
    "use strict";
    $(function(){

        /*------------------------------------*\
            #GLOBAL
        \*------------------------------------*/
        $(window).load(function(){
            $( "#ship-to-different-address-checkbox" ).prop( "checked", false );
            $( 'div.shipping_address' ).hide();

            $( "<div class='bg-notice'></div>" ).insertBefore( ".returning-user" );
        });

        $(window).ready(function(){
            footerBottom();
            jsAccordion();
        });

        $(window).on('resize', function(){
            footerBottom();
        });

        $('#mc_signup_submit').on('click',function(e){
            footerBottom();
        });

        // $('.payment_method_paypal *').on('click',function(e){
        //     console.log('checando');
        //     $('.mesage-openpay').addClass('hidden');
        // });
    });
})(jQuery);

//Footer Bottom

function footerBottom(){
    var alturaFooter = getFooterHeight();
    $('.main-body').css('padding-bottom', alturaFooter );
}

function getHeaderHeight(){
    return $('.js-header').outerHeight();
}// getHeaderHeight

function getFooterHeight(){
    return $('footer').outerHeight();
}// getFooterHeight

function jsAccordion(){
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].onclick = function(){
            /* Toggle between adding and removing the "active" class,
            to highlight the button that controls the panel */
            this.classList.toggle("active");

            /* Toggle between hiding and showing the active panel */
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        }
    }

}
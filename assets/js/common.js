$(function () {
   
    
    //Do not include! This prevents the form from submitting for DEMO purposes only!
    $('form').submit(function(event) {
        event.preventDefault();
        return false;
    });
     //Do not include! This prevents the form from submitting for DEMO purposes only!
    $('.toggleBilling').click(function() {
        $(".billing-address").toggleClass("hidden");
    });
    
});


/**
 *
 * Add to Cart function
 *
 */

var ecommSketch = (function(){

    /* Globals */

    var $body;

    $body = $('body');

   
    
    var addToCart = function(){

        //alert('hi');

    };//Add to Cart
    return {
        init: function(){
           
           addToCart();
        }
    };
})();


//2
$(document).ready(function() {
  
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");        
    });
    
    $(".toggleSidebar").click(function() {       
        $("#wrapper").toggleClass("active2");
        $("#wrapper").toggleClass("active");
        $("#sidebar-wrapper").toggleClass("hidden");
        $("#topnav").toggleClass("hidden");
    });
    
    $(".toggleLayout").click(function() {        
        $("body").toggleClass("fluid");    
        //$('.thumbnail').attr('src', 'http://placehold.it/300x300');    
    });

   ecommSketch.init(); 

});


(function(){

        $('.search-btn').click(function(){
            if( $('.search-container').hasClass('search-div-hide') ){

                $('.search-container').removeClass('search-div-hide');
                $('.search-container').animate({"left":"52px"}, "fast", function(){
                     $('.menu-text').fadeOut('fast');
                     $(this).fadeIn('fast').addClass('search-div-show');
                     $('#sidebar-wrapper').css({
                        'background': '#272C30'
                    });


                     if( $('.side-menu-child').hasClass('opened') ){
                        $('.side-menu-child').fadeOut('fast').addClass('closed');
                        $('.side-menu-child').removeClass('opened');
                        
                     }   

                })

            }else if( $('.search-container').hasClass('search-div-show') ){
                 $('.search-container').removeClass('search-div-show');
                 $('.search-container').animate({"left": "250px"}, "fast", function(){
                    $(this).fadeOut('fast').addClass('search-div-hide');
                    
                    $('.menu-text').fadeIn('fast');
                     $('#sidebar-wrapper').css({
                        'background': '#31373D'
                     });
                 })
                
            }
        });


        /*check active class present on submenu*/


        /* logo click */

        $('.sidebar-brand').click(function(){
             if( $('.search-container').hasClass('search-div-show') ){
                    $('.search-container').animate({"left": "250px"}, "fast", function(){
                         $(this).fadeOut('fast').addClass('search-div-hide');
                          
                 });
            }

            $('.side-menu-child').animate({"left":"250px"}, "fast");
             $('.menu-text').fadeIn('fast');
        });

        /* Menu btns */

        $('.menu-btn').click(function(){
            var type =  $(this).find('.menu-text').attr('data-type');


              if( $('.search-container').hasClass('search-div-show') ){
                    $('.search-container').animate({"left": "250px"}, "fast", function(){
                         $(this).fadeOut('fast').addClass('search-div-hide');
                          
                 });
            }

             $('#sidebar-wrapper').css({
                        'background': ' #272C30'
              });


            var sideMenuChild = function(status, type){

                 $('.side-menu-child[data-type='+type+']').animate({"left":"52px"}, "fast", function(){
                    
                    $('.menu-text').fadeOut('fast');
                    $('.side-menu-child-single').fadeOut('fast').removeClass('selected');
                    $('.side-menu-child-single#'+ type).toggle('slide', { direction: 'right' }, 100).addClass('selected');
                   
                   if(status == "opened"){
                        $(this).fadeIn('fast');
                   }else if(status == "closed"){
                        $(this).fadeIn('fast').addClass('opened');
                   } 

                });

            };




            if( $('.side-menu-child').hasClass('closed') ){

                 $('.side-menu-child').removeClass('closed');
                 sideMenuChild('closed', type); 
            } 

             if( $('.side-menu-child').hasClass('opened') ){
                sideMenuChild('opened', type);
            } 



          
        });

        /* hover menu */

   var menuSlideFunc = function(){

            $('.side-menu-child').animate({"left":"250px"}, "fast");
            $('.menu-text').fadeIn('fast');
           
    }

    var menuSlideFuncBackwards = function(){
            $('.side-menu-child').animate({"left":"52px"}, "fast");
            $('.menu-text').fadeOut('fast');
    }


        $('.main-menu-link').mouseenter(function(){
              if( $('.side-menu-child').hasClass('opened') ){  
                menuSlideFunc();

              }  

        });

        $('#sidebar-wrapper').mouseleave(function(){
          
            if( $('.side-menu-child').hasClass('opened') ){  
                menuSlideFuncBackwards();
            }  

        });

        $(function(){
            $("[data-toggle=popover]").popover({
                html : true,
                content: function() {
                  var content = $(this).attr("data-popover-content");
                  return $(content).children(".popover-body").html();
                }
            });
        });



})();



$(document).ready(function(){            
 
        $('.autumnshowcase_carousel').jcarousel({
            'wrap': 'circular'
        });
        
        if (autoscroll){
            $('.autumnshowcase_carousel').jcarouselAutoscroll({
                interval: autoscrollInterval
            });
        };
                
        $('.carousel_prev').jcarouselControl({
            target: '-=1'
        });

        $('.carousel_next').jcarouselControl({
            target: '+=1'
        });
        
                
        $('.featured_products .autumnshowcase_carousel').touchwipe({
            wipeLeft: function() {
               $('.featured_products .carousel_next').click();
            },
            
            wipeRight: function() {
               $('.featured_products .carousel_prev').click();
            },
            
            preventDefaultEvents: false
        });
        
        $('.new_products .autumnshowcase_carousel').touchwipe({
            wipeLeft: function() {
               $('.new_products .carousel_next').click();
            },
            
            wipeRight: function() {
               $('.new_products .carousel_prev').click();
            },
            
            preventDefaultEvents: false
        });
        
        $('.special_products .autumnshowcase_carousel').touchwipe({
            wipeLeft: function() {
               $('.special_products .carousel_next').click();
            },
            
            wipeRight: function() {
               $('.special_products .carousel_prev').click();
            },
            
            preventDefaultEvents: false
        });
 
});


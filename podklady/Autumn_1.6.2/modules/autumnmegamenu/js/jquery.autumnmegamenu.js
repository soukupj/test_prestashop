    $('document').ready(function(){
        
        $('#megamenu > li').hoverIntent({
                interval: 50, // milliseconds delay before onMouseOver
		over: showMenu,
		timeout: 50, // milliseconds delay before onMouseOut
		out: hideMenu
        });
        
        function showMenu(){
            $(this).children('.megamenu_context').show();
        }
        
        function hideMenu(){
            $(this).children('.megamenu_context').hide();
        }
        
        $('#megamenu-responsive-root li.parent').prepend('<p>+</p>');
        
        
        $('.menu-toggle').click(function(){
            $('.root').toggleClass('open');
            
            if ($(window).width() < 479){
                $('#header-fluid').height( $('#megamenu-responsive ul').height() + 180 );
            }else{
                $('#header-fluid').height( $('#megamenu-responsive ul').height() + 120 );
            }
        });
        
        
        $('#megamenu-responsive-root li.parent > p').click(function(){

            if ($(this).text() === '+'){
                $(this).parent('li').children('ul').show();
                $(this).text('-');
            }else{
                $(this).parent('li').children('ul').hide();
                $(this).text('+');
            }
            
            if ($(window).width() < 479){
                $('#header-fluid').height( $('#megamenu-responsive ul').height() + 180 );
            }else{
                $('#header-fluid').height( $('#megamenu-responsive ul').height() + 120 );
            }
            
        });
        
                
        $(window).resize(function(){
            if ( $(window).width() > 959 ){
                $('#header-fluid').css('height', '');    
            }
            else if ( $(window).width() < 479 ){
                $('#header-fluid').height( $('#megamenu-responsive ul').height() + 180 );
            }
            else{
                $('#header-fluid').height( $('#megamenu-responsive ul').height() + 120 );
            }
        });
        
        
    });
    
    $(window).load(function () {
        $('#megamenu .root_menu').each(function(index){
            var contextWidth = $(this).children('.megamenu_context').width();
            
            var offset = $(this).offset();
            
            var right = offset.left + contextWidth;
            var ww = $(window).width();
            
            if (right > ww){
                var ctxLeft = ww - contextWidth - 40;
                var left = - (offset.left - ctxLeft);
            }else{
                var left = -20;
            }
            
            $(this).children('.megamenu_context').css('left', left);
        });
    });
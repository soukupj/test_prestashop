$('document').ready(function(){
    
   
    /* Toggle the unfold class in footer blocks */
    $('.open-close-footer').click(function(){
        var ww = $(window).width();
        if (ww < 480){
            $(this).parent().toggleClass('unfold');
            $(this).parent().children('.block_content').toggleClass('unfold');
            return false;
        }
    });



});
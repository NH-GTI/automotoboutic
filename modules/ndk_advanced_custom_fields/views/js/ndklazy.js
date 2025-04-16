/**
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2020 Hendrik Masson
 *  @license   Tous droits réservés
*/
$(function(){
	$('img').each(function(){
	  $(this).attr('data-original-ndk-src', $(this).attr('src')).attr('src', '').addClass('lazyLoadMe');
	  lazyload();
	});
	
	$(window).on('scroll', function(event) {
	  lazyload();
	});
});


function lazyload(){
	 $('.lazyLoadMe').each(function(){
         if(checkVisible($(this))) {
           $(this);
           $(this).attr('src', $(this).attr('data-original-ndk-src')).removeClass('lazyLoadMe');
			 }
    })
}


function checkVisible( elm, evalType ) {
    evalType = evalType || "visible";

    var vpH = $(window).height(), // Viewport Height
        st = $(window).scrollTop(), // Scroll Top
        y = $(elm).offset().top,
        elementHeight = $(elm).height();

    if (evalType === "visible") return ((y < (vpH + st)) && (y > (st - elementHeight)));
    if (evalType === "above") return ((y < (vpH + st)));
}
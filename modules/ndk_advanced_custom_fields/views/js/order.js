/**
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*/


$(document).ready(function(){
	$.when(
		redesignTextFields()
	).done(function(){
		/*setTimeout(function(){
			equalheight('.typedText li');
		}, 2000)*/
	});
	
/*
	$('.ndk-rowcustomization .fancyboxButton').each(function(){
	  cartRenderImage($(this));
	})
*/
});


/*
prestashop.on('updateCart', function(){
	setTimeout(function(){
		$('.ndk-rowcustomization .fancyboxButton').each(function(){
			cartRenderImage($(this));
		})
	}, 500)
	
})
*/

function cartRenderImage(button){
  $.ajax({
	    'async': true,
	    type: "GET",
	    'global': false,
	    'dataType': 'html',
	    'url': button.attr('href'),
	    'success': function (data) {
        button.parent().parent().parent().find('.product-image').html(data).find('.print-page-breaker').not(":eq(0)").remove()
	    }
    
	});
}
   
   
   
function redesignTextFields(){
	if (typeof(redesignTextFields_Override) == 'function') { 
		return redesignTextFields_Override();
	}
	$('.typedText li').each(function(){
		$(this).addClass('clearfix')
	  originalText = $(this).html();
	  splitted = originalText.split(':');
	  finalText = '<b class="clear clearfix">'+(typeof(splitted[0])!='undefined' ? splitted[0].replace(/;/gi, '<br/>') : '')+'</b>'+(typeof(splitted[1])!='undefined' ? splitted[1].replace(/;/gi, '<br/>') : '')+(typeof(splitted[2])!='undefined' ? splitted[2].replace(/;/gi, '<br/>') : '');
	  $(this).html(finalText);
	});
	
	$('.product-customization-line .value').each(function(){
	  originalText = $(this).text();
	  finalText = originalText.replace(/;/gi, '<br/>');
	  $(this).html(finalText);
	});
}

equalheight = function(container){
	var currentTallest = 0,
	     currentRowStart = 0,
	     rowDivs = new Array(),
	     $el,
	     topPosition = 0;
	 $(container).each(function() {
	   $el = $(this);
	   //$el.height('auto');
	   topPostion = $el.position().top;
	     rowDivs.push($el);
	     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
	   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
	     rowDivs[currentDiv].height(currentTallest);
	   }
	 });
}

equalheightbyRow = function(container){
	var currentTallest = 0,
	     currentRowStart = 0,
	     rowDivs = new Array(),
	     $el,
	     topPosition = 0;
	 $(container).each(function() {
	   $el = $(this);
	   $($el).height('auto')
	   topPostion = $el.position().top;
	   topPositionParent = $el.parent().parent().position().top;
	
	   if (currentRowStart != topPostion) {
	     for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
	       rowDivs[currentDiv].height(currentTallest);
	     }
	     rowDivs.length = 0; // empty the array
	     currentRowStart = topPostion;
	     currentTallest = $el.height();
	     rowDivs.push($el);
	   } else if (currentRowStart != topPositionParent) {
	   	for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
	   	  rowDivs[currentDiv].height(currentTallest);
	   	}
	   	rowDivs.length = 0; // empty the array
	   	currentRowStart = topPositionParent;
	   	currentTallest = $el.height();
	   	rowDivs.push($el);
	   } else {
	     rowDivs.push($el);
	     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
	  }
	   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
	     rowDivs[currentDiv].height(currentTallest);
	   }
	 });
}



$(document).on('click', '.fancyboxButton', function(e){
	if($(window).width() > 400)
	{
		e.preventDefault();
		url = $(this).attr('href');
		if (!!$.prototype.fancybox)
			$.fancybox({
				'afterLoad' : function(){
					$contents = $(".fancybox-iframe").contents();
					$head = $contents.find("head");
					
					if($contents.find('.print-page-breaker').length > 4)
						$head.append('<style>.print-page-breaker{width:30%;display:inline-block;margin:1%; border:1px solid #efefef} body, html{display:table; text-align:center}</style>');
					else if($contents.find('.print-page-breaker').length > 1)
						$head.append('<style>.print-page-breaker{width:48%;display:inline-block;margin:1%;border:1px solid #efefef} body, html{display:table; text-align:center}</style>');
					else
					$head.append('<style>body, html{display:table; text-align:center;margin:auto} .print-page-breaker{display:inline-block}</style>');
					
				},
				'padding':  0,
				'type':     'iframe',
				'href':     url,
							
				
			});
		}
})
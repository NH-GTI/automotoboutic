(function ($) {
	$(document).ready(function () {
		// Choose a configuration
		$('.form-choose-config-color').on('click', 'input[name="id_conf-id_product"]', function (e) {
			surmesureChooseConfProduct($(this).data('id_conf'), $(this).data('id_product'));
		});
		
		// Choose a color
		$('.form-choose-config-color').on('click', 'input[name="color"]', function (e) {
			$('.block_choice_color label').removeClass('active');
			$(this).parents('label').addClass('active');
			var selectedConfProd = $('input[name="id_conf-id_product"]:checked').eq(0);
			surmesureChooseConfProduct(selectedConfProd.data('id_conf'), selectedConfProd.data('id_product'));
			$('.block_color_image.imgcontainer').hide();
			$('#imgcolors-' + $(this).data('color_alias')).show();
		});
		
		// Init Recap
		$('input[name="color"]:checked').trigger('click');
        
		$(".img-fancybox").fancybox();
	});
})(jQuery);

function surmesureChooseConfProduct (id_conf, id_product) {
	var selectedConfItem = $('.block_choice_container.id_conf-id_product-' + id_conf + '-'+ id_product).eq(0);
	var selectedConfRadio = $('#id_conf-id_product-' + id_conf + '-'+ id_product);
	var selectedColorRadio = $('.block_choice_color input[name="color"]:checked').eq(0);
	
	$('#surmesure-id_conf').val(id_conf);
	$('#surmesure-id_product').val(id_product);
	$('#surmesure-color').val(selectedColorRadio.val());
	$('#surmesure-price').val(selectedConfItem.find('.price').eq(0).html());
	console.log($('.block_recap_config .price_container .price').html());

	$('.block_recap_config .img_choice img').attr('src', selectedConfRadio.data('image'));
	$('.block_recap_config .img_choice img').removeClass('hide');
	$('.block_recap_config .details_choice_text').html( $(selectedConfRadio.data('desc_target')).html() );
	$('.block_recap_config .color_palette').css('background-image', selectedColorRadio.data('background'));
	$('.block_recap_config .color_palette_text').html( selectedColorRadio.data('color_name') );
	$('.block_recap_config .price_cta .price').html( selectedConfItem.find('.price').eq(0).html() );
	$('.block_recap_config .price_cta .taxe_unit').html( selectedConfItem.find('.taxe_unit').eq(0).html() );
    
    $('.block_choice_container').removeClass('active');
    selectedConfItem.addClass('active');
}
function loadColor(alias,j) {
	$(".imgcontainer").css('display',"none");
	$("#imgcolors-" + alias).css('display',"block");
	$(".thumb").removeClass('active');
	$("#thumb-" + j + "-1").addClass('active');
	$(".links").removeClass('active');
	$("#link-" + j).addClass('active');
	$("#radio-" + alias).attr('checked','true');

}
function changeImg(j, k, alias) {
	var imgSrc = $('#link-'+ j +'-'+ k).attr('href');
	$("#main-img-" + j).attr('src', imgSrc);
}
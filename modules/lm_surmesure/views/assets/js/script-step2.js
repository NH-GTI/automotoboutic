(function($) {
	$(document).ready(function() {
		$('body #step-3 a.poppup_image').click(function(event) {
			event.preventDefault();
			PreviewImage($(this).attr('href'));
		});

		$('#seo-show-more').click(function(event) {
			event.preventDefault();
			let seeMore = document.getElementById('seo-more'); 
			let seeShowMoreButton = document.getElementById('seo-show-more'); 
			if (seeMore.style.display === "none" || seeMore.style.display === "") {
				seeMore.style.display = "inline";
				seeShowMoreButton.innerHTML = "Réduire";
			} 
			else {
				seeMore.style.display = "none";
				seeShowMoreButton.innerHTML = "...Lire plus";
			}
		});
	});
})(jQuery);

function changeImg(alias, image) {
	$("#main-img-" + alias).attr('src', surmesure_img_url + alias + "/" + image);
}

PreviewImage = function(uri) {
	//Get the HTML Elements
	imageDialog = $("#dialog-img-popup");
	imageTag = $('#image_popup');
    
	//Split the URI so we can get the file name
	uriParts = uri.split("/");
    
	//Set the image src
	imageTag.attr('src', uri);
    
	//When the image has loaded, display the dialog
	imageTag.load(function() {
		$('#dialog-img-popup').dialog({
			modal: true,
			resizable: true,
			draggable: false,
			width: 'auto',
			title: uriParts[uriParts.length - 1]
		});
	});
}
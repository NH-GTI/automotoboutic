(function ($) {
	$(document).ready(function () {
		//----- OPEN
		
		//----- CLOSE
		$('[data-popup-close]').on('click', function(e)  {
			var targeted_popup_class = $(this).attr('data-popup-close');
			$('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);
			e.preventDefault();
		});
		
	});
})(jQuery);

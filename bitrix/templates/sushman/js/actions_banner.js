var pageY;
var pageX;
$.showBanner = function(banner) {
	if (banner){
		$('body').append('<div id="actionBanner" style="display:none">'+banner+'</div><a class="banneropen" href="#actionBanner"></a>');
		$('.banneropen').fancybox({}).click();
	}
}
$.checkCookie = function() {
	
	if ($.cookie('showed')) return true;

	return false;
	
}	

$(document).ready(function(){

	$actionpage = $('.is_action_popup').html();
	if ($actionpage) {
		$.cookie('showed', 1, { expires: 1 });
	}
	allsumm = parseInt($('#allSum_FORMATED').text());
	if (allsumm){
			if ($.checkCookie()) return false;
			inputData = {'summ': allsumm};
	
			$.ajax({
				url:'/specials/getbanner.php',
				data: inputData,
				type: 'GET',
				success: function(outputData) {
					$.showBanner(outputData);
					$.cookie('showed', 1, { expires: 1 });
				}
			});

	}


	if ($.checkCookie()) return false;

	$(document).mousemove(function(event){

		if ($.checkCookie()) return false;

		pageX = event.pageX
		pageY = event.pageY

		var ev = event;
		//console.log(pageX);
		if (pageY <= 30 || pageX <= 30){

			setTimeout(function(){

				if (ev.pageY == pageY && ev.pageX == pageX){
					inputData = {};
					if (allsumm){
						inputData = {'summ': allsumm};
					}
					

					$.ajax({
						url:'/specials/getbanner.php',
						data: inputData,
						type: 'GET',
						success: function(outputData) {
							$.showBanner(outputData);
							$.cookie('showed', 1, { expires: 1 });
						}
					});

				}

			}, 60);

		}


	})
})
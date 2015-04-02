(function ($) {
	Drupal.behaviors.getMap = {
		attach: function (context, settings) {
			var resLatLng = new google.maps.LatLng(settings.resGeocode.lat, settings.resGeocode.lng);
			var mapOptions = {
				center: resLatLng,
				zoom: 16,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			var map = new google.maps.Map(document.getElementById("res-map"),
				mapOptions);
			var marker = new google.maps.Marker({
				position: resLatLng,
				map: map
			});
		}
	}
})(jQuery);
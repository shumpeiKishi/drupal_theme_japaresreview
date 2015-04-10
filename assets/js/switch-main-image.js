(function ($) {
	Drupal.behaviors.switchMainImage = {
		attach: function (context, settings) {
			var $mainImg = $('#comment-image-main'),
			$mainImgLink = $('#fancybox-comment-image-main')
			$thumbPhotos = $('.comment-image-thumbs .res-thumb-container'),
			$thumbsBox = $('#comment-image-thumbs'),
			$thumbsBoxWrapper = $('#comment-image-thumbs-wrapper');
			$upBtn = $('#comment-image-thumb-up'),
			$downBtn = $('#comment-image-thumb-down');

			var changeResImage = function () {
				var thumbImgUrl = $(this).css('background-image').replace(/url\(|\"|\'|\)/ig,'');
				$mainImgLink.attr('href', thumbImgUrl);
				$mainImg.attr('src', thumbImgUrl);
			};
			var scrollThumbBox = function (direction) {
				var boxHeight = $thumbsBox.height(),
				wrapperHeight = $thumbsBoxWrapper.height();
				switch (direction) {
					case 'up' : {
						$thumbsBox.animate({top: '+=60px'});
						break;
					}
					case 'down' : {
						$thumbsBox.animate({top: '-=60px'});
						break;
					}
				}

				setTimeout(function () {
					if ($thumbsBox.position().top > 0) {
						$thumbsBox.animate({top: '0px'});
					} else if ($thumbsBox.position().top <= (wrapperHeight - boxHeight)) {
						$thumbsBox.animate({top: (wrapperHeight - boxHeight) + 'px'});
					}
				}, 100);
			}
			$upBtn.click(function () {
				scrollThumbBox('up');
			});
			$downBtn.click(function () {
				scrollThumbBox('down');
			});
			$thumbPhotos.click(changeResImage);
		}
	}
})(jQuery);

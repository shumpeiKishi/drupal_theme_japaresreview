(function ($) {
	Drupal.behaviors.myFancybox = {
		attach: function (context, settings) {
				// Add rel attribute for each group to make image gallery.
				var _add_rel_attr = function (anchor_wrapper, value) {
					$anchor_wrapper = $(anchor_wrapper);
					for (var i = 0, len = $anchor_wrapper.length; i < len; i ++) {
						$anchor_wrapper.eq(i).find('a').attr('rel', value + '-' + i);
					}
				};

				$('.fancybox').fancybox(); // General popup image
				$('#fancybox-comment-image-main').fancybox(); // Main image for restaurant pages.

				// Each comment images
				_add_rel_attr('.field-name-field-photos', 'field-name-field-photos');
				$('.field-name-field-photos a').fancybox(); 

				// Menu images at restaurant pages and homepage new reviews.
				$('.field-name-field-menu a').fancybox();
			}
		}	
	})(jQuery);

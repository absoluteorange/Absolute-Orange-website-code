
define(['jquery'], function ($) {

 	var Navigation= function() {
		var _init = function () {
		    var $navSection = $('nav');
		    $('#page-content-container').prepend($navSection);
		    $('.icon-mobile-menu').on('click touch', function(e) {
		       $navSection.toggleClass('mobile-enabled');
		       e.preventDefault();

		    })
		}
		_init();
	}
	return Navigation;
	

 });



define(['jquery'], function ($) {

 	var Navigation= function() {
		var _init = function () {
		    var $navSection = $('nav');
		    $('header').after($navSection);
		    $('.icon-mobile-menu').on('click touch', function() {
		       $navSection.toggleClass('mobile-enabled');
		    })
		}
		_init();
	}
	return Navigation;
	

 });


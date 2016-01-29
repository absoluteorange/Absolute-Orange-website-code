/**
* Created by amyvarga on 11/03/2014.
*/


//window.Mobile = (window.Mobile || {});
var Navigation= function() {
var _moveNavigation = function () {
    var $navSection = $('nav');
    $('header').after($navSection);
    $('.icon-mobile-menu').on('click touch', function() {
       $navSection.toggleClass('mobile-enabled');
    })
}
_moveNavigation();
}




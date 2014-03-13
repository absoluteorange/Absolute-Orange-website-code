define(['use!Backbone', 'Router', 'globals'], function (Backbone, Router, globals) {
	if (window.location == globals.domain+'web') {
		window.location = globals.domain+'web#gallery';
	}
	var app = new Router();
	Backbone.history.start({pushState: true, root: "/webApp/"});
	features.load;
	if (features.list.matchMedia == false) {	
	  var respond = require(['polyfills/respond']);
	}
});
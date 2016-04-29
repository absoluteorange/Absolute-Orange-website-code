define(['Backbone', 'Router', 'globals'], function (Backbone, Router, globals) {
	if (window.location == globals.domain+'web') {
		window.location = globals.domain+'web#gallery';
	}
	var app = new Router();
	Backbone.history.start({pushState: true, root: "/Gallery/"});
});

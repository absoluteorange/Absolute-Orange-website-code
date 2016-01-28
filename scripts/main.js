require.config ({
	paths: {
		'use': 'plugins/use',
		'text': 'plugins/text',
		'order': 'plugins/order',
		'globals': '../dynamicScripts/jsGlobals',
		'utils': 'utils',
		'features': 'features',
		'isotope': 'jquery.isotope',
		'domready': 'plugins/domReady',
		'unveil': 'plugins/jquery.unveil.min',
		'lightbox': 'plugins/lightbox-2.6.min',
		'jquery': 'libs/jquery-1.7.1.min',
		'underscore': 'libs/underscore',
		'Backbone': 'libs/backbone-min',
		'Mustache': 'plugins/mustache',
		'Photo': 'app/models/Photo',
		'PhotoCollection': 'app/collections/PhotoCollection',
		'PhotoListView': 'app/views/PhotoListView',
		'PhotoItemView': 'app/views/PhotoItemView',
		'PhotoItemBase64View': 'app/views/PhotoItemBase64View',
		'Login': 'app/models/Login',
		'LoginView': 'app/views/LoginView',
		'User': 'app/models/User',
		'RegisterView': 'app/views/RegisterView',
		'ThankyouView': 'app/views/ThankyouView',
		'Authenticate': 'app/models/Authenticate',
		'Router': 'app/Router',
		'AppView': 'app/appview',
		'Lang': '../dynamicScripts/jsLanguage',
		'templates': '../sharedTemplates/webapp'
	},
	use: {
		'underscore': {
	        attach: "_"
		},
		'Backbone': {
	        deps: ["use!underscore", "jquery"],
	        attach: function(_, $) {
	          return Backbone;
	        }
		},
		'utils': {
			deps: ['use!underscore', 'use!Backbone'],
			attach: function (_, Backbone) {
				return utils;
			}
		},
		'features': { 
			deps: ['use!utils'],
			attach:  function (utils) {
				return features;
			}
		},
		'Mustache': {
			attach: 'Mustache'
		},
		'unveil': {
			deps: ['jquery'],
	        attach: function($) {
	          return $;
	        }
		},
		'isotope': {
			deps: ['jquery'],
	        attach: function($) {
	          return window.$.Isotope;
	        }
		},
		'lightbox': {
			deps: ['jquery'],
	        attach: function($) {
	          return $;
	        }
		}
	},
	baseUrl: '/scripts/',
	urlArgs: 'v0.0.3'
});

require (['webapp'], function () {});
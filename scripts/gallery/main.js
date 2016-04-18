require.config ({
	paths: {
		'use': 'plugins/use',
		'text': 'plugins/text',
		'order': 'plugins/order',
		'globals': '../dynamicScripts/jsGlobals',
		'utils': 'utils/utils',
		'features': 'utils/features',
		'isotope': 'lib/jquery.isotope',
		'domready': 'plugins/domReady',
		'unveil': 'plugins/jquery.unveil.min',
		'lightbox': 'plugins/lightbox',
		'jquery': 'lib/jquery-1.7.1.min',
		'underscore': 'lib/underscore.min',
		'Backbone': 'lib/backbone-min',
		'Mustache': 'plugins/mustache',
		'Photo': 'gallery/models/Photo',
		'PhotoCollection': 'gallery/collections/PhotoCollection',
		'PhotoListView': 'gallery/views/PhotoListView',
		'PhotoItemView': 'gallery/views/PhotoItemView',
		'PhotoItemBase64View': 'gallery/views/PhotoItemBase64View',
		'Login': 'gallery/models/Login',
		'LoginView': 'gallery/views/LoginView',
		'Register': 'gallery/models/Register',
		'RegisterView': 'gallery/views/RegisterView',
		'ThankyouView': 'gallery/views/ThankyouView',
		'Authenticate': 'gallery/models/Authenticate',
		'Validation': 'gallery/models/Validation',
		'Router': 'gallery/Router',
		'AppView': 'gallery/appview',
		'Lang': '../dynamicScripts/jsLanguage',
		'templates': '../sharedTemplates/gallery'
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

require (['gallery/gallery'], function () {});

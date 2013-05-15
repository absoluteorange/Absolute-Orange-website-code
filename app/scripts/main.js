require.config ({
	paths: {
		'use': 'plugins/use',
		'text': 'plugins/text',
		'order': 'plugins/order',
		'globals': '../dynamicScripts/jsGlobals',
		'InputClearer': 'InputClearer',
		'utils': 'utils',
		'features': 'features',
		'IsotopeLoader': 'isotopeLoader',
		'Isotope': 'jquery.isotope',
		'domready': 'plugins/domReady',
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
		'Router': 'app/Router',
		'AppView': 'app/appview',
		'Lang': '../dynamicScripts/jsLanguage',
		'templates': '../sharedTemplates/webapp'
	},
	use: {
		'InputClearer': {
			attach: 'InputClearer'
		},
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
			deps: ['use!InputClearer', 'use!underscore', 'use!Backbone'],
			attach: function (InputClearer, _, Backbone) {
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
		}
	},
	baseUrl: '/scripts/',
	urlArgs: 'v0.0.3'
});

require (['webapp'], function () {});
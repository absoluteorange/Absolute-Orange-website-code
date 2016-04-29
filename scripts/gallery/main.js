require.config ({
	baseUrl: '/scripts/',
	urlArgs: 'v0.0.3',
	paths: {
		'text': 'lib/text',
        'Utils': 'utils/utils',
		'globals': '../dynamicScripts/jsGlobals',

		'domready': 'lib/dom-ready',
		'unveil': 'plugins/jquery.unveil',
		'lightbox': 'plugins/lightbox',
		'jquery': 'lib/jquery-1.7.1.min',
		'underscore': 'lib/underscore.min',
		'Backbone': 'lib/backbone-min',
		'Mustache': 'lib/mustache.min',
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
		'validatorHelper': 'utils//validatorHelper',
		'Router': 'gallery/Router',
		'AppView': 'gallery/appview',
		'lang': '../dynamicScripts/jsLanguage',
		'templates': '../sharedTemplates/gallery'
	},
	shim: {
		'underscore': {
	        exports: "_"
		},
		'backbone': {
	        deps: ["underscore", "jquery"],
	        exports: 'Backbone'
		},
		'Utils': ['underscore'],
		'mustache': {
			exports: 'Mustache'
		},
		'unveil': {
			deps: ['jquery'],
	        init: function($) {
	          return $;
	        }
		},
		'lightbox': {
			deps: ['jquery'],
	        init: function($) {
	          return $;
	        }
		}
	}
});

require (['gallery/gallery'], function () {});

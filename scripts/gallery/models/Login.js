define(['Backbone', 'lang', 'Utils', 'globals'], function(Backbone, lang, Utils, globals){
    var Login = Backbone.Model.extend ({
		urlRoot: "/api/login/validate/format/json",
		defaults: {
			'values': { 'email': lang['email'], 'password': lang['password'] },
			'csrf': utils.getCookie('csrf'),
            'default': {},
            'errors': {},
            'registerUrl': globals.registerUrl
		},
	});
	return Login;
});

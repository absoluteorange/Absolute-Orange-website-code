define(['Backbone', 'Utils', 'validatorHelper'], function(Backbone, Utils, validatorHelper){
    var Login = Backbone.Model.extend ({
		urlRoot: "/api/login/validate/format/json",
		attributes: {
			'email': '', 
            'password': '',
			'csrf_secure': Utils.getCookie('csrf')
		}
	});
	return Login;
});

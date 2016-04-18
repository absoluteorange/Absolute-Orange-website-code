define(['use!Backbone'], function(Backbone){
    var Login = Backbone.Model.extend ({
		urlRoot: "/api/login/validate/format/json",
		defaults: {
			'email': '',
			'password': '',
			'csrf_secure': ''
		},
	});
	return Login;
});

define(['Backbone', 'validatorHelper'], function(Backbone, validatorHelper){
	var user = Backbone.Model.extend ({
		urlRoot: "/api/users/register/format/json",
		defaults: {
			'email': '',
			'password': '',
			'username': '',
			'csrf_secure': '',
		}
	});
	return user;
});

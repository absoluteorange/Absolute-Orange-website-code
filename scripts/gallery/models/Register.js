define(['use!Backbone', 'Validation'], function(Backbone, Validation){
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

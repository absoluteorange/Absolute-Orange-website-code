define(['Backbone'], function(Backbone){
	var authenticate = Backbone.Model.extend ({
		urlRoot: "/api/authentication/authenticate/format/json",
		defaults:{
			'status': ''
		}
	});
	return authenticate;
});

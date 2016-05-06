define(['Backbone'], function(Backbone){
	var Authenticate = Backbone.Model.extend ({
		urlRoot: "/api/authentication/authenticate/format/json",
		defaults:{
			'status': ''
		}
	});
	return Authenticate;
});

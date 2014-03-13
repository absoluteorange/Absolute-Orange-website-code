define(['use!Backbone', 'Lang'], function(Backbone, Lang){
	var user = Backbone.Model.extend ({
		urlRoot: "/api/users/register/format/json",
		defaults: {
			'email': '',
			'password': '',
			'username': '',
			'csrf_secure': '',
			'newRegistration': ''
		},
		validate: function (attr) {
			var failed = false,
				errors = new Object(),
				fields = new Object({email: attr.email, password: attr.password, username: attr.username});
			for (var i in fields) {
				if (fields[i].toLowerCase() == '' || fields[i].toLowerCase().indexOf('your') != -1) {
					errors[i] = Lang['default_value'] + i +'.';
					failed = true;
				}; 
			}
			if (errors['email'] == undefined) {
				if (!this.validateEmail(fields['email'])) {
					errors.email = Lang['email_invalid'];
					failed = true;
				};
			}
			if (errors['password'] == undefined) {
				if (!this.validatePassword(fields['password'])) {
					errors.password = Lang['password_invalid'];
					failed = true;
				};
			}
			if (errors['name'] == undefined) {
				if (!this.validateName(fields['username'])) {
					errors.name = Lang['username_invalid'];
					failed = true;
				};
			}
			if (failed == true) {
				return errors;
			}
		},
		validateEmail: function (email) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		},
		validatePassword: function (password) {
			//at least one uppercase letter and one number
			var re = /[A-Z].*\d|\d.*[A-Z]/;
			return re.test(password);
		},
		validateName: function (name) {
			var re = /^[a-zA-Z ]+$/;
			return re.test(name);
		}
	});
	return user;
});
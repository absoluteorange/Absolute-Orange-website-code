define(['use!utils', 'use!Backbone', 'Login', 'text!templates/login.html', 'use!Mustache', 'Lang'], function(Utils, Backbone, Login, LoginTemplate, Mustache, Lang){
	var LoginView = Backbone.View.extend ({
		 template:_.template(LoginTemplate),
		 initialize:function () {
			 this.csrf = utils.getCookie('csrf');
		 },
		 render:function (eventName) {
			 $('html, body').animate({ scrollTop: 0 }, 0);
			 document.title = 'Login';
			 $(this.el).empty();
			 $(this.el).append('<header><h1>Login</h1></header>');
			 if (this.options.registerModel.attributes.newRegistration == 'true') {
				 this.newRegistration = "<div id='form-helper'><p>"+Lang['helper_succesfully_registered']+"</p></div>";
			 }
			 var view = {
				formUrl: '',
				registerUrl: '#register',
				csrf: this.csrf,
				passwordHelper: Lang['helper_password'],
				usernameHelper: Lang['helper_username'],
			 	values: new Object ({email: Lang['email'], password: Lang['password']}),
			 	newRegistration: this.newRegistration
			 };
			 this.htm = Mustache.render(this.template(), view);
			 $(this.el).append(this.htm);
			 utils.inputClearer();
		 },
		 events: {
			 'click button#loginButton' : 'login',
			 'click a#openForgottenPassword' : 'openView',
			 'click a#openRegister' : 'openView'
		 },
		 login: function () {
			 $(this.el).find('.btn').html('Loading...');
			 var arr = $('#loginForm').serializeArray();
			 var data = _(arr).reduce(function(acc, field) {
			      acc[field.name] = field.value;
			      return acc;
			    }, {});
			 var thisObj = this;
			 this.options.loginModel.save(data, {
				 error: function(model, errors) {
					 for (var i in errors) {
						 $('#'+i).addClass('error');
						 $('#'+i).children('.help-inline').html(errors[i]);
					 }
					 if (errors.status != undefined && ($('.form-helpers'))) {
						 $('#form-helper').remove();
						 if (errors.status == '400') {
							 $('#loginForm').append("<div id='form-helper'><p>"+Lang['password_incorrect']+"</p></div>");
						 }
						 if (errors.status == '404') {
							 $('#loginForm').append("<div id='form-helper'><p>"+Lang['email_not_recognised']+"</p></div>");
						 }
						 if (errors.responseText != 'null') {
							 var formErrors = $.parseJSON(errors.responseText).error;
							 for (var i in formErrors) {
								 if (formErrors[i] != '') {
									 $('#'+i).find('.help-inline').html(formErrors[i]);
								 }
							 }
						 }
					 }
					 $('#loginForm').find('.btn').html('Log in');
				 }, success: function(model) {
					 thisObj.openView();
				 }
			 });
			 return false;
		 },
		 close: function () {
		       $('#login').unbind();
		       $('#login').remove();
		 },
		 openView: function (e) {
			 this.close();
			 if (e == undefined) {
				 this.options.loginModel.set({status: 'true'}, {silent: true});
				 Backbone.history.navigate('gallery', true);
			 } else {
				 var view = e.currentTarget.id.replace('open', '').toLowerCase();
				 Backbone.history.navigate(view, true);
			 }
			 return false;
		 }
	});
	return LoginView;
});
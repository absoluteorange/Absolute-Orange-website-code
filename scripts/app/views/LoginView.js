define(['use!utils', 'use!Backbone', 'Login', 'text!templates/login.html', 'use!Mustache', 'Lang'], function(Utils, Backbone, Login, LoginTemplate, Mustache, Lang){
	var LoginView = Backbone.View.extend ({
		 template:_.template(LoginTemplate),
		 initialize:function () {
			 this.csrf = utils.getCookie('csrf');
		 },
		 render:function (eventName) {
			 $('html, body').animate({ scrollTop: 0 }, 0);
			 $(this.el).find('h1').html("Login");
			 document.title = 'Login';
			 if (this.options.registerModel.attributes.newRegistration == 'true') {
				 this.newRegistration = "<div id='form-helper'><p>"+Lang['helper_succesfully_registered']+"</p></div>";
			 }
			 if ($(this.el).find('#login').length == 0) {
				 var view = {
					formUrl: '',
					registerUrl: 'javascript: void(0);',
					csrf: this.csrf,					
					passwordHelper: Lang['helper_password'],
				 	values: new Object ({email: Lang['email'], password: Lang['password']}),
				 	newRegistration: this.newRegistration
				 };
				 this.htm = Mustache.render(this.template(), view);
				 $(this.el).append(this.htm);
			 } else {
				 $('#login').fadeIn('fast');	
			 } 
		 },
		 events: {
			 'click button#loginButton' : 'login',
			 'click a#openForgottenPassword' : 'openView',
			 'click a#openRegister' : 'openView',
			 'click input#openGallery' : 'openView'
		 },
		 login: function () {
			 var $submitButton = $(this.el).find('#loginButton');
			 $submitButton.html('Loading...');
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
					 $submitButton.html('Submit');
				 }, success: function(model) {
					 thisObj.options.authenticateModel.fetch();
					 thisObj.openView();
				 }
			 });
			 return false;
		 },
		 close: function () {
		       
		 },
		 openView: function (e) {
			 var thisObj = this;
			 $('#login').fadeOut('slow', function () {
				 if (e == undefined) {
					 thisObj.options.loginModel.set({status: 'true'}, {silent: true});
					 Backbone.history.navigate('gallery', true);
				 } else {
					 var view = e.currentTarget.id.replace('open', '').toLowerCase();
					 Backbone.history.navigate(view, true);
				 }
				 return false;
			 });
		 }
	});
	return LoginView;
});
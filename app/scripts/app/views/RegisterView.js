define(['use!utils', 'use!Backbone', 'User', 'Login', 'text!templates/register.html', 'use!Mustache', 'Lang'], function(Utils, Backbone, User, Login, RegisterTemplate, Mustache, Lang){
	var RegisterView = Backbone.View.extend ({
		 template:_.template(RegisterTemplate),
		 initialize: function () {
			 this.csrf = utils.getCookie('csrf');
		 },
		 render:function (eventName) {
			 $('html, body').animate({ scrollTop: 0 }, 0);
			 document.title = 'Register';
			 $(this.el).empty();
			 $(this.el).append('<header><h1>Register</h1></header>');
			 var view = {
				formUrl: '',
				loginUrl: 'web#login',
				csrf: this.csrf,
				passwordHelper: Lang['helper_password'],
				usernameHelper: Lang['helper_username'],
			 	values: new Object ({email: Lang['email'], username: Lang['username'], password: Lang['password']})
			 };
			 this.htm = Mustache.render(this.template(), view);
			 $(this.el).append(this.htm);
			 utils.inputClearer();
		 },
		 events: {
			 'click button#registerButton' : 'register',
			 'click a#openLogin' : 'openView'
		 },
		 register: function () {
			 $(this.el).find('.btn').html('Loading...');
			 var arr = $('#registerForm').serializeArray();
			 var data = _(arr).reduce(function(acc, field) {
			      acc[field.name] = field.value;
			      return acc;
			    }, {});
			 var thisObj = this;
			 this.options.registerModel.save(data, {
				 error: function(model, errors) {
					 for (var i in errors) {
						 $('#'+i).addClass('error');
						 $('#'+i).children('.help-inline').html(errors[i]);
					 }
					 if (errors.status != undefined && ($('.form-helpers'))) {
						 $('#form-helper').remove();
						 if (errors.status == '400') {
							 $('#registerForm').append("<div id='form-helper'><p>"+Lang['registered']+"</p></div>");
						 }
						 if (errors.status == '403') {
							 var formErrors = $.parseJSON(errors.responseText).error;
							 for (var i in formErrors) {
								 if (formErrors[i] != '') {
									 $('#'+i).find('.help-inline').html(formErrors[i]);
								 }	 
							 }
						 }
					 }
					 $('#registerForm').find('.btn').html('Register');
				 }, success: function(model) {
					 thisObj.openView();
				 }
			 });
			 return false;
		 },
		 close: function () {
			 $('#register').unbind();
		     $('#register').remove();
		 },
		 openView: function (e) {
			 this.close();
			 if (e == undefined) {
				 this.options.registerModel.set({newRegistration: 'true'}, {silent: true});
				 Backbone.history.navigate('login', true);
			 } else {
				 var view = e.currentTarget.id.replace('open', '').toLowerCase();
				 Backbone.history.navigate(view, true);				
			 }
		 }
	});
	return RegisterView;
});
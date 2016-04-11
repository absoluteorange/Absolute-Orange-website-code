define(['use!utils', 'use!Backbone', 'User', 'Login', 'text!templates/register.html', 'use!Mustache', 'Lang'], function(Utils, Backbone, User, Login, RegisterTemplate, Mustache, Lang){
	var RegisterView = Backbone.View.extend ({
		 template:_.template(RegisterTemplate),
		 initialize: function () {
			 this.csrf = utils.getCookie('csrf');
		 },
		 render:function (eventName) {
			 $('html, body').animate({ scrollTop: 0 }, 0);
			 $(this.el).find('h1').html("Register");
			 document.title = 'Register';
			 if ($(this.el).find('#register').length == 0) {
				 var view = {
					formUrl: '',
					loginUrl: '',
					csrf: this.csrf,
					passwordHelper: Lang['helper_password'],
					usernameHelper: Lang['helper_username'],
				 	values: new Object ({email: Lang['email'], username: Lang['username'], password: Lang['password']})
				 };
				 this.htm = Mustache.render(this.template(), view);
				 $(this.el).append(this.htm);
			 } else {
				 $('#register').fadeIn('fast');
			 }
		 },
		 events: {
			 'click button#registerButton' : 'register',
			 'click a#openLogin' : 'openView'
		 },
		 register: function () {
			 var $submitButton = $(this.el).find('#registerButton');
			 $submitButton.html('Loading...');
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
					 $submitButton.html('Submit');
				 }, success: function(model) {
					 thisObj.options.authenticateModel.fetch();
					 thisObj.openView();
				 }
			 });
			 return false;
		 },
		 openView: function (e) {
			 var thisObj = this;
			 $('#register').fadeOut('slow', function () {
				 if (e == undefined) {
					 thisObj.options.registerModel.set({newRegistration: 'true'}, {silent: true});
					 Backbone.history.navigate('gallery', true);
				 } else {
					 var view = e.currentTarget.id.replace('open', '').toLowerCase();
					 Backbone.history.navigate(view, true);				
				 }
		 	});
            return false;
		 }
	});
	return RegisterView;
});

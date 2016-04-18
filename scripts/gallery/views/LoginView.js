define(['use!utils', 'use!Backbone', 'Login', 'text!templates/login.html', 'use!Mustache', 'Lang', 'Validation'], function(Utils, Backbone, Login, LoginTemplate, Mustache, Lang, Validation){
	var LoginView = Backbone.View.extend ({
		 template: _.template(LoginTemplate),
		 initialize: function () {
			 this.csrf = utils.getCookie('csrf');
		 },
         el: $('#loginForm'),
		 render: function (eventName) {
			 document.title = 'Login';
			 if (this.$el) {
				 this.$el.fadeIn('fast');	
			 } else {
				 var view = {
					csrf: this.csrf,					
				 	values: new Object ({email: Lang['email'], password: Lang['password']}),
				 };
				 this.htm = Mustache.render(this.template(), view);
				 $('#subscribe').append(this.htm);
			 } 
		 },
		 events: {
			 'click button#loginButton' : 'login',
			 'click a#forgottenPassword' : 'openView',
			 'click a#register' : 'openView'
		 },
		 login: function () {
             Validation.disableButton($('#loginButton'));
             var data = {};
             this.$el.find('input').each( function () {
                 data[$(this).attr('name')] = $(this).val();
             });
             Validation.removeErrors(data);
             var validationErrors = Validation.validate(data);
			 if (_.isEmpty(validationErrors)) {
                 this.model.save (data, {
                     success: function(model) {
                         thisObj.options.authenticateModel.fetch();
                         thisObj.openView();
                     }
                 });
             } else {
                Validation.displayErrors(validationErrors);
                Validation.enableButton($('#loginButton'), 'Login');
             }
			 return false;
         },
		 openView: function (e) {
			 this.$el.fadeOut('slow', function () {
                 var view = e.currentTarget.id.replace('open', '').toLowerCase();
                 Backbone.history.navigate(view, true);
				 return false;
			 });
		 }
	});
	return LoginView;
});

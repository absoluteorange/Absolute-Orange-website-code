define(['use!utils', 'use!Backbone', 'Register', 'Login', 'text!templates/register.html', 'use!Mustache', 'Lang', 'Validation'], function(Utils, Backbone, Register, Login, RegisterTemplate, Mustache, Lang, Validation){
	var RegisterView = Backbone.View.extend ({
		 template:_.template(RegisterTemplate),
		 initialize: function () {
			 this.csrf = utils.getCookie('csrf');
		 },
         el: $('#registerForm'),
		 render:function (eventName) {
			 document.title = 'Register';
			 if (this.$el) {
				 $('#register').fadeIn('fast');
			 } else {
				 var view = {
					csrf: this.csrf,
				 	values: new Object ({email: Lang['email'], username: Lang['username'], password: Lang['password']})
				 };
				 this.htm = Mustache.render(this.template(), view);
				 $('#subscribe').append(this.htm);
			 }
		 },
		 events: {
			 'click button#registerButton' : 'register',
			 'click a#login' : 'openView'
		 },
		 register: function () {
             Validation.disableButton($('#registerButton'));
             var formValues = this.$el.serializeArray();
			 var data = _(formValues).reduce(function(arrForm, field) {
			     arrForm[field.name] = field.value;
                 return arrForm;
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
                Validation.enableButton($('#registerButton'), 'Register');
             }
			 return false;
		 },
		 openView: function (e) {
			 this.$el.fadeOut('slow', function () {
                 Backbone.history.navigate('login', true);				
		 	});
            return false;
		 }
	});
	return RegisterView;
});

define(['Backbone', 'Register', 'Login', 'text!templates/register.html', 'Mustache', 'lang', 'validatorHelper'], function(Backbone, Register, Login, RegisterTemplate, Mustache, lang, validatorHelper){
	var RegisterView = Backbone.View.extend ({
		 template:_.template(RegisterTemplate),
		 initialize: function () {
			 //TO DO: no longer using utils this.csrf = utils.getCookie('csrf');
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
             validatorHelper.disableButton($('#registerButton'));
             var formValues = this.$el.serializeArray();
			 var data = _(formValues).reduce(function(arrForm, field) {
			     arrForm[field.name] = field.value;
                 return arrForm;
			 });
             validatorHelper.removeErrors(data);
             var validationErrors = validatorHelper.validate(data);
			 if (_.isEmpty(validationErrors)) {
                 this.model.save (data, {
                     success: function(model) {
                         thisObj.options.authenticateModel.fetch();
                         thisObj.openView();
                     }
                 });
             } else {
                validatorHelper.displayErrors(validationErrors);
                validatorHelper.enableButton($('#registerButton'), 'Register');
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

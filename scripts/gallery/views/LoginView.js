define(['Utils', 
    'globals',
    'Backbone', 
    'Login', 
    'text!templates/headerLogin.html', 
    'text!templates/headerWelcome.html', 
    'text!templates/headerRegister.html', 
    'Mustache', 
    'lang', 
    'validatorHelper'], 
    function(Utils, 
        globals,
        Backbone, 
        Login, 
        templateHeaderLogin,
        templateHeaderRegister,
        templateHeaderWelcome,
        Mustache, 
        lang, 
        validatorHelper){
	var LoginView = Backbone.View.extend ({
		 template: _.template(templateHeaderLogin),
		 initialize: function () {
             this.view = {
                'default': { 'email': lang['email'], 'password': lang['password'] },
                'csrf': utils.getCookie('csrf'),
                'values': {},
                'errors': {},
                'registerUrl': globals.registerUrl
             }
		 },
         el: $('#subscribe'),
		 render: function () {
             this.$el.html(Mustache.render(this.template(), this.view));
		 },
		 events: {
			 'click button#loginButton' : 'login',
			 'click a#register' : 'register'
		 },
		 login: function () {
             var formValues = {};
             var errors = {};
             var thisObj = this;
             validatorHelper.disableButton($('#loginButton'));
             this.$el.find('input').each( function () {
                 formValues[$(this).attr('name')] = $(this).val();
             });
             errors = validatorHelper.validate(formValues);
			 if (_.isEmpty(errors)) {
                 this.model.save (formValues, {
                     success: function(model) {
                         thisObj.options.authenticateModel.fetch();
                         thisObj.$el.remove();
                     }
                 });
             } else {
                this.view.errors = errors;
                this.view.values = formValues;
             }
             this.render();
			 return false;
         },
		 register: function () {
             Backbone.history.navigate('register', true);
             return false;
		 }
	});
	return LoginView;
});

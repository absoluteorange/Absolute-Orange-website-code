define(['Utils', 
    'globals',
    'Backbone', 
    'Login', 
    'text!templates/headerLogin.html', 
    'text!templates/headerWelcome.html', 
    'text!templates/login.html', 
    'Mustache', 
    'lang', 
    'validatorHelper'], 
    function(Utils, 
        globals,
        Backbone, 
        Login, 
        templateHeaderLogin,
        templateHeaderWelcome,
        templateBodyLogin,
        Mustache, 
        lang, 
        validatorHelper){
	var LoginView = Backbone.View.extend ({
		initialize: function (options) {
             this.view = {
                'default': { 'email': lang['email'], 'password': lang['password'] },
                'csrf': Utils.getCookie('csrf'),
                'values': {},
                'errors': {},
                'registerUrl': globals.registerUrl,
                'user': ''
             }
             this.authenticateModel = options.authenticateModel;
             this.setTmplAndContainerToBodyForm = function () {
                this.setElement($('#contentContainer'));
                this.template =  _.template(templateBodyLogin);
             }
             this.setTmplAndContainerToHeaderForm = function () {
                this.setElement($('header'));
                this.template = _.template(templateHeaderLogin);
             }
             this.setTmplAndContainerToHeaderWelcome = function () {
                 this.setElement($('header'));
                 this.template = _.template(templateHeaderWelcome);
                 this.view.user = Utils.getCookie('user-name');
             }
             if (this.authenticateModel.attributes.status === true) {
                 this.setTmplAndContainerToHeaderWelcome();
             } else {
                 this.setTmplAndContainerToHeaderForm();
             }
		 },
		 render: function () {
             $('#subscribe').remove();
             $('.welcome').remove();
             this.$el.append(Mustache.render(this.template(), this.view));
		 },
		 events: {
			 'click button#loginButton' : 'login',
			 'click a#register' : 'register'
		 },
		 login: function () {
             var formValues = {};
             var errors = {};
             var deferred;
             validatorHelper.disableButton($('#loginButton'));
             this.$el.find('input').each( function () {
                 formValues[$(this).attr('name')] = $(this).val();
             });
             this.view.values = formValues;
             errors = validatorHelper.validate(formValues);
			 if (_.isEmpty(errors)) {
                 var deferred = this.model.save(formValues);
                 $.when(deferred).then(this.success.bind(this), this.error.bind(this));
             } else {
                this.view.errors = errors;
                if (validatorHelper.formEmpty(formValues) === false) {
                     Backbone.history.navigate('login', {trigger: false});
                     this.setTmplAndContainerToBodyForm();
                }
                this.render();
             }
			 return false;
         },
         error: function (response) {
            this.setTmplAndContainerToBodyForm();
            switch (response.status) {
                case 400:
                    this.view.errors = validatorHelper.setPasswordError();
                    break;
                case 404:
                    this.view.errors = validatorHelper.setDoesNotExistError();
                    break;
            }
            this.render();
         },
         success: function (response) {
             this.setTmplAndContainerToHeaderWelcome();
             this.authenticateModel.fetch();
             this.render();
         },
		 register: function () {
             Backbone.history.navigate('register', {trigger: true});
             return false;
		 }
	});
	return LoginView;
});

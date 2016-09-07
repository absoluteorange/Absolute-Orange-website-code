define(['Backbone', 
    'PhotoCollection', 
    'PhotoListView', 
    'Login', 
    'Register', 
    'Authenticate', 
    'LoginView', 
    'RegisterView'], 
    function(Backbone, 
        PhotoCollection, 
        PhotoListView, 
        Login, 
        Register, 
        Authenticate, 
        LoginView, 
        RegisterView){
	var AppRouter = Backbone.Router.extend({
        deferred: {},
        routes:{
                "gallery":"gallery",
                "login":"login",
                "register":"register"
        },
        initialize: function () {
            this.myAuthenticate = new Authenticate();
            deferred = this.myAuthenticate.fetch();
            this.myLogin = new Login();
            this.myRegister = new Register();
        },
        gallery: function () {
            this.photoList = new PhotoCollection();
            this.photoListView =  new PhotoListView({collection: this.photoList, authenticateModel: this.myAuthenticate});
            this.photoList.fetch({reset: true});
            $.when(deferred).done(this.initialiseLogin.bind(this));
        },
        login: function () {
            $.when(deferred).done(this.initialiseLogin.bind(this));
        },
        register: function () {
            this.registerView = new RegisterView({model: this.myRegister});
            this.registerView.render();
        },
        initialiseLogin: function () {
            this.loginView = new LoginView({model: this.myLogin, authenticateModel: this.myAuthenticate});
            this.loginView.render();
        }
    });
	return AppRouter;
});

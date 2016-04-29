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
        routes:{
                "gallery":"gallery",
                "login":"login",
                "register":"register"
        },
        initialize: function () {
            this.myAuthenticate = new Authenticate();
            this.myAuthenticate.fetch();
            this.myLogin = new Login();
            this.myRegister = new Register();
        },
        gallery: function () {
            this.photoList = new PhotoCollection();
            this.photoListView =  new PhotoListView({collection: this.photoList, authenticateModel: this.myAuthenticate});
            //this.photoListView.render();
            this.photoList.fetch({reset: true});
        },
        login: function () {
            this.loginView = new LoginView({model: this.myLogin});
            this.loginView.render();
        },
        register: function () {
            this.registerView = new RegisterView({model: this.myRegister});
            this.registerView.render();
        }
    });
	return AppRouter;
});

define(['use!Backbone', 'PhotoCollection', 'PhotoListView', 'Login', 'Register', 'Authenticate', 'LoginView', 'RegisterView'], function(Backbone, PhotoCollection, PhotoListView, Login, Register, Authenticate, LoginView, RegisterView){
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
	list: function () {
	    this.photoList = new PhotoCollection();
	    this.photoListView = this.getPhotoListView();
	    this.photoList.fetch();
	},
	login: function () {
		this.loginView = new LoginView({model: this.myLogin});
		this.loginView.render();
	},
	register: function () {
		this.registerView = new RegisterView({model: this.myRegister});
		this.registerView.render();
	},
	getPhotoListView: function () {
		var photoListView;
		if (typeof(this.photoListView) == 'undefined') {
			photoListView = new PhotoListView({
	    		collection: this.photoList, 
	    		el: $('#contentContainer'), 
	    		authenticateModel: this.myAuthenticate});
		} else {
			photoListView = this.photoListView;
			photoListView.render();
		}
		return photoListView;
	}});
	return AppRouter;
});

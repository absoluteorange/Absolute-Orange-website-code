define(['use!Backbone', 'PhotoCollection', 'PhotoListView', 'Login', 'User', 'LoginView', 'RegisterView'], function(Backbone, PhotoCollection, PhotoListView, Login, User, LoginView, RegisterView){
	var AppRouter = Backbone.Router.extend({
	routes:{
	        "gallery":"list",
	        "login":"login",
	        "register":"register"
	},
	initialize: function () {
		this.myLogin = new Login();
		this.myLogin.set({status: 'false'}, {silent: true});
		this.myUser = new User();
		this.myUser.set({newRegistration: 'false'}, {silent: true});
	},
	list: function () {
	    this.photoList = new PhotoCollection();
	    this.photoListView = new PhotoListView({collection: this.photoList, el: $('#contentContainer'), loginModel: this.myLogin});
	    this.photoList.fetch();
	},
	login: function () {
		this.loginView = new LoginView({el: $('#contentContainer'), registerModel: this.myUser, loginModel: this.myLogin});
		this.loginView.render();
	},
	register: function () {
		this.registerView = new RegisterView({el: $('#contentContainer'), registerModel: this.myUser});
		this.registerView.render();
	}});
	return AppRouter;
});
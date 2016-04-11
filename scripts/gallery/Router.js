define(['use!Backbone', 'PhotoCollection', 'PhotoListView', 'Login', 'User', 'Authenticate', 'LoginView', 'RegisterView', 'ThankyouView'], function(Backbone, PhotoCollection, PhotoListView, Login, User, Authenticate, LoginView, RegisterView, ThankyouView){
	var AppRouter = Backbone.Router.extend({
	routes:{
	        "gallery":"list",
	        "login":"login",
	        "register":"register", 
	        "thankyou":"thankyou"
	},
	initialize: function () {
		this.myAuthenticate = new Authenticate();
		this.myAuthenticate.fetch();
		this.myLogin = new Login();
		this.myUser = new User();
		this.myUser.set({newRegistration: 'false'}, {silent: true});
	},
	list: function () {
	    this.photoList = new PhotoCollection();
	    this.photoListView = this.getPhotoListView();
	    this.photoList.fetch();
	},
	login: function () {
		this.loginView = new LoginView({el: $('#contentContainer'), registerModel: this.myUser, loginModel: this.myLogin, authenticateModel: this.myAuthenticate});
		this.loginView.render();
	},
	register: function () {
		this.registerView = new RegisterView({el: $('#contentContainer'), registerModel: this.myUser, authenticateModel: this.myAuthenticate});
		this.registerView.render();
	},
	thankyou: function () {
		this.thankyouView = new ThankyouView({el: $('#contentContainer')});
		this.thankyouView.render();
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

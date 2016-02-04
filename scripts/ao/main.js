define(['navigation',
	'layout/media-query',
	'layout/modules-layout-manager',	
	'text!templates/tweets.html'

	], function (Navigation,
		MediaQuery,
		ModulesLayoutManager,
		tweets
		) {

 	var myNavigation = new Navigation();
	ModulesLayoutManager.addModule(tweets);
 	ModulesLayoutManager.init();
 	

 });



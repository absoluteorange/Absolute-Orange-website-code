define(['navigation',
	'layout/media-query',
	'layout/modules-layout-manager',	
	'text!templates/tweets.html',
    'showcase',
    'blogs/code-revealer', 
    'blogs/geoLocator',
    'utils/InputClearer'

	], function (Navigation,
		MediaQuery,
		ModulesLayoutManager,
		tweets,
        Showcase,
        CodeRevealer,
        GeoLocator,
        InputClearer
		) {

 	var myNavigation = new Navigation();
	ModulesLayoutManager.addModule(tweets);
 	ModulesLayoutManager.init();
    if (typeof GeoLocator !== 'undefined') {
        window.GeoLocator = GeoLocator;
        GeoLocator.init(GeoLocator.myLocation, "Absolute Orange's location");
    }
 });



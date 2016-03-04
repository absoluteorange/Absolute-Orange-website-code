define(['navigation',
	'layout/media-query',
	'layout/modules-layout-manager',	
	'text!templates/tweets.html',
    'showcase',
    'blogs/code-revealer', 
    'blogs/geoLocator',
    'utils/InputClearer',
    'plugins/lightbox',
    'blogs/FileAPI'

	], function (Navigation,
		MediaQuery,
		ModulesLayoutManager,
		tweets,
        Showcase,
        CodeRevealer,
        GeoLocator,
        InputClearer,
        Lightbox,
        FileAPI
		) {

 	var myNavigation = new Navigation();
	ModulesLayoutManager.addModule(tweets);
 	ModulesLayoutManager.init();
    if ((typeof GeoLocator !== 'undefined') &&  $('#lab').length > 0) {
        window.GeoLocator = GeoLocator;
        GeoLocator.init(GeoLocator.myLocation, "Absolute Orange's location");
    }
 });



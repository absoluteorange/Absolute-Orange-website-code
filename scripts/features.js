// singleton to hold browser capabilities
var features = {
	// holds a list of fixed defined key/value properties
	list: {},
	
	// holds a list of user defined key/value properties
	custom: {},
	
	// override or add a custom key
	set: function(key, value){
		this.custom[key] = value;
		this.merge(); // merge the custom keys with the default set
	},
	
	get: function(key){
		return this.list[key];
	},
	
	// merge the custom properties with the default ones before rendering on screen
	merge: function(){
		for (a in this.custom) { 
			this.list[a] = this.custom[a]; 
		}
	},
	
	// loads default properties
	load: function() {
		try {
			this.list = {
			localStorage: (typeof window.localStorage === "undefined" ? false : true),
			geoLocation: (typeof navigator.geolocation === "undefined" ? false : true), // @fixme Triggers UI prompt on firefox :(
			mouse: (typeof window.onmousedown === "undefined" ? false : true),
			touch: (typeof window.ontouchstart === "undefined" ? false : true),
			screenHeight: screen.height,
			screenWidth: screen.width,
			windowWidth: window.innerWidth || document.body.offsetWidth || 0,
			windowHeight: window.innerHeight || document.body.offsetHeight || 0,
			orientation: (typeof window.orientation === "undefined" ? false : window.orientation), // typically going to be 0, -90, 90
			isFullScreen: (typeof navigator.standalone === "undefined" ? false : true), // iOS in webapp mode
			matchMedia: (typeof window.matchMedia('(min-width: 400px)') === "undefined" ? false : true)
			};
			this.merge(); // merge back custom keys
		} catch(e) {};
	},
	// render properties on the screen
	render: function(){
		// reload before rendering
		this.load();
		html = '<ul class="debug-info">';
		html += '<li class="span-6"><strong>random</strong> '+Math.floor(Math.random(100000)*100000);+'</li>'; // helps visualise rotatation events
		for (var capability in this.list){
			html += '<li class="span-6"><strong>'+capability+':</strong> '+this.list[capability]+'</li>';
		}
		html += '</ul>';
		utils.qs('#debug-features').innerHTML = html;
	}
};
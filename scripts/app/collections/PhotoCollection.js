define(['use!Backbone', 'Photo'], function(Backbone, Photo){
	var PhotoCollection = Backbone.Collection.extend ({
		model: Photo,
		url: '/api/photos/photo/format/json'
	});
	return PhotoCollection;
});
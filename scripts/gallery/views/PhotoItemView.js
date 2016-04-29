define(['globals', 
    'Backbone', 
    'Photo', 
    'text!gallery/templates/photoItem.html'], 
    function(globals, 
        Backbone, 
        Photo, 
        PhotoItemTemplate){
	var PhotoItemView = Backbone.View.extend ({
		 tagName: 'div',
		 template: _.template(PhotoItemTemplate),
		 initialize:function () {
			 this.siteUrl = globals.domain+'images/gallery/small';
		 },
		 render:function (eventName) {
			 $(this.el).addClass('photo');
			 this.model.set({'siteUrl': this.siteUrl}, {silent:true});
			 $(this.el).html(this.template(this.model.toJSON()));
			return this;
		 }
	});
	return PhotoItemView;
});

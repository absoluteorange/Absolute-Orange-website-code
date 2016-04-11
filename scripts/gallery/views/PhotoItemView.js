define(['globals', 'use!Backbone', 'Photo', 'text!gallery/templates/photoItem.html', 'use!features'], function(globals, Backbone, Photo, PhotoItemTemplate, Features){
	var PhotoItemView = Backbone.View.extend ({
		 tagName: 'div',
		 template: _.template(PhotoItemTemplate),
		 initialize:function () {
			 this.deviceGroup = utils.getCookie('deviceGroup');
			 this.siteUrl = globals.domain+'images/gallery';
		 },
		 render:function (eventName) {
			 if (this.deviceGroup == 'large' || this.deviceGroup == 'compact') {
				 this.scale = 'med';
			 } else if (this.deviceGroup == 'smart') {
				 this.scale = 'thumb';
			 } else  {
				 this.scale = 'small';
			 }
			 $(this.el).addClass('item');
			 this.model.set({'siteUrl': this.siteUrl, 'scale': this.scale}, {silent:true});
			 $(this.el).html(this.template(this.model.toJSON())); 
			return this;
		 }
	});
	return PhotoItemView;
});

define(['use!Backbone', 'Photo', 'text!app/templates/photoItemBase64.html'], function(Backbone, Photo, PhotoTemplate){
	var PhotoItemBase64View = Backbone.View.extend ({
		 tagName: 'div',
		 template: _.template(PhotoTemplate),
		 render:function (eventName) {
			 $(this.el).addClass('item');
			 $(this.el).html(this.template(this.model.toJSON())); 
			return this;
		 }
	});
	return PhotoItemBase64View;
});
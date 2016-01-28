define(['use!utils', 'use!Backbone', 'text!templates/thankyou.html', 'use!Mustache', 'Lang'], function(Utils, Backbone, ThankyouTemplate, Mustache, Lang){
	var ThankyouView = Backbone.View.extend ({
		template:_.template(ThankyouTemplate), 
		initialize:function () {
		 },
		 render:function () {
			 $('html, body').animate({ scrollTop: 0 }, 0);
			 $(this.el).find('h1').html("Thank you");
			 document.title = 'Thank you';
			 if ($(this.el).find('#thankyou').length == 0) {
				 var view = {
					'thankyou_one': Lang['thankyou_one'],
					'thankyou_two': Lang['thankyou_two'],
					'thankyou_three': Lang['thankyou_three'],
					'thankyou_four': Lang['thankyou_four'],
					'thankyouImageSrc': '/images/webapp/large/062.jpg'
				 };
				 this.htm = Mustache.render(this.template(), view);
				 $(this.el).append(this.htm);
			 } else {
				 $('#thankyou').fadeIn('fast');
			 }
		 },
		 events: {
			 'click a#openGallery' : 'openView'
		 },
		 openView: function (e) {
			 var thisObj = this;
			 $('#thankyou').fadeOut('slow', function () {
				 $('.backgroundImg').removeClass('thankyou');
				 if (e == undefined) {
					 thisObj.options.loginModel.set({status: 'true'}, {silent: true});
					 Backbone.history.navigate('gallery', true);
				 } else {
					 var view = e.currentTarget.id.replace('open', '').toLowerCase();
					 Backbone.history.navigate(view, true);
				 }
			 });
			 return false;
		 }
	});
	return ThankyouView;
});
define(['domready!', 'require', 'use!Backbone', 'use!features', 'Photo', 'PhotoItemView', 'PhotoItemBase64View', 'text!templates/gallery.html', 'use!Mustache', 'Lang', 'isotopeLoader'], function(document, require, Backbone, features, Photo, PhotoItemView, PhotoItemBase64View, GalleryTemplate, Mustache, Lang, isotopeLoader){
	var PhotoListView = Backbone.View.extend ({
			 count: 0,
			 initialize:function () {
				 this.collection.bind("reset", this.render, this);
				 this.csrf = utils.getCookie('csrf');
				 this.authenticated = this.options.loginModel.attributes.status;
				 this.deviceGroup = utils.getCookie('deviceGroup');
			 },
			 template:_.template(GalleryTemplate),
			 render:function (eventName) {
				 $('html, body').animate({ scrollTop: 0 }, 0);
				 document.title = 'Gallery';
				 $(this.el).empty();
				 $(this.el).append('<header><h1>Gallery</h1></header>');
				 var view = {};
				 $(this.el).append(Mustache.render(this.template(), view));
				 _.each(this.collection.models, function (photo) {
					$('#photos').append(new PhotoItemView({model: photo}).render().el);
				 }, this);
				 //masonry
				 this.isotope();
			 },
			 events: {
				 'click a#openLogin' : 'openView'
			 },
			 dragenter: function (e) {
				 e.stopPropagation();
				 e.preventDefault();
				 return false;
			 },
			 dragover: function (e) {			 
				 e.stopPropagation();
				 e.preventDefault();
				 return false;
			 },
			 drop: function (e) {
				 e.stopPropagation();
				 e.preventDefault();
				 var dt = e.dataTransfer, 
				 photos = dt.files;
				 for (var i = 0; i<photos.length; i++) {
					 var thisObj = this,
					 	message = '';
					 if (thisObj.authenticated == 'true') {
						 var photo = photos[i],
						 	photoSplit = photo.name.split('.'),
						 	photoFormat = _.last(photoSplit).toLowerCase(),
						 	photoName = photoSplit[0],
						 	validatedPhotoName = photoName.replace(/([.*+?^$|(){}\[\]])/mg, ''),
						 	myPhoto =  new Photo(),
						 	reader = new FileReader();
						 	message += "<p><span class='photoName'>"+photoName+"</span> "+Lang['photo_uploading']+"</p>";
						 	this.loader(e, this.count, message);	
							reader.onload = ( function(photo) { 
								return function(e) {
									myPhoto.save(
										{ name: validatedPhotoName, data: e.target.result, file: photo, alt: 'alt', format: photoFormat, csrf_secure: thisObj.csrf},
										{ error: function(model, errors) {
											message = '';
											if (errors.size || errors.invalid  || errors.status) {
												 if (errors.status == '403') {
													 if (errors.responseText) {
														 var formErrors = $.parseJSON(errors.responseText).error;
														 for (var i in formErrors) {
															 if (formErrors[i] != '') {
																 message += "<p>"+formErrors[i]+"</p>";
															 }
														 }
													 }
												 } else {
											 		if (errors.invalid || errors.status == '400') {									
											 			message += "<p><span class='photoName'>"+photoName+"</span> "+Lang['photo_invalid_format'];+"</p>";
											 		}
											 		if (errors.size) {
											 			message += "<p><span class='photoName'>"+photoName+"</span> "+errors.size+"</p>";
											 		}
												 }
												 $('#image-'+thisObj.count).html(message);
											 	 thisObj.removeFormHelper(thisObj.count);
											}								
										},
										success: function(model) {
											message = '';
											thisObj.saved(myPhoto);
											$('#image-'+thisObj.count).html("<p><span class='photoName'>"+photoName+"</span> "+Lang['photo_uploaded']+"</p>");
											thisObj.removeFormHelper(thisObj.count);
										}}
									);
								};
							})(photo);
							reader.readAsDataURL(photo);
					 } else {
						 message += "<p>"+Lang['not_logged_in']+"</p>";
						 this.loader(e, this.count, message);	
						 $('#image-'+thisObj.count).html(message);
						 thisObj.removeFormHelper(thisObj.count);
					 }
				} 
				return false;
			 },
			 dropbox: function () {
				var thisObj = this;
				if (window.File && window.FileReader && thisObj.deviceGroup == 'large') {
					var dropbox = document.getElementById("photos");
					$('#photoContainer').addClass('dropzone');
					$('#form-helper').remove();
					$('#photoContainer').prepend("<div id='form-helper'><p>"+Lang['helper_dropzone']+"</p></div>");
					dropbox.addEventListener("dragenter", function(e) { thisObj.dragenter(e); }, false);
					dropbox.addEventListener("dragover", function(e) { thisObj.dragover(e); }, false);
					dropbox.addEventListener("drop", function(e) { thisObj.drop(e); }, false);
				}
			 },
			 loader: function (e, id, message) {  
					var mouseXPos = e.pageX;
					var mouseYPos = e.pageY;
					$('#photoContainer').prepend("<div class='form-helper success' id='image-"+id+"'>"+message+"</div>");
					$('#image-'+id).css({'position': 'absolute', 'top' : mouseYPos, 'left': mouseXPos});
					return true;	
			 },
			 saved: function (model) {
				 var photoView = new PhotoItemBase64View({model: model});
				 photoView.render();
				 $('#photos').prepend(photoView.el).isotope( 'reloadItems' ).isotope({ sortBy: 'original-order' });
			 },
			 isotope: function () {
				var thisObj = this;
				require([isotopeLoader], function (Isotope) {
					 $('#photos').isotope({
						 itemSelector: '.item'
					 }, function () { 
						 thisObj.dropbox(); 
						 if (thisObj.deviceGroup == 'compact' || thisObj.deviceGroup == 'smart') {
							window.addEventListener("resize", function () { $('#photos').isotope('reLayout'); }, false);
						} 
					});
				});
			 },
			 removeFormHelper: function () {
				 var id = this.count;
				 var remove = _.bind (function () {
					 $('#image-'+id).fadeOut('slow', function() {
						 $('#image-'+id).remove();
					}); 
				 });
				 _.delay (remove, 5000);
			 },
			 close: function () {
				 $('#photoGallery').unbind();
			     $('#photoGallery').remove();
			 },
			 openView: function (e) {
				 this.close();
				 var view = e.currentTarget.id.replace('open', '').toLowerCase();
				 Backbone.history.navigate(view, true);
				 $('#photoGallery').unbind().remove();
				 return false;
			 }
		});
		return PhotoListView;
});
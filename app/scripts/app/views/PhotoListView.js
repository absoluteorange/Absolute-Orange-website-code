define(['domready!', 'require', 'use!Backbone', 'use!features', 'Photo', 'PhotoItemView', 'PhotoItemBase64View', 'text!templates/gallery.html', 'use!Mustache', 'Lang', 'use!isotope', 'use!unveil', 'use!lightbox'], function(document, require, Backbone, features, Photo, PhotoItemView, PhotoItemBase64View, GalleryTemplate, Mustache, Lang, isotopeLoader){
	var PhotoListView = Backbone.View.extend ({
			uploadImages: false,	 
			count: 0,
			 initialize:function () {
				 this.collection.bind("reset", this.render, this);
				 this.csrf = utils.getCookie('csrf');
				 this.deviceGroup = utils.getCookie('deviceGroup');
			 },
			 template:_.template(GalleryTemplate),
			 render:function (eventName) {
				 $('html, body').animate({ scrollTop: 0 }, 0);
				 $(this.el).find('h1').html("This weekend a year ago");
				 document.title = 'This weekend a year ago';
				 if ($(this.el).find('#photoGallery').length == 0) {
					 var view = {};
					 $(this.el).append(Mustache.render(this.template(), view));
					 _.each(this.collection.models, function (photo) {
						$('#photos').append(new PhotoItemView({model: photo}).render().el);
					 }, this);
					 var thisObj = this;
					 
				 } else {
					 $('#photoGallery').fadeIn('fast');	
				 }
				 if ((this.uploadImages == true)&& (this.options.authenticateModel.attributes.status == true)) {
					 this.dropbox();
				 }
				 var thisObj = this;
				 $("img").unveil(200, function() {
					 $(this).load(function() {
						 $(this).parents('.item').addClass('item-loaded');
						 if (this.deviceGroup = 'large') {
							 thisObj.isotope();
						 }
					 });
				 });
				 if ((window.File && window.FileReader && this.deviceGroup == 'large') && ($('#upload'.length == 0))) {
					$('#upload').css('display', 'block');
				 }				 
			 },
			 events: {
				 'click input#openLogin': 'dropbox',
				 'click input#close' : 'disableDropbox',
				 'click a#openThankyou' : 'openView'
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
				 	photo = photos[i],
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
				} 
				return false;
			 },
			 dropbox: function (e) {
				if (this.options.authenticateModel.attributes.status == true) {
					var thisObj = this,
						$dropbox = document.getElementById("photos"),
						$photoContainer = $('#photoContainer'),
						$formHelper = $('<div>', {
							id:'form-helper'
						}).prependTo($('#photoContainer')),
						$uploadButton = $('#openLogin');
					$photoContainer.addClass('dropzone');
					$formHelper.addClass('expanded');
					$formHelper.prepend("<p>"+Lang['helper_dropzone']+"</p>");
					$dropbox.addEventListener("dragenter", function(e) { thisObj.dragenter(e); }, false);
					$dropbox.addEventListener("dragover", function(e) { thisObj.dragover(e); }, false);
					$dropbox.addEventListener("drop", function(e) { thisObj.drop(e); }, false);
					$uploadButton.val('close').attr('class', 'btn btn-success').attr('id', 'close');
				} else {
					if (e != undefined) {
						this.uploadImages = true;
						this.openView(e);
					}
				}
			 },
			 disableDropbox: function () {
				var $photoContainer = $('#photoContainer'),
				$dropbox = document.getElementById("photos"),
				$closeButton = $('#close');
				$photoContainer.removeClass('dropzone');
				$('#form-helper').remove();
				$dropbox.removeEventListener('dragenter');
				$dropbox.removeEventListener('dragover');
				$dropbox.removeEventListener('drop');
				$closeButton.val('Add images').attr('class', 'btn btn-warning').attr('id', 'openLogin');
				this.uploadImages = false;
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
				
					 $('#photos').isotope({
						 itemSelector: '.item'
					/* }, function () { 
						 if (thisObj.deviceGroup == 'compact' || thisObj.deviceGroup == 'smart') {
							window.addEventListener("resize", function () { 
								$('#photos').isotope('reLayout'); 
							}, false);
						} */
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
			     $('#photoGallery').fadeOut('slow');
			 },
			 openView: function (e) {
				 this.close();
				 var view = e.currentTarget.id.replace('open', '').toLowerCase();
				 Backbone.history.navigate(view, true);
				 return false;
			 }
		});
		return PhotoListView;
});
define(['use!Backbone'], function(Backbone){
	var data = Backbone.Model.extend ({
		urlRoot: "./index.php/api/photos/photo",
		defaults: {
			'data': '',
			'event': ''
		}, 
		validate: function (attr) {
			var data= attr.data;
			var e = attr.event;
			var errors = new Object();
			var failed = false;
			var imageType = /image.*/;
			var reader = new FileReader();
			var nBytes;
			/*if (!image.type.match(imageType)) {
				errors.invalid = 'This is not a valid image image.';
				failed = true;
			}*/
			nBytes = data.size;
			for (var aMultiples = ["KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"], nMultiple = 0, nApprox = nBytes / 1024; nApprox > 1; nApprox /= 1024, nMultiple++) {
				sOutput = nApprox.toFixed(3) + " " + aMultiples[nMultiple] + " (" + nBytes + " bytes)";
			}
			//alert('Your image size is '+sOutput);
			if (nBytes > 5242880) {
				error.size = 'Your image is to big to upload.  Please choose an image that is smaller than 5Mb';
				failed = true;
			};
			if (failed == true) {
				return errors;
			}
		}
	});
	return data;
});
	
	
	
	
	
	/*function PhotoUploader (element) {
		this.init(element);
	};
	
	PhotoUploader.prototype.init = function (element) {
		this.dropbox = element;
		this.droppedFile;
	};
	
	PhotoUploader.prototype.dragenter = function (e) {
		e.stopPropagation();
		e.preventDefault();
	};
	
	PhotoUploader.prototype.dragover = function (e) {
	  e.stopPropagation();
	  e.preventDefault();
	};
	
	PhotoUploader.prototype.drop = function (e) {
	  e.stopPropagation();
	  e.preventDefault();
	  var dt = e.dataTransfer, 
	  files = dt.files;
	  if (this.droppedFile == null) {
		this.droppedFile ++;
		this.handleDropFiles(files);
	  } else {
		alert('Sorry you can only upload one image per session.');
	  }  
	};
	
	PhotoUploader.prototype.handleDropFiles = function (files) {
	  for (var i = 0; i < files.length; i++) {
		var file = files[i],*/ 
		//imageType = /image.*/,
		/*img = document.createElement("img");
		if (!file.type.match(imageType)) {
		  alert('This is not a valid image file.');
		  continue;
		}
		img.classList.add("obj");
		img.file = file;
		this.dropbox.appendChild(img);
		var reader = new FileReader();
		reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
		reader.readAsDataURL(file);
		nBytes = file.size;
		for (var aMultiples = ["KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"], nMultiple = 0, nApprox = nBytes / 1024; nApprox > 1; nApprox /= 1024, nMultiple++) {
			sOutput = nApprox.toFixed(3) + " " + aMultiples[nMultiple] + " (" + nBytes + " bytes)";
		}
		alert('Your image size is '+sOutput);
		if (nBytes <= 5242880) {
			this.FileUpload(file);
		} else {
			alert('Your image is to big to upload.  Please choose an image that is smaller than 5Mb');
		}
	  }
	};
	
	PhotoUploader.prototype.FileUpload = function (file) {
	  var xhr = new XMLHttpRequest();
	  xhr.open("POST", "./index.php/api/photos/photo", true);
	  xhr.setRequestHeader("X_FILENAME", file.name);  
	  xhr.onload = function (e) {
		if (this.status == 200)  {
			
			/*var img = this.responseText;
			var parentEle = document.getElementById('uploadedImages');
			
			var aEle = document.createElement("a");
			aEle.rel = "lightbox";
			aEle.id = "uploaded";
			aEle.className = 'img';
			var imgEle = document.createElement('img');
			imgEle.src = '/uploads/'+this.responseText;
			imgEle.alt = '';
			imgEle.title= '';
			aEle.appendChild(imgEle);
			parentEle.appendChild(aEle);
			var paraEle = document.createElement('p');
			var content = 'Image address is: http://www.absoluteorange.com/uploads/'+this.responseText;
			if(typeof paraEle.innerText !== 'undefined') {
					paraEle.innerText = content;
				}
				else {
					paraEle.innerHTML = content;
				}
			}
			parentEle.appendChild(paraEle);
			var imageLink = document.getElementById('uploaded');
			imageLink.lightBox;
		}
	  };
	  xhr.send(file);
	};
});*/
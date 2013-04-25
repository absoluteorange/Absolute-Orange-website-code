var droppedFile;

function dragenter(e) {
  e.stopPropagation();
  e.preventDefault();
}

function dragover(e) {
  e.stopPropagation();
  e.preventDefault();
}

function drop(e) {
  e.stopPropagation();
  e.preventDefault();

  var dt = e.dataTransfer;
  var files = dt.files;
  if(droppedFile == null) {
	droppedFile ++;
	handleDropFiles(files);
  } else {
	alert('Sorry you can only upload one image per session.');
  }
}

function handleDropFiles(files) {
  for (var i = 0; i < files.length; i++) {
	var file = files[i];
	var imageType = /image.*/;
	
	if (!file.type.match(imageType)) {
	  alert('This is not a valid image file.');
	  continue;
	}

	var img = document.createElement("img");
	img.classList.add("obj");
	img.file = file;
	dropbox.appendChild(img);
	
	var reader = new FileReader();
	reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
	reader.readAsDataURL(file);
	nBytes = file.size;
	for (var aMultiples = ["KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"], nMultiple = 0, nApprox = nBytes / 1024; nApprox > 1; nApprox /= 1024, nMultiple++) {
		sOutput = nApprox.toFixed(3) + " " + aMultiples[nMultiple] + " (" + nBytes + " bytes)";
	}
	alert('Your image size is '+sOutput);
	if (nBytes <= 5242880) {
		FileUpload(file);
	} else {
		alert('Your image is to big to upload.  Please choose an image that is smaller than 5Mb');
	}
  }
}

function FileUpload(file) {
  console.log(file);
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "http://www.absoluteorange.com/fileAPIUpload", true);
  xhr.setRequestHeader("X_FILENAME", file.name);  
  xhr.onload = function (e) {
	if (this.status == 200)  {
		var img = this.responseText;
		var parentEle = document.getElementById('uploadedImages');
		var aEle = document.createElement("a");
		aEle.rel = "lightbox";
		aEle.id = "uploaded";
		aEle.className = 'img';
		var imgEle = document.createElement('img');
		imgEle.src = '/images/publicUpload/'+this.responseText;
		imgEle.alt = '';
		imgEle.title= '';
		aEle.appendChild(imgEle);
		parentEle.appendChild(aEle);
		var paraEle = document.createElement('p');
		var content = 'Image address is: http://www.absoluteorange.com/images/publicUpload/'+this.responseText;
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
  xhr.send(file);
}
var dropbox
dropbox = document.getElementById("dropbox");
dropbox.addEventListener("dragenter", dragenter, false);
dropbox.addEventListener("dragover", dragover, false);
dropbox.addEventListener("drop", drop, false);
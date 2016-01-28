var FileApi = function () {

    "use strict";

    var droppedFile = null;

    var dragEnter = function (e) {
        e.stopPropagation();
        e.preventDefault();
    };

    var dragOver = function (e) {
        e.stopPropagation();
        e.preventDefault();
    };

    var drop = function (e) {
        e.stopPropagation();
        e.preventDefault();
        var dt = e.dataTransfer,
            files = dt.files;
        if (droppedFile === null) {
            droppedFile ++;
            handleDropFiles(files);
        } else {
            window.alert('Sorry you can only upload one image per session.');
        }
    };

    var handleDropFiles = function (files) {
        for (var i= 0, len=files.length; i<len; i++) {
            var file = files[i],
                imageType = /image.*/;

            if (!file.type.match(imageType)) {
                window.alert('This is not a valid image file.');
                continue;
            }

            var img = document.createElement("img");
            img.classList.add("obj");
            img.file = file;
            dropbox.appendChild(img);

            var reader = new FileReader();
            reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
            reader.readAsDataURL(file);
            var nBytes = file.size,
                sOutput;
            for (var aMultiples = ["KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"], nMultiple = 0, nApprox = nBytes / 1024; nApprox > 1; nApprox /= 1024, nMultiple++) {
                sOutput = nApprox.toFixed(3) + " " + aMultiples[nMultiple] + " (" + nBytes + " bytes)";
            }
            window.alert('Your image size is '+ sOutput);
            if (nBytes <= 5242880) {
                fileUpload(file);
            } else {
                window.alert('Your image is to big to upload.  Please choose an image that is smaller than 5Mb');
            }
        }
    };

    var fileUpload = function (file) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/fileAPIUpload", true);
        xhr.setRequestHeader("X_FILENAME", file.name);
        xhr.onload = function (e) {
            if (this.status === 200)  {
                var img = this.responseText;
                var parentEle = document.getElementById('uploadedImages');
                var aEle = document.createElement("a");
                aEle.rel = "lightbox";
                aEle.id = "uploaded";
                aEle.className = 'img';
                var imgEle = document.createElement('img');
                imgEle.src = '/images/publicUpload/small/'+this.responseText;
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
        };
        xhr.send(file);
    };
    var dropbox = document.getElementById("dropbox");
    dropbox.addEventListener("dragenter", dragEnter, false);
    dropbox.addEventListener("dragover", dragOver, false);
    dropbox.addEventListener("drop", drop, false);
};
var myFileApi = new FileApi();
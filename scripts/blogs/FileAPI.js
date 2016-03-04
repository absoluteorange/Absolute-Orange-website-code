define(['lib/dom-ready', 'lib/signals', 'plugins/lightbox'], function (domReady, Signals, Lightbox) {
    var uploadFileSignal = new Signals.Signal()
    var subscribe=function(event,callback){
        switch(event){
            case 'uploadFile':
                uploadFileSignal.add(callback);
                break;
        }
    };
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
                img.id = 'dropped-image';
                img.file = file;
                dropbox.appendChild(img);
                var reader = new FileReader();
                reader.onload = (function(aImg, droppedFile) { 
                    return function(e) { 
                        aImg.src = e.target.result; 
                        var nBytes = file.size;
                        var sOutput;
                        for (var aMultiples = ["KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"], nMultiple = 0, nApprox = nBytes / 1024; nApprox > 1; nApprox /= 1024, nMultiple++) {
                            sOutput = nApprox.toFixed(3) + " " + aMultiples[nMultiple] + " (" + nBytes + " bytes)";
                        }
                        window.alert('Your image size is '+ sOutput);
                        if (nBytes <= 2097152) {
                            droppedFile ++;
                            var uploadBox = document.getElementById('uploadedImages');
                            uploadBox.className += ' loading';
                            window.scrollBy(uploadBox.offsetHeight, window.scrollY);
                            uploadBox.innerHTML = '<p>Your image is being uploaded, this may take a minute</p>';
                            fileUpload(file, e.target.result);
                        } else {
                            window.alert('Your image is to big to upload.  Please choose an image that is smaller than 5Mb');
                            var droppedImg = document.getElementById('dropped-image');
                            var dropbox = document.getElementById('dropbox');
                            droppedImg.parentNode.removeChild(droppedImg);
                        }
                    }; 
                })(img, droppedFile);
                reader.readAsDataURL(file);
            }
        };
        var fileUpload = function (file, imgData) {
            uploadFileSignal.dispatch($(this));
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/fileAPIUpload", true);
            xhr.setRequestHeader("X-FILENAME", file.name);
            xhr.overrideMimeType('text/plain; charset=x-user-defined-binary');
            xhr.onload = function (e) {
                if (this.status === 200)  {
                    var img = this.responseText;
                    var parentEle = document.getElementById('uploadedImages');
                    var aEle = document.createElement("a");
                    aEle.dataset.lightbox = img.substring(0, img.indexOf('.'));
                    aEle.id = "uploaded";
                    aEle.className = 'img';
                    aEle.href = '/images/publicUpload/small/'+img;
                    var imgEle = document.createElement('img');
                    imgEle.src = '/images/publicUpload/small/'+img;
                    imgEle.alt = '';
                    imgEle.title= '';
                    aEle.appendChild(imgEle);
                    parentEle.appendChild(aEle);
                    parentEle.classList.remove('loading');
                    var paraEle = document.createElement('p');
                    var content = 'Image address is: http://www.absoluteorange.com/images/publicUpload/'+img;
                    if(typeof paraEle.innerText !== 'undefined') {
                        paraEle.innerText = content;
                    }
                    else {
                        paraEle.innerHTML = content;
                    }
                }
                parentEle.appendChild(paraEle);
            };
            xhr.send(imgData);
        };
        var dropbox = document.getElementById("dropbox");
        dropbox.addEventListener("dragenter", dragEnter, false);
        dropbox.addEventListener("dragover", dragOver, false);
        dropbox.addEventListener("drop", drop, false);
        return {
            subscribe: subscribe
        }
    };
    if ($('#lab').length > 0) {
        return new FileApi();
    }
});

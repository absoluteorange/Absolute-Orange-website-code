var utils = {
	qs:  function (queryString) {
		try {
			return document.querySelector(queryString);
		}
		catch(e) {
			// console.log(e);
			return false;
		}
	},
	// remove a class from an element
	removeClass : function (elm, strClass) {
		if (!!elm) {
			var re = new RegExp(strClass, 'g');
			elm.className = elm.className.replace(re, '');
		}
	},
	//input clearer
	inputClearer: function () {
		var arrInputs = document.getElementsByTagName('input');
		for (var i = 0; i<arrInputs.length; i++) {
			var myInputClearer = new InputClearer(arrInputs[i]);
		}
	}, 
	getCookie: function (c_name) {
		var i,
			x,
			y,
			arrCookies = document.cookie.split(";");
		for (i = 0; i < arrCookies.length; i++) {
		  x = arrCookies[i].substr(0, arrCookies[i].indexOf("="));
		  y = arrCookies[i].substr(arrCookies[i].indexOf("=")+1);
		  x=x.replace(/^\s+|\s+$/g,"");
		  if (x == c_name)  {
		    return unescape(y);
		  }
		}
	}
};

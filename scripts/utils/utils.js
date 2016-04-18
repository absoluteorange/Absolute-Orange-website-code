var utils = {
	qs:  function (queryString) {
		try {
			return document.querySelector(queryString);
		}
		catch(e) {
			return false;
		}
	},
	removeClass : function (elm, strClass) {
		if (!!elm) {
			var re = new RegExp(strClass, 'g');
			elm.className = elm.className.replace(re, '');
		}
	},
	inputClearer: function () {
		var arrInputs = document.getElementsByTagName('input');
		for (var i = 0, len = arrInputs.length;  i<len; i++) {
			var myInputClearer = new InputClearer(arrInputs[i]);
		}
	}, 
	getCookie: function (key) {
		var arrCookie = document.cookie.split(";");
        for (var i=0, len=arrCookie.length; i<len; i++) {
          var cookie = arrCookie[i];
		  var cookieKey = cookie.substr(0, cookie.indexOf("=")).replace(/^\s+|\s+$/g,"");
		  var value = unescape(cookie.substr(cookie.indexOf("=")+1));
		  if (cookieKey === key)  {
              return value;
		  }
        }
	}
};

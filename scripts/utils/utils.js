define([], function () {
    var Utils = function () {
        function getCookie (key) {
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
        return {
            getCookie : getCookie
        }
    };
    return new Utils;
});

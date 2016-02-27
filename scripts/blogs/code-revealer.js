define(['jquery', 'lib/dom-ready'], function ($, domReady) {
     codeRevealer = function () {
        var codeEvents;
        var eventElements;
        var blogName = $('h2').data('blog-name');
        var eleCodeDisplay = $('#code').find('pre');
        var eleCodeHeading = $('#code').find('h4');
        this.init=function(){
            if (blogName === 'HTML5 Geolocation API') {
                codeEvents = ['domReady', 'click', 'GeoLocator.subscribe', 'GeoLocator.subscribe'];
                eventElements = ['', '#findLocation', 'allow', 'deny'];
            }
            var getData = $.ajax({
                dataType: 'json',
                url: '/lab/getCode', 
                data: {
                    name: 'HTML5 Geolocation API'
                }
            });
            getData.complete(function(codeArray) {
                var codeArray = codeArray.responseJSON;
                for(var i=0, len=codeArray.length; i<len; i++) {
                    if (codeEvents[i] === 'domReady') {
                        $(document).ready(function() {
                            var title = 'Code executed on DOM event, DOM ready';
                            displayCode(title, codeArray[i]);
                        });
                    } else if (codeEvents[i].indexOf('subscribe') > -1) {
                        eval(codeEvents[i])(eventElements[i], function(i) {
                            var title = 'Code executed on '+eventElements[i]+' being clicked';
                            displayCode(title, codeArray[i]);
                        }.bind(this, i));
                    } else {
                        $(eventElements[i]).on(codeEvents[i], {index: i}, function(event) {
                            var index = event.data.index;
                            var title = 'Code executed on '+codeEvents[index]+' of '+eventElements[index];
                            displayCode(title, codeArray[index]);
                        });
                    }
                }
            });
        };
        displayCode = function (title, code) {
            eleCodeDisplay.empty();
            eleCodeHeading.empty();
            eleCodeHeading.html(title);
            eleCodeDisplay.append(code);
        };
        domReady(this.init.bind(this));
    };
    if ($('#lab').length > 0) {
        return new codeRevealer();
    }
});

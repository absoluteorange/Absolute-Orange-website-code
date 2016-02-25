define(['jquery', 'lib/dom-ready', 'lib/signals', 'lib/lodash'], function ($, domReady, Signals, _) {
     codeRevealer = function () {
        this.init=function(){
            var codeEvents = ['domReady', 'click'];
            var eventElements = ['', '#findLocation'];
            var $eleCodeDisplay = $('#code').find('pre');
            var $eleCodeHeading = $('#code').find('h4');
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
                            $eleCodeDisplay.empty();
                            $eleCodeHeading.empty();
                            $eleCodeHeading.html('Code executed on DOM event, DOM ready');
                            $eleCodeDisplay.append(codeArray[i]);
                        }());
                    } else {
                        $(eventElements[i]).on(codeEvents[i], function() {
                            $eleCodeDisplay.empty();
                            $eleCodeHeading.empty();
                            $eleCodeHeading.html('Code executed on '+codeEvents[i]+' of '+eventElements[i]);
                            $eleCodeDisplay.append(codeArray[i]);
                        });
                    }
                }
            });
        };

        
        domReady(this.init.bind(this));
    };
    if ($('#lab').length > 0) {
        return new codeRevealer();
    }
});

define(['jquery', 'lib/dom-ready', 'lib/signals', 'lib/lodash'], function ($, domReady, Signals, _) {
     codeRevealer = function () {
        this.codeEvents = ['domReady', 'click'];
        this.eventElements = ['', '#findLocation'];
        this.$eleCodeDisplay = $('#code').find('pre');
        this.$eleCodeHeading = $('#code').find('h4');
        this.init=function(){
            var getData = $.ajax({
                dataType: 'json',
                url: '/lab/getCode', 
                data: {
                    name: 'HTML5 Geolocation API'
                }
            });
            var thisObj = this;
            getData.complete(function(codeArray) {
                var codeArray = codeArray.responseJSON;
                for(var i=0, len=codeArray.length; i<len; i++) {
                    if (thisObj.codeEvents[i] === 'domReady') {
                        $(document).ready(function() {
                            thisObj.$eleCodeDisplay.empty();
                            thisObj.$eleCodeHeading.empty();
                            thisObj.$eleCodeHeading.html('Code executed on DOM event, DOM ready');
                            thisObj.$eleCodeDisplay.append(codeArray[i]);
                        });
                    } else {
                        $(thisObj.eventElements[i]).on(thisObj.codeEvents[i], {index: i}, function(event) {
                            var index = event.data.index;
                            thisObj.$eleCodeDisplay.empty();
                            thisObj.$eleCodeHeading.empty();
                            thisObj.$eleCodeHeading.html('Code executed on '+thisObj.codeEvents[index]+' of '+thisObj.eventElements[index]);
                            thisObj.$eleCodeDisplay.append(codeArray[index]);
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

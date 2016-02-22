define(['jquery','lib/dom-ready', 'lib/signals'], function ($, domReady, Signals) {
    var codeRevealer = function () {
        this.init=function(){
/*            $.ajax({
                dataType: 'html',
                url: '/lab/getCode', 
                data: {
                    name: 'HTML5 Geolocation API'
                }
            }).done(function (html) {
                console.log(html);
            });*/
        };
        domReady(this.init.bind(this));
    };
    return new codeRevealer();
});

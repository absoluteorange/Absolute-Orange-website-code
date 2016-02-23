define(['jquery','lib/dom-ready', 'lib/signals'], function ($, domReady, Signals) {
    var codeRevealer = function () {
        this.init=function(){
            var $ele = $('#code').find('pre');
            $.ajax({
                dataType: 'json',
                url: '/lab/getCode', 
                data: {
                    name: 'HTML5 Geolocation API'
                }
            }).done(function (array) {
                $ele.append(array[0]);
            });
        };
        domReady(this.init.bind(this));
    };
    return new codeRevealer();
});

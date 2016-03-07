define(['jquery','lib/dom-ready', 'lib/signals'], function ($, domReady, Signals) {
    var showcase = function() {
        var _showcaseClickedSignal = new Signals.Signal()
        this.init=function(){
            $('.showcase , .showcase-image ').each(function(i) {
                $(this).on('click', function(e) {
                    e.preventDefault();
                    _showcaseClickedSignal.dispatch($(this));
                });
            });
        };
        _showcaseClickedSignal.add(onClicked);
        function onClicked(element) {
            var eleListItem = element.parents('.detail-list');
            if (eleListItem.hasClass('selected') === false) {
                var _showcase = eleListItem.find('h3').html();
                $.ajax({
                    dataType: 'html',
                    url: '/Showcase/getShowcase', 
                    data: {
                        name: _showcase
                    }
                }).done(function (html) {
                    eleListItem.append(html).addClass('selected');
                });
            } else {
                eleListItem.find('.widget-detail').empty();
                eleListItem.removeClass('selected');
            }
        };
        domReady(this.init.bind(this));
    };
    return new showcase();
});


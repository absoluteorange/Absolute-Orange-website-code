define(['jquery','lib/dom-ready', 'lib/signals'], function ($, domReady, Signals) {
    var showcase = function() {
        var showcaseClosedIcon = 'icon-arrow-a';
        var showcaseOpenIcon = 'icon-arrow-a-selected';
        var showcaseClickedSignal = new Signals.Signal()
        this.init=function(){
            $('.showcase , .showcase-image ').each(function(i) {
                $(this).on('click', function(e) {
                    e.preventDefault();
                    showcaseClickedSignal.dispatch($(this));
                });
            });
        };
        showcaseClickedSignal.add(onClicked);
        function onClicked(element) {
            var eleListItem = element.parents('.detail-list');
            var showcase = eleListItem.find('h3').html();
            if (eleListItem.hasClass('selected') === false) {
                closeShowcase();
                getShowcase(showcase, eleListItem);
            } else {
                closeShowcase(eleListItem, showcase);
            }
        };
        function getShowcase(showcase, eleListItem) {
            $.ajax({
                dataType: 'html',
                url: '/Showcase/getShowcase',
                data: {
                    name: showcase
                }
            }).done(function(html) {
                openShowcase(html, eleListItem, showcase);
            });
        };
        function openShowcase(html, eleListItem, showcase) {
            eleListItem.append(html).addClass('selected');
            eleListItem.find('.widget-summary').removeClass(showcaseClosedIcon).addClass(showcaseOpenIcon);
            updateURL('add', showcase);
            updateAnchor('add', eleListItem);

        }
        function updateURL(state, showcase) {
            var pageTitle = document.title;
            var currentURL = window.location.href;
            var showcaseURL = encodeURI(showcase);
            switch (state) {
                case 'add':
                    window.history.pushState({"pageTitle": pageTitle},
                    pageTitle,
                    currentURL+"/"+showcaseURL+"#selected-content");
                 break;
                 case 'remove':
                    var newURL = currentURL.substring(0, currentURL.indexOf("/"+showcaseURL));
                    window.history.pushState({"pageTitle": pageTitle},
                    pageTitle,
                    newURL);
                 break;
            }
        };
        function closeShowcase(eleListItem, showcase) {
            if (eleListItem === undefined) {
                getOpenShowcases();
                return;
            }
            eleListItem.find('.widget-detail').empty();
            eleListItem.removeClass('selected');
            eleListItem.find('.widget-summary').removeClass(showcaseOpenIcon).addClass(showcaseClosedIcon);
            updateURL('remove', showcase);
            updateAnchor('remove', eleListItem);
        };
        function getOpenShowcases() {
            $('.showcase').each(function(i) {
                var parentListItem = $(this).parent('li');
                if (parentListItem.hasClass('selected')) {
                    var showcase = parentListItem.find('.widget-body').attr('title');
                    closeShowcase(parentListItem, showcase);
                }
            });
        };
        function updateAnchor(state, eleListItem) {
            switch (state) {
                case 'add':
                    var anchor = $(eleListItem).prev('a');
                    $(anchor).attr('name', 'selected-content');
                    $(document).scrollTop( $(anchor).offset().top );
                break;
                case 'remove':
                    $(eleListItem).prev('a').attr('name', '');
                break;
            }
        };
        domReady(this.init.bind(this));
    };
    return new showcase();
});



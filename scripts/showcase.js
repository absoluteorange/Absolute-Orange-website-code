define(['jquery','lib/dom-ready', 'lib/signals'], function ($, domReady, Signals) {
    var showcase = function() {
        var showcaseClosedIcon = 'icon-arrow-a';
        var showcaseOpenIcon = 'icon-arrow-a-selected';
        var showcaseClickedSignal = new Signals.Signal();

        this.init=function(){
            $('.showcase , .showcase-image ').each(function(i) {
                $(this).on('click', function(e) {
                    e.preventDefault();
                    showcaseClickedSignal.dispatch($(this));
                });
            });
            fixPageState();
        };
        showcaseClickedSignal.add(toggleShowcase);

        function toggleShowcase(element) {
            var eleListItem = element.parents('.detail-list');
            var showcase = eleListItem.find('h3').html();
            if (eleListItem.hasClass('selected') === false) {
                closeShowcases();
                getShowcaseData(showcase, eleListItem);
            } else {
                closeShowcases();
            }
        };

        function getShowcaseData(showcase, eleListItem) {
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
            if (html !== 'exists') {
                eleListItem.append(html);
                updateURL('add', showcase);
            }
            eleListItem.find('.widget-summary').removeClass(showcaseClosedIcon).addClass(showcaseOpenIcon);
            $(eleListItem).addClass('selected');
            updateAnchor('add', eleListItem);
        };

        function fixPageState() {
            var currentURL = window.location.href;
            var indexURLHash = currentURL.indexOf('#');
            currentURL = currentURL.slice(0, indexURLHash);
            var arrCurrentURL = currentURL.split('/');
            if (arrCurrentURL.length === 6) {
                var showcase = decodeURI(arrCurrentURL[6]);
                var eleListItem = $('.widget-detail').parent('li');
                var html = 'exists';
                openShowcase(html, eleListItem, showcase);
            }
        };

        function updateURL(state, showcase) {
            var pageTitle = document.title;
            var currentURL = window.location.href.replace('#tweets', '');
            var showcaseURL = encodeURI(showcase);
            switch (state) {
                case 'add':
                    var url = currentURL+"/"+showcaseURL+"#selected-content";
                    window.history.pushState({"pageTitle": pageTitle},
                    pageTitle,
                    url);
                 break;
                 case 'remove':
                    var url = currentURL.substring(0, currentURL.indexOf("/"+showcaseURL));
                    window.history.pushState({"pageTitle": pageTitle},
                    pageTitle,
                    url);
                 break;
            }
        };

        function closeShowcases() {
            $('.showcase').each(function(i) {
                var parentListItem = $(this).parent('li');
                if (parentListItem.hasClass('selected')) {
                    var showcase = parentListItem.find('.widget-body').attr('title');
                    parentListItem.find('.widget-detail').remove();
                    parentListItem.removeClass('selected');
                    parentListItem.find('.widget-summary').removeClass(showcaseOpenIcon).addClass(showcaseClosedIcon);
                    updateURL('remove', showcase);
                    updateAnchor('remove', parentListItem);
                }
            })
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



'use strict';
/**
 * @author Batch Themes Ltd.
 */
(function() {
    $(function() {
        var storage = Storages.localStorage;
        var config = storage.get('config');
        //custom events used to update demo views. this can be removed in production
        //right sidebar
        $('body').on('toggle:right-sidebar', function() {
            $('.right-sidebar-outer').toggleClass('show-from-right');
            if ($('.right-sidebar-outer').hasClass('show-from-right')) {
                $('.right-sidebar-backdrop').toggleClass('fade in');
            } else {
                $('.right-sidebar-backdrop')
                    .removeClass('fade')
                    .removeClass('in');
            }
        });
        //fullscreen
        $('body').on('toggle:fullscreen', function() {
            var body = $('body');
            var value = body.attr('data-fullscreen') === 'true' ? true : false;
            body.attr('data-fullscreen', !value);
            $(document).fullScreen(!value);
        });
        //collapsed
        $('body').on('toggle:collapsed', function() {
            var body = $('body');
            var layout = body.attr('data-layout');
						console.log('layout', layout);
            var collapsed = body.attr('data-collapsed') === 'true' ? true : false;
            body.attr('data-collapsed', !collapsed);
						if(layout === 'sidebar-over-1') {
                var backdrop = $('.left-sidebar-backdrop');
								if(backdrop.hasClass('in')) {
									backdrop.removeClass('fade');
									backdrop.removeClass('in');
								} else {
									backdrop.toggleClass('fade in');
								}
						}
        });
        //background
        $('body').on('changed:background', function() {
            var body = $('body');
            var background = body.attr('data-background');
            config = Object.assign({}, config, {
                background: background
            });
            storage.set('config', config);
        });
        //sidebar
        $('body').on('changed:sidebar', function() {
            var body = $('body');
            var sidebar = body.attr('data-sidebar');
            config = Object.assign({}, config, {
                sidebar: sidebar
            });
            storage.set('config', config);
        });
        //logo
        $('body').on('changed:logo', function() {
            var body = $('body');
            var logo = body.attr('data-logo');
            config = Object.assign({}, config, {
                logo: logo
            });
            storage.set('config', config);
        });
        //navbar
        $('body').on('changed:navbar', function() {
            var body = $('body');
            var navbar = body.attr('data-navbar');
            config = Object.assign({}, config, {
                navbar: navbar
            });
            storage.set('config', config);
        });
        //top-navigation
        $('body').on('changed:top-navigation', function() {
            var body = $('body');
            var topNavigation = body.attr('data-top-navigation');
            config = Object.assign({}, config, {
                topNavigation: topNavigation
            });
            storage.set('config', config);
        });
    });
})();

'use strict';
/**
 * @author Batch Themes Ltd.
 */
(function() {
    $(function() {

        //default config values
        var backgroundColors = background_colors();

        //set loader
        if ($('html').hasClass('loading')) {
            var loaderTime = 2000;
            $('#fakeloader').fakeLoader({
                timeToHide: loaderTime,
                zIndex: '99999',
                spinner: 'spinner7',
                bgColor: backgroundColors['blue-grey']
            });
            setTimeout(function() {
                $('html').removeClass('loading');
            }, loaderTime);
        }
    });
})();

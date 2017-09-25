'use strict';
/**
 * @author Batch Themes Ltd.
 */
(function() {
    $(function() {
        if (!element_exists('#widgets-bar-chart-widgets')) {
            return false;
        }

        var colors = bootstrap_colors();
        peityBar1('#bar-chart-1', 75, colors.info);
        peityBar1('#bar-chart-2', 75, colors.success);
        peityBar1('#bar-chart-3', 60, colors.warning);
        peityBar1('#bar-chart-5', 60, lighten(colors.info, 20));
        peityBar1('#bar-chart-4', 80, colors.danger);
        peityBar1('#bar-chart-6', 80, darken(colors.success, 20));
        peityBar1('#bar-chart-7', 70, colors.info);
        peityBar1('#bar-chart-8', 70, lighten(colors.danger, 20));
        //redraw components after window resize. this can be removed in production
        function onResizeEnd() {
            peityBar1('#bar-chart-1', 75, colors.info);
            peityBar1('#bar-chart-2', 75, colors.success);
            peityBar1('#bar-chart-3', 60, colors.warning);
            peityBar1('#bar-chart-5', 60, lighten(colors.info, 20));
            peityBar1('#bar-chart-4', 80, colors.danger);
            peityBar1('#bar-chart-6', 80, darken(colors.success, 20));
            peityBar1('#bar-chart-7', 70, colors.info);
            peityBar1('#bar-chart-8', 70, lighten(colors.danger, 20));
            $('body').trigger('changed:background');
        }
        var resizeTimeout;
        $(window).on('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(onResizeEnd, 500);
        });
        $('body').on('toggle:collapsed', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(onResizeEnd, 500);
        })
    });
})();

function peityBar1(element, height, color) {
    $(element).peity('bar', {
        height: height,
        width: '100%',
        fill: [color]
    });
}

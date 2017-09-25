'use strict';
/**
 * @author Batch Themes Ltd.
 */
(function() {
    $(function() {
        if (!element_exists('#widgets-area-chart-widgets')) {
            return false;
        }

        var colors = bootstrap_colors();
        peityArea1('#area-chart-1', 75, colors.info);
        peityArea1('#area-chart-2', 75, colors.success);
        peityArea1('#area-chart-3', 60, colors.warning);
        peityArea1('#area-chart-5', 60, lighten(colors.info, 20));
        peityArea1('#area-chart-4', 80, colors.danger);
        peityArea1('#area-chart-6', 80, darken(colors.success, 20));
        peityArea1('#area-chart-7', 70, colors.info);
        peityArea1('#area-chart-8', 70, lighten(colors.danger, 20));
        //redraw components after window resize. this can be removed in production
        function onResizeEnd() {
            peityArea1('#area-chart-1', 75, colors.info);
            peityArea1('#area-chart-2', 75, colors.success);
            peityArea1('#area-chart-3', 60, colors.warning);
            peityArea1('#area-chart-5', 60, lighten(colors.info, 20));
            peityArea1('#area-chart-4', 80, colors.danger);
            peityArea1('#area-chart-6', 80, darken(colors.success, 20));
            peityArea1('#area-chart-7', 70, colors.info);
            peityArea1('#area-chart-8', 70, lighten(colors.danger, 20));
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

function peityArea1(element, height, color) {
    $(element).peity('line', {
        height: height,
        width: '100%',
        stroke: color,
        fill: color
    });
}

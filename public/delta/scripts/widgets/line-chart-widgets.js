'use strict';
/**
 * @author Batch Themes Ltd.
 */
(function() {
    $(function() {
        if (!element_exists('#widgets-line-chart-widgets')) {
            return false;
        }

        var colors = bootstrap_colors();
        peityLine1('#line-chart-1', 75, colors.info);
        peityLine1('#line-chart-2', 75, colors.success);
        peityLine1('#line-chart-3', 60, colors.warning);
        peityLine1('#line-chart-5', 60, lighten(colors.info, 30));
        peityLine1('#line-chart-4', 80, colors.danger);
        peityLine1('#line-chart-6', 80, lighten(colors.success, 30));
        peityLine1('#line-chart-7', 70, colors.info);
        peityLine1('#line-chart-8', 70, lighten(colors.danger, 30));
        //redraw components after window resize. this can be removed in production
        function onResizeEnd() {
            peityLine1('#line-chart-1', 75, colors.info);
            peityLine1('#line-chart-2', 75, colors.success);
            peityLine1('#line-chart-3', 60, colors.warning);
            peityLine1('#line-chart-5', 60, lighten(colors.info, 30));
            peityLine1('#line-chart-4', 80, colors.danger);
            peityLine1('#line-chart-6', 80, lighten(colors.success, 30));
            peityLine1('#line-chart-7', 70, colors.info);
            peityLine1('#line-chart-8', 70, lighten(colors.danger, 30));
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

function peityLine1(element, height, color) {
    $(element).peity('line', {
        height: height,
        width: '100%',
        stroke: color,
        strokeWidth: 2,
        fill: 'transparent'
    });
}

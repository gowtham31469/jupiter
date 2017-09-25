'use strict';
/**
 * @author Batch Themes Ltd.
 */
(function() {
    $(function() {
        if (!element_exists('#widgets-pie-chart-widgets')) {
            return false;
        }

        var colors = bootstrap_colors();
        var palette = get_palette();
        peityPie1('#pie-chart-1', 120, [palette['border-color'], colors.info]);
        peityPie1('#pie-chart-2', 120, [palette['border-color'], colors.success]);
        peityPie1('#pie-chart-3', 120, [palette['border-color'], colors.warning]);
        peityPie1('#pie-chart-5', 120, [palette['border-color'], colors.danger]);
        //redraw components after window resize. this can be removed in production
        function onResizeEnd() {
            peityPie1('#pie-chart-1', 120, [palette['border-color'], colors.info]);
            peityPie1('#pie-chart-2', 120, [palette['border-color'], colors.success]);
            peityPie1('#pie-chart-3', 120, [palette['border-color'], colors.warning]);
            peityPie1('#pie-chart-5', 120, [palette['border-color'], colors.danger]);
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

function peityPie1(element, radius, colors) {
    $(element).peity('pie', {
        height: radius,
        width: radius,
        radius: radius,
        fill: colors
    });
}

'use strict';
/**
 * @author Batch Themes Ltd.
 */
(function() {
    $(function() {
        if (!element_exists('#widgets-donut-chart-widgets')) {
            return false;
        }

        var colors = bootstrap_colors();
        var palette = get_palette();
        peityDonut1('#donut-chart-1', 120, [palette['border-color'], colors.info]);
        peityDonut1('#donut-chart-2', 120, [palette['border-color'], colors.success]);
        peityDonut1('#donut-chart-3', 120, [palette['border-color'], colors.warning]);
        peityDonut1('#donut-chart-5', 120, [palette['border-color'], darken(colors.danger, 20)]);
        //redraw components after window resize. this can be removed in production
        function onResizeEnd() {
            $('body').trigger('changed:background');
            peityDonut1('#donut-chart-1', 120, [palette['border-color'], colors.info]);
            peityDonut1('#donut-chart-2', 120, [palette['border-color'], colors.success]);
            peityDonut1('#donut-chart-3', 120, [palette['border-color'], colors.warning]);
            peityDonut1('#donut-chart-5', 120, [palette['border-color'], darken(colors.danger, 20)]);
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

function peityDonut1(element, radius, colors) {
    return $(element).peity('donut', {
        width: radius,
        height: radius,
        radius: radius,
        fill: colors
    });
}

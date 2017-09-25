'use strict';
/**
 * @author Batch Themes Ltd.
 */
(function() {
    $(function() {
        if (!element_exists('#dashboards-dashboard')) {
            return false;
        }

        setInterval(function() {
            var index = Math.floor(Math.random() * 6);
            $('.table-widget-1 tr').each(function(i, v) {
                var td = $('td:nth-child(3)', $(this));
                var value = td.text().trim();
                var random = Math.floor(Math.random() * 1000);
                td.css({
                    'font-weight': 500
                })
                if (i === index) {
                    td.text(random);
                    td.css({
                        'font-weight': 700
                    })
                }
            });
            $('.table-widget-1 tr').each(function(i, v) {
                var td = $('td:nth-child(4)', $(this));
                var value = parseInt(td.text().trim());
                var random = Math.floor(Math.random() * 100);
                td.css({
                    'font-weight': 500
                })
                if (i === index) {
                    td.text(random + '%');
                    td.css({
                        'font-weight': 700
                    })
                }
            });

            var v1 = Math.floor(Math.random() * 100) + 4200;
            $('#v-1').html(accounting.formatMoney(v1, '', 0, ','));
            $('#v-5').html('(' + (Math.random() * 10).toFixed(1) + '%)');

            var v2 = Math.floor(Math.random() * 1000) + 9500;
            $('#v-2').html(accounting.formatMoney(v2, '$', 0, ','));
            $('#v-6').html('(' + (Math.random() * 10).toFixed(1) + '%)');

            var v3 = Math.floor(Math.random() * 100) + 500;
            $('#v-3').html(accounting.formatMoney(v3, '', 0, ','));
            $('#v-7').html('(' + (Math.random() * 20).toFixed(1) + '%)');

            var v4 = Math.floor(Math.random() * 100) + 900;
            $('#v-4').html(accounting.formatMoney(v4, '', 0, ','));
            $('#v-8').html('(' + (Math.random() * 20).toFixed(1) + '%)');

        }, 1000);

        function peityArea1(element, height, color) {
            var chart = $(element).peity('line', {
                height: height,
                width: '100%',
                stroke: color,
                fill: color
            });
            setInterval(function() {
                var random = Math.floor(Math.random() * 10) + 2;
                var values = chart.text().split(',');
                values.shift();
                values.push(random);
                chart.text(values.join(',')).change();
            }, 1000);
        }

        function peityBar1(element, height, color) {
            var chart = $(element).peity('bar', {
                height: height,
                width: '100%',
                fill: [color]
            });
            setInterval(function() {
                var random = Math.floor(Math.random() * 10) + 2;
                var values = chart.text().split(',');
                values.shift();
                values.push(random);
                chart.text(values.join(',')).change();
            }, 1000);
        }
        var colors = bootstrap_colors();
        peityBar1('.bar-chart-1', 75, colors.success);
        peityBar1('.bar-chart-2', 75, colors.danger);
        peityArea1('.area-chart-1', 75, colors.warning);
        peityArea1('.area-chart-2', 75, colors.info);
        morrisBar('bar-chart-3', colors);
        morrisLine('line-chart-1', colors);
        //redraw components after window resize. this can be removed in production
        function onResizeEnd() {
            peityBar1('.bar-chart-1', 75, colors.success);
            peityBar1('.bar-chart-2', 75, colors.danger);
            peityArea1('.area-chart-1', 75, colors.warning);
            peityArea1('.area-chart-2', 75, colors.info);
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
        setTimeout(function() {
            $('.progress-bar').each(function() {
                var value = $(this).attr('data-value');
                $(this).animate({
                    width: value + '%'
                }, 1000);
            });
        }, 1000);
    });
})();

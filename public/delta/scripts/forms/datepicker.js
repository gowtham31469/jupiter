'use strict';
/**
 * @author Batch Themes Ltd.
 */
(function() {
    $(function() {
        if (!element_exists('#forms-datepicker')) {
            return false;
        }
        $('#date-picker-1').datepicker({
            format: 'mm/dd/yyyy',
            orientation: 'bottom'
        });
        $('#date-picker-1').on('changeDate', function() {
            var val = $('#date-picker-1').datepicker('getFormattedDate');
            console.log('date: ', val);
        });
        $('#date-picker-2').datepicker({
            orientation: 'bottom'
        });
        $('#date-picker-2').on('changeDate', function() {
            var val = $('#date-picker-2').datepicker('getFormattedDate');
            console.log('date: ', val);
        });
        $('#date-picker-3').datepicker({
            orientation: 'bottom'
        });
        $('#date-picker-3').on('changeDate', function() {
            var val = $('#date-picker-3').datepicker('getFormattedDate');
            console.log('date: ', val);
        });
        $('#date-picker-4').datepicker({
            orientation: 'bottom'
        });
        $('#date-picker-4').on('changeDate', function() {
            var val = $('#date-picker-4').datepicker('getFormattedDate');
            console.log('date: ', val);
        });
        $('#date-picker-5').datepicker({
            orientation: 'bottom'
        });
        $('#date-picker-5').on('changeDate', function() {
            var val = $('#date-picker-5').datepicker('getFormattedDate');
            console.log('date: ', val);
            $('#date-picker-5-input').val(val);
        });
    });
})();

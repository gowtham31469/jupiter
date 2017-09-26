'use strict';
/**
 * @author Batch Themes Ltd.
 */
(function() {
    $(function() {
        $('.left-sidebar-backdrop').on('click', function() {
            $(this).removeClass('fade');
            $(this).removeClass('in');
            var body = $('body');
            body.attr('data-collapsed', false);
        });
        $('.right-sidebar-backdrop').on('click', function() {
            $(this).removeClass('fade');
            $(this).removeClass('in');
            $('.right-sidebar-outer').removeClass('show-from-right');
        });
    });
})();

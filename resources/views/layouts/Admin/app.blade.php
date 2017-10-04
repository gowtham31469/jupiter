<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>JGA</title>
    <meta name="description" content="dashboard, admin, template, templates, delta, bootstrap 4, angular 4 templates, angular 4 themes">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('delta/assets/icons/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('delta/assets/icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('delta/assets/icons/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('delta/assets/icons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('delta/assets/icons/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('delta/assets/icons/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <!-- icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/weather-icons/2.0.9/css/weather-icons.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/weather-icons/2.0.9/css/weather-icons-wind.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- build:css /css/vendor.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/bower_components/fullcalendar/dist/fullcalendar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/bower_components/fakeLoader/fakeLoader.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/bower_components/PACE/themes/blue/pace-theme-minimal.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/bower_components/sweetalert2/dist/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/bower_components/nouislider/distribute/nouislider.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/bower_components/chartist/dist/chartist.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/bower_components/nvd3/build/nv.d3.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/bower_components/morris.js/morris.css') }}">
    <!-- endbuild -->
    <!-- build:css /css/main.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/activity-widgets/activity-widget-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/activity-widgets/activity-widget-3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/activity-widgets/activity-widget-4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/activity-widgets/activity-widget-5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/activity-widgets/activity-widget-7.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/finance-widgets/finance-widget-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/table-widgets/table-widget-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/table-widgets/table-widget-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/table-widgets/table-widget-3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/table-widgets/table-widget-4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/table-widgets/table-widget-5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/table-widgets/table-widget-6.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/table-widgets/table-widget-7.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/table-widgets/table-widget-8.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/table-widgets/table-widget-9.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/icon-widgets/icon-widget-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/icon-widgets/icon-widget-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/icon-widgets/icon-widget-3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/icon-widgets/icon-widget-4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/icon-widgets/icon-widget-5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/icon-widgets/icon-widget-6.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/text-widgets/text-widget-9.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/text-widgets/text-widget-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/text-widgets/text-widget-10.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/text-widgets/text-widget-11.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/text-widgets/text-widget-12.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/timeline-widgets/timeline-widget-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/timeline-widgets/timeline-widget-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/timeline-widgets/timeline-widget-3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/user-widgets/user-widget-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/user-widgets/user-widget-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/user-widgets/user-widget-3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/user-widgets/user-widget-4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/user-widgets/user-widget-5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/user-widgets/user-widget-6.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/user-widgets/user-widget-7.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/user-widgets/user-widget-8.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/line-chart-widgets/line-chart-widget-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/line-chart-widgets/line-chart-widget-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/line-chart-widgets/line-chart-widget-3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/line-chart-widgets/line-chart-widget-4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/line-chart-widgets/line-chart-widget-5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/line-chart-widgets/line-chart-widget-6.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/line-chart-widgets/line-chart-widget-7.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/line-chart-widgets/line-chart-widget-8.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/bar-chart-widgets/bar-chart-widget-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/bar-chart-widgets/bar-chart-widget-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/bar-chart-widgets/bar-chart-widget-3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/bar-chart-widgets/bar-chart-widget-4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/bar-chart-widgets/bar-chart-widget-5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/bar-chart-widgets/bar-chart-widget-6.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/bar-chart-widgets/bar-chart-widget-7.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/bar-chart-widgets/bar-chart-widget-8.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pie-chart-widgets/pie-chart-widget-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pie-chart-widgets/pie-chart-widget-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pie-chart-widgets/pie-chart-widget-3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pie-chart-widgets/pie-chart-widget-5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/area-chart-widgets/area-chart-widget-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/area-chart-widgets/area-chart-widget-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/area-chart-widgets/area-chart-widget-3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/area-chart-widgets/area-chart-widget-5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/area-chart-widgets/area-chart-widget-4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/area-chart-widgets/area-chart-widget-6.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/area-chart-widgets/area-chart-widget-7.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/area-chart-widgets/area-chart-widget-8.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/donut-chart-widgets/donut-chart-widget-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/donut-chart-widgets/donut-chart-widget-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/donut-chart-widgets/donut-chart-widget-3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/donut-chart-widgets/donut-chart-widget-5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/layouts/off-canvas-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/layouts/default-sidebar-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/left-sidebar-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/navbar-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/layouts/collapsed-sidebar-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/collapsed-left-sidebar-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/collapsed-sidebar-heading-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/layouts/sidebar-over-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/layouts/top-navigation-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/top-navigation-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/layouts/top-navigation-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/top-navigation-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/layouts/empty-view-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/widget.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/backdrops.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/sidebar-heading-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/sidebar-widget-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/sidebar-widget-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/logo.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/dropdown-grid.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/dropdown-tasks.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/dropdown-messages.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/dropdown-flags.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/dropdown-user.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/right-sidebar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/jumbotron-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/elements/jumbotron-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/dashboards/dashboard.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pages/login.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/apps/calendar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/ui-elements/buttons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/ui-elements/images.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/ui-elements/lists.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/ui-elements/typography.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/ui-elements/social-media-buttons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/ui-elements/badges.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/ui-elements/progress-bars.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/ui-elements/tabs.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/ui-elements/breadcrumbs.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/ui-elements/cards.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/ui-elements/dropdowns.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/ui-elements/pagination.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/notifications/tooltips.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/notifications/popovers.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/notifications/modals.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/notifications/alerts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/notifications/sweet-alert-2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/notifications/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/icons/flags.css') }}">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/icons/weather-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/simple-line-icons-master/css/simple-line-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/icons/ionicons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/forms/default-forms.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/forms/file-uploads.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/forms/steps.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/forms/datepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/forms/sliders.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/tables/default-tables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/tables/datatable.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/tables/jquery-treegrid.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/charts/peity.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/charts/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/charts/nvd3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/charts/easy-pie-chart.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/charts/morris-js.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/documentation/code-structure.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/documentation/customization.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/documentation/layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/documentation/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/documentation/credits.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/maps/google-maps.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/maps/vector-maps.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pages/coming-soon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pages/contact-us.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pages/create-account.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pages/error-page.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pages/reset-password.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pages/subscribe.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pages/under-maintenance.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pages/unlock-account.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('delta/css/pages/user-profile.css') }}">
    <!-- endbuild -->
</head>
        @if (Auth::guard("web")->user()) @include('layouts.Admin.navbar') @include('layouts.Admin.sidebar') 
        @elseif(!Auth::guest())
            <script type="text/javascript">
                window.location = "{{ url('/admin/login') }}";
            </script>
        @endif
        @yield('content')   
    <script>
    var base_url = "<?php echo  url('/') ?>";
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=weather,visualization,panoramio&key=AIzaSyDRuAzjz4dLpeQnvW4D8qZ7mX-G0pAZEcI"></script>
    <!-- build:js /js/vendor.js -->
    <script src="{{ asset('delta/bower_components/jquery-1.9.1/index.js') }}"></script>
    <script src="{{ asset('delta/bower_components/tether/dist/js/tether.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/lodash/dist/lodash.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/js-storage/js.storage.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/mousetrap/mousetrap.js') }}"></script>
    <script src="{{ asset('delta/bower_components/d3/d3.js') }}"></script>
    <script src="{{ asset('delta/bower_components/moment/moment.js') }}"></script>
    <script src="{{ asset('delta/bower_components/fakeLoader/fakeLoader.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/jquery-fullscreen/jquery.fullscreen-min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/PACE/pace.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/fullcalendar/dist/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/dropzone/dist/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/approvejs/dist/approve.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/nouislider/distribute/nouislider.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/datatables/media/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/table-export/tableExport.js') }}"></script>
    <script src="{{ asset('delta/bower_components/table-export/jquery.base64.js') }}"></script>
    <script src="{{ asset('delta/bower_components/table-export/jspdf/libs/sprintf.js') }}"></script>
    <script src="{{ asset('delta/bower_components/table-export/jspdf/jspdf.js') }}"></script>
    <script src="{{ asset('delta/bower_components/table-export/jspdf/libs/base64.js') }}"></script>
    <script src="{{ asset('delta/bower_components/jquery-treegrid/js/jquery.treegrid.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/nvd3/build/nv.d3.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/morris.js/morris.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/topojson/topojson.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/datamaps/dist/datamaps.all.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/accounting.js/accounting.min.js') }}"></script>
    <script src="{{ asset('delta/bower_components/jquery.countdown/dist/jquery.countdown.min.js') }}"></script>
    <!-- endbuild -->
    <!-- build:js /js/main.js -->
    <script src="{{ asset('delta/scripts/colors.js') }}"></script>
    <script src="{{ asset('delta/scripts/functions.js') }}"></script>
    <script src="{{ asset('delta/scripts/charts/functions.js') }}"></script>
    <script src="{{ asset('delta/scripts/maps/functions.js') }}"></script>
    <script src="{{ asset('delta/scripts/icons/functions.js') }}"></script>
    <script src="{{ asset('delta/scripts/custom-events.js') }}"></script>
    <!--<script src="{{ asset('delta/scripts/app.js') }}"></script>-->
    <script src="{{ asset('delta/scripts/mousetrap.js') }}"></script>
    <script src="{{ asset('delta/scripts/elements/right-sidebar.js') }}"></script>
    <script src="{{ asset('delta/scripts/elements/navbar.js') }}"></script>
    <script src="{{ asset('delta/scripts/elements/backdrops.js') }}"></script>
    <script src="{{ asset('delta/scripts/dashboards/dashboard.js') }}"></script>
    <script src="{{ asset('delta/scripts/widgets/line-chart-widgets.js') }}"></script>
    <script src="{{ asset('delta/scripts/widgets/bar-chart-widgets.js') }}"></script>
    <script src="{{ asset('delta/scripts/widgets/pie-chart-widgets.js') }}"></script>
    <script src="{{ asset('delta/scripts/widgets/area-chart-widgets.js') }}"></script>
    <script src="{{ asset('delta/scripts/widgets/donut-chart-widgets.js') }}"></script>
    <script src="{{ asset('delta/scripts/apps/calendar.js') }}"></script>
    <script src="{{ asset('delta/scripts/ui-elements/progress-bars.js') }}"></script>
    <script src="{{ asset('delta/scripts/notifications/tooltips.js') }}"></script>
    <script src="{{ asset('delta/scripts/notifications/popovers.js') }}"></script>
    <script src="{{ asset('delta/scripts/notifications/sweet-alert-2.js') }}"></script>
    <script src="{{ asset('delta/scripts/notifications/toastr.js') }}"></script>
    <script src="{{ asset('delta/scripts/icons/font-awesome.js') }}"></script>
    <script src="{{ asset('delta/scripts/icons/flags.js') }}"></script>
    <script src="{{ asset('delta/scripts/icons/material-design-icons.js') }}"></script>
    <script src="{{ asset('delta/scripts/icons/weather-icons.js') }}"></script>
    <script src="{{ asset('delta/scripts/icons/simple-line-icons.js') }}"></script>
    <script src="{{ asset('delta/scripts/icons/ionicons.js') }}"></script>
    <script src="{{ asset('delta/scripts/forms/file-uploads.js') }}"></script>
    <script src="{{ asset('delta/scripts/forms/steps.js') }}"></script>
    <script src="{{ asset('delta/scripts/forms/validation.js') }}"></script>
    <script src="{{ asset('delta/scripts/forms/datepicker.js') }}"></script>
    <script src="{{ asset('delta/scripts/forms/sliders.js') }}"></script>
    <script src="{{ asset('delta/scripts/tables/default-tables.js') }}"></script>
    <script src="{{ asset('delta/scripts/tables/datatable.js') }}"></script>
    <script src="{{ asset('delta/scripts/tables/table-export.js') }}"></script>
    <script src="{{ asset('delta/scripts/tables/jquery-treegrid.js') }}"></script>
    <script src="{{ asset('delta/scripts/charts/peity.js') }}"></script>
    <script src="{{ asset('delta/scripts/charts/chartist.js') }}"></script>
    <script src="{{ asset('delta/scripts/charts/nvd3.js') }}"></script>
    <script src="{{ asset('delta/scripts/charts/easy-pie-chart.js') }}"></script>
    <script src="{{ asset('delta/scripts/charts/morris-js.js') }}"></script>
    <script src="{{ asset('delta/scripts/maps/google-maps.js') }}"></script>
    <script src="{{ asset('delta/scripts/maps/vector-maps.js') }}"></script>
    <script src="{{ asset('delta/scripts/plugins/moment-js.js') }}"></script>
    <script src="{{ asset('delta/scripts/plugins/accounting-js.js') }}"></script>
    <script src="{{ asset('delta/scripts/pages/coming-soon.js') }}"></script>
    <script src="{{ asset('delta/scripts/pages/error-page.js') }}"></script>
    <script src="{{ asset('js/Admin/scripts.js') }}"></script>
    @yield('jqueryIncludes')
    <script>
    $(function () {
        var url = window.location;
        $('.menu-bottom a[href="' + url + '"]').parent('li').addClass('current-page');
        $('.menu-bottom a').filter(function () {
            return this.href == url;
        }).parent('li').addClass('current-page').parent('ul').show('fast').addClass('active');
    });
    </script>
    <!-- endbuild -->
</html>

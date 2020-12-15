<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="">
    <title>JGA</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ asset('css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('DataTables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('DataTables/Buttons-1.5.1/css/buttons.dataTables.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/ion-icons.min.css') }}">
     <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
     <link href="{{ asset('font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/jqtree.min.css') }}" rel="stylesheet" /> 
   <link rel="stylesheet" href="{{ asset('css/style.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/toastr.css') }}" />
      <!-- Bootstrap datepicker -->
    <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datepicker.standalone.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/easy-autocomplete.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/easy-autocomplete.themes.min.css') }}">

</head>
<body>
    
    <div class="container-fluid">
        <div class="row">
        @if (Auth::guard("web")->user())
        @include('layouts.Admin.navbar')
        @elseif(!Auth::guest())
            <script type="text/javascript">
                 window.location = "{{ url('/admin/login') }}";
            </script>
        @endif
        @yield('content')
        </div>
    </div>
    
    <script type="text/javascript" src="{{ asset('DataTables/jQuery-3.2.1/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bootstrap-3.3.7-dist/js/bootstrap.min.js') }}"></script>
    <!-- <script type="text/javascript" src="{{ asset('DataTables/DataTables-1.10.16/js/jquery.dataTables.min.js') }}"></script> -->
    <script src="{{ asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script> 
    <script type="text/javascript" src="{{ asset('DataTables/Buttons-1.5.1/js/dataTables.buttons.min.js') }}"></script>

     <script type="text/javascript" src="{{ asset('DataTables/Buttons-1.5.1/js/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/Buttons-1.5.1/js/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/Buttons-1.5.1/js/buttons.print.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/Buttons-1.5.1/js/buttons.colVis.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/JSZip-2.5.0/jszip.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/Chart.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/dropzone.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/select2.min.js') }}"></script>

    <script src="{{ asset('js/JsBarcode.all.min.js') }}"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>
    <script src="{{ asset('js/clipboard.min.js') }}"></script>
    <script src="{{ asset('js/tree.jquery.min.js') }}"></script>
    <script src="{{ asset('js/jstree.min.js') }}"></script>  
    <script src="{{ asset('js/jquery.nicescroll.js') }}"></script>
    <script src="{{ asset('js/dataTables.checkboxes.min.js') }}"></script>

    
      <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/jquery.nicescroll-ajax.js') }}" type="text/javascript"></script>
	
    <!-- Latest compiled and minified JavaScript -->
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>

    <script src="{{ asset('js/jquery.easy-autocomplete.min.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <script src="{{ asset('js/qrjs2.min.js') }}"></script>
    
<script>
    var base_url = "<?php echo  url('/') ?>";
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

     $(document).ready(function() {
    $('#send_feedback').trigger("reset");
    $('#admin_profile_result').hide();
    $('#change_admin_password').trigger("reset");
    $('#change_password_result').hide();
});
/** Send feedback bootstrap form validation **/
$('#send_feedback').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {

    } else {
        e.preventDefault();
        var form_data = new FormData($(this)[0]);

        form_data.append('_token', csrf_token);
        /* call sendFeedback function*/
        sendFeedback(form_data);
    }
})
/**
 * Send feedback function.
 * author <praveen>
 */
function sendFeedback(form_data) {
    try {
        $.ajax({
            url: base_url + "/feedback-mail/send",
            type: "POST",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            tryCount: 0,
            retryLimit: 2,
            beforeSend: function() {
                $('#sendFeedbackModal').modal('hide');
                $(".loader").show();
            },
            statusCode: {
                /** [ Show response status based on HTTP status code ] */
                200: function() {
                    $('.snackbar_span').html('Feedback Sent Successfully');
                    showSnackbar();
                },
                400: function() {
                    console.log('error')
                }
            },
            error: function(errorXHR, errorStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
            },
            complete: function() {
                $(".loader").hide();
            }
        });

    } catch (e) {
        console.log("Error:" + e)
    }
}
/** To refresh modal while hidden **/
$('.modal').on('hidden.bs.modal', function(e) {
    $('#send_feedback').trigger("reset");
    $('#admin_profile_result').hide();
    $('#change_admin_password').trigger("reset");
    $('#change_password_result').hide();
})
/**
 * showSnackbar function
 * @author    <praveen>
 */
function showSnackbar() {
    try {
        /** [ Get the snackbar DIV ] */
        var x = document.getElementById("snackbar")

        /** [ Add the "show" class to DIV ] */
        x.className = "show";

        /** [  After 3 seconds, remove the show class from DIV ] */
        setTimeout(function() {
            x.className = x.className.replace("show", "");
        }, 3000);
    } catch (e) {
        console.log("Error:" + e)
    }
}
/**
 * Validation for numeric values
 * @param  evt  
 * @return {Boolean} 
 * @author <praveen>
 */
function isNumber(evt) {
    try {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    } catch (e) {
        console.log("Error:" + e)
    }
}
/**
 * Get admin profile details function.
 * author <praveen>
 */
function getAdminProfile(id) {
    try {
        $.ajax({
            url: base_url + "/admin/get-profile-details",
            type: "GET",
            data: {
                id: id
            },
            tryCount: 0,
            retryLimit: 2,
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {

                /** [Populate values in form inputs] */
                $('#admin_profile_id').val(id);
                $('#admin_profile_name').val(data.admin[0].name);
                $('#admin_profile_email').val(data.admin[0].email);
                $('#admin_profile_phone').val(data.admin[0].phone);
                $('#admin_profile_address').val(data.admin[0].address);

                /** [ Backdrop for bootstrap modal ] */
                $('#editAdminModal').modal({
                    backdrop: 'static',
                    keyboard: false
                })

                /** [ Validate admin_profile_form form using bootstrap validator ] */
                $('#admin_profile_form').validator('validate');
                $('#editAdminModal').modal('show');
            },
            error: function(errorXHR, errorStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
            },
            complete: function() {
                $(".loader").hide();
            }
        });

    } catch (e) {
        console.log("Error:" + e)
    }
}

/**Update admin profile bootstrap form validation **/
$('#admin_profile_form').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {

    } else {
        e.preventDefault();

        /* call editAdmin function*/
        editAdmin();
    }
})
/**
 * Function to update admin details
 * @author <praveen>
 */
function editAdmin() {              
    try {
        var id      = $('#admin_profile_id').val();
        var name    = toTitleCase($('#admin_profile_name').val());
        var phone   = $('#admin_profile_phone').val();
        var email   = $('#admin_profile_email').val();
        var address = toTitleCase($('#admin_profile_address').val().replace(/,/g, ", "));

        $.ajax({
            url: base_url + "/admin/update-admin-profile-details",
            dataType: "json",
            tryCount: 0,
            retryLimit: 2,
            beforeSend: function() {
                $(".loader").show();
            },
            method: "put",
            data: {
                _token: csrf_token,
                id: id,
                name: name,
                email: email,
                phone: phone,
                address: address
            },
            statusCode: {

                /** [Show response status based on HTTP status code] */
                200: function() {
                    $('.snackbar_span').html('Profile Updated Successfully');
                    showSnackbar();
                    $('#editAdminModal').modal('hide');
                },
                409: function() {
                    $('#admin_profile_result').removeAttr('style');
                    $('#admin_profile_result').html('Email already registered.')
                    $("#admin_profile_result").addClass("alert alert-warning");
                }
            },
            error: function(errorXHR, errorStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
            },
            complete: function() {
                /** [ To make top menu active ] */
                $(function() {
                    var url = window.location;
                    $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
                    $('.dynamic-menu a').filter(function() {
                        return this.href == url;
                    }).parent('li').addClass('current-page').find('a').addClass('active');
                });
                $(".loader").hide();
            }
        })
    } catch (e) {
        console.log("Error:" + e)
    }
}
/**Change admin password bootstrap form validation **/
$('#change_admin_password').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {

    } else {
        e.preventDefault();

        /* call updateAdminPassword function*/
        updateAdminPassword();
    }
})
/**
 * Function to update admin password
 * @author <praveen>
 */
function updateAdminPassword() {
    try {
        var id               = $('#admin_id').val();
        var current_password = $('#current_password').val();
        var new_password     = $('#new_password').val();

        $.ajax({
            url: base_url + "/admin/update-admin-password",
            dataType: "json",
            tryCount: 0,
            retryLimit: 2,
            beforeSend: function() {
                $(".loader").show();
            },
            method: "put",
            data: {
                _token: csrf_token,
                id: id,
                np: new_password,
                cp: current_password
            },
            statusCode: {

                /** [Show response status based on HTTP status code] */
                200: function() {
                    $('.snackbar_span').html('Password Changed Successfully');
                    showSnackbar();
                    $('#changePasswordModal').modal('hide');
                },
                400: function() {
                    $('#change_password_result').removeAttr('style');
                    $('#change_password_result').html('Your current password does not matches with the password you provided. Please try again.')
                    $("#change_password_result").addClass("alert alert-warning");
                },
                409: function() {
                    $('#change_password_result').removeAttr('style');
                    $('#change_password_result').html('New Password cannot be same as your current password. Please choose a different password.')
                    $("#change_password_result").addClass("alert alert-warning");
                }
            },
            error: function(errorXHR, errorStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
            },
            complete: function() {
                /** [ To make top menu active ] */
                $(function() {
                    var url = window.location;
                    $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
                    $('.dynamic-menu a').filter(function() {
                        return this.href == url;
                    }).parent('li').addClass('current-page').find('a').addClass('active');
                });
                $(".loader").hide();
            }
        })
    } catch (e) {
        console.log("Error:" + e)
    }
}

 /**
  * Function to  title case conversion
  * @param {*} str 
  * @author <praveen>
  */
function toTitleCase(str) {
  return str.replace(/(?:^|\s)\w/g, function(match) {
      return match.toUpperCase();
  });
}
</script>
@yield('jqueryIncludes')
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script>
    $('.logout-button').click(function() 
    {
        localStorage.clear();
    });
    
    $(document).ready(function() {
        $('#buyer-select').selectpicker({
           style: 'btn-default',
           size: 4
        });

        $('#example').DataTable();
        $("#example_filter input").addClass('form-control input-sm');
        $("#example_filter input").attr('placeholder', 'Data Search');
        $('#datatable-example-2_length select').addClass('form-control input-sm');

    });
    $(function () {
    var url = window.location;
    $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
    $('.dynamic-menu a').filter(function () {
        return this.href == url;
    }).parent('li').addClass('current-page').find('a').addClass('active');
});

    var myDoughnutChart = new Chart(myChart, {
        type: 'doughnut',
        data: data = {
            datasets: [{
                data: [1600, 2230],
                backgroundColor: [
                    '#facc55', '#5ac0c0', '#e74c3c'
                ],
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Orchestra',
                'Enrich',
            ]
        },
        options: [{
        }]
    });
    var myDoughnutChart = new Chart(myChart1, {
        type: 'doughnut',
        data: data = {
            datasets: [{
                data: [1600, 2230],
                backgroundColor: [
                    '#facc55', '#5ac0c0', '#e74c3c'
                ],
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Orchestra',
                'Enrich',
            ]
        },
        options: [{

        }]
    });
    var myDoughnutChart = new Chart(myChart2, {
        type: 'doughnut',
        data: data = {
            datasets: [{
                data: [1600, 2230],
                backgroundColor: [
                    '#facc55', '#5ac0c0', '#e74c3c'
                ],
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Orchestra',
                'Enrich',
            ]
        },
        options: [{

        }]
    });
//         $('.buyer-select').select2({
//            placeholder: "Buyer Listing",
//            allowClear: true
//        });
    
    
</script>
</body>
</html>

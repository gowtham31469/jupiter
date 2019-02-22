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
     <link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />
</head>
<body class="customer-wallpaper">
    <div class="container-fluid">
        <div class="row">
        @if (Auth::guard("web_customer")->user())
            @include('layouts.Customer.navbar')
        @elseif(!Auth::guest())
            <script type="text/javascript">
                window.location = "{{ url('/customer/login') }}";
            </script>
        @endif
        @yield('content')
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('DataTables/jQuery-3.2.1/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bootstrap-3.3.7-dist/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/DataTables-1.10.16/js/jquery.dataTables.min.js') }}"></script>
   
    <script type="text/javascript" src="{{ asset('DataTables/Buttons-1.5.1/js/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/Buttons-1.5.1/js/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/Buttons-1.5.1/js/buttons.print.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/Buttons-1.5.1/js/buttons.colVis.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/JSZip-2.5.0/jszip.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script> 
    <script type="text/javascript" src="{{ asset('js/Chart.js') }}"></script>
    <script src="{{ asset('js/dropzone.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>

     <script src="{{ asset('js/JsBarcode.all.min.js') }}"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>
    <script src="{{ asset('js/clipboard.min.js') }}"></script>
    <script src="{{ asset('js/tree.jquery.min.js') }}"></script>
    <script src="{{ asset('js/jstree.min.js') }}"></script>  
    <script src="{{ asset('js/jquery.nicescroll.js') }}"></script>
    
        <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/jquery.nicescroll-ajax.js') }}" type="text/javascript"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>

<script src="{{ asset('js/jquery.easy-autocomplete.min.js') }}"></script>
	<!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/i18n/defaults-*.min.js"></script> -->
<script src="{{ asset('js/toastr.js') }}"></script>
<script>
    var base_url = "<?php echo  url('/') ?>";

     $(document).ready(function() {
    $('#send_feedback').trigger("reset");
    $('#change_customer_password').trigger("reset");
    $('#change_password_result').hide();
});
/** Send feedback bootstrap form validation **/
$('#send_feedback').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {

    } else {
        e.preventDefault();
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
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
    $('#change_customer_password').trigger("reset");
    $('#change_password_result').hide();
    $('#customer_edit_result').hide();
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
/**Change customer password bootstrap form validation **/
$('#change_customer_password').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {

    } else {
        e.preventDefault();

        /* call updateCustomerPassword function*/
        updateCustomerPassword();
    }
})
/**
 * Function to update customer password
 * @author <praveen>
 */
function updateCustomerPassword() {
    try {
        var id               = $('#customer_id').val();
        var current_password = $('#current_password').val();
        var new_password     = $('#new_password').val();

        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/customer/update-customer-password",
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
 * Get customer profile details function.
 * author <praveen>
 */
function getCustomerProfile(id) {
    try {
        $.ajax({
            url: base_url + "/customer/get-profile-details",
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
                $('#customer_edit_id').val(id);
                $('#customer_name').val(data.customer[0].name);
                $('#customer_email').val(data.customer[0].email);
                $('#customer_title').val(data.customer[0].title);

                /** [ Backdrop for bootstrap modal ] */
                $('#editCustomerModal').modal({
                    backdrop: 'static',
                    keyboard: false
                })

                /** [ Validate edit_customer_form form using bootstrap validator ] */
                $('#edit_customer_form').validator('validate');
                $('#editCustomerModal').modal('show');
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

/**Update customer profile bootstrap form validation **/
$('#edit_customer_form').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {

    } else {
        e.preventDefault();

        /* call editCustomer function*/
        editCustomer();
    }
})
/**
 * Function to update customer details
 * @author <praveen>
 */
function editCustomer() {              
    try {
        var id      = $('#customer_edit_id').val();
        var name    = toTitleCase($('#customer_name').val());
        var email   = $('#customer_email').val();
        var title   = $('#customer_title').val();

        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/customer/update-customer-profile-details",
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
                title: title
            },
            statusCode: {

                /** [Show response status based on HTTP status code] */
                200: function() {
                    $('.snackbar_span').html('Profile Updated Successfully');
                    showSnackbar();
                    $('#editCustomerModal').modal('hide');
                },
                409: function() {
                    $('#customer_edit_result').removeAttr('style');
                    $('#customer_edit_result').html('Email already registered.')
                    $("#customer_edit_result").addClass("alert alert-warning");
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
    </script>
</body>
</html>

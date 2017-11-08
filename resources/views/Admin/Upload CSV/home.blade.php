@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-12 main">
            <div class="top-content">
                <ol class="breadcrumb">
                    <li><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="active">Upload CSV</li>
                </ol>
            </div>
            <div class="main-content">
                <div class="row">
                    <div class="col-md-8">
                        <div class="parcel-details card">
                            <h4><a class="btn btn-primary btn-sm pull-right">Upload Parcel Details</a>Parcel Details</h4>
                            <hr/>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="">
                                        <h5><sub class="pull-right"> (Supported Files : .csv)</sub>Parcel Detail File - 1 </h5>
                                        <form action="/file-upload" class="dropzone" id="ParcelDetails1"></form>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="">
                                        <h5><sub class="pull-right"> (Supported Files : .csv)</sub>Parcel Detail File - 2 </h5>
                                        <form action="/file-upload" class="dropzone" id="ParcelDetails2"></form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="label-details card">
                            <h4><a class="btn btn-primary btn-sm pull-right">Upload label Details</a>Label Details</h4>
                            <hr/>
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="">
                                        <h5><sub class="pull-right"> (Supported Files : .csv)</sub>Label Details File </h5>
                                        <form action="/file-upload" class="dropzone" id="ParcelDetails1"></form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
             <h3>Notes</h3>
            <p class="info">You can also overwrite all default event actions in the options</p>
            <p class="info">You can also overwrite all default event actions in the options</p>
            <p class="info">You can also overwrite all default event actions in the options</p>
            <p class="info">You can also overwrite all default event actions in the options</p>
            </div>
           
        </div>
    </div>
</div>



@endsection @else
<script type="text/javascript">
    window.location = "{{ url('/admin') }}";
</script>
@endif @section('jqueryIncludes')
<script type="text/javascript">
    $(document).ready(function() {
        $('.logo').html('CSV Upload')
        $('#add_buyer_form').trigger("reset");
        $('#edit_buyer_form').trigger('reset');
        $('#add_merchandiser_form').trigger('reset');
        $('#edit_merchandiser_form').trigger('reset');
        $('#buyer_result').hide();
        $('#edit_buyer_result').hide();
        $('#delete_buyer_result').hide();
        $('#add_merchandiser_result').hide();
        $('#edit_merchandiser_result').hide();
    });

    $('#add_buyer_form').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            addBuyer();
        }
    })
    /** To refresh modal **/
    $('.modal').on('hidden.bs.modal', function(e) {
        $('#add_buyer_form').trigger("reset");
        $('#add_merchandiser_form').trigger('reset');
        $('#buyer_result').hide();
        $('#edit_buyer_result').hide();
        $('#delete_buyer_result').hide();
        $('#merchandiserr_result').hide();
        $('#edit_merchandiserr_result').hide();
        $('#delete_merchandiserr_result').hide();
    })
    // get edit buyer details
    $(document).on('click', '.edit-buyer', function(e) {
        var id = $(this).closest('tr').attr('id');
        e.preventDefault();
        getBuyer(id);
    });
    /** submit edit buyer form **/
    $('#edit_buyer_form').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            editBuyer();
        }
    })
    $('#deleteBuyerModal').on('show.bs.modal', function(e) {
        var config = $(e.relatedTarget).data('userid');
        $('#delete_buyer_id').attr('value', config);
    });
    /** submit delete buyer form **/
    $('#delete_buyer_form').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            var id = $('#delete_buyer_id').val();
            deleteBuyer(id);
        }
    })
    /** generate password **/
    $("#generatePwd").validator().click(function() {
        function randomString(length, chars) {
            var mask = '';
            if (chars.indexOf('a') > -1) mask += 'abcdefghijklmnopqrstuvwxyz';
            if (chars.indexOf('A') > -1) mask += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if (chars.indexOf('#') > -1) mask += '0123456789';
            if (chars.indexOf('!') > -1) mask += '!@#$%&*()+=?';
            var result = '';
            for (var i = length; i > 0; --i) result += mask[Math.round(Math.random() * (mask.length - 1))];
            return result;
        }
        $("#merchandiser_password").val(randomString(10, 'aA#!'));
        $('#add_merchandiser_form').validator('validate');
    })
    /** Copy password to clipboard **/
    $(document).ready(function() {
        var clipboard = new Clipboard('.clipboard');
        clipboard.on('success', function(e) {
            $(e.trigger).text("Copied!");
            e.clearSelection();
            setTimeout(function() {
                $(e.trigger).text("Copy");
            }, 2500);
        });
    });
    $('#add_merchandiser_form').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            addMerchandiser();
        }
    })
    // get edit merchandiser details
    $(document).on('click', '.edit-merchandiser', function(e) {
        var id = $(this).closest('tr').attr('id');
        e.preventDefault();
        getMerchandiser(id);
    });
    /** submit edit merchandiser form **/
    $('#edit_merchandiser_form').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            editMerchandiser();
        }
    })
    $('#deleteMerchandiserModal').on('show.bs.modal', function(e) {
        var config = $(e.relatedTarget).data('userid');
        $('#delete_merchandiser_id').attr('value', config);
    });
    /** submit delete merchandiser form **/
    $('#delete_merchandiser_form').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            var id = $('#delete_merchandiser_id').val();
            deleteMerchandiser(id);
        }
    })
</script>
@endsection
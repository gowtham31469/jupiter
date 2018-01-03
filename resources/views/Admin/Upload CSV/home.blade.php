@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-12 main">
            <div class="top-content">
<!--
                <ol class="breadcrumb">
                    <li><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="active">Upload CSV</li>
                </ol>
-->
            </div>
            <div class="main-content">
                <div class="row">
                    <div class="col-md-8">
                        <div class="parcel-details card">
                            <h4><a class="btn btn-primary btn-sm pull-right upload-parcel-details">Upload Parcel Details</a>Parcel Details</h4>
                            <hr/>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="">
                                        <h5><sub class="pull-right"> (Supported Files : .csv)</sub>Parcel Detail File - 1 </h5>
                                        <form action="/file-upload" class="dropzone" id="parcel-details-1">
                                             {{ csrf_field() }}
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="">
                                        <h5><sub class="pull-right"> (Supported Files : .csv)</sub>Parcel Detail File - 2 </h5>
                                        <form action="/file-upload" class="dropzone" id="parcel-details-2">
                                             {{ csrf_field() }}
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="label-details card">
                            <h4><a class="btn btn-primary btn-sm pull-right upload-label-details">Upload label Details</a>Label Details</h4>
                            <hr/>
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="">
                                        <h5><sub class="pull-right"> (Supported Files : .csv)</sub>Label Details File </h5>
                                       <form method="post"  class="dropzone" id="label-details">
                                            {{ csrf_field() }}
                                       </form>
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
        $(document).ready(function(){
        $('.logo').html('Upload CSV');
    })
    Dropzone.options.labelDetails = {
      // Prevents Dropzone from uploading dropped files immediately
        url:"order-management/multi-upload",
        autoProcessQueue: false,
        maxFilesize: 10,
        maxFiles:1,
        acceptedFiles: ".csv, .xlsx, .xls",

        init: function() {
            var submitButton = document.querySelector(".upload-label-details")
                myDropzone = this; // closure

            submitButton.addEventListener("click", function() {
              myDropzone.processQueue(); // Tell Dropzone to process all queued files.
            });

            // You might want to show the submit button only when 
            // files are dropped here:
            this.on("addedfile", function() {
              // Show submit button here and/or inform user to click it.
            });

        }
    };

    Dropzone.options.parcelDetails1 = {
      // Prevents Dropzone from uploading dropped files immediately
        url:"order-management/multi-upload",
        autoProcessQueue: false,
        maxFilesize: 10,
        maxFiles:1,
        acceptedFiles: ".csv, .xlsx, .xls",

        init: function() {
            var submitButton = document.querySelector(".upload-parcel-details")
                myDropzone1 = this; // closure

            submitButton.addEventListener("click", function() {
              myDropzone1.processQueue(); // Tell Dropzone to process all queued files.
            });

            // You might want to show the submit button only when 
            // files are dropped here:
            this.on("addedfile", function() {
              // Show submit button here and/or inform user to click it.
            });
        }
    };

    Dropzone.options.parcelDetails2 = {
      // Prevents Dropzone from uploading dropped files immediately
        url:"order-management/multi-upload",
        autoProcessQueue: false,
        maxFilesize: 10,
        maxFiles:1,
        acceptedFiles: ".csv, .xlsx, .xls",

        init: function() {
            var submitButton = document.querySelector(".upload-parcel-details")
                myDropzone2 = this; // closure

            submitButton.addEventListener("click", function() {
              myDropzone2.processQueue(); // Tell Dropzone to process all queued files.
            });

            // You might want to show the submit button only when 
            // files are dropped here:
            this.on("addedfile", function() {
              // Show submit button here and/or inform user to click it.
            });

        }
    };


</script>
@endsection
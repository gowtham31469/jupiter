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
                            <h4><a class="btn btn-primary btn-sm pull-right upload-parcel-details ">Upload Parcel Details</a>Parcel Details</h4>
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
                            <h4><a class="btn btn-primary btn-sm pull-right upload-label-details hidden">Upload label Details</a>Label Details</h4>
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
    Dropzone.options.labelDetails = {
      // Prevents Dropzone from uploading dropped files immediately
        url:"upload-label-details",
        autoProcessQueue: false,
        maxFilesize: 500,
        maxFiles:1,
        acceptedFiles: ".csv, .xlsx, .xls",
        addRemoveLinks: true,

        init: function() {

            var submitButton = document.querySelector(".upload-label-details")
                myDropzone = this; // closure

            submitButton.addEventListener("click", function()
            {          
              myDropzone.processQueue();
              // Tell Dropzone to process all queued files.
            });

            // You might want to show the submit button only when 
            // files are dropped here:
            this.on("addedfile", function(file)
            {
                var filename = file.name;   
                if (filename.indexOf("ItemDetails_ORCHESTRA") !=-1)
                {
                    $(".upload-label-details").removeClass("hidden");
                }
                else
                {
                   alert('You uploaded the wrong file !')                   
                   this.removeFile(file);
                }
              // Show submit button here and/or inform user to click it.
            });
            this.on("complete", function(file){        
                 this.removeFile(file);

            });
            this.on("queuecomplete", function (file)
            {
                getUpdateDetails();
            });
            this.on("success", function(file, responseText){ 
                $('.dz-remove').hide();
                this.removeFile(file);
                $(".upload-label-details").addClass("hidden");
                
            });
            this.on("error", function(file, responseText) {
                 alert('error');
                 this.removeFile(file);
                 $(".upload-label-details").addClass("hidden");
            });


        }
    };

    Dropzone.options.parcelDetails1 = {
      // Prevents Dropzone from uploading dropped files immediately
        url:"upload-parcel-details-file-one",
        autoProcessQueue: false,
        maxFilesize: 10,
        maxFiles:1,
        acceptedFiles: ".csv, .xlsx, .xls",
        addRemoveLinks: true,

        init: function() {
             myDropzone1 = this; // closure

            // You might want to show the submit button only when 
            // files are dropped here:
            this.on("addedfile", function(file) {
              // Show submit button here and/or inform user to click it.
               var filename = file.name;             
                if (filename.indexOf("ParcelDetails_File1") !=-1)
                {
                    $(".upload-parcel-details").removeClass("hidden");
                }
                else
                {
                   alert('You uploaded the wrong file !')                   
                   this.removeFile(file);
                }
            });

            this.on("complete", function(file){        
                 this.removeFile(file);
            });
            this.on("success", function(file, responseText){ 
                $('.dz-remove').hide();
                 this.removeFile(file);
            });
            this.on("error", function(file, responseText) {
                 alert('error');
                 this.removeFile(file);
                 $(".upload-parcel-details").addClass("hidden");
            });
        }
    };

    Dropzone.options.parcelDetails2 = {
      // Prevents Dropzone from uploading dropped files immediately
        url:"upload-parcel-details-file-two",
        autoProcessQueue: false,
        maxFilesize: 10,
        maxFiles:1,
        acceptedFiles: ".csv, .xlsx, .xls",
        addRemoveLinks: true,

        init: function() {
            myDropzone2 = this; // closure

            // You might want to show the submit button only when files are dropped here:
            this.on("addedfile", function(file) {
              // Show submit button here and/or inform user to click it.
                var filename = file.name;             
                if (filename.indexOf("ParcelDetails_File2") !=-1)
                {
                    $(".upload-parcel-details").removeClass("hidden");
                }
                else
                {
                   alert('You uploaded the wrong file !')                   
                   this.removeFile(file);
                }
            });
            this.on("complete", function(file){        
                 this.removeFile(file);
            });
            this.on("success", function(file, responseText){ 
                $('.dz-remove').hide();
                 this.removeFile(file);
            });
            this.on("error", function(file, responseText) {
                alert('error');
                this.removeFile(file);
                $(".upload-parcel-details").addClass("hidden");
            });
        }
    };

    $('.upload-parcel-details').on('click', function ()
    {
        myDropzone1.processQueue();
        myDropzone2.processQueue();
    });
    function getUpdateDetails()
    {
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/admin/csv-updates",
            type: "get",
           
            statusCode: {
                 200: function(data) {
                    alert("success")
                 },
            }
        })
    }
</script>
@endsection
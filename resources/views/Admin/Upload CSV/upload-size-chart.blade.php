@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<style>
    .large .modal-lg {
        margin: 0 !important;
        width: 100%;
    }

    .large .modal-lg .modal-content {
        border-radius: 0px;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-12 main">
            <div class="top-content">
            </div>
            <div class="main-content">
                <div class="parcel-details card">
                    <h4><a class="btn btn-primary btn-sm pull-right upload-csv-files ">Upload</a>Size Chart</h4>
                    <hr/>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <h5><sub class="pull-right"> (Supported Files : .csv)</sub><strong>PO file</strong> </h5>
                            <form action="/file-upload" class="dropzone" id="po-csv-file">
                                {{ csrf_field() }}
                            </form>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection @else
<script type="text/javascript">
    window.location = "{{ url('/admin') }}";
</script>
<script src="{{ asset('js/Admin/orchestra-csv-upload.js') }}"></script>
@endif @section('jqueryIncludes')

<script type="text/javascript">
    $(document).ready(function() {
        $('.logo').html('Upload Translation');

        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    })

    Dropzone.options.poCsvFile = {

        // Prevents Dropzone from uploading dropped files immediately
        url:"upload-size-chart-csv",
        autoProcessQueue: false,
        maxFilesize: 10,
        maxFiles: 1,
        acceptedFiles: ".csv, .xlsx, .xls",
        addRemoveLinks: true,
    
        init: function() {
             myDropzone1 = this; // closure
    
            // You might want to show the submit button only when 
            // files are dropped here:
            this.on("addedfile", function(file) {
                // Show submit button here and/or inform user to click it.
                //Original string 
                var filename_array = [];
                var filename       = file.name;    
                
                while(filename.includes("UK_CE_PRIMARY") === false)
                {                  
                    $('.snackbar_span').html("Upload correct file !!");
                    showSnackbar();
                    this.removeFile(file);
                }
            });
    
            this.on("complete", function(file){        
                 this.removeFile(file);
                 $('.snackbar_span').html("Size Chart uploaded successfully");
                 showSnackbar();
            });
            this.on("success", function(file, response){ 
                $('.snackbar_span').html("Size Chart uploaded successfully");
                showSnackbar();
                $('.dz-remove').hide();
                 this.removeFile(file);
            });
            this.on("error", function(file, response) {
                 $('.snackbar_span').html("You can only upload a maximum 1 file. ");
                 showSnackbar();
                 this.removeFile(file);
                 $(".upload-csv-files").addClass("hidden");
            });
        }
    };
    
    $('.upload-csv-files').on('click', function ()
    {
        if(myDropzone1.files.length === 1)
        {
            myDropzone1.processQueue();
        }
        else
        {
            $('.snackbar_span').html("Size Chart Required.");
            showSnackbar();
        }
    });
 
</script>
@endsection
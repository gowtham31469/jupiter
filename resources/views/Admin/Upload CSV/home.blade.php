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
                <div class="loader">
                    <div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>
                </div>
                <div class="parcel-details card">
                    <h4><a class="btn btn-primary btn-sm pull-right upload-csv-files ">Upload</a>Orchestra CSV Files</h4>
                    <hr />
                    @if($errors->any())
                    {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
                    @endif
                    <div class="row">
                    <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <h5><sub class="pull-right"> (Supported Files : .csv)</sub><strong>PO & Price files</strong> </h5>
                            <form class="dropzone" id="orch-order-csv-file">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    <div class="col-md-4"></div>
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
        $('.logo').html('Upload CSV');

        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    })

    Dropzone.options.orchOrderCsvFile = {

        // Prevents Dropzone from uploading dropped files immediately
        url: "upload-orchestra-order-csv-files",
        autoProcessQueue: false,
        maxFiles: 2,
        minFiles: 2,
        addRemoveLinks: true,
        acceptedFiles: 'text/csv',
        paramName: 'csv_files',
        autoDiscover: false,
        uploadMultiple:true,
        init: function() {
            myDropzone1 = this;

            this.on('processing', function(file) {
                $(".loader").show();
            });

            this.on("addedfile", function(file) {
                if (this.files.length > 2) {
                    this.removeFile(file);
                }
            });

            this.on("success", function(file, response) {
                $(".loader").hide();
                $('.snackbar_span').html("CSV Files uploaded successfully");
                showSnackbar();
                $('.dz-remove').hide();
                this.removeFile(file);
            });

            this.on("error", function(file, responseText) {
                $(".loader").hide();
                $(file.previewElement).addClass("dz-error").find('.dz-error-message').text(responseText.message);
            });
        },
        accept: function(file, done) {
            if (file.name.includes("ORC_M3_PRICE_FILE") || file.name.includes("ORC_M3_PO_FILE")) {
                done();
            } else {
                done('Error! Select valid CSV files !!');
            }
        },
    };

    $('.upload-csv-files').on('click', function() {
        if (myDropzone1.files.length === 2) {
            myDropzone1.processQueue();
        } else {
            $('.snackbar_span').html("Orchestra PO File and Price Files are Required.");
            showSnackbar();
        }
    });
</script>
@endsection
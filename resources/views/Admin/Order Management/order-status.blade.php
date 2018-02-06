@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<div class="col-sm-12 col-md-12 main">
    <div class="top-content">
        <div class="buyer-div hidden">
            <select class="js-example-responsive buyer-select show-tick show-menu-arrow" data-live-search="true">
                <option value="">Select Buyer</option>
                    @foreach($buyers as $buyer)
                    <option title="Selected Buyer : {{$buyer->buyer_name}}" value="{{$buyer->id}}">{{$buyer->buyer_name}}</option>
                    @endforeach
              </select>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="main-content">
        <div class="select-buyer">
            <img src="../../images/select-buyer.png" class="img-responsive">
        </div>
    </div>
    <div class="modal fade po-details" id="po-file-upload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Purchase Order</h4>
                </div>
                <div class="modal-body">
                   <a href="#" hidden><i class="fa fa-file-pdf-o" style="font-size:36px"></i> Open the pdf!</a>
                    <label>Upload Purchase Order</label>
                    <form method="post" class="dropzone" id="po-upload">
                        <input type="hidden" name="tableName" id="tableName" value="" >
                        <input type="hidden" name="rowID" id="rowID" value="" >
                        {{ csrf_field() }}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary upload-po" data-dismiss="modal">Upload</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="profoma-invoice" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="float:right">
                        <button class="btn btn-success btn-xs">Approve</button>
                        <button class="btn btn-danger btn-xs">Reject</button>
                        <button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" aria-label="Close">Close</button>
                    </div>
                    <h5><strong>Proforma Invoice for <span id="uvc_number"></span></strong></h5>

                </div>
                <div class="modal-body">
                    <div class="col-md-12 text-center">
                        <h4><strong><u>Proforma Invoice</u></strong></h4>
                        <br/>
                        <br/>
                    </div>
                    <div class="row color-invoice">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="right" style="float: right">
                                        <img style="height:50px;" src="/images/logo.png" class="img-responsive">
                                        <p><strong>Date:</strong> {{ date('d-m-Y ') }}</p>
                                        <p><strong>PI:</strong>493</p>
                                        <p><strong>REV:</strong>2</p>
                                    </div>
                                    <div id="company_details">
                                        
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <h3  style="margin-left: 15px;">Customer Details : </h3>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4" id="bill_to">
                                  
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 pull-right" id="ship_to">
                                  
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5">

                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <strong>ITEM DESCRIPTION & DETAILS :</strong>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <p class="text-right"><strong>Advance Payment   |   Freight at your cost</strong></p>
                                </div>
                                <hr/>
                            </div>
                            <div class="row" id="item_details">
                                
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12" id="bank_details">
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div style="float:right">
                        <button class="btn btn-success btn-xs">Approve</button>
                        <button class="btn btn-danger btn-xs">Reject</button>
                        <button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" aria-label="Close">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Show snackbar html code --}}
     <div id="snackbar"><span class="snackbar_span"></span></div>
    @endsection @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif @section('jqueryIncludes')

    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script src="{{ asset('js/Admin/order.js') }}"></script>
    <script>

		localStorage.removeItem("page_navigation");
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
            $('.po').attr("disabled", "disabled");
        })
        $(document).ready(function() {
            $('.logo').html('Order Status');
            $('.select-buyer').click(function() {
                $('.select-buyer').addClass('hidden');
            })
            $('.buyer-nav-select').html($('.buyer-div').html())
            if (localStorage.getItem("status_buyer") === null) {

            } else {
                orderStatus();
            }
            $('.buyer-select').val(localStorage.getItem("status_buyer_id"))
            $('.buyer-nav-select .buyer-select').selectpicker({
                style: 'btn-default',
                size: 4
            });
            $('.buyer-select.bootstrap-select').on('changed.bs.select', function() {
                if ($('.buyer-nav-select .buyer-select option:selected').val() != "") {
                    localStorage.setItem("status_buyer", $('.buyer-nav-select .buyer-select').find("option:selected").text().toLowerCase());
                    localStorage.setItem("status_buyer_id", $('.buyer-nav-select .buyer-select').find("option:selected").val());
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: base_url + "/admin/order-management/order_sub_modules",
                        type: "Post",
                        dataType: "json",
                        data: {
                            _token: csrf_token,
                            buyer: $(this).find("option:selected").text().toLowerCase()
                        },
                        beforeSend: function() {

                        },
                        success: function(data) {

                            orderStatus();
                        },
                        error: function(data) {

                        },
                        complete: function() {
                            $(".loader").hide();
                            $(".loader").css('margin-top', '0%');
                        }
                    })
                } else {
                    localStorage.removeItem("status_buyer");
                    localStorage.removeItem("status_buyer_id");
                    location.reload()
                }
            });
        });

   Dropzone.options.poUpload = {

      // Prevents Dropzone from uploading dropped files immediately
        url:base_url + "/admin/orchestra/set-po",
        autoProcessQueue: false,
        maxFilesize: 500,
        maxFiles:1,
        acceptedFiles: ".pdf",
        addRemoveLinks: true,

        init: function() {

            var submitButton = document.querySelector(".upload-po")
            myDropzone = this; // closure


            submitButton.addEventListener("click", function() {
                myDropzone.processQueue(); // Tell Dropzone to process all queued files.
         

            });

            // You might want to show the submit button only when 
            // files are dropped here:

            this.on("addedfile", function(file)
            {
                var filename = file.name;   
               
              // Show submit button here and/or inform user to click it.
            });
            this.on("complete", function(file){        
                 this.removeFile(file);

            });
            this.on("queuecomplete", function (file)
            {
                
            });
            this.on("success", function(file, response){ 
                 console.log(response);
                $('.dz-remove').hide();
                this.removeFile(file);
                $(".upload-po").addClass("hidden");
                
            });
            this.on("error", function(file, response) {
                 alert('error');
                 this.removeFile(file);
                 $(".upload-po").addClass("hidden");
            });


        }
    };

    </script>

    @endsection
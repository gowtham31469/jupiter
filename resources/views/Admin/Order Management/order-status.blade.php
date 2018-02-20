@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<style type="text/css">
    .bank_details tr {
        display: table-row;
        vertical-align: inherit;
        border-color: inherit;
    }
</style>
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
                    <div class="po_file hidden">
                        <a href="#"><i class="fa fa-file-pdf-o" style="font-size:36px"></i></a>
                        <p> OR </p>
                        <button type="button" class="btn btn-primary update-po-file">Update PO File</button> 
                    </div>
                    <div class="po_upload">
                        <label>Upload Purchase Order</label>
                        <form method="post" class="dropzone" id="po-upload">
                        <input type="hidden" name="tableName" id="tableName" value="" >
                        <input type="hidden" name="rowID" id="rowID" value="" >
                        {{ csrf_field() }}
                    </form>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary disabled upload-po">Upload</button>
                </div>
            </div>
        </div>
    </div>
	<div class="modal fade" id="po-file-upload-div" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="gridSystemModalLabel">Purchase Order</h4>
                    </div>
                    <div class="modal-body">
						<div id="exist_artwork_div">
							<h4>Artworks : </h4>
							<label style='color:red' id='check_error' class='hidden'>Please select atleast one artwork</label>
							<ul class="list-group checked-list-box">
							</ul>
							<button type="button" id="new_po1" class="btn btn-primary">New PO</button>
							<button type="button" id="map_po" class="btn btn-primary">Map PO</button>
							<hr/>
						</div>
						
						<div id="map_po_div" class="hidden">
							<h4>Mapping to Exist PO : </h4>
							<label style="color:red" id="select_error" class="hidden">Please Select PO</label>
							<div class="form-group" id="selectpo">
								<label>Select PO</label>
								<select class="form-control">
								  <option>1</option>
								  <option>2</option>
								  <option>3</option>
								  <option>4</option>
								  <option>5</option>
								</select>
                        	</div>
							
						</div>
						
						<div id="new_po_div" class="hidden">
							<h4>New PO : </h4>
							<label style='color:red' id='new_po_error' class='hidden'>Please Enter PO Number</label>
							 <div class="form-group">
                            <label>PO Number</label>
                            <input id="new_po_no" type="text" class="form-control">
                        	</div>
							<div class="form-group">
								<label for="exampleInputFile">PO File</label>
								<input type="file" id="new_po_file">
								<p class="help-block">Example block-level help text here.</p>
							</div>
							
						</div>
                       
                        
                     
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="update_new_po" class="btn btn-primary hidden" >Update</button>
						<button type="button" id="update_po" class="btn btn-primary hidden" >Update</button>
						
						<button type="button" id="new_pos" class="btn btn-primary hidden" >Update</button>
                    </div>
                </div>
            </div>
        </div>
    <div class="modal fade" id="profoma-invoice" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="margin-left: 5px" class="pi_actions pull-right">
                        
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
                    <div style="float:right" class="pi_actions">
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	
	<div class="modal fade" id="po-file-upload-group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="gridSystemModalLabel">Purchase Order</h4>
                    </div>
                    <div class="modal-body">
						<div id="uvc_select_div">
						</div>
						<div id="exist_artwork_div_status" class="hidden">
							<h4>Artworks : </h4>
							<label style='color:red' id='check_error_status' class='hidden'>Please select atleast one artwork</label>
							<ul class="list-group checked-list-box">
							</ul>
							<button type="button" id="new_po1_status" class="btn btn-primary hidden">New PO</button>
							<button type="button" id="map_po_status" class="btn btn-primary hidden">Map PO</button>
							<hr/>
						</div>
						
						<div id="map_po_div_status" class="hidden">
							<h4>Mapping to Exist PO : </h4>
							<label style="color:red" id="select_error_status" class="hidden">Please Select PO</label>
							<div class="form-group" id="selectpo_status">
								<label>Select PO</label>
								<select class="form-control">
								  <option>1</option>
								  <option>2</option>
								  <option>3</option>
								  <option>4</option>
								  <option>5</option>
								</select>
                        	</div>
							
						</div>
						
						<div id="new_po_div_status" class="hidden">
							<h4>New PO : </h4>
							<label style='color:red' id='new_po_error_status' class='hidden'>Please Enter PO Number</label>
							 <div class="form-group">
                            <label>PO Number</label>
                            <input id="new_po_no_status" type="text" class="form-control">
                        	</div>
							<div class="form-group">
								<label for="exampleInputFile">PO File</label>
								<input type="file" id="new_po_file_status">
								<p class="help-block">Example block-level help text here.</p>
							</div>
						</div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" id="update_new_po_status" class="btn btn-primary hidden" >Update</button>
						<button type="button" id="update_po_status" class="btn btn-primary hidden" >Update</button>
						
						<button type="button" id="new_pos_status" class="btn btn-primary hidden" >Update</button>
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
    <script src="{{ asset('js/Admin/tesco-order-status.js') }}"></script>
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

            } else if (localStorage.getItem("status_buyer") === 'orchestra'){
                orderStatus();
            }
            else
            {
                tescoOrderStatus();
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
                    var buyer = $(this).find("option:selected").text().toLowerCase();
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: base_url + "/admin/order-management/order_sub_modules",
                        type: "Post",
                        dataType: "json",
                        data: {
                            _token: csrf_token,
                            buyer: buyer
                        },
                        beforeSend: function() {

                        },
                        success: function(data) {
                            if (buyer === 'orchestra') {
                               orderStatus();
                            } else {
                                tescoOrderStatus();
                            }
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
        url:base_url + "/admin/orchestra/set-po-file",
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
                $('.upload-po').removeClass('disabled')
              // Show submit button here and/or inform user to click it.
            });
            this.on("complete", function(file){        
                 this.removeFile(file);

            });
            this.on("queuecomplete", function (file)
            {
                
            });
            this.on("success", function(file, response){ 
                $('.dz-remove').hide();
                this.removeFile(file);
                $('#po-file-upload').modal('hide')
                $('.snackbar_span').html('Purchase Order File Uploaded Successfully');
                showSnackbar();
                
            });
            this.on("error", function(file, response) {
                this.removeFile(file);
                $('.snackbar_span').html('Something went wrong');
                $('#snackbar').css('background-color','#F44336');
                showSnackbar();
            });
        }
    };

    </script>

    @endsection
@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<style type="text/css">
    .bank_details tr {
        display: table-row;
        vertical-align: inherit;
        border-color: inherit;
    }

    .left-panel.active {
        background: rgba(0, 0, 0, 0.56);
        margin-right: 0% !important;
        transition: 0.5s ease all;
    }
	.tot_qty_table {
		 height: 30px; 
		 margin-bottom: 0px;
	}
	.qty-table-modal input, .qty-table-modal input::-webkit-input-placeholder {
		font-size: 12px; 
		font-weight: 400;
	}
    .html2canvas-container { width: 3000px !important; height: 3000px !important; }
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
                    <div class="po_file hidden text-center" id="po_file">
                        <a href="javascript:void(0)" target="_blank" id="po_link"><i class="fa fa-file-pdf-o fa-4x" style="color: #ff151599;"></i></a>
                        <br>
                        <button type="button" class="btn btn-primary btn-xs update-po-file" style="margin-top: 10px;">Update PO File</button>
                    </div>
                    <div class="po_upload" id="po_upload">
                        <label>Upload Purchase Order</label>
                        <form method="post" class="dropzone" id="po-upload">
                            <input type="hidden" name="tableName" id="tableName" value="">
                            <input type="hidden" name="rowID" id="rowID" value=""> {{ csrf_field() }}
                        </form>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm disabled upload-po">Upload</button>
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
                            <label style='color:red' id='new_po_file_error' class='hidden'>Please upload Po File</label>
                            <label for="exampleInputFile">PO File</label>
                            <input type="file" id="new_po_file">
                            <p class="help-block">Example block-level help text here.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" id="update_new_po" class="btn btn-primary btn-sm hidden">Update</button>
                    <button type="button" id="update_po" class="btn btn-primary  btn-smhidden">Update</button>
                    <button type="button" id="new_pos" class="btn btn-primary  btn-sm btn-sm hidden">Update</button>
                    
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
                <div class="modal-body" id="proforma-invoice">
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
                                        <img id="company-logo" style="height:50px;" src="" class="img-responsive">
                                        <p><strong>Date</strong>
                                        <span style="padding-left: 14px;"> : <span style="padding-left: 6px;" class="pi-date"></span> </p>
                                        <p><strong>PI </strong>
                                            <span style="padding-left: 31px;"> : </span>
                                            <span style="padding-left: 6px;" class="pi_number"></span></p>
                                        <p class="rev hidden"><strong>REV : </strong><span class="rev_number"> </span></p>
                                    </div>
                                    <div id="company_details">

                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <h3 style="margin-left: 15px;">Customer Details : </h3>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4" id="bill_to">

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 pull-right" id="ship_to" style="padding-left: 92px;">

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
                                    <p class="text-right"><strong>Payment Terms : </strong><span class="advnc"></span></p><p class="text-right"><strong>Freight Type  : <span class="bond"></span></strong><span class="freight"> </span></p>
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
    <div id="editor"></div>
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
                            <label style='color:red' id='new_po_file_error_status' class='hidden'>Please upload Po File</label>
                            <br><label >PO File</label>
                            <input type="file" accept="application/pdf" id="new_po_file_status">
                            <p class="help-block">Example block-level help text here.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" id="update_new_po_status" class="btn btn-primary hidden">Update</button>
                    <button type="button" id="update_po_status" class="btn btn-primary hidden">Update</button>
                    <button type="button" id="new_pos_status" class="btn btn-primary hidden">Update</button>
                </div>
            </div>
        </div>
    </div>
    <div class="left-panel col-md-12" style="display: none;">
        <div class="loader"></div>
        <div class="div-max">
            
				<form method="POST" id="create_tesco_order_status_form" data-toggle="validator" role="form" novalidate="true">
					<div class="col-md-4">
                <h4 class="text-center osorderdetails">Order Details</h4>
                <div class="scroll" style="height: calc(100vh - 80px);">
					
						<input type="hidden" id="edit_template_name">
						<input type="hidden" id="edit_zone">
						<input type="hidden" id="edit_template_type">
						<input type="hidden" id="edit_id">
						 <div class="form-group has-feedback">
                            <label for="tesco_uk_style">PO Number.</label>
                            <input type="text" maxlength="9" class="form-control" name="tesco_po_number" id="tesco_po_number" required="" onkeyup="dynamicSVGTemplates()">
							 <label style="color:red" id="po_already_error" class="hidden">PO Number already exists</label>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="tesco_uk_style">UK Style Ref.</label>
                            <input type="text" class="form-control" name="tesco_uk_style" id="tesco_uk_style" placeholder="Enter UK Style Ref." maxlength="10" required="" onkeyup="dynamicSVGTemplates()">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="tesco_ce_style">CE Style Ref.</label>
                            <input type="text" class="form-control" name="tesco_ce_style" id="tesco_ce_style" maxlength="10" placeholder="Enter CE Style Ref." required="" onkeyup="dynamicSVGTemplates()">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="tesco_season_code">Season Code</label>
                            <input type="text" class="form-control" name="tesco_season_code" id="tesco_season_code" required="" placeholder="Enter Season Code" onkeyup="dynamicSVGTemplates()" maxlength="4">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="tesco_description">Short Description</label>
                            <input type="text" class="form-control" name="tesco_description" id="tesco_description" required="" placeholder="Enter Description" maxlength="24" onkeyup="dynamicSVGTemplates()">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="tesco_eqos">EQOS Site Code</label>
                            <input type="text" class="form-control" name="tesco_eqos" id="tesco_eqos" required="" placeholder="Enter EQOS Site Code" maxlength="10" onkeypress="return isNumber(event)" onkeyup="dynamicSVGTemplates()">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="tesco_manufacture_date">Manufactured Date</label>
                            <div class="input-group tesco_manufacture_date date" data-provide="datepicker" id="datePicker1">
                                <input value="" id="tesco_manufacture_date" name="tesco_manufacture_date" type="text" class="form-control" required="" placeholder="Select Manufactured Date" onchange="dynamicSVGTemplates()">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"> </span > 
                            </div > 
                        </div >
                        <div class="help-block with-errors"></div>
                    </div>
                              <div class="form-group has-feedback">
                        <label for="tesco_department">Department</label>
                        <select class="selectpicker" data-live-search="true" id="tesco_department" name="tesco_department" required="" onchange="dynamicSVGTemplates()">
                            <option selected value="">Select Department</option>
                            @foreach($tesco_department as $dept)
                                <option title="{{$dept->department}}" value="{{$dept->id}}">{{$dept->department}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                        <div class="form-group has-feedback">
                        <label for="tesco_brand">Brand</label> 
                        <select class="selectpicker" data-live-search="true" id="tesco_brand" name="tesco_brand" onchange="dynamicSVGTemplates()" required="">
                            <option value="">Select Brand</option>
                            @foreach($tesco_brand as $brand)
                                <option title="{{$brand->brand}}" value="{{$brand->id}}">{{$brand->brand}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                           <div class="form-group has-feedback">
                        <label for="tesco_supplier">Supplier</label> 
                        <select class="selectpicker" data-live-search="true" id="tesco_supplier" name="tesco_supplier" required="" onchange="dynamicSVGTemplates()">
                            <option value="">Select Supplier</option> @foreach($tesco_suppliers as $suppliers)
                                <option title="{{$suppliers->company_name}}" value="{{$suppliers->id}}">{{$suppliers->company_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                         <div class="form-group has-feedback">
                        <label for="tesco_rfid">RFID Compliant</label> 
                        <select class="selectpicker" id="tesco_rfid" name="tesco_rfid" required="" onchange="dynamicSVGTemplates()">
                            <option value="">Select RFID Compliant</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option> 
                        </select>
                    </div>
                       <div class="form-group has-feedback">
                        <label for="tesco_tag_at_source">Tag At Source</label> 
                        <select class="selectpicker" id="tesco_tag_at_source" name="tesco_tag_at_source" required="" onchange="dynamicSVGTemplates()">
                            <option value="">Select Tag At Source</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option> 
                        </select>
                    </div>
               
            </div>
            </div>
            <div class="col-md-8">
            <div class="scroll" style="height: calc(100vh - 40px);">
                <h4 class="text-center osartwork">Artwork</h4>
                <div class="row">
                <div class=" col-md-6 col-md-offset-3 artwork_tesco">
               
                 </div>
                <div class="col-md-12">
                    <div class="edit-qty-table-div"></div>
					<div class="osfooter">
					
					</div>
                   
                </div>
                 
                    
                    </div>
                </div>
            </div>
            </form>
            <div class="loader">
                <div class=" fa-3x">
                    <i class="fa fa-spinner fa-pulse"></i>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>

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

        } else if (localStorage.getItem("status_buyer") === 'orchestra') {
            orderStatus();
        } else {
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
        url: base_url + "/admin/orchestra/set-po-file",
        autoProcessQueue: false,
        maxFilesize: 500,
        maxFiles: 1,
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

            this.on("addedfile", function(file) {
                var filename = file.name;
                $('.upload-po').removeClass('disabled')
                // Show submit button here and/or inform user to click it.
            });
            this.on("complete", function(file) {
                this.removeFile(file);

            });
            this.on("queuecomplete", function(file) {

            });
            this.on("success", function(file, response) {
                $('.dz-remove').hide();
                this.removeFile(file);
                var po_link = base_url+response.po_file;
                var row_id  = $('#rowID').val();
                var tab_nm  = $('#tableName').val();
                $('#'+tab_nm+'_'+row_id+' .po-style').attr('onclick','tescoPOUpload(this,"'+row_id+'","'+response.po_file+'","'+tab_nm+'")');
                var po_num = $('#'+tab_nm+'_'+row_id+' .tesco-po').val();
                if(po_num != null && po_num != '' && po_num != 'undefined') {
                    $('#'+tab_nm+'_'+row_id+' .tesco-po').val();
                    $('#'+tab_nm+'_'+row_id).find('.po_class').html("PO Uploaded");
                    $('#'+tab_nm+'_'+row_id).find('.po_class').removeClass("label-POPending").addClass('label-POUploaded');
                }
                $('#'+tab_nm+'_'+row_id).find('.tesco-po-submit').attr('onclick','tescoSetPO(this,"'+row_id+'","'+tab_nm+'","'+response.po_file+'")');
                $('#'+tab_nm+'_'+row_id).find('.po-view').css('border-color', '#FFFFFF');
                $('#po-file-upload').modal('hide')
                $('#snackbar').css('background-color','#00b6ff');
                $('.snackbar_span').html('Purchase Order File Uploaded Successfully');
                showSnackbar();
            });
            this.on("error", function(file, response) {
                this.removeFile(file);
                $('.snackbar_span').html('Something went wrong');
                $('#snackbar').css('background-color', '#F44336');
                showSnackbar();
            });
        }
    };

    /** To refresh modal and form data**/
    $(".modal").on("hidden.bs.modal", function() {
        myDropzone.removeAllFiles();
    });
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth());
    $('#datePicker1').datepicker({
        autoclose: true,
        minViewMode: 1,
        format: 'MM,yyyy',
        startDate: today,
    })
    $('.remove').click(function() {
        $('.left-panel').removeClass('active');
    })

   
</script>

@endsection
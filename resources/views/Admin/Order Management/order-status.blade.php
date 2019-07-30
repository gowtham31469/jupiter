@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
 <link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />
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
	.left-panelorc.active {
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
	.check-list
	{
		position: relative;
		display: block;
		padding: 10px 15px;
		margin-bottom: -1px;
		background-color: #fff;
		border: 1px solid #ddd;
	}
    .html2canvas-container { width: 3000px !important; height: 3000px !important; }
</style>
<div class="col-sm-12 col-md-12 main">
    <div class="top-content">
        <div class="buyer-nav-div hidden">
           <a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Buyer</a>
        </div>
        {{ Form::hidden('invisible', $buyer_count, array('id' => 'invisible__count_id')) }}
        <div class="clearfix"></div>
    </div>

    <div class="main-content">
        <div class="select-buyer">
            
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
                        <span id="error_msg_span" style="color:red;"></span>
                        <br>
                        <button type="button" class="btn btn-primary btn-xs update-po-file" style="margin-top: 10px;">Update PO File</button>
                    </div>
                    <div class="po_upload" id="po_upload">
                        <label>Upload Purchase Order</label>
                        <form method="post" class="dropzone" id="po-upload">
                        <input type="hidden" name="buyer_id" id="buyer_id" value="">
                            <input type="hidden" name="tableName" id="tableName" value="">
                            <input type="hidden" name="id" id="rowID" value=""> {{ csrf_field() }}
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
                        <h4>Map to Existing PO : </h4>
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
                            <p class="help-block"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" id="update_new_po" class="btn btn-primary btn-sm hidden">Update</button>
                    <button type="button" id="update_po" class="btn btn-primary btn-sm hidden">Update</button>
                    <button type="button" id="new_pos" class="btn btn-primary btn-sm btn-sm hidden">Update</button>
                    
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
                                        <img id="company-logo" style="height: 45px;" src="" class="img-responsive">
                                        <strong>Date</strong>
                                        <span style="padding-left: 14px;"> : <span style="padding-left: 6px;" class="pi-date"></span>
                                        </span></br>
                                        <strong>PI </strong>
                                            <span style="padding-left: 31px;"> : </span>
                                            <span style="padding-left: 8px;" class="pi_number"></span>
                                        <p class="rev hidden"><strong>Rev no</strong><span style="padding-left: 3px;"> : </span>
                                        <span style="padding-left: 4px;" class="rev_number"> </span></p>
                                    </div>
                                    <div id="company_details">

                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <strong style="margin-left: 15px; font-size:11px;">Customer Details : </strong>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4" id="bill_to">

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 pull-right" id="ship_to" style="padding-left: 45px;">

                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5">

                                </div>
                            </div>
                            <hr />
                            <strong>ITEM DESCRIPTION & DETAILS :</strong>
                            <div class="row" id="item_details">

                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <p class="text-left"><strong>Payment Terms : </strong><span class="advnc"></span></p><p class="text-left"><strong>Freight Type  : <span class="bond"></span></strong><span class="freight"> </span></p>
                                </div>
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
                        <h4>Map to Existing PO : </h4>
                        <label style="color:red" id="select_error_status" class="hidden">Please Select PO</label>
                        <div class="form-group" id="po_select_div">
                            <label>Select PO</label>
                        </div>
                    </div>

                    <div id="new_po_div_status" class="hidden">
                        <h4>New PO : </h4>
                        <label style='color:red' id='new_po_error_status' class='hidden'>Please Enter PO Number</label>
						 <label style='color:red' id='new_po_error_length_status' class='hidden'>Max 9 characters</label>
                        <div class="form-group">
                            <label>PO Number</label>
                            <input id="new_po_no_status" style="width: 40%;" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label style='color:red' id='new_po_file_error_status' class='hidden'>Please upload Po File</label>
                            <br><label >PO File</label>
                            <input type="file" accept="application/pdf" id="new_po_file_status">
                            <p class="help-block"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" id="update_new_po_status" class="btn btn-primary hidden">Update</button>
                    <button type="button" id="update_po_status" class="btn btn-primary btn-sm hidden">Update</button>
                    <button type="button" id="new_pos_status" class="btn btn-primary btn-sm hidden">Update</button>
                </div>
            </div>
        </div>
    </div>
    <div class="left-panel col-md-12" style="display: none;">
        <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
        <div class="div-max">          
			<form method="POST" id="create_tesco_order_status_form" data-toggle="validator" role="form" novalidate="true">
				<div class="col-md-4">
            <h4 class="text-center osorderdetails">Order Details</h4>
            <div class="scroll" style="height: calc(100vh - 80px);">
				
				<input type="hidden" id="edit_template_name">
				<input type="hidden" id="edit_zone">
				<input type="hidden" id="edit_template_type">
				<input type="hidden" id="edit_id">
				<input type="hidden" id="order_type">
				<div class="form-group has-feedback">
                    <label for="tesco_uk_style">PO Number.</label>
                    <input type="text" maxlength="9" class="form-control" name="tesco_po_number" id="tesco_po_number" required="" onkeyup="dynamicSVGTemplates()">
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
                    <input type="text" class="form-control" name="tesco_season_code" id="tesco_season_code" required="" placeholder="Enter Season Code" onkeyup="dynamicSVGTemplates()" data-minlength="4" maxlength="4" data-required-error="Mandatory Field." data-minlength-error="Enter 4 digits."  maxlength="4">
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback">
                    <label for="tesco_description">Short Description</label>
                    <input type="text" class="form-control" name="tesco_description" id="tesco_description" required="" placeholder="Enter Description" maxlength="45" onkeyup="dynamicSVGTemplates()">
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
                    <select class="selectpicker"  data-live-search="true" id="tesco_department" name="tesco_department" required="" onchange="dynamicSVGTemplates()">
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
                    <label for="tesco_supplier">Customer</label> 
                    <select class="selectpicker" data-live-search="true" id="tesco_supplier" name="tesco_supplier" required="" onchange="dynamicSVGTemplates()">
                        <option value="">Select Customer</option> @foreach($tesco_suppliers as $suppliers)
                            <option title="{{$suppliers->supplier_name}}" value="{{$suppliers->id}}">{{$suppliers->supplier_name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group has-feedback">
                    <label for="tesco_ocn">OCN Code</label>
                    <input type="text" class="form-control" name="tesco_ocn" id="tesco_ocn" required="" placeholder="Enter OCN Code">
                    <div class="help-block with-errors"></div>
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
               <!--  <span class="pull-right" style="cursor:pointer">Enlarge</span>
                <h4 class="text-center osartwork">Artwork</h4>  -->
                <p class="enlarge-btn"></p>
                
                <h4 class="text-center osartwork" style="padding-left: 164px;">Artwork</h4> 

                <div class="row">
                    <div class=" col-md-6 col-md-offset-3 artwork_tesco">
                    </div>
                    <div class="preview"></div>
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


    <div class="left-panelorc col-md-12" style="display: none;">
        <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
        <div class="div-max">          
			<form method="POST" id="create_tesco_order_status_form" data-toggle="validator" role="form" novalidate="true">
				<div class="col-md-4">
            <h4 class="text-center osorderdetails">Order Details</h4>
            <div class="scroll" style="height: calc(100vh - 80px);">
				
				<input type="hidden" id="edit_template_name">
				<input type="hidden" id="edit_zone">
				<input type="hidden" id="edit_template_type">
				<input type="hidden" id="edit_id">
				<input type="hidden" id="order_type">
				<div class="form-group has-feedback">
                    <label for="tesco_uk_style">PO Number.</label>
                    <input type="text" maxlength="9" class="form-control" name="tesco_po_number" id="tesco_po_number" required="" onkeyup="dynamicSVGTemplates()">
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
                    <input type="text" class="form-control" name="tesco_season_code" id="tesco_season_code" required="" placeholder="Enter Season Code" onkeyup="dynamicSVGTemplates()" data-minlength="4" maxlength="4" data-required-error="Mandatory Field." data-minlength-error="Enter 4 digits."  maxlength="4">
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback">
                    <label for="tesco_description">Short Description</label>
                    <input type="text" class="form-control" name="tesco_description" id="tesco_description" required="" placeholder="Enter Description" maxlength="45" onkeyup="dynamicSVGTemplates()">
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
                    <select class="selectpicker"  data-live-search="true" id="tesco_department" name="tesco_department" required="" onchange="dynamicSVGTemplates()">
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
                    <label for="tesco_supplier">Customer</label> 
                    <select class="selectpicker" data-live-search="true" id="tesco_supplier" name="tesco_supplier" required="" onchange="dynamicSVGTemplates()">
                        <option value="">Select Customer</option> @foreach($tesco_suppliers as $suppliers)
                            <option title="{{$suppliers->supplier_name}}" value="{{$suppliers->id}}">{{$suppliers->supplier_name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group has-feedback">
                    <label for="tesco_ocn">OCN Code</label>
                    <input type="text" class="form-control" name="tesco_ocn" id="tesco_ocn" required="" placeholder="Enter OCN Code">
                    <div class="help-block with-errors"></div>
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
               <!--  <span class="pull-right" style="cursor:pointer">Enlarge</span>
                <h4 class="text-center osartwork">Artwork</h4>  -->
                <p class="enlarge-btn"></p>
                
                <h4 class="text-center osartwork" style="padding-left: 164px;">Artwork</h4> 

                <div class="row">
                    <div class=" col-md-6 col-md-offset-3 artwork_tesco">
                    </div>
                    <div class="preview"></div>
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
<!-- Buyer selection Modal -->
<div class="modal buyer-selection-modal fade" id="buyerSelectionModalCenter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog buyer-selection-modal-dialog" role="document">
         <div class="modal-content buyer-selection-modal-content">
                <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title buyer-modal-title" id="myModalLabel">Select a Buyer</h4>
                 </div>
                 <div class="modal-body buyer-selection-modal-body">
                    <div class="alert alert-warning" role="alert" id="hidden-buyer-div" style="display: none;margin-bottom: 5px;">
                      <strong>Warning!</strong> Select a buyer to proceed.
                    </div>
                    @foreach($buyers as $buyer)
                     <a class="buyer-card-link" style="cursor: pointer;">
                         <div class="card buyers-card elevation-2dp active-buyer" data-name ="{{$buyer->buyer_name}}"  data-value ="{{$buyer->id}}" id="{{ strtolower($buyer->buyer_name) }}">
                           <img src="{{$buyer->logo}}" class="img-fluid" alt="Responsive image" height="120" class="img-fluid float-none">
                         </div>
                     </a>
                    @endforeach
                 </div>
                 <div class="modal-footer buyer-selection-modal-footer">
                     {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                 </div>
            {!! Form::close() !!}
         </div>
     </div>
 </div>
 <div class="modal fade" id="age_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header alert-warning">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <p class="modal-title" id="myModalLabel"><i class="fa fa-warning" style="font-size:24px"></i>   The size of the file you uploaded exceeds the limit !</p>
                </div>
            </div>
        </div>
    </div>
@endsection @else
<script type="text/javascript">
    window.location = "{{ url('/admin') }}";
</script>
@endif @section('jqueryIncludes')
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/daterangepicker.js') }}"></script>
<script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
<script src="{{ asset('js/Admin/order.js') }}"></script>
<script src="{{ asset('js/Admin/tesco-pi.js') }}"></script>
<script src="{{ asset('js/Admin/tesco-order-status.js') }}"></script>
<script src="{{ asset('js/Admin/orchestra-order-status.js') }}"></script>
<script src="{{ asset('js/Admin/orchestra-pi.js') }}"></script>
<script>
    localStorage.removeItem("page_navigation");
    var buyer_count = $('#invisible__count_id').val();
    var buyer = localStorage.getItem("buyer");
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
        $('.po').attr("disabled", "disabled");
    })
    $(document).ready(function() {
       /* Show logo on left side */
       $('.logo').html('Orders');

       /** [ Make buyers card active ] */
       $('div.buyers-card').click(function(){
         $('div.buyers-card').removeClass("active");
         $(this).addClass("active");
         $('#hidden-buyer-div').hide();

         /* Save selected buyer into localdb */
         if ($('.buyers-card.active-buyer.active').length != 0) 
         {
             $('#hidden-buyer-div').hide();
             localStorage.setItem("buyer", $('.buyers-card.active-buyer.active').data("name").toLowerCase());
             localStorage.setItem("buyer_id", $('.buyers-card.active-buyer.active').data("value"));
             $('.selected-buyer').text('Selected Buyer : '+$('.buyers-card.active-buyer.active').data("name"))
             $('#buyerSelectionModalCenter').modal('hide');
             var buyer = localStorage.getItem("buyer");
             if (buyer === 'orchestra') {
                 orderStatus();
             } else {
                 tescoOrderStatus();
             }
             
             
         } else {
             localStorage.removeItem("buyer");
             localStorage.removeItem("buyer_id");
             $('#hidden-buyer-div').show();
         }
       });

       /** [ Show already selected(active) buyer on clicking buyer menu] */
       $('.buyer-nav-menu').on('click', function() {
           if (localStorage.getItem("buyer") === null) 
           {
              $("#buyerSelectionModalCenter").modal("show");
              
           } 
           else 
           {
               var id_buyer = localStorage.getItem("buyer").replace("&", "\\&");
               $('#'+id_buyer).addClass('active');
               $("#buyerSelectionModalCenter").modal("show");
           }
       });

       /** [If only 1 buyer , auto select that buyer] */
       
       if(buyer_count <= 1) 
       {
           $('#hidden-buyer-div').hide();
           $('div.buyers-card').addClass("active");
           localStorage.setItem("buyer", $('.buyers-card.active-buyer.active').data("name").toLowerCase());
           localStorage.setItem("buyer_id", $('.buyers-card.active-buyer.active').data("value"));
           $('.selected-buyer').text('Selected Buyer : '+$('.buyers-card.active-buyer.active').data("name"))
       }   
       
       /** add buyer selection menu into navbar */
       $('.buyer-nav-menu').html($('.buyer-nav-div').html());

       /** Load selected buyer from local-storage on pageload */
       if (localStorage.getItem("buyer") === null) 
       {
           $(document).ready(function() {
              $("#buyerSelectionModalCenter").modal("show");
           })
       } 
       else 
       {
           $('.selected-buyer').text('Selected Buyer : '+localStorage.getItem("buyer"));
           var buyer = localStorage.getItem("buyer");
           if (buyer === 'orchestra') {
               orderStatus();
           } else if (buyer === 'tesco')  {
               tescoOrderStatus();
           }
		   else {
				 localStorage.removeItem("buyer")
				 $("#buyerSelectionModalCenter").modal("show");
			}
       }

       /** To refresh modal while hidden **/
       $('#buyerSelectionModalCenter').on('hidden.bs.modal', function (e) {
         $('div.buyers-card').removeClass("active");
         if (localStorage.getItem("buyer") === null) 
         {
             $(document).ready(function() {
                $("#buyerSelectionModalCenter").modal("show");
             })
         } 
         else 
         {
             $('.selected-buyer').text('Selected Buyer : '+localStorage.getItem("buyer"));
             var buyer = localStorage.getItem("buyer");
             if (buyer === 'orchestra') {
                 orderStatus();
             } else {
                 tescoOrderStatus();
             }
         }
        })
    });

    Dropzone.options.poUpload = {

        // Prevents Dropzone from uploading dropped files immediately
        url: base_url + "/admin/" + buyer + "/set-po-file",
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
               if(((file.size/1024)/1024)>2){
                       $('#age_modal').modal("show");
                        this.removeFile(file);
                    }else{
                        var filename = file.name;
                        $('.upload-po').removeClass('disabled')
                    } 
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
                $('#'+tab_nm+'_'+row_id+' .po-style').attr('onclick',buyer+'POUpload(this,"'+row_id+'","'+response.po_file+'","'+tab_nm+'")');
                var po_num = $('#'+tab_nm+'_'+row_id+' .'+buyer+'-po-'+row_id).val();
                if(po_num != null && po_num != '' && po_num != 'undefined') {
                    $('#'+tab_nm+'_'+row_id+' .'+buyer+'-po-'+row_id).val();
                    $('#'+tab_nm+'_'+row_id).find('.po_class').html("PO Uploaded");
                    $('#'+tab_nm+'_'+row_id).find('.po_class').removeClass("label-POPending").addClass('label-POUploaded');
                }
                $('#'+tab_nm+'_'+row_id).find('.'+buyer+'-po-submit').attr('onclick',buyer+'SetPO(this,"'+row_id+'","'+tab_nm+'","'+response.po_file+'")');
                $('#'+tab_nm+'_'+row_id).find('.po-view').css('border-color', '#FFFFFF');
                $('#po-file-upload').modal('hide')
                $('#snackbar').css('background-color','#00b6ff');
                $('.snackbar_span').html('Purchase Order File Uploaded Successfully');
                showSnackbar();
                window.location = "{{ url('/admin/order-management/order-status') }}";
            });
            this.on("error", function(file, response) {
                this.removeFile(file);
                $('.snackbar_span').html('Cancelled Uploading !');
                $('#snackbar').css('background-color', '#F44336');
                showSnackbar();
            });
            this.on("removedfile", function(file){
                $('.upload-po').addClass('disabled')
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
        format: 'MM,yyyy'
    })
    $('.remove').click(function() {
        $('.left-panel').removeClass('active');
    })

   
</script>

@endsection
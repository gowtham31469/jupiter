@extends('layouts.Customer.app') @if (Auth::guard("web_customer")->user()) @section('content')
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

{{-- Hidden div for svg rendering --}}
<div id="modaldiv">
        <div id="svgdiv" style="position:absolute;visibility:hidden">\
         <svg id="ean-13"></svg>\
       </div>
    </div>
<input type="hidden" id="status_flag">
<div class="col-sm-12 col-md-12 main">

        <div class="main-content">
                <div class="select-buyer">
                </div>
            </div>
    <div class="top-content">
        <div class="buyer-nav-div hidden">
           <a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Buyer</a>
        </div>
        {{ Form::hidden('invisible', $buyer_count, array('id' => 'invisible__count_id')) }}
        <div class="clearfix"></div>
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
                            <input type="text" placeholder="Enter Revise PO Number" class="form-control" name="po_number_updation" id="po_number_updation">
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
                            <p class="help-block"></p>
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
                                <div class="col-lg-4 col-md-4 col-sm-4 pull-right" id="ship_to" style="padding-left: 50px;">

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
                        <h4>Artworks :  <span style="display: inline-block;margin-left: 100px;" class="tab"></span> Buyer PO No :<span style="display: inline-block;margin-left: 100px;" class="tab"></span> Cust PO No :</h4>
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
                            <select id="select_po_status" class="form-control">
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
						<label style='color:red' id='new_po_error_length_status' class='hidden'>Max 9 characters</label>
                        <div class="form-group">
                            <label>PO Number</label>
                            <input id="new_po_no_status" type="text" class="form-control">
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
                    <button type="button" id="update_po_status" class="btn btn-primary hidden">Update</button>
                    <button type="button" id="new_pos_status" class="btn btn-primary hidden">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="approve-upload-group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridSystemModalLabel">Approve Artwork</h4>
                </div>
                <div class="modal-body">
                    <div id="uvc_approve_select_div">
                    </div>
                    <div id="exist_artwork_approve_div_status" class="hidden">
                        <h4>Artworks : <span style="display: inline-block;margin-left: 100px;" class="tab"></span> Buyer PO No :</h4>
                        <label style='color:red' id='check_approve_error_status' class='hidden'>Please select atleast one artwork</label>
                        <ul class="list-group checked-list-box">
                        </ul>
                        <hr/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" id="approve_button" class="btn btn-primary hidden">Approve Artwork</button>
                </div>
            </div>
        </div>
    </div>
    <div class="left-panel col-md-12 edit-sticker-order-panel" style="display: none;">
        <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
        <div class="div-max">
                <form method="POST" id="create_tesco_order_status_form" data-toggle="validator" role="form" novalidate="true">
                    <div class="col-md-4">
                <h4 class="text-center osorderdetails">Order Details</h4>
                <div class="scroll" style="height: calc(100vh - 80px);">
                    	<input type="hidden" id="qr_flag">
                        <input type="hidden" id="edit_template_name">
                        <input type="hidden" id="edit_zone">
                        <input type="hidden" id="edit_template_type">
                        <input type="hidden" id="edit_id">
                         <div class="form-group has-feedback">
                            <label for="tesco_uk_style">PO Number.</label>
                            <input type="text" maxlength="9" class="form-control" name="tesco_po_number" id="tesco_po_number" required="" onkeyup="dynamicSVGTemplates()">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="tesco_uk_style">UK Style Ref.</label>
                            <input type="text" class="form-control" name="tesco_uk_style" id="tesco_uk_style" placeholder="Enter UK Style Ref." maxlength="8" required="" onkeyup="dynamicSVGTemplates()">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="tesco_ce_style">CE Style Ref.</label>
                            <input type="text" class="form-control" name="tesco_ce_style" id="tesco_ce_style" maxlength="8" placeholder="Enter CE Style Ref." required="" onkeyup="dynamicSVGTemplates()">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="tesco_season_code">Season Code</label>
                            <input type="text" class="form-control" name="tesco_season_code" id="tesco_season_code" required="" placeholder="Enter Season Code" onkeyup="dynamicSVGTemplates()" maxlength="6" data-required-error="Mandatory Field.">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="tesco_description">Short Description</label>
                            <input type="text" class="form-control" name="tesco_description" id="tesco_description" required="" placeholder="Enter Description" maxlength="45" onkeyup="dynamicSVGTemplates()">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="tesco_eqos">EQOS Site Code</label>
                            <input type="text" class="form-control" name="tesco_eqos" id="tesco_eqos" required="" placeholder="Enter EQOS Site Code" maxlength="7" onkeypress="return isNumber(event)" onkeyup="dynamicSVGTemplates()">
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

    <div class="left-panel col-md-12 edit-label-order-panel" style="display: none;">
        <div class="loader">
            <div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>
        </div>
        <div class="div-max">
        {!! Form::open(['id' => 'update_tesco_label_order_status_form','data-toggle' => 'validator','role' => 'form']) !!}
            <span id="edit_label_order_result"></span>
                <div class="col-md-2">
                    <h4 class="text-center label-order-details">Order Details</h4>
                    <div class="scroll" style="height: calc(100vh - 80px);">
                        <input type="hidden" id="label_order_id">
                        <input type="hidden" id="label_template_id">
                        <input type="hidden" id="label_template_name">
                        <div class="form-group has-feedback">
                            {!! Form::label('tesco_care_po', 'Tesco PO Number', ['class' => 'form_label']) !!}
                            {!! Form::text('tesco_care_po',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Tesco PO Number','required','maxlength' => '10')) !!}
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group has-feedback">
                            {!! Form::label('tesco_care_eqos', 'EQOS Supplier Number', ['class' => 'form_label']) !!}
                            {!! Form::text('tesco_care_eqos',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'EQOS Supplier Number','required','maxlength' => '10')) !!}
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group has-feedback">
                            {!! Form::label('tesco_care_ce_style', 'Option ID', ['class' => 'form_label']) !!}
                            {!! Form::text('tesco_care_ce_style',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Option ID','required','maxlength' => '')) !!}
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group has-feedback">
                            {!! Form::label('tesco_care_uk_style', 'UK Style Number', ['class' => 'form_label']) !!}
                            {!! Form::text('tesco_care_uk_style',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'UK Style Number','required','maxlength' => '8')) !!}
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group has-feedback">
                            {!! Form::label('tesco_care_fabric_code', 'Fabric Batch Code', ['class' => 'form_label']) !!}
                            {!! Form::text('tesco_care_fabric_code',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Fabric Batch Code', 'maxlength' => '7')) !!}
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group has-feedback">
                            {!! Form::label('tesco_care_leather_id', 'Leather & Suede ID', ['class' => 'form_label']) !!}
                            {!! Form::text('tesco_care_leather_id',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Leather & Suede ID', 'maxlength' => '')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            {!! Form::label('tesco_care_button_space', 'Dont Show', ['class' => 'form_label', 'style' => 'visibility:hidden']) !!}
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('tesco_care_button_space',null,null,['id' => 'tesco_care_button_space']) !!} Include Button Sew Space
                                </label>
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="align-items form-group has-feedback">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('tesco_care_exclusive_trim',null,null,['id' => 'tesco_care_exclusive_trim']) !!} Exclusive of Trims
                                </label>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('tesco_care_non_textile_part',null,null,['id' => 'tesco_care_non_textile_part']) !!} Contains Non Textile Parts
                                </label>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('tesco_care_part_of_set',null,null,['id' => 'tesco_care_part_of_set']) !!} Part of Set
                                </label>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="label-metadata-select"></div>
                        <hr>
                        <div class="row hidden" id="baby_sleeping_bag_div">
                            <div class="col-md-12">
                                <div class="alert alert-danger" role="alert">These 3 fields are required only if the size chart selected is “Baby Sleeping Bags”</div>
                            </div>
                            <div class="col-md-12">
                                <div class="align-items form-group0 has-feedback">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('tesco_care_bs_en',null,null,['id' => 'tesco_care_bs_en']) !!} Conforms to BS EN 16781:2018
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="checkbox form-group has-feedback">
                                        <label>
                                            {!! Form::checkbox('tesco_care_sleep_bag_symbol',null,null,['id' => 'tesco_care_sleep_bag_symbol']) !!} <img class="baby-sleep-bag-symbol" src="{{ asset('images/i symbol.png') }}" />
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group has-feedback form-inline">
                                        {!! Form::label('tesco_care_tog', 'TOG', ['class' => 'form_label']) !!}
                                        {!! Form::text('tesco_care_tog',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'TOG')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="scroll" style="height: calc(100vh - 40px);">
                        <div class="row">
                             <h4 class="text-center" style="padding-left: 164px;">Order Quantity</h4>
                             <div class="teasco-care-quantity-div"></div>
                        </div>
                        <div class="row">
                             <div class="garment-component-div"></div>
                        </div>                        
                       <div class="row">
                            <div class="label-order-footer"></div>
                       </div>
                    </div>
                </div>
                {!! Form::close() !!}
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
<!-- Tesco Artwork Approve Modal -->
<div class="modal fade" id="approveTescoArtworkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Approve Artwork</h4>
            </div>
                <div class="modal-body">
                        <input type="hidden" value="" id="ts_table_id">
                        <input type="hidden" value="" id="ts_table_name">
                        <p>Are you sure, you want to  approve this artwork ?</p>
                </div>
                <div class="modal-footer">
                    {!! Form::button('No', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                    {!! Form::button('Yes', ['class' => 'btn btn-primary btn-sm', 'id' => 'approve_tesco_artwork_btn','onclick' => 'approveTescoArtwork()'] ) !!}
                </div>
        </div>
    </div>
</div>

<!-- Tesco Saved order alertbox -->
<div class="modal fade" id="savedOrderAlertBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <p>Please SUBMIT your order at the Create Order Page.</p>
            </div>
        </div>
    </div>
</div>
    @endsection @else
    <script type="text/javascript">
        window.location = "{{ url('/customer') }}";
    </script>
    @endif @section('jqueryIncludes')
	<script  type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/daterangepicker.js') }}"></script>

    <script src="{{ asset('js/Customer/customer-order.js') }}"></script>
    <script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script src="{{ asset('js/Customer/customer-order-status.js') }}"></script>
    <script src="{{ asset('js/Customer/orchestra-order-status.js') }}"></script>
    <script src="{{ asset('js/Customer/customer-pi.js') }}"></script>
    <script src="{{ asset('js/Customer/customer-orchestra-pi.js') }}"></script>
    <script src="{{ asset('js/common/tesco-care-label-order-status.js') }}"></script>
    <script src="{{ asset('js/common/tesco-care-label-orders.js') }}"></script>
    <script src="{{ asset('js/common/view-tesco-care-label-orders.js') }}"></script>
    
    <script type="text/javascript">
		$('#status_flag').val("orderStatus")
        var buyer = localStorage.getItem("buyer");
        Dropzone.options.poUpload = {   

            // Prevents Dropzone from uploading dropped files immediately
            url: base_url + "/customer/" + localStorage.getItem("buyer") + "/set-po-file",
            autoProcessQueue: false,
            maxFilesize: 500,
            maxFiles: 1,
            acceptedFiles: ".pdf",
            addRemoveLinks: true,

            init: function() {

                var submitButton = document.querySelector(".upload-po")
                myDropzone = this; // closure


                submitButton.addEventListener("click", function() {
                    $(".loader").show();
                    myDropzone.processQueue(); // Tell Dropzone to process all queued files.
                });

                // You might want to show the submit button only when 
                // files are dropped here:

                this.on("addedfile", function(file) {
                    if(((file.size/1024)/1024)>2){
                       $('#age_modal').modal("show");
                        this.removeFile(file);
                        $('.upload-po').addClass('disabled')
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
                    // $('.dz-remove').hide();
                    // this.removeFile(file);
                    // var po_link = base_url+response.po_file;
                    // var row_id  = $('#rowID').val();
                    // var tab_nm  = $('#tableName').val();
                    // $('#'+tab_nm+'_'+row_id+' .po-style').attr('onclick',buyer+'POUpload(this,"'+row_id+'","'+response.po_file+'","'+tab_nm+'")');
                    // var po_num = $('#'+tab_nm+'_'+row_id+' .'+buyer+'-po-'+row_id).val();
                    // if(po_num != null && po_num != '' && po_num != 'undefined') {
                    //     $('#'+tab_nm+'_'+row_id+' .'+buyer+'-po-'+row_id).val();
                    //     $('#'+tab_nm+'_'+row_id).find('.po_class').html("PO Uploaded");
                    //     $('#'+tab_nm+'_'+row_id).find('.po_class').removeClass("label-POPending").addClass('label-POUploaded');
                    // }
                    // $('#'+tab_nm+'_'+row_id).find('.'+buyer+'-po-submit').attr('onclick',buyer+'SetPO(this,"'+row_id+'","'+tab_nm+'","'+response.po_file+'")');
                    // $('#'+tab_nm+'_'+row_id).find('.po-view').css('border-color', '#FFFFFF');
                    // $('#po-file-upload').modal('hide')
                    // $('#snackbar').css('background-color','#00b6ff');
                    // $('.snackbar_span').html('Purchase Order File Uploaded Successfully');
                    // showSnackbar();
                    // //window.location = "{{ url('/customer/order-status') }}";
                    // if(response.po_status == "PO Uploaded")
                    // {
                    //     $('#'+tab_nm+'_'+row_id).find('.po_status a span').text("PO Uploaded")
                    //     $('#'+tab_nm+'_'+row_id).find('.po_status a span').removeClass("label-POPending")
                    //     $('#'+tab_nm+'_'+row_id).find('.po_status a span').addClass("label-POUploaded")
                    // }
                    $('#po-file-upload').modal('hide')
                    setTimeout(function(){
                        $(".loader").hide();
                        submitOs(this)
                        $('.snackbar_span').html('PO Updated Successfully');
                        showSnackbar();
                    }, 10000);
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
        myDropzone.options.url = base_url + "/customer/" + localStorage.getItem("buyer") + "/set-po-file";
    });
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth());
    $('#datePicker1').datepicker({
        autoclose: true,
        minViewMode: 1,
        format: 'MM,yyyy',
    })
    </script>  
    @endsection
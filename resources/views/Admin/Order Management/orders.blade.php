@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<style>
    .washing_select+.select2,
    .instruction_select+.select2,
    .parts_select+.select2,
    .cleaning_select+.select2,
    .ironing_select+.select2,
    .dryer_select+.select2,
    .whitening_select+.select2 {
        width: 100% !important;
        height: 50px !important;
    }

    .washing_select+.select2-container .select2-selection--single,
    .instruction_select+.select2-container .select2-selection--single,
    .parts_select+.select2-container .select2-selection--single,
    .cleaning_select+.select2-container .select2-selection--single,
    .ironing_select+.select2-container .select2-selection--single,
    .dryer_select+.select2-container .select2-selection--single,
    .whitening_select+.select2-container .select2-selection--single {
        height: 34px;
    }

    .washing_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow,
    .cleaning_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow,
    .ironing_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow,
    .instruction_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow,
    .parts_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow,
    .dryer_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow,
    .whitening_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 10px;
        right: 10px;
    }

    .washing_select+.select2.select2-container--default .select2-selection--single .select2-selection__rendered,
    .cleaning_select+.select2.select2-container--default .select2-selection--single .select2-selection__rendered,
    .ironing_select+.select2.select2-container--default .select2-selection--single .select2-selection__rendered,
    .instruction_select+.select2.select2-container--default .select2-selection--multiple .select2-selection__rendered,
    .parts_select+.select2.select2-container--default .select2-selection--multiple .select2-selection__rendered,
    .dryer_select+.select2.select2-container--default .select2-selection--single .select2-selection__rendered,
    .whitening_select+.select2.select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 45px;
    }

    .washing_select+.select2-container .select2-selection--single,
    .cleaning_select+.select2-container .select2-selection--single,
    .ironing_select+.select2-container .select2-selection--single,
    .instruction_select+select2-container .select2-selection--multiple,
    .parts_select+select2-container .select2-selection--multiple,
    .dryer_select+.select2-container .select2-selection--single,
    .whitening_select+.select2-container .select2-selection--single {
        height: 50px;
    }

    .washing_select+.select2-container--default .select2-selection--single,
    .cleaning_select+.select2-container--default .select2-selection--single,
    .ironing_select+.select2-container--default .select2-selection--single,
    .instruction_select+.select2-container--default .select2-selection--multiple,
    .parts_select+.select2-container--default .select2-selection--multiple,
    .dryer_select+.select2-container--default .select2-selection--single,
    .whitening_select+.select2-container--default .select2-selection--single {
        border: 2px solid #dce4ec;
    }
.files input {
    outline: 2px dashed #92b0b3;
    outline-offset: -10px;
    -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
    transition: outline-offset .15s ease-in-out, background-color .15s linear;
    padding: 106px 0px 50px 24%;
    text-align: center !important;
    margin: 0;
    width: 100% !important;
}
.files input:focus{     outline: 2px dashed #92b0b3;  outline-offset: -10px;
    -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
    transition: outline-offset .15s ease-in-out, background-color .15s linear; border:1px solid #92b0b3;
 }
.files{ position:relative}
.files:after {  pointer-events: none;
    position: absolute;
    top: 60px;
    left: 0;
    width: 50px;
    right: 0;
    height: 56px;
    content: "";
    background-image: url(https://image.flaticon.com/icons/png/128/109/109612.png);
    display: block;
    margin: 0 auto;
    background-size: 100%;
    background-repeat: no-repeat;
}
.color input{ background-color:#f1f1f1;}
.files:before {
    position: absolute;
    bottom: 10px;
    left: 0;  pointer-events: none;
    width: 100%;
    right: 0;
    height: 57px;
    display: block;
    margin: 0 auto;
    color: #2ea591;
    font-weight: 600;
    text-transform: capitalize;
    text-align: center;
}  

/** Style for bottom-bar buttons */
span > i {
color: white;
}
span > input {
background: none;
color: white;
padding: 0;
border: 0;
}
    
.more-menu {
    width: 200px;
}

/* More Button / Dropdown Menu */

.more-btn,
.more-menu-btn {
    background: none;
    border: 0 none;
    line-height: normal;
    overflow: visible;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    width: 100%;
    text-align: left;
    outline: none;
    cursor: pointer;
}

.more-dot {
    background-color: #aab8c2;
    margin: 0 auto;
    display: inline-block;
    width: 7px;
    height: 7px;
    margin-right: 1px;
    border-radius: 50%;
    transition: background-color 0.3s;
}

.more-menu {
    position: absolute;
    top: 100%;
    z-index: 900;
    float: left;
    padding: 10px 0;
    margin-top: 9px;
    background-color: #fff;
    border: 1px solid #ccd8e0;
    border-radius: 4px;
    box-shadow: 1px 1px 3px rgba(0,0,0,0.25);
    opacity: 0;
    -webkit-transform: translate(0, 15px) scale(.95);
    transform: translate(0, 15px) scale(.95);
    transition: transform 0.1s ease-out, opacity 0.1s ease-out;
    pointer-events: none;
}

.more-menu-caret {
    position: absolute;
    top: -10px;
    left: 12px;
    width: 18px;
    height: 10px;
    float: left;
    overflow: hidden;
}

.more-menu-caret-outer,
.more-menu-caret-inner {
    position: absolute;
    display: inline-block;
    margin-left: -1px;
    font-size: 0;
    line-height: 1;
}

.more-menu-caret-outer {
    border-bottom: 10px solid #c1d0da;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    height: auto;
    left: 0;
    top: 0;
    width: auto;    
}

.more-menu-caret-inner {
    top: 1px;
    left: 1px;
    border-left: 9px solid transparent;
    border-right: 9px solid transparent;
    border-bottom: 9px solid #fff;
}

.more-menu-items {
    margin: 0;
    list-style: none;
    padding: 0;
}

.more-menu-item {
    display: block;
}

.more-menu-btn {
    min-width: 100%;
    color: #66757f;
    cursor: pointer;
    display: block;
    font-size: 13px;
    line-height: 18px;
    padding: 5px 20px;
    position: relative;
    white-space: nowrap;
}

.more-menu-item:hover {
    background-color: #489fe5;
}

.more-menu-item:hover .more-menu-btn {
    color: #fff;
}

.more-btn:hover .more-dot,
.show-more-menu .more-dot {
    background-color: #516471;
}

.show-more-menu .more-menu {
    opacity: 1;
    -webkit-transform: translate(0, 0) scale(1);
    transform: translate(0, 0) scale(1);
    pointer-events: auto;
}
.input-fields {
 margin-bottom: 20px;
 }
 .input-fields input,
 select {
 width: 100%;
 height: 20px;
 -ms-box-sizing: content-box;
 -moz-box-sizing: content-box;
 box-sizing: content-box;
 -webkit-box-sizing: content-box;
 }
 .order-details-notes {
 font-weight: 300;
 }
.templateDiv {
    text-align: center;
}
.po-number-div::before {
font-weight: 700;
display: inline-block;
max-width: 100%;
margin-bottom: 4px;
content: '';
margin-left: -117px;
height: 1px;
width: 75%;
background: rgba(0,0,0,0.2);
}
	
	
	.list_div p {
		margin: 5px 0px 5px 0px;
	}
	
	.list_div p.uvc {
		font-weight: bold;
	}
	
	.list_div p.name {
		font-weight: 400;
		color: #666;
		font-size: 13px;
	}
	
	.list_div span.label {
		vertical-align: bottom;
	}
	
	
	.list_div {
		padding-top: 50px;
	}
	
	.list-group img {
		width: 30px;
		height: 30px;
	}
	
	.list_div li {
		border: 1px solid #f7f7f7;
		box-shadow: -1px 2px 4px 1px #0000000f;
		outline: none;
	}
	.nav-tabs-item
	{
		border-bottom: 0px solid;
		    margin-left: 70px;
	}
	
	.sticker {
		border-radius: 10px;
		background-color: #1A237E;
		padding: 3px 15px;
	}
	
	.labels {
		border-radius: 10px;
		background-color: #00BCD4;
		padding: 3px 15px;
	}
	
	.btn-ghost {
		background-color: #fff;
		border: 1px solid #818080;
		color: #818080;
		padding: 4px -1px;
		border-radius: 3px;
		transition: 0.3s ease;
        font-size: 10px;
	}
	
	.btn-ghost:hover {
		background-color: #333;
		color: #fff;
	}
	
	.nav-tabs>.li-item.active>.li-a,
	.nav-tabs>.li-item.active>.li-a:focus,
	.nav-tabs>.li-item.active>.li-a:hover {
		border: 2px solid transparent;
		border-bottom: 2px solid;
		color: #273238;
	}
	
	.nav-tabs>.li-item>.li-a:hover {
		border-color:  rgba(39, 50, 56, 0.66);
		background-color: transparent;
		border: 2px solid transparent;
		border-bottom: 2px solid;
	}
	
	.left-panel.active {
    margin-right: 0%;
}
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
	<div class="modal fade" id="age_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header alert-warning">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<p class="modal-title" id="myModalLabel">SUPPLIER IS SOLELY RESPONSIBLE TO CHOOSE CORRECT AGE GROUP. IN CASE OF ANY WRONG SELECTION, <strong>ORCHESTRA/JUPITER</strong> ARE NOT LAIBLE FOR CONSEQUENCES THERE OFF.</p>
				</div>
			</div>
		</div>
	</div>

	<div class="modal needed_modal fade" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header alert-danger">
					<button type="button" class="close modal_close1" data-dismiss="modal" aria-hidden="true">&times;</button>
					<p>Use this column only if needed</p>
				</div>
			</div>
		</div>
	</div>

<!--
	<div class="modal fade po-details" id="po-file-upload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Purchase Order</h4>
				</div>
				<div class="modal-body">
					<div id="exist_artwork_div" style="margin-left: 10px;padding-bottom:25px;color: #002c3e;font-weight: 600;">
					</div>
					<div id="map_po_div" style="margin-top: 40px;" class="hidden">
					</div>
					<div id="new_po_div" style="margin-top:50px;" class="hidden">
						<label style="color:red" id="new_po_error" class="hidden">Please Enter Purchase Order Number</label>
						<div class="form-group">
						{!! Form::label('po', 'Purchase Order Number', ['class' => 'form_label']) !!} {!! Form::text('po',$value = '', array('id'=>'new_po_no','class' => 'form-control input-sm po ','placeholder' => 'Purchase Order Number','required')) !!}
						</div>
						<input type="file" id="new_po_file">
						<button style="margin-top:10px" type="button" class="btn btn-primary" id="update_new_po">Update New PO</button>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
-->
	
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
    </div>
{{-- Artwork buyer po-upload and delivery date upload modal --}}
<div class="modal fade" id="poUploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div id="order-exists-alert"></div>
        <div id="price-missing-alert"></div>
        <div class="modal-content po-modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Customer Purchase Order Upload</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" id="success-alert">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>Success! </strong>
                    PO uploaded successfully.
                </div>
                <form id="upload_combined_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data">
                <div id="po-upload-modal-content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm upload-later" data-dismiss="modal">Upload Later</button>
                <button class="btn btn-primary btn-sm po-submit-btn" type="submit">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Confirm artwork order modal --}}
<div class="modal fade" id="confirmOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirm Order</h4>
            </div>
            <div class="modal-body">
                <h5>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="alert-message alert-message-warning" style="background-color: #f2f5fc;border-color: #f0ad4e;padding: 20px;border-left: 3px solid #b4d1f0">
                                <h4 style="color: #00b6ff !important">Artwork quantity missing for following artworks:</h4>
                                <ol id="body_msg" style="font-weight: bolder;">
                                    
                                </ol>
                                <h4>Do you want to place the order ?</h4>
                            </div>
                        </div>
                    </div>
                </h5>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default btn-sm">No</button>
               <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm" id="confirm_btn">Yes</button>
            </div>
        </div>
    </div>
</div>

{{-- Order already exists warning modal --}}
<div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div id="order-exists-msg"></div>
    </div>
</div>

{{-- Cancel Order modal --}}
<div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Cancel Order</h4>
               </div>
                   <div class="modal-body">
                         <p>You are about to cancel this order. Do you want to proceed ?</p>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('No', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                       {!! Form::button('Yes', ['class' => 'btn btn-primary btn-sm', 'id' => 'cancel_btn','onclick' => 'cancelTescoOrder()'] ) !!}
                   </div>
           </div>
       </div>
   </div>
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



<div class="left-panel col-md-12" style="display: none;">
	<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
	<div class="div-max">
		<div class="col-md-7">
			<h4 class="artwork_name"></h4>
			<hr>
			<div class="section-1 sections">
				<div class="sample-artwork scroll" style="text-align:center;height: calc(100vh - 120px);overflow-y: scroll;"></div>
			</div>
		</div>
		<div class="col-md-5 scroll" style="overflow-y: scroll;height: 100%;">
			<h4>Quantity Information</h4>
			<hr>

			<div class="merchandiser-area">

				<div class="quantity"></div>
				<div class="buttons pull-right" style="margin-top:10px;">

				</div>
			</div>
		</div>

	</div>
</div>
<!-- Proforma Invoice Modal -->
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
	@endsection @else
	<script type="text/javascript">
		window.location = "{{ url('/admin') }}";

	</script>
	@endif @section('jqueryIncludes') 
	<script src="{{ asset('js/Admin/order.js') }}"></script>
	<script src="{{ asset('js/Admin/tesco-reorder.js') }}"></script>
	<script src="{{ asset('js/Admin/tesco-orders.js') }}"></script>
    <script src="{{ asset('js/Admin/tesco-pi.js') }}"></script>
    <script src="{{ asset('js/Admin/parser.js') }}"></script>
    <script src="{{ asset('js/Admin/tesco-view-orders.js') }}"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script>
	$(document).ready (function(){
        $("#success-alert").hide();
     });	
		$(function () {
    $('.list-group.checked-list-box .list-group-item').each(function () {
        
        // Settings
        var $widget = $(this),
            $checkbox = $('<input type="checkbox" class="hidden" />'),
            color = ($widget.data('color') ? $widget.data('color') : "primary"),
            style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };
            
        $widget.css('cursor', 'pointer')
        $widget.append($checkbox);

        // Event Handlers
        $widget.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });
          

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $widget.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $widget.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$widget.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $widget.addClass(style + color + ' active');
            } else {
                $widget.removeClass(style + color + ' active');
            }
        }

        // Initialization
        function init() {
            
            if ($widget.data('checked') == true) {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
            }
            
            updateDisplay();

            // Inject the icon if applicable
            if ($widget.find('.state-icon').length == 0) {
                $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
            }
        }
        init();
    });
    
    $('#get-checked-data').on('click', function(event) {
        event.preventDefault(); 
        var checkedItems = {}, counter = 0;
        $("#check-list-box li.active").each(function(idx, li) {
            checkedItems[counter] = $(li).text();
            counter++;
        });
        $('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
    });
});
		
		Dropzone.autoDiscover = false;
		var buyer = localStorage.getItem("buyer");
		$("div#po-pdf").dropzone({
			url: base_url + "/admin/" + buyer + "/set-po",
			acceptedFiles: ".pdf",
			addRemoveLinks: true,
			maxFiles: 1,
		});


		$(document).ready(function() {
			/** Clear local storage data **/
			localStorage.removeItem("hang_tag");
			localStorage.removeItem("special_tag");
			localStorage.removeItem("main_label");
			localStorage.removeItem("wash_label");
			localStorage.removeItem("wash_composition");
			localStorage.removeItem("deletemain");
			localStorage.removeItem("deletewash");
			localStorage.removeItem("page_navigation");
			//localStorage.removeItem("wash_composition_final");
			localStorage.removeItem("search_string");

			var arr = []; // Array to hold the keys
			// Iterate over localStorage and insert the keys that meet the condition into arr
			for (var i = 0; i < localStorage.length; i++) {
				if (localStorage.key(i).substring(0, 22) == 'wash_composition_final') {
					arr.push(localStorage.key(i));
				}
			}

			// Iterate over arr and remove the items by key
			for (var i = 0; i < arr.length; i++) {
				localStorage.removeItem(arr[i]);
			}

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
                    if (buyer === 'orchestra') 
                    {
                        orders();
                    } else {
                        tescoOrders();
                        reOrderTesco();
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
                    $('#'+localStorage.getItem("buyer")).addClass('active');
                    $("#buyerSelectionModalCenter").modal("show");
                }
            });

            /** [If only 1 buyer , auto select that buyer] */
            var buyer_count = $('#invisible__count_id').val();
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
                if (buyer === 'orchestra') 
                {
                    orders();
                } else {
                    tescoOrders();
					reOrderTesco();
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
                    if (buyer === 'orchestra') 
                    {
                        orders();
                    } else {
                        tescoOrders();
						reOrderTesco();
                    }
              }
             })
            
        });
        
	</script>

	@endsection

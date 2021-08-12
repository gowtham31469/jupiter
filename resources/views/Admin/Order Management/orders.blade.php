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
	.check-list
	{
		position: relative;
		display: block;
		padding: 10px 15px;
		margin-bottom: -1px;
		background-color: #fff;
		border: 1px solid #ddd;
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
.hiddencis_div{
    display: none !important;
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
 .order-details-notes {
 font-weight: 300;
 }
.templateDiv {
    text-align: center;
}
.po-number-div::before {
font-weight: 700;
display: inline-block;
content: '';
margin-left: -117px;
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

.care_label_reorder.active {
    margin-right: 0%;
}
	.bootstrap-select .dropdown-menu {
	width: 100%;
}

.bootstrap-select .dropdown-menu li small {
	white-space: normal;
}
/* Hide HTML5 Up and Down arrows. */
input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button {
	-webkit-appearance: none;
	margin: 0;
}

input[type="number"] {
	-moz-appearance: textfield;
}
</style>
<div class="col-sm-12 col-md-12 main">
	<div class="top-content">
        <div class="buyer-nav-div hidden">
           <a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Buyer</a>
        </div>
        {{ Form::hidden('invisible', $buyer_count, array('id' => 'invisible__count_id')) }}
        <div class="clearfix"></div>

        <!-- Financial year selection div -->
        <div class="year-nav-div hidden"></div>
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
	
	<div class="modal fade"  data-backdrop="static" id="po-file-upload-div" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
                <div class="modal-content">
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
                            <label style='color:red' id='new_po_error' class='hidden'>Please Enter PO Number</label><br>
                            <label style='color:red' id='new_po_file_error' class='hidden'>Please Upload PO File</label>
							 <div class="form-group">
                            <label>PO Number</label>
                            <input id="new_po_no" type="text" placeholder="PO Number" class="form-control">
                        	</div>
							<div class="form-group">
								<label for="exampleInputFile">PO File</label>
								<input type="file" accept="application/pdf" id="new_po_file">
								<p class="help-block"></p>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="combined_delivery_date">Delivery Date</label>
                                <div class="input-group date" data-provide="datepicker" id="datePicker3">
                                    <input value="" id="combined_delivery_date" required="" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span >
                                    </div >
                                </div >
                            </div>
							
						</div>
                       
                        
                     
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm upload-later" data-dismiss="modal">Upload Later</button>
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



<div class="left-panel tesco-left col-md-12" style="display: none;">
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

<div class="care_label_reorder col-md-12" style="display: none">
	<div class="loader" style="display: none; margin-top: 0%;"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
	<div class="div-max">
		<div class="col-md-5">
			<h4 class="artwork_name_care_label_reorder"></h4>
			<hr>
			<div class="section-1 sections">
				<div class="sample_artwork_care_label_reorder scroll" style="text-align:center;height: calc(100vh - 120px);overflow-y: scroll;"></div>
			</div>
		</div>
		<div class="col-md-7 scroll" style="overflow-y: scroll;height: 100%;">
			<h4>Quantity Information</h4>
			<hr>

			<div class="merchandiser-area">

				<div class="quantity_care_label_reorder"></div>
				<div class="buttons pull-right" style="margin-top:10px;">

				</div>
			</div>
		</div>

	</div>
</div>

<div class="care_label_repeatorder col-md-12" style="display: none">
    <div class="loader" style="display: none; margin-top: 0%;"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
    <div class="div-max" style="margin-left: 25%;">
        {{-- <div class="col-md-5">
            <h4 class="artwork_name_care_label_reorder"></h4>
            <hr>
            <div class="section-1 sections">
                <div class="sample_artwork_care_label_reorder scroll" style="text-align:center;height: calc(100vh - 120px);overflow-y: scroll;"></div>
            </div>
        </div> --}}
        <div class="col-md-12 scroll" style="overflow-y: scroll;height: 100%;">
            {{-- <h4>Quantity Information12345</h4> --}}
            <h4 class="artwork_name_care_label_reorder"></h4>
            <hr>

            <div class="merchandiser-area">
                {!! Form::open(['id' => 'tesco_care_label_order_form','data-toggle' => 'validator','role' => 'form']) !!}
                <input type="hidden" id="order_type" name="order_type" value="repeatOrder">
                <input type="hidden" id="primary_size_id" name="primary_size_id" value="">
                <span id="result"></span>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group has-feedback">
                            <label for="tesco_care_po" class="form_label">Tesco PO Number</label>
                            <input class="form-control input-sm " placeholder="Tesco PO Number" required="" maxlength="10" name="tesco_care_po" type="text" value="" id="tesco_care_po">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group has-feedback">
                            <label for="tesco_care_eqos" class="form_label">EQOS Supplier Number</label>
                            <input class="form-control input-sm " placeholder="EQOS Supplier Number" required="" maxlength="10" name="tesco_care_eqos" type="text" value="" id="tesco_care_eqos">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group has-feedback">
                            <label for="tesco_care_ce_style" class="form_label">Option ID</label>
                            <input class="form-control input-sm " placeholder="Option ID" required="" maxlength="" name="tesco_care_ce_style" type="text" value="" id="tesco_care_ce_style">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group has-feedback">
                            <label for="tesco_care_uk_style" class="form_label">UK Style Number</label>
                            <input class="form-control input-sm " placeholder="UK Style Number" required="" maxlength="8" name="tesco_care_uk_style" type="text" value="" id="tesco_care_uk_style">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group has-feedback">
                            <label for="tesco_care_fabric_code" class="form_label">Fabric Batch Code</label>
                            <input class="form-control input-sm " placeholder="Fabric Batch Code" maxlength="7" name="tesco_care_fabric_code" type="text" value="" id="tesco_care_fabric_code">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group has-feedback">
                            <label for="tesco_care_leather_id" class="form_label">Leather &amp; Suede ID</label>
                            <input class="form-control input-sm " placeholder="Leather &amp; Suede ID" maxlength="" name="tesco_care_leather_id" type="text" value="" id="tesco_care_leather_id">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                
               <div class="row">
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_coo', 'Country Of Origin', ['class' => 'form_label']) !!}
            {{  Form::select('tesco_care_coo',(['' => '-- Select Country Of Origin --'] + $coo),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_coo','required', 'data-live-search' => 'true')) }}
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_supplier', 'Supplier', ['class' => 'form_label']) !!}
            {{  Form::select('tesco_care_supplier',(['' => '-- Select Supplier --'] + $tesco_suppliers),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_supplier','required', 'data-live-search' => 'true', 'onchange' => 'getMoq("admin", "create")')) }}
            <div class="help-block with-errors"></div>
        </div>
    </div>
                    <div class="col-md-3">
                        <div class="form-group has-feedback"><label for="tesco_care_button_space" class="form_label" style="visibility:hidden">Dont Show</label>
                            <div class="checkbox">
                                <label>
                                    <input id="tesco_care_button_space" name="tesco_care_button_space" type="checkbox"> Include Button Sew Space
                                </label>
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
<hr>
    <h4>Order Quantity</h4>
                <div class="quantity_care_label_repeatorder"></div>

<hr>
                <div class="row garment-component-div">
    <div class="col-md-12">
        <h4 class="pull-left">Garment Component & Fibres</h4>
        <button class="btn btn-primary add-garment-component-btn pull-right" type="button" data-id="3" id="add_garment_component_btn" onclick="addComponent(this, 'admin', 'tesco_care_label_order_form')"><i class="fa fa-plus" aria-hidden="true"></i> Garment Component</button> 
    </div>
    
    @for ($j = 1; $j <= 3; $j++)
    <div class="col-md-4 component-div">
        <div class="row">
        <div class="col-xs-12">
            <div class="form-group has-feedback">
                {!! Form::label('tesco_care_garment_component_'.$j, 'Garment Component', ['class' => 'form_label component-label']) !!}
                {{  Form::select('tesco_care_garment_component_'.$j,(['' => '-- Select Garment Component --']+$components),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_garment_component_'.$j, 'data-live-search' => 'true', 'name' => 'tesco_care_garment_component[]')) }}
                <div class="help-block with-errors"></div>
            </div>
        </div>
        @for ($i = 1; $i <= 6; $i++)
            <div class="col-xs-8">
                <div class="form-group has-feedback">
                    {{  Form::select('tesco_care_fiber_'.$j.'_'.$i,(['' => '-- Select Fiber --']+$fabriclists),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_fiber_'.$j.'_'.$i, 'data-live-search' => 'true', 'name' => 'tesco_care_fiber[]')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group has-feedback">
                <div class="input-group">
                    <input type="number" class="form-control input-sm fiber-individual-percentage" max="100" id="fiber_individual_percentage_{{$j}}_{{$i}}" name="fiber_individual_percentage[]" data-max-error= "Value must be <= 100">
                    <div class="input-group-addon">%</div>
                </div>
                <div class="help-block with-errors"></div>
                </div>
            </div>
        @endfor
        <div class="col-xs-8">
            <h5 class="text-right"><strong>Total</strong></h5>
        </div>
        <div class="col-xs-4">
            <div class="form-group has-feedback">
            <div class="input-group">
                <input type="number" class="form-control input-sm composition-percentage total-percentage" name="fiber_total_percentage[]" id="fiber_total_percentage_{{ $j }}">
                <div class="input-group-addon">%</div>
            </div>
            <div class="help-block with-errors"></div>
            </div>
        </div>

        </div>
    </div>
    @endfor
</div>
<hr>
<div class="row">
    <div class="col-md-12">
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
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h4>Care Instructions</h4>
    </div>
    <div class="col-md-3">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_wash_instruction', 'Wash Instruction', ['class' => 'form_label']) !!}
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_wash_instruction"  id="tesco_care_wash_instruction">
                <option value="">-- Select Wash Instruction --</option>
                @if(!empty($symbols['Washing']))
                    @foreach($symbols['Washing'] as $key => $value)
                            <option title="{{ $value->degree }}" data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" required >{{ $value->degree }}</option>
                    @endforeach
                @endif
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_bleach_instruction', 'Bleach Instruction', ['class' => 'form_label']) !!}
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_bleach_instruction"  id="tesco_care_bleach_instruction">
                <option value="">-- Select Bleach Instruction --</option>
                @if(!empty($symbols['Whitening']))
                    @foreach($symbols['Whitening'] as $key => $value)
                            <option title="{{ $value->degree }}" data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" required>{{ $value->degree }}</option>
                    @endforeach
                @endif
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_dry_instruction', 'Dry Instruction', ['class' => 'form_label']) !!}
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_dry_instruction"  id="tesco_care_dry_instruction">
                <option value="">-- Select Dry Instruction --</option>
                @if(!empty($symbols['Dryer']))
                    @foreach($symbols['Dryer'] as $key => $value)
                            <option title="{{ $value->degree }}" data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" required>{{ $value->degree }}</option>
                    @endforeach
                @endif
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_iron_instruction', 'Iron Instruction', ['class' => 'form_label']) !!}
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_iron_instruction"  id="tesco_care_iron_instruction">
                <option value="">-- Select Iron Instruction --</option>
                @if(!empty($symbols['Ironing']))
                    @foreach($symbols['Ironing'] as $key => $value)
                            <option title="{{ $value->degree }}" data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" required>{{ $value->degree }}</option>
                    @endforeach
                @endif
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_dry_clean_instruction', 'Dry Clean Instruction', ['class' => 'form_label']) !!}
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_dry_clean_instruction"  id="tesco_care_dry_clean_instruction">
                <option value="">-- Select Dry Clean Instruction --</option>
                @if(!empty($symbols['Prof Cleaning']))
                    @foreach($symbols['Prof Cleaning'] as $key => $value)
                            <option title="{{ $value->degree }}" data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" required>{{ $value->degree }}</option>
                    @endforeach
                @endif
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
</div>
<hr>
<div class="row">
<div class="col-md-8">
    <div class="row">
    <div class="col-md-12">
        <h4>Additional Instructions</h4>
    </div>
    @for ($i = 1; $i <= 3; $i++)
    <div class="col-md-4">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_additional_instruction_'.$i, 'Additional Instruction '.$i, ['class' => 'form_label']) !!}
            {{  Form::select('tesco_care_additional_instruction_'.$i,(['' => '-- Select Additional Instruction --'] + $additional_instructions),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_additional_instruction_'.$i, 'data-live-search' => 'true', 'name' => 'tesco_care_additional_instruction_'.$i)) }}
            <div class="help-block with-errors"></div>
        </div>
    </div>
    @endfor
    
    </div>
</div>
<div class="col-md-6">
    <div class="row">
        <div class="col-md-12">
            <h4>Additional Instructions</h4>
        </div>
        <div class="col-md-6">
            <div class="form-group has-feedback">
                {!! Form::label('tesco_care_kids_warning', 'Kids Warning Statement', ['class' => 'form_label']) !!}
                {{  Form::select('tesco_care_kids_warning',(['' => '-- Select Kids Warning Statement --'] + $kids_warning),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_kids_warning', 'data-live-search' => 'true')) }}
                <div class="help-block with-errors"></div>
            </div>
        </div>
    </div>
    <div class="label-order-invisible-div"></div>
</div>
</div>




                <div class="buttons pull-right" style="margin-top:10px;">
                    <button style="margin-left: 10px;" class="btn btn-primary pull-right" type="submit" onclick="validateTescoCareLabelOrder('admin')">Submit</button><button type="button" class="btn btn-danger remove pull-right">Close</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

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

    <div class="modal fade" id="cancelLabelOrderModal" tabindex="-1" role="dialog" aria-labelledby="cancelLabelOrderModal">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="cancelLabelOrderModal">Cancel Order</h4>
               </div>
                   <div class="modal-body">
                         <p>You are about to cancel this order. Do you want to proceed ?</p>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('No', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                       {!! Form::button('Yes', ['class' => 'btn btn-primary btn-sm', 'id' => 'cancel_btn','onclick' => 'cancelCareLabelOrder("admin")'] ) !!}
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
    <script src="{{ asset('js/Admin/orchestra-pi.js') }}"></script>
    <script src="{{ asset('js/Admin/parser.js') }}"></script>
    <script src="{{ asset('js/Admin/tesco-view-orders.js') }}"></script>
    <!-- Tesco care labels orders js -->
    <script src="{{ asset('js/common/tesco-care-label-orders.js') }}"></script>
    <script src="{{ asset('js/common/view-tesco-care-label-orders.js') }}"></script>
    <!-- Financial year selection script -->
    <script src="{{ asset('js/common/financial-year-switch.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
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
			localStorage.removeItem("artwork_back");
			localStorage.removeItem("back_flag");
			localStorage.removeItem("order_type_back");
			localStorage.removeItem("back_zone");
			localStorage.removeItem("back_template_id");
			localStorage.removeItem("style_number_back");
			localStorage.removeItem("po_num_back");
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
                    // var buyer = localStorage.getItem("buyer");
                    // if (buyer === 'orchestra') 
                    // {
                    //     orders();
                    // } else if (buyer === 'tesco')  {
                    //     tescoOrders(reOrderTesco);
                    // }
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
                }  else if (buyer === 'tesco')  {
                    tescoOrders(reOrderTesco);
                }
				else{
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
                    if (buyer === 'orchestra') 
                    {
                        orders();
                    } else if (buyer === 'tesco')  {
                       tescoOrders(reOrderTesco);
                    }
              }
             })
            
        });
	</script>

	@endsection

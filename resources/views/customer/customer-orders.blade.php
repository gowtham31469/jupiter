@extends('layouts.Customer.app') @if (Auth::guard("web_customer")->user()) @section('content')
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
{{-- Artwork buyer po-upload and delivery date upload modal --}}
<div class="modal fade" id="poUploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div id="order-exists-alert"></div>
        <div class="modal-content po-modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Buyer Purchase Order Upload</h4>
            </div>
            <div class="modal-body">
                <div class="tabbable">{{-- Only required for left/right tabs --}}
                    <ul class="nav nav-tabs">
                        <li class="active" id='single_tab'>
                            <a href="#single" data-toggle="tab">Single PO Upload</a>
                        </li>
                        <li id='combined_tab'>
                            <a href="#combined" data-toggle="tab">Combined PO Upload</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="single"></div>
                        <div class="tab-pane" id="combined"></div>
                    </div>
                </div>
            </div>
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
                                <h4 style="color: #00b6ff !important">Artwork count missing for following artworks:</h4>
                                <ol id="body_msg" style="font-weight: bolder;">
                                    
                                </ol>
                                <h4>Do you want to place the order ?</h4>
                            </div>
                        </div>
                    </div>
                </h5>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default btn-sm">Cancel</button>
               <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm" id="confirm_btn">Confirm</button>
            </div>
        </div>
    </div>
</div>

{{-- Artwork count missing for all artworks modal --}}
<div class="modal fade" id="countMissingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h5>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="alert-message alert-message-warning" style="background-color: #f2f5fc;border-color: #f0ad4e;padding: 20px;border-left: 3px solid #b4d1f0">
                                <h4 style="color: #00b6ff !important">Please enter atleast one artwork quantity(count) and count must be greater than 0.</h4>
                            </div>
                        </div>
                    </div>
                </h5>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm">OK</button>
            </div>
        </div>
    </div>
</div>

<div id="snackbar"><span class="snackbar_span"></span></div>
@endsection @else
<script type="text/javascript">
    window.location = "{{ url('/customer') }}";
</script>
@endif @section('jqueryIncludes')      
<script src="{{ asset('js/Customer/customer-tesco-order.js') }}"></script>
<script>
    $(document).ready(function() {
        localStorage.removeItem("hang_tag");
        localStorage.removeItem("special_tag");
        localStorage.removeItem("main_label");
        localStorage.removeItem("wash_label"); 
        localStorage.removeItem("wash_composition");
        localStorage.removeItem("deletemain");
        localStorage.removeItem("deletewash");
        $('.select-buyer').click(function(){
            $('.select-buyer').addClass('hidden');
        })
         $('.logo').html('Orders')
         $('.buyer-nav-select').html($('.buyer-div').html())
         /** [ Show create order page based on buyer selection ] */
        if (localStorage.getItem("buyer") === null) {

        } else if (localStorage.getItem("buyer") === 'orchestra') {
            orders();
        } else {
            tescoOrders();
        }
        $('.buyer-select').val(localStorage.getItem("buyer_id"))
          $('.buyer-nav-select .buyer-select').selectpicker({
               style: 'btn-default',
               size: 4
            });
        $('.buyer-select.bootstrap-select').on('changed.bs.select', function() {
            if ($('.buyer-nav-select .buyer-select option:selected').val() != "") {
                localStorage.setItem("buyer",$('.buyer-nav-select .buyer-select').find("option:selected").text().toLowerCase());
                localStorage.setItem("buyer_id", $('.buyer-nav-select .buyer-select').find("option:selected").val());
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var buyer = $(this).find("option:selected").text().toLowerCase();
                /**  [ Show orders page based on buyer selection ] **/
                if (buyer === 'orchestra') {
                    orders();
                } else {
                    tescoOrders();
                } 
            } else {
                localStorage.removeItem("buyer");
                localStorage.removeItem("buyer_id");
                location.reload()
            }
        });
    });
</script>

@endsection
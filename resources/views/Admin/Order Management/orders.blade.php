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
           
<!--
        <ol class="breadcrumb">
            <li><a href="/admin/dashboard">Dashboard</a></li>
            <li class="active">Order Management</li>
        </ol>
        <form class="navbar-form pull-right" style="margin-top:0px;">
            <label style="color: #333; margin-right: 10px;"><i class="ion-crop"></i>Select Buyer</label>
            <select class="js-example-responsive buyer-select">
                  <option></option>
                    @foreach($buyers as $buyer)
                    <option value="{{$buyer->id}}">{{$buyer->buyer_name}}</option>
                    @endforeach
              </select>
        </form>
        <h3><i class="ion-ios-browsers-outline"></i>Order Management</h3>

        <div class="main-sub-menu hidden">
            <div class="action-group pull-right">
               <a href="#" class=" btn btn-primary btn-sm"><i class="ion-upload"></i>Upload Data</a> 
                <a href="#" id="uploadModal" data-target="#uploadData" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-upload"></i>Upload Data</a>
            </div>
            <ul class="sub-nav-list">
                <li class="dashboard sub_modules"><a href=""><i class="ion-grid"></i>Dashboard</a></li>
                <li class="sticker_orders sub_modules"><a href="#" onclick="sticker_orders()"><i class="ion-social-codepen"></i>Sticker Orders</a></li>
                <li class="label_orders sub_modules"><a class="" href="#" onclick="label_orders()"><i class="ion-ios-pricetag"></i>Label Orders</a></li>
            </ul>
            <hr/>
        </div>
    </div>-->
    <div class="clearfix"></div>
</div>
    <div class="main-content">
    </div>
<div class="modal fade" id="uploadData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Upload Data</h4>
            </div>
            <div class="modal-body" class="dropzone" id="dropzoneFileUpload">
                {!! Form::open([ 'route' => [ 'dropzone.store' ], 'files' => true, 'enctype' => 'multipart/form-data', 'class' => 'dropzone', 'id' => 'upload']) !!}
            </div>
            <div class="modal-footer">
                {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="modal fade" id="uploadMultiData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Upload Multiple Data Files</h4>
            </div>
            <div class="modal-body" class="dropzone" id="dropzoneMultiFileUpload">
                {!! Form::open([ 'route' => [ 'dropzone.multi-store' ], 'files' => true, 'enctype' => 'multipart/form-data', 'class' => 'dropzone', 'id' => 'multi-upload','multiple'=>"true"]) !!}
            </div>
            <div class="modal-footer">
                {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
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
@endsection @else
<script type="text/javascript">
    window.location = "{{ url('/admin') }}";
</script>
@endif @section('jqueryIncludes')
<script>
    $(document).ready(function() {
         $('.logo').html('Orders')
         $('.buyer-nav-select').html($('.buyer-div').html())
         if (localStorage.getItem("buyer") === null) {
                
         }
        else
        {
             sticker_orders();
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
                    //                         $('.sub-nav-list').html('<li class="dashboard sub_modules"><a href="" ><i class="ion-grid"></i>Dashboard</a></li>')
                    //                         $.each(data, function (index, value) {
                    //                            $('.sub-nav-list').append('<li class="'+value.module_name.toLowerCase().replace(" ", "_")+' sub_modules"><a href="#" onclick="'+value.url_path+'"><i class="'+value.image+'"></i>'+value.module_name+'</a></li>')
                    //                        });
                    //                         $('.main-sub-menu').removeClass('hidden');

                    //                        if(data[0].module_name == "Label Orders")
                    //                            {
                    //                                label_orders();
                    //                            }
                    //                         if(data[0].module_name == "Sticker Orders")
                    //                             {
                    sticker_orders();
                    //}                        
                },
                error: function(data) {

                },
                complete: function() {
                    $(".loader").hide();
                    $(".loader").css('margin-top', '0%');
                }
            })
        } else {
            localStorage.removeItem("buyer");
                localStorage.removeItem("buyer_id");
            location.reload()
        }
    });
    });
   
    
</script>
<script src="{{ asset('js/dropzone.js') }}"></script>
<script type="text/javascript">
    Dropzone.options.imageUpload = {
        maxFilesize: 10,
        acceptedFiles: ".csv",
        ".xls"
    };
    $('.modal').on('hidden.bs.modal', function(e) {
        $('#uploadMultiData').trigger("reset");
        $('#uploadData').trigger('reset');
    })
</script>
@endsection
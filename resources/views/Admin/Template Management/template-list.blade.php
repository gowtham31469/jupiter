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


    .jstree-leaf>.jstree-anchor>.fa-folder:before {
        content: "\f02b";
    }

    .force-overflow {
        min-height: 450px;
    }

    /*
 *  STYLE 4
 */

    #style-4::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        background-color: #F5F5F5;
    }

    #style-4::-webkit-scrollbar {
        width: 5px;
        background-color: #F5F5F5;
    }

    #style-4::-webkit-scrollbar-thumb {
        background-color: #000000;
        border: 1px solid #555555;
    }

    .descriptionDiv {
background: #fff;
    padding: 10px;
    margin-left: -13px;
    text-align: center;
    margin-bottom: 11px;
    position: absolute;
    bottom: 0;
    width: 97%;
    border: 1px dashed #333;
    }

    .descriptionDiv span {
        font-weight: 400;
    }

    .descriptionDiv h4 {
        color: #333;
        border-bottom: 1px solid #cacaca;
        padding-bottom: 8px;
    }
    p.delta {
    display: inline-block;
    margin-right: 30px;
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
        <div class="row">

            <div class="col-md-4">

                <div class="search-tree">

                    <input type="text" id="tree1_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div>
                <div class="card js-card" id="style-4" style="height:calc(100vh - 110px); overflow-y:scroll; position:relative;margin-bottom: 15px;">
                    <div class="jaytree force-overflow" id="tree1">
                    </div>
                </div>
                <button type="button" style="background-color: #263238;border: #263238;" class="btn btn-xs btn-success" id="expand">Expand</button>
                <button type="button" class="btn btn-xs btn-success" style="float: right;background-color: #263238;border: #263238;" id="collapse">Collapse</button>
            </div>
            <div class="col-md-8">
                <div class="card preview-tag" style="height:calc(100vh - 110px);">
                    <div class="loader"></div>
                    <div class="viewDiv">

                    </div>
                    <div class="descriptionDiv">

                    </div>
                </div>
            </div>
        </div>

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
            //$('.js-card').niceScroll()
            $('.logo').html('Templates')
            $('.buyer-nav-select').html($('.buyer-div').html())
            if (localStorage.getItem("template_buyer") === null) {

            } else {
                template_list();
            }
            $('.buyer-select').val(localStorage.getItem("template_buyer_id"))
            $('.buyer-nav-select .buyer-select').selectpicker({
                style: 'btn-default',
                size: 4
            });

            $('.buyer-select.bootstrap-select').on('changed.bs.select', function() {
                if ($('.buyer-nav-select .buyer-select option:selected').val() != "") {
                    localStorage.setItem("template_buyer", $('.buyer-nav-select .buyer-select').find("option:selected").text().toLowerCase());
                    localStorage.setItem("template_buyer_id", $('.buyer-nav-select .buyer-select').find("option:selected").val());
                    template_list();
                } else {
                    localStorage.removeItem("template_buyer");
                    localStorage.removeItem("template_buyer_id");
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
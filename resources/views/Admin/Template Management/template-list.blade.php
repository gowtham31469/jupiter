@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<style>
    .jstree-leaf>.jstree-anchor>.fa-folder:before {
        content: "\f02b";
    }

    .force-overflow {
        min-height: 450px;
    }

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
        <div class="clearfix"></div>
    </div>
    <div class="main-content">
         <div class="select-buyer">
            <img src="../../images/select-buyer.png" class="img-responsive">
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
    <script src="{{ asset('js/Admin/template.js') }}"></script>
    <script>
        $(document).ready(function() {
             $('.select-buyer').click(function(){
            $('.select-buyer').addClass('hidden');
        })
            
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
    @endsection
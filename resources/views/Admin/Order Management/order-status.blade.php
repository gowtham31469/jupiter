@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

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
    <div class="modal fade po-details" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Purchase Order</h4>
                </div>
                {!! Form::open(['id' => 'add_po_form','data-toggle' => 'validator','role' => 'form']) !!}
                <div class="modal-body">
                    <div class="form-group">
                       <!--  <label>Purchase Order Number</label>
                        <input type="text" class="form-control po" placeholder="Purchase Order Number"> -->
                        {!! Form::label('po', 'Purchase Order Number', ['class' => 'form_label']) !!}
                          {!! Form::text('po',$value = '', array('class' => 'form-control input-sm po ','placeholder' => 'Purchase Order Number','required','onkeypress' => 'return isNumber(event)')) !!}
                    </div>
                    <div class="form-group ">
                        <label>Upload Purchase Order</label>
                    <div class="dropzone dropzone-previews" id="po-pdf"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     {!! Form::submit('Save changes', ['class' => 'btn btn-primary upload-po'] ) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal fade" id="profoma-invoice" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="float:right">
                        <button class="btn btn-success btn-xs">Approve</button>
                        <button class="btn btn-danger btn-xs">Reject</button>
                        <button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" aria-label="Close">Close</button>
                    </div>
                    <h5><strong>Proforma Invoice for <span id="uvc_number">UVC0F0001</span></strong></h5>

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
                                        <p><strong>Date:</strong>30/12/2017</p>
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
                                <!-- <div class="col-lg-5 col-md-5 col-sm-5">

                                </div> -->
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
                    <div style="float:right">
                        <button class="btn btn-success btn-xs">Approve</button>
                        <button class="btn btn-danger btn-xs">Reject</button>
                        <button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" aria-label="Close">Close</button>
                    </div>
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
    <script>
		localStorage.removeItem("page_navigation");
        $(document).ready(function(){
            $('#po-submit').prop('disabled',true);
            $('#po').keyup(function(){
                $('#po-submit').prop('disabled', this.value == "" ? true : false);     
            })
        }); 
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
        $(document).ready(function() {
            $('.logo').html('Order Status');
            $('.select-buyer').click(function() {
                $('.select-buyer').addClass('hidden');
            })
            $('.buyer-nav-select').html($('.buyer-div').html())
            if (localStorage.getItem("status_buyer") === null) {

            } else {
                orderStatus();
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

                            orderStatus();
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
     $('#add_po_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             savePO();
          }
        })    
    Dropzone.autoDiscover = false;
    jQuery(document).ready(function() {
      var buyer = localStorage.getItem("status_buyer");
      $("div#po-pdf").dropzone({
        url: base_url + "/admin/" + buyer + "/set-po",
        // autoProcessQueue: false,
        acceptedFiles: ".pdf",
        addRemoveLinks: true,
        maxFiles:1,
        });
    });
    function savePO()
    {
        var buyer = localStorage.getItem("status_buyer");
        var po_number = $('.po').val();
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
        url: base_url + "/admin/" + buyer + "/set-po",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            po_number:po_number
        },
        success: function (data) {
            $('.po-details').modal('hide');
        },
        error: function (data) {
            alert("error")
        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
    }

    </script>

    @endsection
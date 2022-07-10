@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/get-purchase-reqisition') }}" style="color: #596881;">PURCHASE
                            DETAILS</a></span>
                    <span><a href="{{ url('inventory/get-purchase-reqisition') }}" style="color: #596881;">PURCHASE
                            REQISITION</a></span>
                    <span><a href="">{{ request()->pr_id ? 'Edit' : 'Add' }} purchase reqisition master</a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                    {{ request()->pr_id ? 'Edit' : 'Add' }} purchase reqisition master</h4>
                <div class="az-dashboard-nav">
                    <nav class="nav">
                        <a class="nav-link    "
                            href="{{ url('inventory/edit-purchase-reqisition?pr_id=' . request()->pr_id) }}">Purchase
                            reqisition master </a>
                        <a class="nav-link  active" @if (request()->pr_id) href="{{ url('inventory/get-purchase-reqisition-item?pr_id=' . request()->pr_id) }}" @endif> Purchase reqisition item </a>
                        <a class="nav-link  " href=""> </a>
                    </nav>

                </div>

                <div class="row">

                    <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 "
                        style="border: 0px solid rgba(28, 39, 60, 0.12);">



                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <form method="POST" id="commentForm" novalidate="novalidate">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                        <i class="fas fa-address-card"></i> Basic details </label>
                                    <div class="form-devider"></div>
                                </div>
                            </div>

                            <div class="row">


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label for="exampleInputEmail1">Item code * <span
                                            class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span></label>
                                    <input type="text" class="form-control" name="Itemcode" id="Itemcode" value=""
                                        placeholder="Item code">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Item type * </label>
                                    <input type="text" readonly class="form-control" value="" name="Itemtype" id="Itemtype"
                                        placeholder="Item type">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Item description *</label>
                                    <textarea value="" readonly class="form-control" id="Itemdescription"
                                        name="Itemdescription" placeholder=""></textarea>

                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>HSN/SAC code *</label>
                                    <input type="text" readonly value="" class="form-control" name="HSNSAC" id="HSNSAC"
                                        placeholder="">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Supplier *</label>
                                    <select class="form-control Supplier" name="Supplier">
                                        <option value="">--- select one ---</option>
                                    </select>
                                </div>
                                
                                
                                <!-- form-group -->
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Unit name*</label>
                                    <input type="text" readonly value="" class="form-control" name="Unit" id="Unit" placeholder="">
                                </div><!-- form-group -->


             
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Stock Qty *</label>
                                    <input type="text" readonly class="form-control" value="" id="StockQty" name="StockQty"
                                        placeholder="">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Open PO Qty *</label>
                                    <input type="text" readonly class="form-control" value="" id="OpenPOQty" name="OpenPOQty"
                                        placeholder="">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Actual order Qty *</label>
                                    <input type="text" class="form-control" value="" name="ActualorderQty"
                                        placeholder="Actual order Qty">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Min Level *</label>
                                    <input type="text" readonly class="form-control" value="" id="MinLevel" name="MinLevel"
                                        placeholder="">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Max Level *</label>
                                    <input type="text" readonly class="form-control" value="" id="MaxLevel" name="MaxLevel"
                                        placeholder="">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                  <label>Basic Value *</label>
                                  <input type="text" name="BasicValue" value="" class="form-control"
                                      placeholder="Basic Value">
                              </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Rate *</label>
                                    <input type="text" class="form-control" value="" name="Rate" id="Rate" placeholder="Rate">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Discount ( % ) *</label>
                                    <input type="text" class="form-control" value="" id="Discount" name="Discount"
                                        placeholder="Discount ( % )">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> GST *</label>
                                    {{-- <input type="text" class="form-control" value="" name="GST" id="GST" placeholder="GST"> --}}
                                    <select class="form-control GST" name="GST">
                                      <option value="">--- select one ---</option>
                                      @foreach ($data['response']['raw_materials'] as $item)
                                          <option value="{{$item['gst']}}">{{$item['gst']}}  %</option>
                                      @endforeach
                                  </select>
                                  </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Currency *</label>
                                    <select class="form-control Currency" name="Currency">
                                      <option value="">--- select one ---</option>
                                      @foreach (['USD','INR'] as $item)
                                          <option value="{{ $item }}">{{ $item }}</option>
                                      @endforeach
                                  </select>
                                   
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Net value *</label>
                                    <input type="text" readonly class="form-control" value="" id="Netvalue" name="Netvalue"
                                        placeholder="">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Reason for Remarks *</label>
                                    <textarea value="" class="form-control" name="Remarks"
                                        placeholder="Reason for Remarks"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><i
                                            class="fas fa-save"></i> Save</button>
                                </div>
                            </div>
                            <div class="form-devider"></div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- az-content-body -->
    </div>
    <script src="<?= url('') ?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
    <script>
      $(function(){

      // $('.GST').select2({
      //     placeholder: 'Choose one',
      //     searchInputPlaceholder: 'Search',
      // });


    function  netvalue(){
      let Rate = $('#Rate').val();
      let Discount = $('#Discount').val() ? $('#Discount').val() : 0;
      let gst = $('.GST').val() ? $('.GST').val() : 0;
      if(Rate){
        let total = ( Rate - ((Rate / 100) * Discount)) ;

        let netvalue = (+total + ((total / 100) * gst));
        console.log(gst);
        console.log(((total / 100) * gst));


        $('#Netvalue').val(netvalue);

      }else{
       $('#Discount').val('');
       $('.GST').val('');
      }
    }

    $( "#Rate" ).on( "input",function() {
      netvalue();
    });
    $( "#Discount" ).on( "input",function() {
      netvalue();
    });
    $('.GST').on('change', function(e) {

      netvalue();
    });


        
    function get_itemcode(element,event){
        $('.spinner-button').show();
        $('#Itemcode-error').remove();
        $('#Itemdescription').text('');
        $('#Itemtype').val('');
        $('#HSNSAC').val('');
        $('#Unit').val('');
        $('#MinLevel').val('');
        $('#MaxLevel').val('');
     
        $.get( "{{ url('inventory/itemcodesearch') }}/"+$(element).val(), function(res) {
          $('.spinner-button').hide();
          if(res.discription){
            $('#Itemdescription').text(res.discription);
          }
          if(res.item_type.type_name){
            $('#Itemtype').val(res.item_type.type_name);
          }
          if(res.hsn_code){
            $('#HSNSAC').val(res.hsn_code);
          }
          if(res.receipt_unit.unit_name){
            $('#Unit').val(res.receipt_unit.unit_name);
          }
          if(res.min_stock){
            $('#MinLevel').val(res.min_stock);
          }
          if(res.max_stock){
            $('#MaxLevel').val(res.max_stock);
          }
          if(res.opening_quantity){
            $('#OpenPOQty').val(res.opening_quantity);
          }
          if(res.availble_quantity){
            $('#StockQty').val(res.availble_quantity);
          }

        }).fail(function(error) {
          $('.spinner-button').hide();
          $('#Itemcode').after('<label id="Itemcode-error" class="error Itemcode-error" for="Itemcode">'+error.responseJSON.message+'</label>');
        });
    }

        
        $("#commentForm").validate({
          onfocusout: function(element, event) {
          if ($(element).attr('name') == "Itemcode") {
               get_itemcode(element,event)
           }
        },
                rules: {
                    Itemcode: {
                        required: true,
                    },
                    Itemtype: {
                        required: true,
                    },
                    // Itemdescription: {
                    //     required: true,
                    // },
                    HSNSAC: {
                      required: true,
                    },
                    PRSR: {
                        required: true,
                    },
                    Supplier: {
                        required: true,
                    },
                    Unit: {
                        required: true,
                    },
                    BasicValue: {
                        required: true,
                    },
                    StockQty: {
                        required: true,
                    },
                    OpenPOQty: {
                        required: true,
                    },
                    ActualorderQty: {
                        required: true,
                    },
                    MinLevel: {
                        required: true,
                    },
                    MaxLevel: {
                        required: true,
                    },
                    Rate: {
                        required: true,
                    },
                    Discount: {
                        required: true,
                    },
                    GST: {
                        required: true,
                    },
                    Currency: {
                        required: true,
                    },
                    Netvalue: {
                        required: true,
                    },
                    Remarks: {
                        required: true,
                    }
                }
            });

                $('.Supplier').select2({
                    placeholder: 'Choose one',
                    searchInputPlaceholder: 'Search',
                    minimumInputLength: 3,
                    allowClear: true,
                    ajax: {
                    url: "{{url('inventory/suppliersearch')}}",
                    processResults: function (data) {
                      return {
                        results: data
                      };
                    }
                  }
                });
      });
    </script>


@stop




{{-- Stock Qty *
Open PO Qty *
Actual order Qty * 


Basic Value  - man

Net value = (Basic Value  - Discount (% to int))  +  gst




--}}
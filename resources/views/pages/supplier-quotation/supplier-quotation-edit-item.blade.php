@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/supplier-quotation') }}" style="color: #596881;">SUPPLIER QUOTATION
                    </a></span>
                    <!-- <span><a href="{{ url('inventory/get-purchase-reqisition') }}" style="color: #596881;">EDIT SUPPLIER QUOTATION ITEM </a></span> -->
                    <!-- <span><a href="">{{ request()->item ? 'Edit' : 'Add' }} Supplier quotation item</a></span> -->
                    <span><a> Edit Supplier Quotation Item</a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                    <!-- {{ request()->item ? 'Edit' : 'Add' }}  Supplier quotation item</h4> -->
                    Edit Supplier Quotation Item
                </h4>
                <!-- <div class="az-dashboard-nav">
                    <nav class="nav">
                        <a class="nav-link" href="{{ url('inventory/edit-purchase-reqisition?pr_id=' . request()->pr_id) }}">Supplier Quotation</a>
                        <a class="nav-link  active" @if (request()->pr_id) href="{{ url('inventory/get-purchase-reqisition-item?pr_id=' . request()->pr_id) }}" @endif>Edit Supplier Quotation item </a>
                        <a class="nav-link  " href=""> </a>
                    </nav>

                </div> -->

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
                                    <input type="text" class="form-control" name="Itemcode" id="Itemcode" placeholder="Item code">
                                        <input type="hidden"  name="Itemcodehidden" id="Itemcodehidden" >
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Item Name * </label>
                                    <input type="text" readonly class="form-control"  name="Itemtype" id="Itemtype"
                                        placeholder="Item type">
                                        <input type="hidden"  name="Itemtypehidden" id="Itemtypehidden" >
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Item description *</label>
                                    <textarea  readonly class="form-control" id="Itemdescription"
                                        name="Itemdescription" placeholder=""></textarea>

                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>HSN code *</label>
                                    <input type="text" readonly  class="form-control" name="HSN" id="HSNSAC"
                                        placeholder="">
                                </div><!-- form-group -->

                                

                                <!-- <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Supplier *</label>
                                    <select class="form-control Supplier" name="Supplier">
                                      @if(!empty($datas))
                                    <option value=" ">{{$datas['supplier']['vendor_name']}}</option>
                                      @endif
                                    </select>
                                </div> -->


             
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Basic Value *</label>
                                    <input type="text" readonly class="form-control"  id="StockQty" name="StockQty"
                                        placeholder="">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Discount Percent *</label>
                                    <input type="text" readonly class="form-control"  id="StockQty" name="StockQty"
                                        placeholder="">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Discount Value *</label>
                                    <input type="text" readonly class="form-control" value="" id="OpenPOQty" name="OpenPOQty"
                                        placeholder="">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> CGST *</label>
                                    <input type="text" class="form-control" value="" name="ActualorderQty"
                                        placeholder="Actual order Qty">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> SGST *</label>
                                    <input type="text" readonly class="form-control" value="" id="MinLevel" name="MinLevel"
                                        placeholder="">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> IGST *</label>
                                    <input type="text" readonly class="form-control" value="" id="MaxLevel" name="MaxLevel"
                                        placeholder="">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Unit *</label>
                                    <select class="form-control Supplier" name="Supplier">
                                     <option value=" ">KG</option>
                                     <option>Litre</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                  <label>Quantity *</label>
                                  <input type="text" name="quantity" value="" class="form-control"
                                      placeholder="Quantity">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Rate *</label>
                                    <input type="text" class="form-control" value="" name="rate" id="rate" placeholder="Rate">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Discount *</label>
                                    <input type="text" class="form-control" value="" name="discount" id="discount" placeholder="Discount">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Specification *</label>
                                    <input type="text" class="form-control" value="" id="Discount" name="Discount"
                                        placeholder="">
                                </div>
                                
                                                                

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                      role="status" aria-hidden="true"></span> <i
                                            class="fas fa-save"></i>
                                            {{ request()->item ? 'Update' : 'Save' }}
                                            </button>
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
        $('#Netvalue').val(netvalue.toFixed(2));
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


        
    function get_itemcode(element){
        $('.spinner-button').show();
        $('#Itemcode-error').remove();
        $('#Itemdescription').text('');
        $('#Itemtype').val('');
        $('#HSNSAC').val('');
        $('#Unit').val('');
        $('#MinLevel').val('');
        $('#MaxLevel').val('');
        $('#Itemtypehidden').val('');
     
        $.get( "{{ url('inventory/itemcodesearch') }}/"+element, function(res) {
          $('.spinner-button').hide();
          if(res.discription){
            $('#Itemdescription').text(res.discription);
          }
          if(res.item_type.type_name){
            $('#Itemtype').val(res.item_type.type_name);
            $('#Itemtypehidden').val(res.item_type.id);
            
          }
          if(res.hsn_code){
            $('#HSNSAC').val(res.hsn_code);
          }
          if(res.receipt_unit.unit_name){
            $('#Unit').val(res.receipt_unit.unit_name);
            $('#Unithidden').val(res.receipt_unit.id);
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
          if(res.id){
            $('#Itemcodehidden').val(res.id);
          }
          

        }).fail(function(error) {
          $('.spinner-button').hide();
          $('#Itemcode').after('<label id="Itemcode-error" class="error Itemcode-error" for="Itemcode">'+error.responseJSON.message+'</label>');
        });
    }

        
        $("#commentForm").validate({
          onfocusout: function(element, event) {
          if ($(element).attr('name') == "Itemcode") {
              get_itemcode($(element).val());
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
                        number: true
                    },
                    StockQty: {
                        required: true,
                    },
                    OpenPOQty: {
                        required: true,
                    },
                    ActualorderQty: {
                        required: true,
                        number: true
                    },
                    MinLevel: {
                        required: true,
                    },
                    MaxLevel: {
                        required: true,
                    },
                    Rate: {
                        required: true,
                        number: true
                    },
                    Discount: {
                        required: true,
                        number: true
                    },
                    GST: {
                        required: true,
                    },
                    Currency: {
                        required: true,
                    },
                    Netvalue: {
                        required: true,
                        number: true
                    },
                    Remarks: {
                        required: true,
                    }
                },
                submitHandler: function(form) {
                $('.spinner-button').show();
                 $.get( "{{ url('inventory/itemcodesearch') }}/"+$('#Itemcode').val(), function(res) {
                  if(res.id){
                      form.submit();
                  }else{
                    alert('item code is not valid');
                  }
                 }).fail(function(error) {
                  alert('item code is not valid');
                 });

                    
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
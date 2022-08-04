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
                            REQUISITION</a></span>
                    <span><a href="">{{ request()->item ? 'Edit' : 'Add' }} purchase requisition master</a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                    {{ request()->item ? 'Edit' : 'Add' }} purchase requisition master</h4>
                <div class="az-dashboard-nav">
                    <nav class="nav">
                        <a class="nav-link    "
                            href="{{ url('inventory/edit-purchase-reqisition?pr_id=' . request()->pr_id) }}">Purchase
                            requisition master </a>
                        <a class="nav-link  active" @if (request()->pr_id) href="{{ url('inventory/get-purchase-reqisition-item?pr_id=' . request()->pr_id) }}" @endif> Purchase requisition item </a>
                        <a class="nav-link  " href=""> </a>
                    </nav>

                </div>

                <div class="row">

                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">

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
                                    <label for="exampleInputEmail1">Item code * </label>


                                    <select class="form-control Item-code" name="Itemcode" id="Itemcode">
                                        {{-- @if (!empty($datas))
                                            <option value="{{$datas['supplier']['id']}}">{{$datas['supplier']['vendor_name']}}</option>
                                              @endif --}}
                                    </select>



                                    {{-- <input type="text" class="form-control" name="Itemcode" id="Itemcode" 
                                    value="{{ (!empty($datas)) ? $datas['item_code']['item_code'] : ''}}"
                                        placeholder="Item code">
                                        <input type="hidden" value="{{ (!empty($datas)) ? $datas['item_code']['id'] : ''}}" name="Itemcodehidden" id="Itemcodehidden" > --}}
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Item type * </label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['item_code']['item_type']['type_name'] : '' }}"
                                        name="Itemtype" id="Itemtype" placeholder="Item type">
                                    <input type="hidden"
                                        value="{{ !empty($datas) ? $datas['item_code']['item_type']['id'] : '' }}"
                                        name="Itemtypehidden" id="Itemtypehidden">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Item description *</label>
                                    <textarea readonly class="form-control" id="Itemdescription" name="Itemdescription"
                                        placeholder="">{{ !empty($datas) ? $datas['item_code']['discription'] : '' }}</textarea>

                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>HSN/SAC code *</label>
                                    <input type="text" readonly
                                        value="{{ !empty($datas) ? $datas['item_code']['hsn_code'] : '' }}"
                                        class="form-control" name="HSNSAC" id="HSNSAC" placeholder="">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Supplier *</label>
                                    <select class="form-control Supplier" name="Supplier">
                                        @if (!empty($datas))
                                            <option value="{{ $datas['supplier']['id'] }}">
                                                {{ $datas['supplier']['vendor_name'] }}</option>
                                        @endif
                                    </select>
                                </div>


                                <!-- form-group -->
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Unit name*</label>
                                    <input type="text" readonly
                                        value="{{ !empty($datas) ? $datas['item_code']['receipt_unit']['unit_name'] : '' }}"
                                        class="form-control" name="Unit" id="Unit" placeholder="">
                                    <input type="hidden"
                                        value="{{ !empty($datas) ? $datas['item_code']['receipt_unit']['id'] : '' }}"
                                        name="Unithidden" id="Unithidden">
                                </div><!-- form-group -->



                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Stock Qty *</label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['item_code']['availble_quantity'] : '' }}"
                                        id="StockQty" name="StockQty" placeholder="">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Open PO Qty *</label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['item_code']['opening_quantity'] : '' }}"
                                        id="OpenPOQty" name="OpenPOQty" placeholder="">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Actual order Qty *</label>
                                    <input type="text" class="form-control"
                                        value="{{ !empty($datas) ? $datas['actual_order_qty'] : '' }}"
                                        name="ActualorderQty" placeholder="Actual order Qty">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Min Level *</label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['item_code']['min_stock'] : '' }}"
                                        id="MinLevel" name="MinLevel" placeholder="">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Max Level *</label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['item_code']['max_stock'] : '' }}"
                                        id="MaxLevel" name="MaxLevel" placeholder="">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Basic Value *</label>
                                    <input type="text" name="BasicValue"
                                        value="{{ !empty($datas) ? $datas['basic_value'] : '' }}" class="form-control"
                                        placeholder="Basic Value">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Rate *</label>
                                    <input type="text" class="form-control"
                                        value="{{ !empty($datas) ? $datas['rate'] : '' }}" name="Rate" id="Rate"
                                        placeholder="Rate">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Discount ( % ) *</label>
                                    <input type="text" class="form-control"
                                        value="{{ !empty($datas) ? $datas['discount_percent'] : '' }}" id="Discount"
                                        name="Discount" placeholder="Discount ( % )">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> GST *</label>
                                    {{-- <input type="text" class="form-control" value="" name="GST" id="GST" placeholder="GST"> --}}
                                    <select class="form-control GST" name="GST">
                                        <option value="">--- select one ---</option>
                                        @foreach ($data['gst'] as $item)
                                            <option value="{{ $item['gst'] }}" @if (!empty($datas))  @if ($item['gst']==$datas['gst'])
                                                selected @endif
                                        @endif
                                        >{{ $item['gst'] }} %</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Currency *</label>
                                    <select class="form-control Currency" name="Currency">
                                        <option value="">--- select one ---</option>
                                        @foreach (['USD', 'INR'] as $item)
                                            <option value="{{ $item }}" @if (!empty($datas))  @if ($item==$datas['currency'])
                                                selected @endif
                                        @endif>{{ $item }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Net value *</label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['net_value'] : '' }}" id="Netvalue"
                                        name="Netvalue" placeholder="">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Reason for Remarks *</label>
                                    <textarea value="" class="form-control" name="Remarks"
                                        placeholder="Reason for Remarks">{{ !empty($datas) ? $datas['remarks'] : '' }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                            class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
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

                    $("#commentForm").validate({
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
                                // HSNSAC: {
                                //   required: true,
                                // },
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
                                // StockQty: {
                                //     required: true,
                                // },
                                // OpenPOQty: {
                                //     required: true,
                                // },
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
                                  form.submit();
                            }
                        });

                            $('.Supplier').select2({
                                placeholder: 'Choose one',
                                searchInputPlaceholder: 'Search',
                                minimumInputLength: 3,
                                allowClear: true,
                                ajax: {
                                url: "{{ url('inventory/suppliersearch') }}",
                                processResults: function (data) {
                                  return {results: data
                                  };
                                }
                              }
                            });

                            $('.Item-code').select2({
                                placeholder: 'Choose one',
                                searchInputPlaceholder: 'Search',
                                minimumInputLength: 6,
                                allowClear: true,
                                ajax: {
                                url: "{{ url('inventory/itemcodesearch') }}",
                                processResults: function (data) {

                                  return { results: data };
                                }
                              }
                            }).on('change', function (e) {
             
                    $('#Itemcode-error').remove();
                    $('#Itemdescription').text('');
                    $('#Itemtype').val('');
                    $('#HSNSAC').val('');
                    $('#Unit').val('');
                    $('#MinLevel').val('');
                    $('#MaxLevel').val('');
                    $('#Itemtypehidden').val('');
                 
                      let res = $(this).select2('data')[0];
                          if(res.short_description){
                              $('#Itemdescription').text(res.short_description);
                          }
                          if(res.type_name){
                            $('#Itemtype').val(res.type_name);
                          }
                          if(res.hsn_code){
                            $('#HSNSAC').val(res.hsn_code);
                          }
                          if(res.unit_name){
                            $('#Unit').val(res.unit_name);
                          }
                          if(res.min_stock || res.min_stock == 0 ){
                            $('#MinLevel').val(res.min_stock);
                          }
                          if(res.max_stock || res.max_stock == 0){
                            $('#MaxLevel').val(res.max_stock);
                          }
                          if(res.opening_quantity || res.opening_quantity == 0){
                            $('#OpenPOQty').val(res.opening_quantity);
                          }
                          if(res.availble_quantity || res.availble_quantity == 0){
                            $('#StockQty').val(res.availble_quantity);
                          }
                          if(res.id){
                            $('#Itemcodehidden').val(res.id);
                          }
                      });       
                  });

                </script>


@stop

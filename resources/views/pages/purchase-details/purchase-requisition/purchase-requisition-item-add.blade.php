@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/get-purchase-reqisition') }}" style="color: #596881;">PURCHASE
                            DETAILS</a></span>
                    <span><a href="{{ url('inventory/get-purchase-reqisition') }}" style="color: #596881;">
                            REQUISITION</a></span>
                    <span><a href="">
                    @if(request()->pr_id)
                        {{ request()->item ? 'Edit' : 'Add' }} purchase requisition item ( {{$data["master"]['pr_no']}}  )
                    @endif
                    @if(request()->sr_id)
                        {{ request()->item ? 'Edit' : 'Add' }} service requisition item ( {{$data["master"]['pr_no']}}  )
                    @endif
                    </a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                    @if(request()->pr_id)
                        {{ request()->item ? 'Edit' : 'Add' }} purchase requisition item ( {{$data["master"]['pr_no']}}  )
                    @endif
                    @if(request()->sr_id)
                            {{ request()->item ? 'Edit' : 'Add' }} service requisition item ( {{$data["master"]['pr_no']}}  )
                    @endif
                </h4>
                <div class="az-dashboard-nav">
                    <nav class="nav">
                    @if(request()->pr_id)
                        <a class="nav-link    "
                            href="{{ url('inventory/edit-purchase-reqisition?pr_id=' . request()->pr_id) }}">Purchase
                            requisition master </a>
                        <a class="nav-link  active" @if (request()->pr_id) href="{{ url('inventory/get-purchase-reqisition-item?pr_id=' . request()->pr_id) }}" @endif> Purchase requisition item </a>
                        <a class="nav-link  " href=""> </a>
                    @endif
                    @if(request()->sr_id)
                        <a class="nav-link    "
                            href="{{ url('inventory/edit-purchase-reqisition?sr_id=' . request()->sr_id) }}">Service 
                            requisition master </a>
                        <a class="nav-link  active" @if (request()->sr_id) href="{{ url('inventory/get-purchase-reqisition-item?sr_id=' . request()->sr_id) }}" @endif> Service requisition item </a>
                        <a class="nav-link  " href=""> </a>
                    @endif
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

                            @foreach ($errors->all() as $errorr)
                    <div class="alert alert-danger "  role="alert" style="width: 100%;">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      {{ $errorr }}
                    </div>
                   @endforeach 
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label for="exampleInputEmail1">Item code * </label>


                                    <select class="form-control Item-code" name="Itemcode" id="Itemcode">
                                        @if (!empty($datas["item"]))
                                            <option value="{{$datas["item"]['Item_code']}}" selected>{{$datas["item"]['item_code']}}</option>
                                        @endif
                                    </select>



                                    {{-- <input type="text" class="form-control" name="Itemcode" id="Itemcode" 
                                    value="{{ (!empty($datas)) ? $datas['item']['item_code'] : ''}}"
                                        placeholder="Item code">
                                        <input type="hidden" value="{{ (!empty($datas)) ? $datas['item_code'] : ''}}" name="Itemcodehidden" id="Itemcodehidden" > --}}
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Item type * </label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['type_name'] : '' }}"
                                        name="Itemtype" id="Itemtype" placeholder="Item type">
                                    <input type="hidden"
                                        value="{{ !empty($datas) ? $datas['item']['item_type_id'] : '' }}"
                                        name="Itemtypehidden" id="Itemtypehidden">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Item description *</label>
                                    <textarea readonly class="form-control" id="Itemdescription" name="Itemdescription"
                                        placeholder="">{{ !empty($datas) ? $datas['item']['discription'] : '' }}</textarea>

                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>HSN/SAC code *</label>
                                    <input type="text" readonly
                                        value="{{ !empty($datas) ? $datas['item']['hsn_code'] : '' }}"
                                        class="form-control" name="HSNSAC" id="HSNSAC" placeholder="">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Supplier *</label>
                                    <select class="form-control Supplier" name="Supplier">
                                        @if (!empty($datas["item"]))
                                            <option value="{{ $datas['item']['supplierId'] }}" selected>
                                            {{ $datas['item']['vendorId'] }} - {{ $datas['item']['vendorName'] }}</option>
                                        @endif
                                    </select>
                                </div>


                                <!-- form-group -->
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Unit name*</label>
                                    <input type="text" readonly
                                        value="{{ !empty($datas) ? $datas['item']['unit_name'] : '' }}"
                                        class="form-control" name="Unit" id="Unit" placeholder="">
                                    <input type="hidden"
                                        value="{{ !empty($datas) ? $datas['item']['unit_id'] : '' }}"
                                        name="Unithidden" id="Unithidden">
                                </div><!-- form-group -->


                                @if(request()->pr_id)
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Stock Qty *</label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['availble_quantity'] : '' }}"
                                        id="StockQty" name="StockQty" placeholder="">
                                </div>
                                @endif

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Open PO Qty *</label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['opening_quantity'] : '' }}"
                                        id="OpenPOQty" name="OpenPOQty" placeholder="">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Actual order Qty *</label>
                                    <input type="text" class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['actual_order_qty'] : '' }}"
                                        name="ActualorderQty" placeholder="Actual order Qty">
                                </div>
                                @if(request()->pr_id)
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Min Level *</label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['min_stock'] : '' }}"
                                        id="MinLevel" name="MinLevel" placeholder="">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Max Level *</label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['max_stock'] : '' }}"
                                        id="MaxLevel" name="MaxLevel" placeholder="">
                                </div>
                                @endif
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Basic Value *</label>
                                    <input type="text" name="BasicValue"
                                        value="{{ !empty($datas) ? $datas['item']['basic_value'] : '' }}" class="form-control"
                                        placeholder="Basic Value">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Rate *</label>
                                    <input type="text" class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['rate'] : '' }}" name="Rate" id="Rate"
                                        placeholder="Rate">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Discount ( % ) *</label>
                                    <input type="text" class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['discount_percent'] : '' }}" id="Discount"
                                        name="Discount" placeholder="Discount ( % )">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> GST *</label>
                                    {{-- <input type="text" class="form-control" value="" name="GST" id="GST" placeholder="GST"> --}}
                                    <select class="form-control GST" name="GST">
                                        <option value="">--- select one ---</option>
                                        @foreach ($data['gst'] as $item)
                                            <option value="{{ $item['id'] }}" @if (!empty($datas))  @if ($item['gst']==$datas['item']['gst'])
                                                selected @endif
                                        @endif
                                        >{{ $item['gst'] }} %</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> CGST ( % ) *</label>
                                    <input type="text" class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['discount_percent'] : '' }}" id="Discount"
                                        name="CGST" placeholder="CGST ( % )">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> SGST ( % ) *</label>
                                    <input type="text" class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['discount_percent'] : '' }}" id="Discount"
                                        name="SGST" placeholder="SGST ( % )">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> UTGST ( % ) *</label>
                                    <input type="text" class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['discount_percent'] : '' }}" id="Discount"
                                        name="UTGST" placeholder="UTGST ( % )">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> IGST ( % ) *</label>
                                    <input type="text" class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['discount_percent'] : '' }}" id="Discount"
                                        name="IGST" placeholder="IGST ( % )">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Currency *</label>
                                    <select class="form-control Currency" name="Currency">
                                        <option value="">--- select one ---</option>
                                        @foreach ($data["currency"] as $item)
                                            <option value="{{ $item['currency_id']}}" @if (!empty($datas))  @if ($item['currency_code']==$datas['item']['currency_code'])
                                                selected @endif
                                        @endif>{{ $item['currency_code'] }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Net value *</label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['net_value'] : '' }}" id="Netvalue"
                                        name="Netvalue" placeholder="">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Reason for Remarks *</label>
                                    <textarea value="" class="form-control" name="Remarks"
                                        placeholder="Reason for Remarks">{{ !empty($datas) ? $datas['item']['remarks'] : '' }}</textarea>
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
                                // Itemtype: {
                                //     required: true,
                                // },
                                // Itemdescription: {
                                //     required: true,
                                // },
                                // HSNSAC: {
                                //   required: true,
                                // },
                                // PRSR: {
                                //     required: true,
                                // },
                                Supplier: {
                                    required: true,
                                },
                                // Unit: {
                                //     required: true,
                                // },
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
                                // MinLevel: {
                                //     required: true,
                                // },
                                // MaxLevel: {
                                //     required: true,
                                // },
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

@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/supplier-quotation') }}">SUPPLIER QUOTATION </a></span>
                     <span> <a href="{{ url('inventory/view-supplier-quotation-items/'.$rq_no.'/'.$supp_id) }}">Supplier Quotation Items</a></span>
                      <span><a> Edit Supplier Quotation Item</a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                    <!-- {{ request()->item ? 'Edit' : 'Add' }}  Supplier quotation item</h4> -->
                    Edit Supplier Quotation Item for  ( {{$data['get_item_single']['vendor_id'] }} - {{$data['get_item_single']['vendor_name'] }} ) 
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
                                        <i class="fas fa-address-card"></i> Item details </label>
                                    <div class="form-devider"></div>
                                </div>
                            </div>


                            <div class="row">

                                <table class="table table-bordered mg-b-0">
                    
                                    <tbody>
                                      <tr>
                                        <th scope="row">Item Code</th>
                                        <td>{{$data['get_item_single']['item_code']}}</td>
                                        <th scope="row">Item description </th>
                                        <td>{{$data['get_item_single']['short_description']}}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Item Name</th> 
                                        <td>{{$data['get_item_single']['item_name']}}</td>
                                        <th scope="row">HSN code</th>
                                        <td>{{$data['get_item_single']['hsn_code']}}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Requested QTY</th>
                                        <td>{{$data['get_item_single']['approved_qty']}}</td>
                                        <th scope="row">Unit</th>
                                        <td>{{$data['get_item_single']['unit_name']}}</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <br>
                                
                        @if (Session::get('success'))
                        <div class="alert alert-success " style="width: 100%;">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                        </div>
                        @endif
                        @foreach ($errors->all() as $errorr)
                        <div class="alert alert-danger "  role="alert" style="width: 100%;">
                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ $errorr }}
                        </div>
                       @endforeach
                                  <div class="row">
                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                            <i class="fas fa-address-card"></i> Supplier required details </label>
                                        <div class="form-devider"></div>
                                    </div>
                                </div>
                                <div class="row">   
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                  <label>Quantity *</label>
                                <input type="text" name="quantity" value="@if($data['get_item_single']['supp_quantity']) {{$data['get_item_single']['supp_quantity']}} @else {{$data['get_item_single']['approved_qty']}} @endif" class="form-control" placeholder="Quantity">
                              
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Rate *</label>
                                    @php 
                                        use Carbon\Carbon;
                                        $today_date = Carbon::now();
                                    @endphp
                                    @if($data['get_item_single']['supp_rate']!=NULL)
                                    <input type="text" class="form-control" value="{{$data['get_item_single']['supp_rate']}}" name="rate" id="rate" placeholder="Rate">
                                    @elseif($data['get_item_single']['is_fixed_rate']==1 && $today_date->format("Y-m-d") <= $data['get_item_single']['rate_expiry_date'])
                                    <input type="text" class="form-control" value="{{$data['get_item_single']['company_purchase_rate']}}" name="rate" id="rate" placeholder="Rate">
                                    @else
                                    <input type="text" class="form-control" value="" name="rate" id="rate" placeholder="Rate">
                                    @endif

                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Discount (%) *</label>
                                    <input type="text" class="form-control" value="{{$data['get_item_single']['supplier_discount']}}" name="discount" id="discount" placeholder="Discount">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" style="float:left;">
											<label> IGST ( % ) </label>
											<input type="hidden" name="gst" id="gst-id" value="@if(!empty($data['get_item_single']['gst'])) {{$data['get_item_single']['gst']}}  @endif">
											<select class="form-control IGST" id="IGST" name="IGST">
												<option value="">--- select one ---</option>
												@if(!empty($data))
												@if($data['get_item_single']['igst']==0)
													<option class="edit-zero" selected>0%</option>
												@endif
												@endif
												<option class="zero-option-igst" value="" style="display:none;">0%</option>
												@foreach ($data['gst'] as $item)
													@if($item['igst']!=0)
													<option value="{{ $item['id'] }}" @if(!empty($data))  @if($item['igst']==$data['get_item_single']['igst'])
														selected @endif @endif >{{ $item['igst'] }} %</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" style="float:left;">
											<label> SGST ( % ) </label>
											<select class="form-control SGST" id="SGST" name="SGST">
												<option value="">--- select one ---</option>
												@if(!empty($data))
												@if($data['get_item_single']['sgst']==0)
													<option class="edit-zero"  selected>0%</option>
												@endif
												@endif
												<option  class="zero-option" value="" style="display:none;">0%</option>
												@foreach ($data['gst'] as $item)
													@if($item['sgst']!=0)
													<option value="{{ $item['id'] }}" @if(!empty($data))  @if($item['sgst']==$data['get_item_single']['sgst'])
														selected @endif @endif >{{ $item['sgst'] }} %</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" style="float:left;">
											<label> CGST ( % ) </label>
											<select class="form-control CGST" id="CGST" name="CGST">
												<option value="">--- select one ---</option>
												@if(!empty($data))
												@if($data['get_item_single']['cgst']==0)
													<option class="edit-zero" selected>0%</option>
												@endif
												@endif
												<option class="zero-option" value="" style="display:none;">0%</option>
												@foreach ($data['gst'] as $item)
													@if($item['cgst']!=0)
													<option value="{{ $item['id'] }}" @if(!empty($data))  @if($item['cgst']==$data['get_item_single']['cgst'])
														selected @endif @endif >{{ $item['cgst'] }} %</option>
													@endif
												@endforeach
											</select>
										</div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Currency *</label>
                                    <select class="form-control currency" name="currency">
                                        <option value="">--- select one ---</option>
                                        @foreach ($data["currency"] as $item)
                                            <option value="{{ $item['currency_id']}}" @if (!empty($data))  @if ($item['currency_code']==$data['get_item_single']['currency_code'])
                                                selected @endif
                                        @endif>{{ $item['currency_code'] }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Specification *</label>
                                    <textarea  class="form-control"  id="Specification" name="Specification" placeholder="Specification">{{$data['get_item_single']['supp_specification']}}</textarea>
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Remarks </label>
                                    <textarea  class="form-control"  id="Remarks" name="Remarks" placeholder="Remarks">{{$data['get_item_single']['remarks']}}</textarea>
                                </div>
                                
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
                         
                        </form>
                        <div class="form-devider"></div>
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
        $("#commentForm").validate({
                rules: {
                    quantity: {
                        required: true,
                        //number: true,
                    },
                    rate: {
                        required: true,
                        number: true,
                    },
                    discount: {
                      required: true,
                      number: true,
                    },
                    Specification: {
                        required: true,
                    },
                    // Remarks: {
                    //   required: true,
                    // },
                },
                submitHandler: function(form) {
                $('.spinner-button').show();
                      form.submit();
                    
            }
            });
            $('#IGST').on('change', function() {
                let igst = $(this).val();
                let igst_percent = $(this).find('option:selected').text();
                var igst_val = parseInt(igst_percent.split('%', 1)[0]);
               
                let Rate = $('#Rate').val();
                let actual_qty = $('#ActualorderQty').val();
                let total = Rate*actual_qty;
                let Discount = $('#Discount').val() ? $('#Discount').val() : 0;
                let discount_rate = (actual_qty*Rate*Discount)/100;
                let netvalue = (total-discount_rate);

                new_net_val = (netvalue*igst_val/100)+netvalue;
                $('#Netvalue').val(new_net_val.toFixed(2));

                $('.append-option').remove();
                $('.edit-zero').remove();
                $('#gst-id').val('');
                // $('#CGST').load();
                // $('#SGST').load();
                $.ajax ({
                    type: 'GET',
                    url: "{{url('getSGSTandCGST')}}",
                    data: { id: '' + igst + '' },
                    success : function(data) {
                        $('#gst-id').val(data.id);
                       $('#SGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.sgst + '%</option>');
                       $('#CGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.cgst + '%</option>');
    
                    }
                });
                
            });
            $('#SGST').on('change', function() {
                $('#Netvalue').val('');
                let sgst = $(this).val();
                let sgst_percent = $(this).find('option:selected').text();
                var sgst_val = parseInt(sgst_percent.split('%', 1)[0]);
               
                let Rate = $('#Rate').val();
                let actual_qty = $('#ActualorderQty').val();
                let total = Rate*actual_qty;
                let Discount = $('#Discount').val() ? $('#Discount').val() : 0;
                let discount_rate = (actual_qty*Rate*Discount)/100;
                let netvalue = (total-discount_rate);
                
                new_net_val = (netvalue*sgst_val/100)+(netvalue*sgst_val/100)+netvalue;
                $('#Netvalue').val(new_net_val.toFixed(2));

                $('#gst-id').val('');
                $('.append-option').remove();
                $('.edit-zero').remove();
                $.ajax ({
                    type: 'GET',
                    url: "{{url('getSGSTandCGST')}}",
                    data: { id: '' + sgst + '' },
                    success : function(data) {
                        // if(data.igst==0){
                        //     $('.zero-option-igst').attr('value',data.id).show();
                        //     $('.zero-option-igst').attr('selected','selected').show();
                        // }
                        // $('.zero-option-igst').hide();
                       $('#gst-id').val(data.id);
                       $('#IGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.igst + '%</option>');
                       $('#CGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.cgst + '%</option>');
    
                    }
                });
                
            });
            $('#CGST').on('change', function() {
                $('#Netvalue').val('');
                let cgst = $(this).val();
                let cgst_percent = $(this).find('option:selected').text();
                var cgst_val = parseInt(cgst_percent.split('%', 1)[0]);
               
                let Rate = $('#Rate').val();
                let actual_qty = $('#ActualorderQty').val();
                let total = Rate*actual_qty;
                let Discount = $('#Discount').val() ? $('#Discount').val() : 0;
                let discount_rate = (actual_qty*Rate*Discount)/100;
                let netvalue = (total-discount_rate);
                
                new_net_val = (netvalue*cgst_val/100)+(netvalue*cgst_val/100)+netvalue;
                $('#Netvalue').val(new_net_val.toFixed(2));

                $('.append-option').remove();
                $('.edit-zero').remove();
                $('#gst-id').val('');
                //$("#SGST").selectmenu("refresh");
                $.ajax ({
                    type: 'GET',
                    url: "{{url('getSGSTandCGST')}}",
                    data: { id: '' + cgst + '' },
                    success : function(data) {
                        // if(data.igst==0){
                        //     $('.zero-option-igst').attr('value',data.id).show();
                        //     $('.zero-option-igst').attr('selected','selected').show();
                        // }
                        // $('.zero-option-igst').hide();
                        $('#gst-id').val(data.id);
                       $('#IGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.igst + '%</option>');
                       $('#SGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.sgst + '%</option>');
    
                    }
                });
                
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
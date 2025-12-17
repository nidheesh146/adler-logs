@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">Sales Return Note(SRN)</a></span> 
                <span><a href="" style="color: #596881;">Manual SRN</a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;">
                Manual SRN
            </h4>
            <div class="az-dashboard-nav">
           
            </div>

			<div class="row">
                    
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(11, 16, 27, 0.12);">
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
                   
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" id="commentForm" autocomplete="off" enctype='multipart/form-data'>
               

                        {{ csrf_field() }}  
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Basic details  
                                </label>
                                <div class="form-devider"></div>
                            </div>
                         </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer  *</label>
                                <select  class="form-control customer" name="customer">
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer Biiling Address</label>
                                <textarea name="billing_address" class="form-control" id="billing_address" readonly>@if(!empty($grs)) {{$grs['shipping_address']}} @endif</textarea>
                            </div> 
                            {{--<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer Shipping Address</label>
                                <textarea name="shipping_address"  class="form-control" id="shipping_address" readonly>@if(!empty($grs)) {{$grs['billing_address']}} @endif</textarea>
                            </div>--}}
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">DNI/EXI No.  *</label>
                                <input type="text" class="form-control dni_number" id="dni_number" name="dni_number">                          
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>DNI Date *</label>
                                <input type="text" value="" class="form-control datepicker" name="dni_date" placeholder="">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label for="exampleInputEmail1">Business Category *</label>
    <select class="form-control" id="product_category" name="product_category">
        <option value="">Select one...</option>
        @foreach($category as $cate)
            <option value="{{ $cate['id'] }}" 
                {{ $cate['category_name'] == 'OBM' ? 'selected' : '' }}>
                {{ $cate['category_name'] }}
            </option>
        @endforeach
    </select>
</div>
<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label for="exampleInputEmail1">Product Category *</label>
    <select class="form-control" id="new_product_category" name="new_product_category">
    <option value="">Select one...</option>
        @foreach($new_category as $cate)
            <option value="{{ $cate['id'] }}" 
                {{ $cate['category_name'] == 'ASD' ? 'selected' : '' }}>
                {{ $cate['category_name'] }}
            </option>
        @endforeach
    </select>
</div>

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Stock location Increase *</label>
                                <select class="form-control" name="location_increase" id="location_increase" required>
                                <option value="">Select one...</option>
                                    @foreach($locations as $loc)
                                    @if($loc['location_name']!='MAA (Material Allocation Area)' && $loc['location_name']!='Quarantine' && $loc['location_name']!='Consignment' && $loc['location_name']!='Loaner')
                                    <option value="{{$loc['id']}}">{{$loc['location_name']}}</option>
                                    
                                    @endif
                                    @endforeach
                                </select>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>SRN Date *</label>
                                <input type="text" value="" class="form-control datepicker" name="srn_date" placeholder="">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Remarks  </label>
                                <textarea type="text" class="form-control" name="remarks" value="" placeholder=""></textarea>
                            </div><!-- form-group -->
                           
                        </div> 
                        <div class="row">
    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
        <label for="other_charges">Other Charges *</label>
        <input type="number" class="form-control" name="other_charges" id="other_charges" placeholder="Enter amount" value="{{ old('other_charges') }}">
    </div>
    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
        <label for="charge_type">Charge Type *</label>
        <select class="form-control" name="charge_type" id="charge_type">
            <option value="">Select Type</option>
            <option value="percentage" {{ old('charge_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
            <option value="lumpsum" {{ old('charge_type') == 'lumpsum' ? 'selected' : '' }}>Lump Sum</option>
        </select>
    </div>
</div>

<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">SRN Items   *</label>
                                <input type="file" required class="form-control file" name="srn_file" id="file">
								<a href="{{ asset('uploads/srn_items_manual_entry.xlsx') }}" target="_blank" style="float: right; font-size: 10px;"> Download Template</a>
                            </div>

                    <div class="form-devider"></div>                    
                    <div class="row save-btn">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded save-button" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Save & Next
                                
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            

        </div>
        
	</div>
	<!-- az-content-body -->
</div>

<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script>

$(function(){
    'use strict'

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true
    });
    $(".datepicker").datepicker("setDate", new Date());
  //  .datepicker('update', new Date());

    $('.datepicker').mask('99-99-9999');
              

    $("#commentForm").validate({
            rules: {
                dni_no: {
                    required: true,
                },
                srn_date: {
                    required: true,
                }
                
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });
  });
  $(".customer").select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 4,
        allowClear: true,
        ajax: {
            url: "{{ url('fgs/customersearch') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    }).on('change', function (e) {
        $('#Itemcode-error').remove();
        $("#billing_address").text('');
        $("#shipping_address").text('');
        $('.dni_number option:gt(0)').remove();
        let res = $(this).select2('data')[0];
        if(typeof(res) != "undefined" )
        {
            if(res.billing_address){
                $("#billing_address").val(res.billing_address);
            }
            if(res.shipping_address){
                $("#shipping_address").val(res.shipping_address);
            }
            $.get("{{ url('fgs/SRN/find-dni-number-for-srn') }}?customer_id="+res.id,function(data){
                if(data!=0)
                {
                    $.each(data,function(key, value)
                    {
                      $(".dni_number").append('<option  value=' + value.id + '>' + value.text + '</option>');
                    });
                }
            });
        }
    });
    
    
    
      
   
    
</script>


@stop
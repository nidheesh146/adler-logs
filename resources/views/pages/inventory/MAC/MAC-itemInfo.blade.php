@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb">  
                <span><a href="" style="color: #596881;"> @if(str_starts_with($mac_number , 'MAC') ) Material @else Work Order @endif   Acceptance</a></span>
                <span><a href="">
                @if(str_starts_with($mac_number , 'MAC') ) MAC @else WOA @endif  Item
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                @if(str_starts_with($mac_number , 'MAC') ) MAC @else WOA @endif  Item
            </h4>
            <div class="az-dashboard-nav">
                
           
            </div>

			<div class="row">
                    
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
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
                    <form method="POST" id="commentForm" autocomplete="off" >
               

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
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Item code </label>
                                <input type="text" value="@if($data) {{$data['item_code']}} @endif" class="form-control" name="Type" readonly>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Item Type </label>
                                <input type="text" value="@if($data) {{$data['type_name']}} @endif" class="form-control" name="Type" readonly>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Item Description </label>
                                <textarea type="text" class="form-control" name="item_description" id="item_description" placeholder="Item Description" readonly>@if($data) {{$data['discription']}} @endif</textarea>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Lot Number *</label>
                                <input type="text" value="@if($data) {{$data['lot_number']}} @endif" class="form-control" name="lot_number" id="lot_number" placeholder="Lot Number" readonly>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Unit Rate @if($data['rate']) (Rate:{{$data['rate']}}, Discount: {{$data['discount']}} %) @endif </label>
                                <input type="text" value="@if($data['rate']) {{$data['rate']-($data['rate']*$data['discount']/100)}} @endif" class="form-control" name="rate" id="rate" placeholder="Supplier Unit Rate" readonly>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Accepted Quantity (Actual Quantity:{{$data['order_qty']}} {{$data['unit_name']}})</label>
                                <input type="text" value="@if($data['accepted_quantity']) {{$data['accepted_quantity']}} @endif" class="form-control " name="accepted_quantity" placeholder="Accepted Quantity" >
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Stock keeping Unit </label>
                                <input type="text" value="@if($data) {{$data['unit_name']}} @endif" class="form-control " name="unit" placeholder="Stk Kpng Unit" readonly>
                            </div><!-- form-group -->
                            
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Expiry date *</label>
                               
                                @if($data['is_expiry']==0)
                                <input type="text" class="form-control datepicker" value="" name="exp_date" placeholder="NA" disabled>
                                @else
                                <input type="text" class="form-control datepicker" value="@if($data) {{$data['mac_exp']}} @else {{date('d-m-Y')}} @endif" name="exp_date"  >
                                @endif
                            </div>
                            <!-- <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Conversion rate (INR) *</label>
                                <input type="text" class="form-control" value="" name="conversion_rate" id="conversion_rate" placeholder="Conversion rate">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Value in INR </label>
                                <input type="text" readonly class="form-control" value="" name="value_inr" id="value_inr" placeholder="Value in INR">
                            </div> -->
                        </div> 
                      

              
            
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                               Save
                                
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
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>

<script>
  $(function(){
    'use strict'

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true
    });
  //  .datepicker('update', new Date());

    $('.datepicker').mask('99-99-9999');
    $("#conversion_rate").on('input',function(){
        curr_net_value()
    });
    $("#currency").on('change',function(){
        curr_net_value()
    });
    function curr_net_value(){
        $("#value_inr").val(($("#rate").val()*$("#conversion_rate").val()).toFixed(2));
    }       

    $("#commentForm").validate({
            rules: {
                accepted_quantity: {
                    required: true,
                    //number: true,
                },
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });

    
  });
  $('.requestor').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
    });
</script>


@stop
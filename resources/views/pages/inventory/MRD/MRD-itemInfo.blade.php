@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb">  
                <span><a href="" style="color: #596881;">Material Rejection</a></span>
                <span><a href="">
                   MRD Item
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                    MRD Item
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
                                <input type="text" value="" class="form-control" name="Type" readonly>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Item Type </label>
                                <input type="text" value="" class="form-control" name="Type" readonly>
                            </div><!-- form-group -->
                            
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Lot Number *</label>
                                <input type="text" value="" class="form-control" name="lot_number" id="lot_number" placeholder="Lot Number" readonly>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Supplier Unit Rate </label>
                                <input type="text" value="" class="form-control" name="rate" id="rate" placeholder="Supplier Unit Rate" readonly>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Quantity </label>
                                <input type="text" value="" class="form-control " name="Quantity" placeholder="Quantity" >
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Stk Kpng Unit </label>
                                <input type="text" value="" class="form-control " name="unit" placeholder="Stk Kpng Unit" readonly>
                            </div><!-- form-group -->
                            
                            
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Conversion rate (INR) *</label>
                                <input type="text" class="form-control" value="" name="conversion_rate" id="conversion_rate" placeholder="Conversion rate">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Value in INR </label>
                                <input type="text" readonly class="form-control" value="" name="value_inr" id="value_inr" placeholder="Value in INR">
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Work Center *</label>
                                <input type="text" value=" " class="form-control" name="expiry_date" placeholder="Expiry Date">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Reason *</label>
                                <textarea type="text" value=" " class="form-control" name="expiry_date" placeholder=""></textarea>
                            </div><!-- form-group -->
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
                lot_number: {
                    required: true,
                },
                currency: {
                    required: true,
                },
                conversion_rate: {
                    required: true,
                },
                 value_inr: {
                    required: true,
                },
                expiry_control: {
                    required: true,
                },
                expiry_date: {
                    required: true,
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
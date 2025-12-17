@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb">  
                <span><a href="" style="color: #596881;"> RAW MATERIAL</a></span>
                <span><a href="">
                FIXED RATE RAW MATERIAL
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Fixed Rate Raw Material
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
                                <input type="text" value="{{$fixed_row_material->item_code}}" class="form-control " name="itemcode" id="itemcode" readonly>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Supplier </label>
                                <input type="text" value="{{$fixed_row_material->vendor_name}}" class="form-control " name="supplier" id="supplier" readonly>

                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Item Description </label>
                                <textarea type="text" class="form-control" name="item_description" id="item_description"  readonly>{{$fixed_row_material->discription}}</textarea>
                            </div><!-- form-group -->
                            
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label> Rate  </label>
                                <input type="number" value="{{$fixed_row_material->rate}}" class="form-control" name="rate" id="rate" >
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>IGST</label>
                                <input type="text" value="{{$fixed_row_material->igst}} " class="form-control " name="igst" id="igst" >
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>CGST</label>
                                <input type="text" value=" {{$fixed_row_material->cgst}}" class="form-control " name="cgst" id="cgst" >
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>SGST</label>
                                <input type="text" value="{{$fixed_row_material->sgst}}" class="form-control " name="sgst" id="sgst" >
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Discount</label>
                                <input type="text" value="{{$fixed_row_material->discount}}" class="form-control " name="discount" id="discount" >
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Currency</label>
                                <input type="text" value="{{$fixed_row_material->currency_code}}" class="form-control " name="currency" id="currency" readonly >
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Delivery within</label>
                                <input type="number" value="{{$fixed_row_material->delivery_within}}" class="form-control " name="delivery_within" id="delivery_within" >
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Expiry Date Start</label>
                                <input type="text" name="expiry_date_start" class="form-control datepicker"  value="{{date('d-m-Y',strtotime($fixed_row_material->rate_expiry_startdate))}}" > 
                            </div>

                            <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-6">
                                <label>Expiry Date End</label>
                                <input type="text" value="{{date('d-m-Y',strtotime($fixed_row_material->rate_expiry_enddate))}}" class="form-control datepicker" name="expiry_date_end" placeholder="">
                                <input type="hidden" value="{{$fixed_row_material->id}}" class="form-control " name="id" >

                            </div>
                            
                            <!-- <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Stock keeping Unit </label>
                                <input type="text" value="" class="form-control " name="unit" placeholder="Stk Kpng Unit" readonly>
                            </div>form-group -->
                            
                            
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
   // $(".min_date").datepicker("setDate", new Date());
    // $(".datepicker").datepicker("setDate", new Date());
  //  .datepicker('update', new Date());

    $('.datepicker').mask('99-99-9999');
    
    $(".Item-code").select2({
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
    });
    $('.supplier').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 3,
        allowClear: true,
        theme: "classic",
        ajax: {
            url: "{{url('inventory/suppliersearch')}}",
            processResults: function (data) {
                return {
                    results: data
                };
            }
        }
    });
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
  
</script>


@stop
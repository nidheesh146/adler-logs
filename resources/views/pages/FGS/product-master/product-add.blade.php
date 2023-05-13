@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb">  
                <span><a href="" style="color: #596881;"> PRODUCT - MASTER</a></span>
                <span><a href="">
                 ADD PRODUCT
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            PRODUCT - MASTER
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
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>SKU code *</label>
                                <input type="text" value="" class="form-control" name="sku_code" id="sku_code" placeholder="SKU Code">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Short Name </label>
                                <input type="text" value="" class="form-control" name="short_name" id="short_name" placeholder="Short Name">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label> Description *</label>
                                <textarea type="text" class="form-control" name="description" id="description" placeholder="Description" ></textarea>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>HSN Code *</label>
                                <input type="text" value="" class="form-control" name="hsn_code" id="hsn_code" placeholder="HSN Code" >
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>GS1 Code</label>
                                <input type="text" value="" class="form-control" name="gs1_code" id="gs1_code" placeholder="GS1 Code" >
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Product group *</label>
                                <select class="form-control" name="product_group">
                                    <option>..select one..</option>
                                    @foreach($data['product_group1'] as $grp)
                                    <option value="{{$grp->id}}">{{$grp->group_name}}</option>
                                    @endforeach
                                </select> 
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Product Type</label>
                                <select class="form-control" name="product_type">
                                    <option>..select one..</option>
                                    @foreach($data['product_type'] as $type)
                                    <option value="{{$type->id}}">{{$type->product_type_name}}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Product OEM</label>
                                <select class="form-control" name="product_oem">
                                    <option>..select one..</option>
                                    @foreach($data['product_oem'] as $oem)
                                    <option value="{{$oem->id}}">{{$oem->oem_name}}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Product Brand</label>
                                <select class="form-control" name="product_brand" id="product_brand">
                                    <option>..select one..</option>
                                    @foreach($data['product_productbrand'] as $brand)
                                    <option value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                    @endforeach
                                </select> 
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Pack Size </label>
                                <input type="text" value="" class="form-control " name="pack_size" placeholder="Pack Size " >
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Min Level </label>
                                <input type="text" value="" class="form-control " name="min_level" placeholder="Min Level" >
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Max Level </label>
                                <input type="text" value="" class="form-control " name="max_level" placeholder="Max Level " >
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Sterile/ Non-Sterile</label>
                                <select class="form-control" name="sterile_nonsterile" >
                                    <option>..select one..</option>
                                    <option value="1">Sterile</option>
                                    <option value="0">Non-Sterile</option>
                                </select> 
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>UOM</label>
                                <select class="form-control" name="uom" id="uom">
                                    <option>..select one..</option>
                                    <option value="nos">Nos</option>
                                </select> 
                            </div><!-- form-group -->
                            
                            
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
    $('#product_brand').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 3,
        allowClear: true,
        theme: "classic",
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
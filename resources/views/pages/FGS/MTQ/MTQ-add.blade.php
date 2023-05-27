@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">Material Transferred To Qurantine(MTQ)</a></span> 
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">
                   
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Material Transferred To Qurantine(MTQ)
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
                                <label for="exampleInputEmail1">Ref No.  *</label>
                                <input type="text" class="form-control" name="ref_no" value="" placeholder="Ref No">
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Ref Date  *</label>
                                <input type="text" class="form-control datepicker" name="ref_date" value="" placeholder="Ref Date">
                            </div>
                            <!-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label for="exampleInputEmail1">Product code * </label>
                                <select class="form-control product" name="product" id="product">
                                </select>
                            </div> -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Product Category  *</label>
                                <select class="form-control" name="product_category">
                                    <option value="">Select one...</option>
                                    @foreach($category as $cate)
                                    <option value="{{$cate['id']}}">{{$cate['category_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Stock Location1  *</label>
                                <select class="form-control" name="stock_location1">
                                    <option value="">Select one...</option>
                                    @foreach($locations as $loc)
                                    @if($loc['location_name']!='MAA (Material Allocation Area)' && $loc['location_name']!='Quarantine' && $loc['location_name']!='Consignment' && $loc['location_name']!='Loaner')
                                    <option value="{{$loc['id']}}">{{$loc['location_name']}}</option>
                                    @endif 
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Stock Location2  *</label>
                                <select class="form-control" name="stock_location2">
                                    @foreach($locations as $loc)
                                    @if($loc['location_name']=='Quarantine')
                                    <option value="{{$loc['id']}}">{{$loc['location_name']}}</option>
                                    @endif 
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <label>MTQ Date *</label>
                                <input type="text" value="" class="form-control datepicker" name="mtq_date" placeholder="">
                            </div><!-- form-group -->
                        </div> 
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit"  class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Save & Next
                                
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
    $(".datepicker").datepicker('update', new Date());

    $('.datepicker').mask('99-99-9999');
              

    $("#commentForm").validate({
            rules: {
                ref_no: {
                    required: true,
                },
                ref_date: {
                    required: true,
                },
                product_category: {
                    required: true,
                },
                stock_location1: {
                    required: true,
                },
                stock_location2: {
                    required: true,
                },
                mtq_date: {
                    required: true,
                }
                
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });
  });
//   $('.product').select2({
//         placeholder: 'Choose one',
//         searchInputPlaceholder: 'Search',
//         minimumInputLength: 4,
//         allowClear: true,
//         ajax: {
//             url: "{{ url('batchcard/productsearch') }}",
//             processResults: function (data) {
//                 return { results: data };
//             }
//         }
//     });
  
  
</script>


@stop
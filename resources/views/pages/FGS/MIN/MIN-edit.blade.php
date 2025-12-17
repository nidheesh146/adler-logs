@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;"> Material Issue Note(MIN)</a></span> 
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">
                   
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Material Issue Note(MIN)
            </h4>
            <div class="az-dashboard-nav">
           
            </div>

			<div class="row">
                    
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                @if(Session::get('error'))
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{Session::get('error')}}
                </div>
                @endif
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
                        <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <label>MIN Number</label>
                                <input type="text" readonly value="{{$min->min_number}}" class="form-control  " name="min" placeholder="">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Ref. No.  *</label>
                                <input type="text" class="form-control" name="ref_number" value="{{$min->ref_number}}" placeholder="Ref. No.">
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Ref Date  *</label>
                                <input type="text" class="form-control " name="ref_date" value="{{date('d-m-Y',strtotime($min->ref_date))}}" placeholder="">
                            </div>
                        
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Product Category  *</label>
                                @if($minitem)
                                <input type="text" readonly value="{{$min->category_id}}" class="form-control  " name="product_category" placeholder="{{$min->category_name}}">

                                @else
                                <select class="form-control" name="product_category">
                                    <option>Select one...</option>
                                    @foreach($category as $cate)
                                    <option value="{{$cate['id']}}" @if ($cate['id'] == $min->product_category) selected @endif>{{$cate['category_name']}}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Stock Location  *</label>
                                @if($minitem)
                                <input type="text" readonly value="{{$min->location_id}}" class="form-control  " name="stock_location" placeholder="{{$min->location_name}}">

                                @else
                                <select class="form-control" name="stock_location">
                                    <option>Select one...</option>
                                    @foreach($locations as $loc)
                                    @if($loc['location_name']!='MAA (Material Allocation Area)' && $loc['location_name']!='Quarantine' && $loc['location_name']!='Consignment' && $loc['location_name']!='Loaner')
                                    <option value="{{$loc['id']}}" @if ($loc['id'] == $min->stock_location) selected @endif>{{$loc['location_name']}}</option>
                                    @endif 
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <label>MIN Date *</label>
                                <input type="text" value="{{date('d-m-Y',strtotime($min->min_date))}}" class="form-control  " name="min_date" placeholder="">
                            </div><!-- form-group -->
                             <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label for="exampleInputEmail1">Remarks  </label>
                                    <textarea type="text" class="form-control" name="remarks" value=" " placeholder="">{{$min->remarks}}</textarea>
                                </div>

                            
                        </div>                       
            
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Update
                                
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
    $(document).ready(function() {
            $('form').submit(function() {
                $(this).find(':submit').prop('disabled', true);
            });
    });
  $(function(){
    'use strict'

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true
    });
   // $(".min_date").datepicker("setDate", new Date());
    $(".datepicker").datepicker("setDate", new Date());
  //  .datepicker('update', new Date());

    $('.datepicker').mask('99-99-9999');
              

    $("#commentForm").validate({
            rules: {
                Requestor: {
                    required: true,
                },
                Department: {
                    required: true,
                },
                Date: {
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
@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">Material Issue To Scrap(MIS)</a></span> 
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">
                   
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Material Issue To Scrap(MIS)
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
                                <label for="exampleInputEmail1">MTQ No.  *</label>
                                <select name="mtq_no" id="mtq_no" class="form-control">
                                </select>
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Business Category  *</label>
                                <select class="form-control" name="product_category" id="product_category">
                                    <option value="">Select one...</option>
                                    @foreach($category as $cate)
                                    <option value="{{$cate['id']}}">{{$cate['category_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label>Product Category *</label>
    <select class="form-control" name="new_product_category">
        <option>..select one..</option>
        @foreach($product_category as $category)
        <option value="{{ $category->id }}"
            @if(!empty($mtq) && ($mtq->new_product_category == $category->id)) selected="selected" @endif>
            {{ $category->category_name }}
        </option>
        @endforeach
    </select>
</div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Stock Location  *</label>
                                <select class="form-control" name="stock_location">
                                    @foreach($locations as $loc)
                                    @if($loc['location_name']=='Quarantine')
                                    <option value="{{$loc['id']}}">{{$loc['location_name']}}</option>
                                    @endif 
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <label>MIS Date *</label>
                                <input type="text" value="" class="form-control datepicker" name="mis_date" placeholder="">
                            </div><!-- form-group -->
                        </div> 
                        <div class="row save-btn" style="display:none;">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Save & Next
                                
                                </button>
                            </div>
                        </div>
                        <div class="form-devider"></div>
                    </form>
                    <div class="data-bindings">
                    </div>
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
    $(".datepicker").datepicker("setDate", new Date());
  //  .datepicker('update', new Date());

    $('.datepicker').mask('99-99-9999');
              

    $("#commentForm").validate({
            rules: {
                mtq_no: {
                    required: true,
                },
                product_category: {
                    required: true,
                },
                stock_location: {
                    required: true,
                },
                mis_date: {
                    required: true,
                }
                
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });
  });
  $('#mtq_no').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 2,
          allowClear: true,
          ajax: {
          url: "{{ url('fgs/MIS/find-mtq-number-for-mis') }}",
          processResults: function (data) {
            return { results: data };

          }
        }
      });
      $('#product_category').on('change', function (e) {
        $('.spinner-button').show();
        category = $('#product_category').val();
        let mtq_id =  $('#mtq_no').val();
        //alert(mtq_id);
        if(mtq_id){
          $.get("{{ url('fgs/MIS/find-mtq-info') }}?id="+mtq_id+"&category="+category,function(data){
            if(data.length>0)
            {
                $('.data-bindings').html(data);
                $('.spinner-button').hide();
                $('.save-btn').show();
            }
            else{
                $('.data-bindings').html('');
                $('.spinner-button').hide();
                $('.save-btn').hide();
            }
            
          });
        }else{
          $('.data-bindings').html('');
          $('.spinner-button').hide();
          $('.save-btn').hide();
        }
      });
  
</script>


@stop
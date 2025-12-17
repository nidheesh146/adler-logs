@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="" style="color: #596881;">Stock Adjustment</a></span>
             <span><a href="">Stock Adjustment - Increase(SAI)</a></span>
        </div>
        {{--@include('includes.fgs.sai-sad-tab') --}}
       
       
        <div class="row ">
            <div class="col-lg-12 col-xl-12 mg-t-20 mg-lg-t-0">
                <!-- <div class="card card-table-one" style="min-height: 500px;"> -->
                    <h4 class="az-content-title" style="font-size: 20px;">
                        Stock Adjustment - Increase(SAI)
                        <div class="right-button">
                            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/SAI-add')}}'" class="badge badge-pill badge-info "><i class="fas fa-plus"></i>&nbsp; SAI Add</button>
                        <div>  
                            
                        </div>
                    </div>
                    </h4>
                    @if (Session::get('succs'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('succs') }}
                    </div>
                    @endif
                    <div class="row row-sm mg-b-20 mg-lg-b-0">
                        <div class="table-responsive" style="margin-bottom: 13px;">
                            <table class="table table-bordered mg-b-0">
                                <tbody>
                                <tr>
                                <style>
                                .select2-container .select2-selection--single {
                                 height: 26px;
                                 /* width: 122px; */
                                 }
                                .select2-selection__rendered {
                                 font-size:12px;
                                  }
                                 </style>
                                 <form autocomplete="off" >
                                    <th scope="row">
                                        <div class="row filter_search" style="margin-left: 0px;">
                                            <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label  style="font-size: 12px;">SAI Number</label>
                                                    <input type="text" value="{{request()->get('sai_no')}}" id="sai_no" class="form-control" name="sai_no" placeholder="SAI No">
                                                </div> 
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label  style="font-size: 12px;">Location</label>
                                                    <input type="text" value="{{request()->get('location')}}" id="location" class="form-control" name="location" placeholder="Location">
                                                </div> 
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label  style="font-size: 12px;">SAI Month</label>
                                                    <input type="text" value="{{request()->get('from')}}" id="from" class="form-control" name="from" placeholder="From">
                                                </div>
                                               
                                            </div>
                                            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                                                        <label style="width: 100%;">&nbsp;</label>
                                                        <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                        @if(count(request()->all('')) > 2)
                                                        <a href="{{url()->current()}}" class="badge badge-pill badge-warning"    style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                        @endif
                                                </div> 
                                            </div>
                                        </div>
                                    </th>
                            </form>
                          </tr>
                       </tbody>
                    </table>
                </div>
            </div>
            <p class="az-content-text mg-b-20"></p>
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0">
                    <thead>
                        <tr>
                        <th>SAI Number</th>
						<th>SAI Date</th>
						<th>Location</th>
						<th>Created By</th>	
                        <th></th>		
                        </tr>
                    </thead>
                    <tbody id="prbody1">
                       @foreach($sai_datas as $sai)
                       <tr>
                           <td>{{$sai->sai_number}}</td>
                           <td>{{date('d-m-Y', strtotime($sai['sai_date']))}}</td>   
                           <td>{{$sai->location_name}}</td>
                           <td>{{$sai->f_name}}  {{$sai->l_name}}</td>
                           <td>
                                <a class="badge badge-info" style="font-size: 13px;" href="{{url('fgs/SAI/item-list/'.$sai["id"])}}" class="dropdown-item"><i class="fas fa-eye"></i> Item</a>
                                <a class="badge badge-default" style="font-size: 13px; color:black;border:solid black;border-width:thin;margin-top:2px;" href="{{url('fgs/SAI/pdf/'.$sai["id"])}}" target="_blank"><i class="fas fa-file-pdf" style='color:red'></i>&nbsp;PDF</a>
                            </td>
                        </tr>
                        @endforeach   
                    </tbody>
                    </table>
                    <div class="box-footer clearfix">
                    {{ $sai_datas->appends(request()->input())->links() }}
                    </div>
                </div><!-- table-responsive -->
                        <!-- </div>card -->
            </div>
        </div>
	</div>
</div>
	<!-- az-content-body -->
	<!-- Modal content-->

	
      

<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script>
    $("#commentForm").validate({
            rules: {
              role:{
                  required: true,
                  minlength: 1,
                  maxlength: 20
               },
               description:{
                  required: true,
                  minlength: 1,
                   maxlength: 115
               },
              
            }

     
          });
</script>
<script>
  $(function(){
    'use strict'
    var date = new Date();
    date.setDate(date.getDate());
    $(".datepicker").datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        // startDate: date,
        autoclose:true
    });

    $('#prbody').show();
  });
  
  $('.search-btn').on( "click", function(e)  {
        var sai_no = $('#sai_no').val();
        var from = $('#from').val();
        var location = $('#location').val();
        if(!sad_no & !from & !location)
        {
            e.preventDefault();
        }
    });

</script>

@stop
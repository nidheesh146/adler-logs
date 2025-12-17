@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="" style="color: #596881;">CDC Report</a></span>
             <span><a href="">CDC Report</a></span>
        </div>
        @include('includes.fgs.inv-trans-tab')
        <br/><br/>
       
        <div class="row ">
            <div class="col-lg-12 col-xl-12 mg-t-20 mg-lg-t-0">
                <!-- <div class="card card-table-one" style="min-height: 500px;"> -->
                    <h4 class="az-content-title" style="font-size: 20px;">
                        CDC Report
                        <div class="right-button">
                            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/CDC-report-export')}}'" class="badge badge-pill badge-info "><i class="fas fa-file-excel"></i> Report</button>
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
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label  style="font-size: 12px;">Doc Number</label>
                                                    <input type="text" value="{{request()->get('doc_no')}}" id="doc_no" class="form-control" name="doc_no" placeholder="Doc No">
                                                </div> 
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label  style="font-size: 12px;">OEF Number</label>
                                                    <input type="text" value="{{request()->get('oef_no')}}" id="oef_no" class="form-control" name="oef_no" placeholder="OEF No">
                                                </div> 
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label  style="font-size: 12px;">Customer</label>
                                                    <input type="text" value="{{request()->get('customer')}}" id="customer" class="form-control" name="customer" placeholder="Customer">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label  style="font-size: 12px;">BATCH No</label>
                                                    <input type="text" value="{{request()->get('batch_no')}}" id="batch_no" class="form-control" name="batch_no" placeholder="BATCH No">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label>SKU Code</label>
                                                    <input type="text" value="{{request()->get('sku_code')}}" name="sku_code"  id="sku_code" class="form-control" placeholder="SKU Code">
                                                </div><!-- form-group -->
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
                        <th>Doc Number</th>
						<th>Doc Date</th>
						<th>OEF Number</th>
						<th>OEF Date</th>
						<th>Ref Number</th>
						<th>Ref Date</th>
                        <th>SKU Code</th>
                        <!-- <th>Description</th> -->
                        <th>Batch Number</th>
                        <th>Qty.</th>
                        <th>TRANSACTION TYPE</th>
                        <th>TRANSACTION Condition</th>
                        <th>STOCK LOCATION1(DECREASE)</th>
                        <th>STOCK LOCATION2(INCREASE)</th>
						<th>Customer</th>				
                        </tr>
                    </thead>
                    <tbody id="prbody1">
                        @if(count($cdc_items)>0)
                            @foreach($cdc_items as $item)
                            <tr>
                                <td>{{$item['cdc_number']}}</td>
                                <td>{{date('d-m-Y', strtotime($item['cdc_date']))}}</td>
								<td>{{$item['oef_number']}}</td>
                                <td>{{date('d-m-Y', strtotime($item['oef_date']))}}</td>
								<td>{{$item['ref_no']}}</td>
                                <td>{{date('d-m-Y', strtotime($item['ref_date']))}}</td>
                                <td>{{$item['sku_code']}} </td>
                                <td>{{$item['batch_no']}} </td>
                                <td>{{$item['batch_qty']}} Nos</td>
                                <td>{{$item['transaction_name']}}</td>
                                <td>@if($item['transaction_condition']==1) Returnable @else Non-returnable @endif</td>
                                <td>{{$item['location_decrease']}}</td>
                                <td>@if($item['location_increase']) {{$item['location_increase']}} @else N.A @endif</td>
                                <td>{{$item['firm_name']}}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="14">
                                    <center>No data found...</center>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $cdc_items->appends(request()->input())->links() }}
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
        var sku_code = $('#sku_code').val();
        var batch_no = $('#batch_no').val();
        var doc_no = $('#doc_no').val();
        var oef_no = $('#oef_no').val();
        var customer = $('#customer').val();
        if(!sku_code & !batch_no & !doc_no & !oef_no & !customer) 
        {
            e.preventDefault();
        }
    });

</script>

@stop
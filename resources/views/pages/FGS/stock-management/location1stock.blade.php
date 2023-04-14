@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="" style="color: #596881;">Stock Management</a></span>
             <span><a href="">Location1</a></span>
        </div>
        @include('includes.fgs.stock-location-tab')
        <br/><br/>
       
        <div class="row ">
            <div class="col-lg-12 col-xl-12 mg-t-20 mg-lg-t-0">
                <!-- <div class="card card-table-one" style="min-height: 500px;"> -->
                    <h4 class="az-content-title" style="font-size: 20px;">
                        {{$title}}
                        <div class="right-button">
                            @if($location=='location1')
                            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/stock-report/location1')}}'" class="badge badge-pill badge-info "><i class="fas fa-file-excel"></i> Report</button>
                            @elseif($location=='location2')
                            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/stock-report/location2')}}'" class="badge badge-pill badge-info "><i class="fas fa-file-excel"></i> Report</button>
                            @elseif($location=='MAA')
                            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/stock-report/MAA')}}'" class="badge badge-pill badge-info "><i class="fas fa-file-excel"></i> Report</button>
                            @endif
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
                            <p class="az-content-text mg-b-20"></p>
                            <div class="table-responsive">
                                <table class="table table-bordered mg-b-0">
                                    <thead>
                                        <tr>
                                            <th>SKU Code</th>
                                            <th>Description</th>
                                            <th>Batch Number</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($stock)>0)
                                        @foreach($stock as $stck)
                                        <tr>
                                            <td>{{$stck['sku_code']}}</td>
                                            <td>{{$stck['discription']}}</td>
                                            <td>{{$stck['batch_no']}}</td>
                                            <td>{{$stck['quantity']}} Nos</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="4">
                                            <center>No data found...</center>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div class="box-footer clearfix">
                                    {{ $stock->appends(request()->input())->links() }}
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
@stop
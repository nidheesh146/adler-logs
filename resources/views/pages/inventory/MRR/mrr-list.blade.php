@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">@if(request()->get('order_type')=='wo') Service Receipt Report(SRR) @else Material Receipt Report(SRR) @endif </a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">@if(request()->get('order_type')=='wo') Service Receipt Report(SRR) @else Material Receipt Report(SRR) @endif 
		  	<div class="right-button">
			  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
				  <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
			  <div class="dropdown-menu">
			  <a href="#" class="dropdown-item">Excel</a>

			  </div> -->
              @if(request()->get('order_type')=='wo') 
              <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/RMRN-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-download"></i> SRR </button> 
               @else 
               <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/RMRN-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-download"></i> MRR</button> 
               @endif 
				
	  		</div>
		</h4>
		
        @if (Session::get('success'))
		<div class="alert alert-success " style="width: 100%;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<i class="icon fa fa-check"></i> {{ Session::get('success') }}
		</div>
		@endif
        @include('includes.purchase-details.purchase-work-order-tab')
        <div class="row row-sm mg-b-20 mg-lg-b-0">
            <div class="table-responsive" style="margin-bottom: 13px;">
                <table class="table table-bordered mg-b-0">
                    <tbody>
                        <tr>
                            <style>
                                .select2-container .select2-selection--single {
                                    height: 26px;
                                    width: 122px;
                                }
                                .select2-selection__rendered {
                                    font-size:12px;
                                }
                            </style>
                            <form autocomplete="off">
                                <th scope="row">
                                    <div class="row filter_search" style="margin-left: 0px;">
                                       <div class="col-sm-10 col-md- col-lg-10 col-xl-12 row">
                                       <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label>@if(request()->get('order_type')=='wo') WOR  @else MRD @endif No:</label>
                                                <input type="text" value="{{request()->get('mrd_no')}}" name="mrd_no" id="mrd_no" class="form-control" placeholder="@if(request()->get('order_type')=='wo') WOR  @else MRD @endif NO">
                                            
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label>MIQ No:</label>
                                                <input type="text" value="{{request()->get('miq_no')}}" name="miq_no" id="miq_no" class="form-control" placeholder="MIQ No"> 
                                                
                                            </div><!-- form-group -->
                                            
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label for="exampleInputEmail1" style="font-size: 12px;">Supplier</label>
                                                <input type="text" value="{{request()->get('supplier')}}" name="supplier" id="supplier" class="form-control" placeholder="SUPPLIER">
                                                
                                            </div>
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label for="exampleInputEmail1" style="font-size: 12px;">Month</label>
                                                <input type="text" value="{{request()->get('from')}}" id="from" class="form-control datepicker" name="from" placeholder="Month(MM-YYYY)">
                                                
                                            </div>
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
                                                <label style="width: 100%;">&nbsp;</label>
                                                <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                @if(count(request()->all('')) > 1)
                                                    <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                    style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                            <!-- <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                                                <label style="width: 100%;">&nbsp;</label>
                                                <button type="submit" class="badge badge-pill badge-primary" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                @if(count(request()->all('')) > 1)
                                                    <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                    style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                @endif
                                            </div> -->
                                        </div>
                                    </div>
                                </th>
                            </form>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
		
		<div class="table-responsive">
			<table class="table table-bordered mg-b-0" id="example1">
				<thead>
					<tr>
						<th>@if(request()->get('order_type')=='wo') WOR  @else MRD @endif  No</th>
                        @if(request()->get('order_type')!='wo')<th> MIQ No</th> @endif
						<th>Supplier</th>
                        <th>Prepared By</th> 
                        <th>MRD Date</th>
                        <th>Action</th>
					</tr>
				</thead>
				<tbody>
                    <tr>
                        <th>23445</th>
                        @if(request()->get('order_type')!='wo')<th> MIQ-12345</th> @endif
						<th>Supplier</th>
                        <th>Prepared By</th> 
                        <th>12-11-2022</th>
                        <th>@if(request()->get('order_type')=='wo') 
                            <a class="badge badge-default" style="font-size: 13px; color:black;border:solid black;border-width:thin;" href="{{url('inventory/receipt-report/1/report')}}" target="_blank"><i class="fas fa-file-pdf" style='color:red'></i>&nbsp;SRR</a>
                            @else 
                            <a class="badge badge-default" style="font-size: 13px; color:black;border:solid black;border-width:thin;" href="{{url('inventory/receipt-report/1/report')}}" target="_blank"><i class="fas fa-file-pdf" style='color:red'></i>&nbsp;MRR</a>
                            @endif  
                        </th>
                    </tr>
				</tbody>
			</table>
			<div class="box-footer clearfix">
            
				
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
    $(".datepicker").datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        // startDate: date,
        autoclose:true
    });
    $('.search-btn').on( "click", function(e)  {
		var miq_no = $('#miq_no').val();
		var mrd_no = $('#mrd_no').val();
		var from = $('#from').val();
        var supplier = $('#supplier').val();
        //var prepared = $('#prepared').val();
		if(!miq_no  & !invoice_no & !from & !supplier )
		{
			e.preventDefault();
		}
	});
</script>
@stop
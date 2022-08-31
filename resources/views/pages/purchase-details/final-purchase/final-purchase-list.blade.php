@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="">Final Purchase Order</a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;">Final Purchase Order list
                <div class="right-button">
                    <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                    <i class="fas fa-file-excel" aria-hidden="true"></i> Report <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                    <div class="dropdown-menu">
                        <a href="{{url('inventory/final-purchase/export/all')}}" class="dropdown-item">All</a>
                        <a href="{{url('inventory/final-purchase/export/open')}}" class="dropdown-item">Open</a>
                    </div>  
                <div>  
                <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/final-purchase-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Final Purchase Order </button> 
            </h4>
        
			
	
      
			
		   @if (Session::get('success'))
		   <div class="alert alert-success " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> {{ Session::get('success') }}
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
                            <form autocomplete="off">
                                <th scope="row">
                                    <div class="row filter_search" style="margin-left: 0px;">
                                       <div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
                        
									   		<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label>RQ No:</label>
                                                <input type="text" value="{{request()->get('rq_no')}}" name="rq_no" id="rq_no" class="form-control" placeholder="RQ NO"> 
                                                
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label>PO No:</label>
                                                <input type="text" value="{{request()->get('po_no')}}" name="po_no" id="po_no" class="form-control" placeholder="PO NO">
                                                
                                            </div><!-- form-group -->
                                            
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label for="exampleInputEmail1" style="font-size: 12px;">Supplier</label>
                                                <input type="text" value="{{request()->get('supplier')}}" name="supplier" id="supplier" class="form-control" placeholder="SUPPLIER">
                                                
                                            </div>
											 <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label  style="font-size: 12px;">PO Date </label>
                                                <input type="text" value="{{request()->get('from')}}" id="from" class="form-control datepicker" name="from" placeholder="PO date (MM-YYYY)">
                                            </div> 
                                                                 
                                        </div>
                                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                                                <label style="width: 100%;">&nbsp;</label>
                                                <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                @if(count(request()->all('')) > 1)
                                                    <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                    style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
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


			<div class="table-responsive">
                <table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
						
							<th style="width:120px;">RQ NO:</th>
							<th>PO NO:</th>
							<th>PO date</th>
							<th>Supplier</th>
							<th>Created Date</th>
							<th>Created By</th>
							<th>Action</th>
						
						</tr>
					</thead>
					<tbody>
						@foreach($data['po_data'] as $po_data)
						 <tr>
                            <td>{{$po_data->rq_no}}</td>
							<td>{{$po_data->po_number}}</td>
							<td>{{date('d-m-Y',strtotime($po_data->po_date))}}</td>
							<td>{{$po_data->vendor_id}} - {{$po_data->vendor_name}}</td>
							<td>{{date('d-m-Y',strtotime($po_data->created_at))}}</td>
							<td>{{$po_data->f_name}} {{$po_data->l_name}}</td>
							<td>
								<a class="badge badge-info" style="font-size: 13px;" href="{{url('inventory/final-purchase-add/'.$po_data->id)}}"><i class="fas fa-edit"></i> Edit</a>
								<a class="badge badge-danger" style="font-size: 13px;" href="{{url('inventory/final-purchase-delete/'.$po_data->id)}}" onclick="return confirm('Are you sure you want to delete this ?');"><i class="fa fa-trash"></i> Delete</a>
								<a class="badge badge-default" style="font-size: 13px; color:black;border:solid black;border-width:thin;" href="{{url('inventory/final-purchase/pdf/'.$po_data->id)}}" target="_blank"><i class="fas fa-file-pdf" style='color:red'></i>&nbsp;Receipt</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<div class="box-footer clearfix">
					{{ $data['po_data']->appends(request()->input())->links() }}
			   </div> 
		
			</div>
		</div>
	</div>
	<!-- az-content-body -->
</div>


<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>


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

  });

  
	$('.search-btn').on( "click", function(e)  {
		var supplier = $('#supplier').val();
		var rq_no = $('#rq_no').val();
		var po_no = $('#po_no').val();
		var from = $('#from').val();
		if(!supplier & !rq_no & !po_no & !from)
		{
			e.preventDefault();
		}
	});

	
</script>


@stop
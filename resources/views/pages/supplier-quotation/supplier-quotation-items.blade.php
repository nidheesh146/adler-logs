@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Supplier Quotation</span>
				 <span><a href="">Supplier Quotation Items</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">Supplier Quotation Items  <span>({{$rq_no}})</span>
              <div class="right-button">
                  <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                      <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                  <div class="dropdown-menu">
                  <a href="#" class="dropdown-item">Excel</a>

                  </div>
              <div>  
              </div>
			<!-- <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/add-supplier-quotation')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Supplier Quotation   </button> -->
          </div>
        </h4>
			<div class="az-dashboard-nav">
				<nav class="nav"> </nav>
			</div>
	
      
			
		   
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
            <div class="row">
           <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                <label>Supplier *</label>
                <select class="form-control Supplier" name="supplier">
                @if(!empty($suppliers))
				@foreach($suppliers as $supplier)
                <option value="{{$supplier['id']}}">{{$supplier['vendor_name']}}</option>
				@endforeach
                @endif
                </select>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                <label style="    width: 100%;">&nbsp;</label>
                <button type="submit" class="badge badge-pill badge-primary" style="margin-top:8px;"><i class="fas fa-search"></i> Search</button>
            </div>
</div>
                                            

			<div class="table-responsive">
				<table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
							<th>No.</th>
							<th>Item Code:</th>
                            <th>HSN</th>
							<th>Requested Qty</th>
							<th>Supplier Qty</th>
							<th>Supplier Rate</th>
                            <th>Supplier Discount %</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody id="prbody">
						@foreach()
                        <tr>
                            <th>1</th>
                            <th>RQ No:</th>
                            <th>Date</th>
							<th>Syppliers</th>
                            <th>Delivery schedule</th>
							<th>Item Count</td>
                            <td><button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
								<div class="dropdown-menu">
									<a href="{{url('inventory/edit-supplier-quotation-item/1')}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
								</div>
								<!-- <a class="badge badge-info" style="font-size: 13px;" href=""  class="dropdown-item"><i class="fas fa-eye"></i> Item</a> -->
							</td>
						</tr>    
                       
					</tbody>
				</table>
				@if(!empty($data['response']))
				@include('includes.pagination',['data'=>$data['response']])
			 	@endif
				<div class="box-footer clearfix">
					<style>
					.pagination-nav {
						width: 100%;
					}
					
					.pagination {
						float: right;
						margin: 0px;
						margin-top: -16px;
					}
					</style>
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
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>

<script>
  $(function(){
    'use strict'

    // $('#example1').DataTable({
    //   language: {
    //     searchPlaceholder: 'Search...',
    //     sSearch: '',
    //     lengthMenu: '_MENU_ items/page',
    //   },
	//   order: [[1, 'desc']],
    // });

    $('#prbody').show();
  });

//   $('.Supplier').select2({
//                     placeholder: 'Choose one',
//                     searchInputPlaceholder: 'Search',
//                     minimumInputLength: 3,
//                     allowClear: true,
//                     ajax: {
//                     url: "{{url('inventory/suppliersearch')}}",
//                     processResults: function (data) {
//                       return {
//                         results: data
//                       };
//                     }
//                   }
//                 });
</script>


@stop
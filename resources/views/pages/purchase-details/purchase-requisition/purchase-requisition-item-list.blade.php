@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="{{url('inventory/get-purchase-reqisition')}}" style="color: #596881;">PURCHASE DETAILS</a></span> 
                <span><a href="{{url('inventory/get-purchase-reqisition')}}" style="color: #596881;">PURCHASE REQISITION</a></span>
                <span><a href="">{{ request()->pr_id? 'Edit' : 'Add' }} purchase reqisition master</a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;">{{ request()->pr_id? 'Edit' : 'Add' }} purchase reqisition master
                <div class="right-button">
                    <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                        <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                    <div class="dropdown-menu">
                    <a href="#" class="dropdown-item">Excel</a>
  
                    </div>
                <div>  
                </div>
              <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/add-purchase-reqisition-item?pr_id='.request()->pr_id)}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Purchase Reqisition   </button>
            </div>
          </h4>


            <div class="az-dashboard-nav">
                <nav class="nav">
                    <a class="nav-link" href="{{url('inventory/edit-purchase-reqisition?pr_id='.request()->pr_id)}}">Purchase reqisition master </a>
                    <a class="nav-link active "  href=""  >  Purchase reqisition item </a>
                     <a class="nav-link" href="http://kssp.com/order-return"> </a>
                </nav>
           
            </div>

            <div class="table-responsive">
				<table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
							<th>Agent ID</th>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>PIN code</th>
							<th>Credit limit /
								<br> Balance</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody >
						<tr>
							<th> <a href=" http://kssp.com/agent/create/agent/gdp">AG03</a> </th>
							<th>Connor Flores Kyla Barnes</th>
							<td>kanoxi@mailinator.com</td>
							<td>9037715996</td>
							<td>3541</td>
							<th><span style="float: right;">1000000.000 / 
                                                                          <a href="http://kssp.com/agent-payment/state/gdp"><span style="color: red;">-166.320</span></a>
								</span>
							</th>
							<td>
								<button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
								<div class="dropdown-menu"> 
							   <a href="{{url('inventory/edit-purchase-reqisition-item?pr_id='.request()->pr_id)}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
							   <a href=" http://kssp.com/agent-subscribers-action/agents/gdp/delete" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> </div>
							</td>
						</tr>
					
					</tbody>
				</table>
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

<script>
  $(function(){
    'use strict'

    $('#example1').DataTable({
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
        lengthMenu: '_MENU_ items/page',
      }
    });

    
  });
</script>


@stop
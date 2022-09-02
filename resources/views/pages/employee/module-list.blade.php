@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
        <div class="az-content-breadcrumb"> 
				 <span>Employee</span>
				 <span><a href="">Module List</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">Module List 
              <div class="right-button">
              <div>  
              </div>
			<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('employee/add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Employee</button>
          </div>
        </h4>
        <div class="az-dashboard-nav">
				<nav class="nav"> </nav>
			</div>
        
			
	
      
			
		   @if (Session::get('success'))
		   <div class="alert alert-success " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> {{ Session::get('success') }}
		   </div>
		   @endif


			<div class="table-responsive">
                <table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
                            <th>SL No:</th>
							<th>Module </th>
                            <th>Status</th>
                            <!-- <th>Date Of hire</th> -->
							<th>Action</th>
						
						</tr>
					</thead>
					<tbody>
                        <?php $i=1; ?>
						
						 <tr>
                            <td>1</td>
                            <td>module1</td>
							<td>Active</td>
							<td>
								<a class="badge badge-info" style="font-size: 13px;" href="{{url('module/edit/1')}}"><i class="fas fa-edit"></i> Edit</a>
								<a class="badge badge-danger" style="font-size: 13px;" href="{{url('module/delete/1')}}" onclick="return confirm('Are you sure you want to delete this ?');"><i class="fa fa-trash"></i> Delete</a>
							</td>
						</tr>
			
					</tbody>
				</table>
				<div class="box-footer clearfix">

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
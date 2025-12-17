
<?php $__env->startSection('content'); ?>

<?php $SupplierQuotation = app('App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController'); ?>
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
		
				 <span><a href="">Supplier Quotation</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">Supplier Quotation
              <div class="right-button">
			  <button style="float: right;font-size: 15px; height:25px; background-color:#85BB65;color:white; border-radius: 25px;" type="button" data-toggle="modal" data-target="#currencyModal">Currency</button>
			  <button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('inventory/supplier-quotation/excel-export').'?'.http_build_query(array_merge(request()->all()))); ?>'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
              <div>  
              </div>
			<!-- <button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('inventory/add-supplier-quotation')); ?>'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Supplier Quotation   </button> -->
          </div>
        </h4>
		<?php echo $__env->make('includes.purchase-details.pr-sr-tab', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      
			
		   <?php if(Session::get('success')): ?>
		   <div class="alert alert-success " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> <?php echo e(Session::get('success')); ?>

		   </div>
		   <?php endif; ?>
		   <!-- <div class="card bd-0">
            	<div class="card-header bg-gray-400 bd-b-0-f pd-b-0" style="background-color: #cdd4e0;">
                    <nav class="nav nav-tabs">
                        <a class="nav-link  active" data-toggle="tab" href="#purchase">Purchase requisition</a>
                        <a class="nav-link" data-toggle="tab" href="#service">  Service requisition </a>
                    </nav>   
                </div>
            </div><br/> -->
			<div class="tab-content">
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
										<form autocomplete="off" id="formfilter">
											<th scope="row">
												<div class="row filter_search" style="margin-left: 0px;">
												<div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
									
														<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
															<label>RQ No:</label>
															<input type="text" value="<?php echo e(request()->get('rq_no')); ?>" name="rq_no"  id="rq_no" class="form-control" placeholder="RQ NO">
														</div><!-- form-group -->
														<input type="hidden" value="<?php echo e(request()->get('prsr')); ?>" id="prsr"  name="prsr">
														<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
															<label  style="font-size: 12px;">Delivery Schedule</label>
															<input type="text" value="<?php echo e(request()->get('from')); ?>" id="from" class="form-control datepicker" name="from" placeholder="Delivery Schedule(MM-YYYY)">
														</div> 
														<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
															<label  style="font-size: 12px;">Supplier</label>
															<input type="text" value="<?php echo e(request()->get('supplier')); ?>"  class="form-control " name="supplier" placeholder="Supplier" >
														</div> 
																			
													</div>
													<div class="col-sm-2 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
														<!-- <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;"> -->
															<label style="width: 100%;">&nbsp;</label>
															<button type="submit" class="badge badge-pill badge-primary search-btn" 
															onclick="document.getElementById('formfilter').submit();"
															style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
															<?php if(count(request()->all('')) > 1): ?>
																<a href="<?php echo e(url()->current()); ?>" class="badge badge-pill badge-warning"
																style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
															<?php endif; ?>
														<!-- </div>  -->
													</div>
												</div>
											</th>
										</form>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				<div class="tab-pane tab-pane active  show" id="purchase">
					
					<div class="table-responsive">
						<table class="table table-bordered mg-b-0" id="example1">
							<thead>
								<tr>
								
									<th style="width:120px;">RQ NO:</th>
									<th>Date</th>
									<th>delivery Schedule </th>
									<th>Suppliers</th>
									<th>Created By</th> 
									<th>Action</th>
								
								</tr>
							</thead>
							<tbody >
								<?php $__currentLoopData = $data['quotation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php
									// $type = $SupplierQuotation->check_reqisition_type($item['quotation_id']);
								?>
								
								<tr>
									
									<td><?php echo e($item['rq_no']); ?></td>
									<td><?php echo e($item['date'] ? date('d-m-Y',strtotime($item['date'])) : '-'); ?></td>
									<td><?php echo e($item['delivery_schedule'] ? date('d-m-Y',strtotime($item['delivery_schedule'])) : '-'); ?></td>
									<td>
										<?php if($item['quotation_id']): ?>
										<?php
											$supp = $SupplierQuotation->get_supplier($item['quotation_id']);
											echo $supp['supplier'];
										?>
										<?php endif; ?>
									</td>
									<td><?php echo e($item['f_name']); ?> <?php echo e($item['l_name']); ?></td>
									<td>
									<a class="badge badge-info" style="font-size: 13px;" href="<?php echo e(url('inventory/view-supplier-quotation-items/'.$item['quotation_id'].'/'.$supp['supplier_id'])); ?>"  class="dropdown-item"><i class="fas fa-eye"></i> View</a>
										<a class="badge badge-primary" style="font-size: 13px;" href="<?php echo e(url('inventory/comparison-quotation/'.$item['quotation_id'])); ?>"  class="dropdown-item"><i class="fa fa-balance-scale"></i> Comparison</a>
									</td>
								</tr>
								
								
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
						
							</tbody>
						</table>
						<div class="box-footer clearfix">
							<?php echo e($data['quotation']->appends(request()->input())->links()); ?>

						</div> 
					</div>
				</div>

				<?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?>
<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>



				<div class="modal" id="currencyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add Currency Exchange Rate</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="exchangeRateForm" action="<?php echo e(url('saveExchangeRate')); ?>" method="POST" autocomplete="off">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="currency">Enter Currency Code:</label>
                        <input type="text" class="form-control" id="currency" name="currency" required maxlength="3" placeholder="e.g., INR" pattern="[A-Z]{3}">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
				<!-- <div class="tab-pane tab-pane <?php if(request()->get('prsr')=='sr'): ?>active  show <?php endif; ?>" id="service">
					
					<div class="table-responsive">
						<table class="table table-bordered mg-b-0" id="example1">
							<thead>
								<tr>
								
									<th style="width:120px;">RQ NO:</th>
									<th>Date</th>
									<th>delivery Schedule </th>
									<th>Suppliers</th>
									
									<th>Action</th>
								
								</tr>
							</thead>
							<tbody >
								<?php $__currentLoopData = $data['quotation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php
									$type = $SupplierQuotation->check_reqisition_type($item['quotation_id']);
								?>
								<?php if($type=="SR"): ?>
								<tr>
									
									<td><?php echo e($item['rq_no']); ?> </td>
									<td><?php echo e($item['date'] ? date('d-m-Y',strtotime($item['date'])) : '-'); ?></td>
									<td><?php echo e($item['delivery_schedule'] ? date('d-m-Y',strtotime($item['delivery_schedule'])) : '-'); ?></td>
									<td>
										<?php
											$supp = $SupplierQuotation->get_supplier($item['quotation_id']);
											echo $supp['supplier'];
										?>
									</td>
									<td>
										<a class="badge badge-info" style="font-size: 13px;" href="<?php echo e(url('inventory/view-supplier-quotation-items/'.$item['quotation_id'].'/'.$supp['supplier_id'])); ?>"  class="dropdown-item"><i class="fas fa-eye"></i> View</a>
										<a class="badge badge-primary" style="font-size: 13px;" href="<?php echo e(url('inventory/comparison-quotation/'.$item['quotation_id'])); ?>"  class="dropdown-item"><i class="fa fa-balance-scale"></i> Comparison</a>
									</td>
								</tr>
								<?php endif; ?>
								
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
						
							</tbody>
						</table>
						<div class="box-footer clearfix">
							<?php echo e($data['quotation']->appends(request()->input())->links()); ?>

						</div> 
					</div>
				</div> -->
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

    //$('#prbody').show();
  });
  
	$('.search-btn').on( "click", function(e)  {
		//var supplier = $('#supplier').val();
		var rq_no = $('#rq_no').val();
		var po_no = $('#po_no').val();
		var from = $('#from').val();
		if(!rq_no & !po_no & !from)
		{
			e.preventDefault();
		}
	});

</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/pages/purchase-details/supplier-quotation/supplier-quotation.blade.php ENDPATH**/ ?>
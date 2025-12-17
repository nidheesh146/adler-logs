
<?php $__env->startSection('content'); ?>
<?php
use App\Http\Controllers\Web\PurchaseDetails\InventoryController;
$obj =new InventoryController;
?>
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Purchase details</span>
				 <span><a href="">
				 	<?php if(request()->get('prsr')=="sr"): ?>
					Service Requisition
					<?php else: ?>
					Purchase Requisition
					<?php endif; ?>
				</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">
				<?php if(request()->get('prsr')=="sr"): ?>
					Service Requisition
				<?php else: ?>
					Purchase Requisition
				<?php endif; ?> 
              <div class="right-button">
                  
              <div>  
				
              </div>
			  
					<?php if(request()->get('prsr')=="sr"): ?>
					<button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('inventory/add-purchase-reqisition')); ?>?prsr=sr'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> 
						Service Requisition
					</button>
					<?php else: ?>
					<button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('inventory/add-purchase-reqisition')); ?>?prsr=pr'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> 
						Purchase Requisition
					</button>
					<?php endif; ?>
			
			
          </div>
        </h4>
        <?php echo $__env->make('includes.purchase-details.pr-sr-tab', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	
		   <?php if(Session::get('error')): ?>
		   <div class="alert alert-danger "  role="alert" style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <?php echo e(Session::get('error')); ?>

		   </div>
	       <?php endif; ?>
		   <?php if(Session::get('success')): ?>
		   <div class="alert alert-success " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> <?php echo e(Session::get('success')); ?>

		   </div>
		   <?php endif; ?>
		   
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
									<form autocomplete="off">
										<th scope="row">
											<div class="row filter_search" style="margin-left: 0px;">
												<div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
								
													<div class="form-group col-sm-12 col-md-3 col-lg- col-xl-4">
														<label><?php if(request()->get('prsr')=="sr"): ?>SR <?php else: ?> PR <?php endif; ?> No:</label>
														<input type="text" value="<?php echo e(request()->get('pr_no')); ?>" name="pr_no" id="pr_no" class="form-control" placeholder="<?php if(request()->get('prsr')=='sr'): ?>SR <?php else: ?> PR <?php endif; ?> NO">
													</div><!-- form-group -->
													
													
													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label for="exampleInputEmail1" style="font-size: 12px;">Department</label>
														<input type="text" value="<?php echo e(request()->get('department')); ?>" name="department" id="department" class="form-control" placeholder="DEPARTMENT">
													</div>
													<!-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
														<label for="exampleInputEmail1" style="font-size: 12px;">PR/SR</label>
														
														<select name="pr_sr" id="pr_sr" class="form-control">
															<option value="">PR/SR</option>
															<option value="PR" <?php echo e((request()->get('pr_sr') == 'PR') ? 'selected' : ''); ?>>PR</option>
															<option value="SR" <?php echo e((request()->get('pr_sr') == 'SR') ? 'selected' : ''); ?>>SR</option>
														</select>
													</div> -->
													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label  style="font-size: 12px;">Month</label>
														<input type="text" value="<?php echo e(request()->get('from')); ?>" id="from" class="form-control datepicker" name="from" placeholder="Month(MM-YYYY)">
													</div>
													
														<input type="hidden" value="<?php echo e(request()->get('prsr')); ?>" id="prsr"  name="prsr">
																		
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
													<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
														<label style="width: 100%;">&nbsp;</label>
														<button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
														<?php if(count(request()->all('')) > 2): ?>
															<a href="<?php echo e(url()->current()); ?>" class="badge badge-pill badge-warning"
															style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
														<?php endif; ?>
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
			
			
					<div class="tab-pane  active  show " id="purchase"> 
					
					<div class="table-responsive">
						<table class="table table-bordered mg-b-0" >
							<thead>
								<tr>
									<th><?php if(request()->get('prsr')=="sr"): ?>SR <?php else: ?> PR <?php endif; ?> NO:</th>
									<th>requestor</th>
									<th>date</th>
									<th>department</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="prbody1">
									
							<?php $__currentLoopData = $data['master']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							
								<tr>
									<th><?php echo e($item['pr_no']); ?> </th>
									<th><?php echo e($item['f_name'].' '.$item['l_name']); ?></th>
									<td><?php echo e(date('d-m-Y',strtotime($item['date']))); ?></td>
									<td><?php echo e($item['dept_name']); ?></td>
									<td >
										<span style="width: 133px;">
										<button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
										<div class="dropdown-menu">
											<?php if($item['prsr_type']=="PR"): ?>
												<?php if(in_array('purchase_details.requisition_edit',config('permission'))): ?>
												<a href="<?php echo e(url('inventory/edit-purchase-reqisition?pr_id='.$item["master_id"])); ?>" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
												<?php endif; ?>
												<?php if(in_array('purchase_details.requisition_item_list',config('permission'))): ?>
												<a href="<?php echo e(url('inventory/add-purchase-reqisition-item?pr_id='.$item["master_id"])); ?>" class="dropdown-item"><i class="fas fa-plus"></i> Item</a> 
												<?php endif; ?>
												<?php if(in_array('purchase_details.requisition_delete',config('permission'))): ?>
												<?php $items = $obj->status($item['master_id']); ?>
												<?php if($items): ?>
												<a onclick="return confirm('You cant delete.It is under next process');" class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>
												<?php else: ?>
												<a href="<?php echo e(url('inventory/delete-purchase-reqisition?pr_id='.$item["master_id"])); ?>" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>
												<?php endif; ?>
												<?php endif; ?>
											<?php else: ?>
												<?php if(in_array('purchase_details.requisition_edit',config('permission'))): ?>
												<a href="<?php echo e(url('inventory/edit-service-reqisition?sr_id='.$item["master_id"])); ?>" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
												<?php endif; ?>
												<?php if(in_array('purchase_details.requisition_item_list',config('permission'))): ?>
												<a href="<?php echo e(url('inventory/add-purchase-reqisition-item?sr_id='.$item["master_id"])); ?>" class="dropdown-item"><i class="fas fa-plus"></i> Item</a> 
												<?php endif; ?>
												<?php if(in_array('purchase_details.requisition_delete',config('permission'))): ?>
												<?php $items = $obj->status($item['master_id']); ?>
												<?php if($items): ?>
												<a onclick="return confirm('You cant delete.It is under next process');" class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>
												<?php else: ?>
												<a href="<?php echo e(url('inventory/delete-service-reqisition?sr_id='.$item["master_id"])); ?>" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>
												<?php endif; ?>
												<?php endif; ?>
												
											<?php endif; ?>
										</div>
										<?php if(in_array('purchase_details.requisition_item_list',config('permission'))): ?>
											<?php if($item['prsr_type']=="PR"): ?>
											<a class="badge badge-info" style="font-size: 13px;" href="<?php echo e(url('inventory/get-purchase-reqisition-item?pr_id='.$item["master_id"])); ?>"  class="dropdown-item"><i class="fas fa-eye"></i> Item</a> 	
											<?php else: ?>
											<a class="badge badge-info" style="font-size: 13px;" href="<?php echo e(url('inventory/get-service-reqisition-item?sr_id='.$item["master_id"])); ?>"  class="dropdown-item"><i class="fas fa-eye"></i> Item</a> 	
											<?php endif; ?>
										<?php endif; ?>
									</span>
									</td>
								</tr>
							
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					
							</tbody>
						</table>
						<div class="box-footer clearfix">
							<?php echo e($data['master']->appends(request()->input())->links()); ?>

						</div>
					</div>
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
	$('#prbody1').show();
	$('#prbody2').show();
  });
  	$('#purchase_tab').on('click',function(){
		$('#pr_no').val(" ");
		$('#department').val("");
		$('#from').val(" ");
	});
	$('#service_tab').on('click',function(){
		$('#pr_no').val(" ");
		$('#department').val("");
		$('#from').val(" ");
	});
	$('.search-btn').on( "click", function(e)  {
		var pr_no = $('#pr_no').val();
		var department = $('#department').val();
		var from = $('#from').val();
		if(!pr_no  & !department & !from)
		{
			e.preventDefault();
		}
	});
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/pages/purchase-details/purchase-requisition/purchase-requisition-list.blade.php ENDPATH**/ ?>
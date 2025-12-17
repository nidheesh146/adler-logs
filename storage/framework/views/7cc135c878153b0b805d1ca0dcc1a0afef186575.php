
<?php $__env->startSection('content'); ?>
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="" style="color: #596881;">Price Master </a></span> 
                <span><a href="" style="color: #596881;">
                Price List
                </a></span>
			</div> 
			<h4 class="az-content-title" style="font-size: 20px;">Price List
			<button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('fgs/price-master/upload-excel')); ?>'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> upload</button>
			<button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('fgs/price-master/add')); ?>'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Price Master</button>
			<button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('fgs/price-master/excel-export').'?'.http_build_query(array_merge(request()->all()))); ?>'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
            </h4>
			
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
										<form autocomplete="off" id="formfilter">
											<th scope="row">
												<div class="row filter_search" style="margin-left: 0px;">
													<div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label>PRODUCT NAME:</label>
															<input type="text" value="<?php echo e(request()->get('sku_code')); ?>" name="sku_code"  id="sku_code" class="form-control" placeholder="PRODUCT NAME">
														</div><!-- form-group -->
									
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">GROUP</label>
															<select name="group_name" id="group_name" class="form-control">
                                    <option value="">-- Select one ---</option>
                                 <?php $__currentLoopData = $price; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->group_name); ?>"><?php echo e($item->group_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
														</div> 
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">HSN CODE</label>
															<input type="text" value="<?php echo e(request()->get('hsn_code')); ?>" id="hsn_code" class="form-control " name="hsn_code" placeholder="HSN CODE" >
														</div> 
													<!-- 	<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">Origin</label>
															<input type="text" value="<?php echo e(request()->get('origin')); ?>"  class="form-control " name="origin" placeholder="ORIGIN" >
														</div>  -->
														
																			
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
				<div class="tab-pane tab-pane active  show" id="purchase">
					
					<div class="table-responsive">
						<table class="table table-bordered mg-b-0" id="example1">
							<thead>
								<tr>
									<th>Product</th>
									<th width="7%">Description </th>
									<!--th>Group </th-->
									<th>HSN Code</th>
									<th>Purchase Price </th>
                                    <th>Sales Price</th>
                                    <th>Transfer Price</th>
									<th>MRP</th>
									<th>Effective Date(From-To)</th>
									<th>Action</th>
									<th>updated_at</th>
								</tr>
							</thead>
							<tbody id="prbody1">
							<?php $__currentLoopData = $prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php if($price['with_effective_from']==NULL || $price['with_effective_to']==NULL ||($price['with_effective_from']<=date('Y-m-d') && $price['with_effective_to']>=date('Y-m-d')) ): ?>
								<tr>
									<td><?php echo e($price['sku_code']); ?></td>
									<td><?php echo e($price['discription']); ?></td>
									<!--td><?php echo e($price['group_name']); ?></td-->
									<td><?php echo e($price['hsn_code']); ?></td>
									<td><?php echo e($price['purchase']); ?></td>
									<td><?php echo e($price['sales']); ?></td>
									<td><?php echo e($price['transfer']); ?></td>
									<td><?php echo e($price['mrp']); ?></td>
									<td>
										From:<b><?php if($price['with_effective_from']): ?> <?php echo e(date('d-m-Y', strtotime($price['with_effective_from']))); ?> <?php endif; ?></b><br/>
										To:<b><?php if($price['with_effective_to']): ?> <?php echo e(date('d-m-Y', strtotime($price['with_effective_to']))); ?> <?php endif; ?></b>
									</td>
									<td>
									<button data-toggle="dropdown" style="width: 64px;" class="badge <?php if($price['status_type']==1): ?> badge-success <?php else: ?> badge-warning <?php endif; ?>"><?php if($price['status_type']==1): ?>  Active <?php else: ?> Inactive <?php endif; ?><i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
										<div class="dropdown-menu">
										<a href="<?php echo e(url('fgs/price-master/edit/'.$price['id'])); ?>" class="dropdown-item">
										<i class="fas fa-edit"></i> Edit
</a>
											<a href="<?php echo e(url('fgs/price-master/delete/'.$price["id"])); ?>" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> 
										</div>
									</td>
									<td><?php echo e($price['updated_at']); ?></td>

									
								</tr>
								<?php endif; ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</tbody>
						</table>
						<div class="box-footer clearfix">
						<?php echo e($prices->appends(request()->input())->links()); ?>

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

    $('#prbody').show();
  });
  
	$('.search-btn').on( "click", function(e)  {
		var sku_code = $('#sku_code').val();
		var group_name = $('#group_name').val();
		var hsn_code = $('#hsn_code').val();
		if(!sku_code & !group_name & !hsn_code)
		{
			e.preventDefault();
		}
	});

</script>

<script>
$(document).ready(function() {
    var seen = {};

    $('#example1 tbody tr').each(function() {
        var key = '';

        // Concatenate all the fields except the date to create a unique key
        $(this).find('td').each(function(index) {
            if(index !== 9) { // Assuming the date is in the 10th column (index 9)
                key += $(this).text().trim() + '|';
            }
        });

        var updatedAt = $(this).find('td:last').text().trim();
        if (seen[key]) {
            // Compare dates, keep the row with the latest date
            var existingDate = seen[key].find('td:last').text().trim();
            if (new Date(updatedAt) > new Date(existingDate)) {
                seen[key].remove();  // Remove the older duplicate
                seen[key] = $(this);  // Update to the current latest row
            } else {
                $(this).remove();  // Remove the current row as it's older
            }
        } else {
            seen[key] = $(this);  // Store this row if not seen before
        }
    });

    // Hide the updated_at column
    $('#example1 thead th:last-child, #example1 tbody td:last-child').hide();
});
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/pages/FGS/price-master/price-master-list.blade.php ENDPATH**/ ?>
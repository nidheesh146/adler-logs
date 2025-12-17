
<?php $__env->startSection('content'); ?>

<?php $SupplierQuotation = app('App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController'); ?>
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="" style="color: #596881;">FGS Item Master</a></span> 
                <span><a href="" style="color: #596881;">
                FGS Item LIST
                </a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;">Products
			<button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('fgs/product-master/upload-excel')); ?>'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> upload</button>
			<button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('fgs/product-master/add')); ?>'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Product</button>
            <button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('fgs/product-master/excel-export').'?'.http_build_query(array_merge(request()->all()))); ?>'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
			</h4>
			
		   <?php if(Session::get('success')): ?>
		   <div class="alert alert-success " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> <?php echo e(Session::get('success')); ?>

		   </div>
		   <?php endif; ?>
		   <?php if(Session::get('error')): ?>
		   <div class="alert alert-danger " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> <?php echo e(Session::get('error')); ?>

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
															<label>SKU Code:</label>
															<input type="text" value="<?php echo e(request()->get('sku_code')); ?>" name="sku_code"  id="sku_code" class="form-control" placeholder="SKU CODE">
														</div><!-- form-group -->
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">HSN CODE</label>
															<input type="text" value="<?php echo e(request()->get('hsn_code')); ?>" id="hsn_code" class="form-control " name="hsn_code" placeholder="HSN CODE" >
														</div>
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">Group</label>
															<input type="text" value="<?php echo e(request()->get('group')); ?>" id="group" class="form-control" name="group" placeholder="GROUP">
														</div> 
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">Brand</label>
															<input type="text" value="<?php echo e(request()->get('brand')); ?>"  class="form-control " name="brand" placeholder="BRAND" >
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
									<th>SKU Code </th>
									<th>Description </th>
									<th>Type </th>
									<th>HSN Code </th>
                                    <th>Product Condition</th>
									<th>Business Category</th>
									<th>Product Category</th>
                                    <th>Brand</th>
									<th>Family</th>
									<th>Group</th>
									<th>GST</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody >
							<?php $__currentLoopData = $data['products']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($item['sku_code']); ?></td>
                            <td><?php echo e($item['discription']); ?></td>
                            <td><?php echo e($item['product_type_name']); ?></td>
                            <td><?php echo e($item['hsn_code']); ?></td>
                            <td><?php if($item['is_sterile']==1): ?> Sterile <?php else: ?> Non-Sterile <?php endif; ?></td>
							<td><?php echo e($item['category_name']); ?></td>
							<td><?php echo e($item['new_category_name']); ?></td>
                            <td><?php echo e($item['brand_name']); ?></td>
                            <td><?php echo e($item['family_name']); ?></td>
                            <td><?php echo e($item['group1_name']); ?></td>
                            <td><?php echo e($item['gst']); ?>%</td>
							<td>
								
								<button data-toggle="dropdown" style="width: 64px;" class="badge <?php if($item['status_type']==1): ?> badge-success <?php else: ?> badge-warning <?php endif; ?>"><?php if($item['status_type']==1): ?>  Active <?php else: ?> Inactive <?php endif; ?><i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
								<div class="dropdown-menu">
									<a href="<?php echo e(url('fgs/product-master/add?id='.$item["id"])); ?>" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
									<a href="<?php echo e(url('product/delete?id='.$item["id"])); ?>" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> 
								</div>
								
							</td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</tbody>
						</table>
						<div class="box-footer clearfix">
							<?php echo e($data['products']->appends(request()->input())->links()); ?>

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

    //$('#prbody').show();
  });
  
	$('.search-btn').on( "click", function(e)  {
		var hsn_code = $('#hsn_code').val();
		var sku_code = $('#sku_code').val();
		var group = $('#group').val();
		var brand = $('#brand').val();
		if(!sku_code & !group & !brand & !hsn_code)
		{
			e.preventDefault();
		}
	});

</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/pages/FGS/product-master/product-list.blade.php ENDPATH**/ ?>
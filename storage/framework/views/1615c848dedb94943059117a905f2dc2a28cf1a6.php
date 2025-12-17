
<?php $__env->startSection('content'); ?>
<?php
use App\Http\Controllers\Web\FGS\OEFController;
$obj_oef=new OEFController;
?>
<div class="az-content az-content-dashboard">
	<br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb">
				<span>Goods Reservation Slip(OEF)</span>
				<span><a href="">
						OEF List
					</a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;">
				OEF List
				<div class="right-button">
					<button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('fgs/OEF-add')); ?>'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i>
						OEF
					</button>
					<div>

					</div>
				</div>
			</h4>
			<?php if(Session::get('error')): ?>
			<div class="alert alert-danger " role="alert" style="width: 100%;">
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
											font-size: 12px;
										}
									</style>
									<form autocomplete="off">
										<th scope="row">
											<div class="row filter_search" style="margin-left: 0px;">
												<div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
												<div class="form-group col-sm-12 col-md-3 col-lg- col-xl-3">
														<label>CUSTOMER :</label>
														<input type="text" value="<?php echo e(request()->get('firm_name')); ?>" name="firm_name" id="firm_name" class="form-control" placeholder="CUSTOMER">
													</div>
													<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
														<label>OEF No :</label>
														<input type="text" value="<?php echo e(request()->get('oef_number')); ?>" name="oef_number" id="oef_number" class="form-control" placeholder="OEF NO">
													</div><!-- form-group -->


													<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
														<label for="exampleInputEmail1" style="font-size: 12px;">Order No</label>
														<input type="text" value="<?php echo e(request()->get('order_number')); ?>" name="order_number" id="order_number" class="form-control" placeholder="ORDER NUMBER">
													</div>

													<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
														<label style="font-size: 12px;">OEF Month</label>
														<input type="text" value="<?php echo e(request()->get('from')); ?>" id="from" class="form-control datepicker" name="from" placeholder="Month(MM-YYYY)">
													</div>

												</div>
												<div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
													<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
														<label style="width: 100%;">&nbsp;</label>
														<button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
														<?php if(count(request()->all('')) > 2): ?>
														<a href="<?php echo e(url()->current()); ?>" class="badge badge-pill badge-warning" style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
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
					<a class="badge badge-success" style="float:right;font-size: 13px; color:white;border:solid black;border-width:thin;margin-top:2px;"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;Email</a><span style="float:right;">&nbsp;&nbsp;Sent Mail : </span>
					&nbsp;&nbsp;
					<a class="badge badge-default" style="float:right;font-size: 13px; color:white;background:blue;border:solid black;border-width:thin;margin-top:2px;"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;Email</a><span style="float:right;">Not Sent Mail : </span>
					
					<div class="table-responsive">
					<br/>
						<table class="table table-bordered mg-b-0">
							<thead>
								<tr>
									<th>OEF Number</th>
									<th>OEF date</th>
									<th>Customer info</th>
									<th>Order number</th>
									<th>Order date</th>
									
									<th>Transaction Type</th>
									<th>Business Category</th>
									<th>Product Category</th>
									
									<th>Action</th>
									<th></th>
								</tr>
							</thead>
							<tbody id="prbody1">
								<?php $__currentLoopData = $oef; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr>

									<td><?php echo e($item['oef_number']); ?></td>
									<td><?php echo e(date('d-m-Y', strtotime($item['oef_date']))); ?></td>
									<td><?php echo e($item['firm_name']); ?><br />
										Contact Person:<?php echo e($item['contact_person']); ?><br />
										Contact Number:<?php echo e($item['contact_number']); ?><br />
									</td>
									<td><?php echo e($item['order_number']); ?></td>
									<td><?php echo e(date('d-m-Y', strtotime($item['order_date']))); ?></td>
									
									<td><?php echo e($item['transaction_name']); ?></td>
									<td><?php echo e($item['category_name']); ?></td>
									<td><?php echo e($item['new_category_name']); ?></td>
									

									
									<td>
										<a class="badge badge-info" style="font-size: 13px;" href="<?php echo e(url('fgs/OEF/item-list/'.$item["id"])); ?>" class="dropdown-item"><i class="fas fa-eye"></i> Item</a><br />
										<a class="badge badge-default" style="font-size: 13px; color:black;border:solid black;border-width:thin;margin-top:2px;" href="<?php echo e(url('fgs/OEF/pdf/'.$item["id"])); ?>" target="_blank"><i class="fas fa-file-pdf" style='color:red'></i>&nbsp;PDF</a>
										<a class="badge badge-default" style="font-size: 13px; color:black;border:solid black;border-width:thin;margin-top:2px;" href="<?php echo e(url('fgs/OEF/ackpdf/'.$item["id"])); ?>" target="_blank"><i class="fas fa-file-pdf" style='color:red'></i>&nbsp;Acknowledgment</a>
										<?php if($item['is_mail_sent']==1): ?>
										<a class="badge badge-success" style="font-size: 13px; color:white;border:solid black;border-width:thin;margin-top:2px;" href="<?php echo e(url('fgs/OEF/order-acknowledgement-mail/'.$item["id"])); ?>"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;Email</a>
										<?php else: ?>
										<a class="badge badge-default" style="font-size: 13px;background:blue; color:white;border:solid black;border-width:thin;margin-top:2px;" href="<?php echo e(url('fgs/OEF/order-acknowledgement-mail/'.$item["id"])); ?>"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;Email</a>
										<?php endif; ?>

									</td>

									<?php if(!empty($obj_oef->check_item($item["id"]))): ?>
									<td> <a class="badge badge-danger" style="font-size: 13px;" onclick="return confirm('Cant Delete.OEF have items!');"><i class="fa fa-trash"></i> Delete</a>
										<a class="badge badge-info" style="font-size: 13px;" href="<?php echo e(url('fgs/OEF-edit/'.$item['id'])); ?>" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>

									</td>
									<?php else: ?>
									<td> <a class="badge badge-danger" style="font-size: 13px;" href="<?php echo e(url('fgs/OEF-delete/'.$item['id'])); ?>" onclick="return confirm('Are you sure you want to delete this ?');"><i class="fa fa-trash"></i> Delete</a>
										<a class="badge badge-info" style="font-size: 13px;" href="<?php echo e(url('fgs/OEF-edit/'.$item['id'])); ?>" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
									</td>
									<?php endif; ?>
								</tr>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</tbody>
						</table>
						<div class="box-footer clearfix">
							<?php echo e($oef->appends(request()->input())->links()); ?>

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
<script src="<?= url(''); ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"> </script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>

<script>
	$(function() {
		'use strict'
		var date = new Date();
		date.setDate(date.getDate());
		$(".datepicker").datepicker({
			format: "mm-yyyy",
			viewMode: "months",
			minViewMode: "months",
			// startDate: date,
			autoclose: true
		});
		$('#prbody1').show();
		$('#prbody2').show();
	});
	$('.search-btn').on("click", function(e) {
		var firm_name = $('#firm_name').val();
		var oef_number = $('#oef_number').val();
		var order_number = $('#order_number').val();
		var from = $('#from').val();
		if (!firm_name & !oef_number & !order_number & !from) {
			e.preventDefault();
		}
	});
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/pages/FGS/OEF/OEF-list.blade.php ENDPATH**/ ?>

<?php $__env->startSection('content'); ?>

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Cancellation Proforma Invoice(CPI)</span>
				 <span><a href="">
				 	CPI  List
				</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">
                CPI List 
              <div class="right-button">
                <button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('fgs/CPI/CPI-add')); ?>'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> 
						CPI
				</button>
              <div>  
				
              </div>
          </div>
        </h4>	
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
														<label>CPI No :</label>
														<input type="text" value="<?php echo e(request()->get('cpi_number')); ?>" name="cpi_number" id="cpi_number" class="form-control" placeholder="CPI NO">
													</div><!-- form-group -->
													
													
													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label for="exampleInputEmail1" style="font-size: 12px;">Customer</label>
														<input type="text" value="<?php echo e(request()->get('customer')); ?>" name="customer" id="customer" class="form-control" placeholder="CUSTOMER">
													</div>
													
													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label  style="font-size: 12px;">CPI Month</label>
														<input type="text" value="<?php echo e(request()->get('from')); ?>" id="from" class="form-control datepicker" name="from" placeholder="Month(MM-YYYY)">
													</div>
			
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
									<th>CPI Number</th>
                                    <th>CPI Date</th>
									<th>Customer</th>
									<th>PI Number</th>
									<th>PI Date</th>
                                    <th>Action</th>
								</tr>
							</thead>
							<tbody id="prbody1">
							<?php $__currentLoopData = $cpi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
									
									<td><?php echo e($item['cpi_number']); ?></td>
									<td><?php echo e(date('d-m-Y', strtotime($item['cpi_date']))); ?></td>
									<td><?php echo e($item['firm_name']); ?></td>
									<td><?php echo e($item['pi_number']); ?></td>
									<td><?php echo e(date('d-m-Y', strtotime($item['pi_date']))); ?></td>
									 <td>
										<a class="badge badge-info" style="font-size: 13px;" href="<?php echo e(url('fgs/CPI/item-list/'.$item["id"])); ?>"  class="dropdown-item"><i class="fas fa-eye"></i> Item</a> 
										<a class="badge badge-default" style="font-size: 13px; color:black;border:solid black;border-width:thin;margin-top:2px;" href="<?php echo e(url('fgs/CPI/pdf/'.$item["id"])); ?>" target="_blank"><i class="fas fa-file-pdf" style='color:red'></i>&nbsp;PDF</a>	
									</td>
								</tr>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</tbody>
						</table>
						<div class="box-footer clearfix">
                        <?php echo e($cpi->appends(request()->input())->links()); ?>

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
	$('.search-btn').on( "click", function(e)  {
		var cpi_number = $('#cpi_number').val();
		var customer = $('#customer').val();
		var from = $('#from').val();
		if(!pi_number   & !customer & !from)
		{
			e.preventDefault();
		}
	});
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/pages/FGS/CPI/CPI-list.blade.php ENDPATH**/ ?>
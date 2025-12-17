
<?php $__env->startSection('content'); ?>

<?php $SupplierQuotation = app('App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController'); ?>
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="" style="color: #596881;">BATCHCARD</a></span> 
                <span><a href="" style="color: #596881;">
                BATCHCARD LIST 
                </a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;">Batchcard List
			<button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('batchcard/batchcard-add')); ?>'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Batchcard</button>
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
			   <i class="icon fa fa-check"></i> <?php echo e(Session::get('success')); ?>

		   </div>
		   <?php endif; ?>
		   <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $errorr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="alert alert-danger "  role="alert" style="width: 100%;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo e($errorr); ?>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
											#example1_filter{
												display:none;
											}
										</style>
										<form autocomplete="off" id="formfilter">
											<th scope="row">
												<div class="row filter_search" style="margin-left: 0px;">
													<div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
														<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
															<label>Batch No:</label>
															<input type="text" value="<?php echo e(request()->get('batch_no')); ?>" name="batch_no"  id="batch_no" class="form-control" placeholder="BATCH NO">
														</div><!-- form-group -->
									
														<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
															<label  style="font-size: 12px;">SKU Code</label>
															<input type="text" value="<?php echo e(request()->get('sku_code')); ?>" id="sku_code" class="form-control" name="sku_code" placeholder="SKU Code">
														</div> 
														<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
															<label  style="font-size: 12px;">Process Sheet</label>
															<input type="text" value="<?php echo e(request()->get('process_sheet')); ?>"  class="form-control " name="process_sheet" id="process_sheet" placeholder="Process Sheet" >
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
					<form autocomplete="off" id="formprint" method="post" action="<?php echo e(url('batchcard/print')); ?>">
					<?php echo e(csrf_field()); ?>  
					<div class="table-responsive">
						<button style="float: right;font-size: 14px;" type="submit"   class="badge badge-pill badge-info submitbatchcard"><i class="fas fa-file-pdf"></i> Print TopSheet</button><br/>
						<table class="table table-bordered mg-b-0" id="example1" style="margin-top:10px;">
							<thead>
								<tr>
									<th><input type="checkbox" class="item-select-radio  check-all"></th>
									<th>Batch No</th>
									<th>Product SKU Code </th>
									<th>SKU Quantity</th>
									<th>Start Date</th>
									<th>End Date </th>
									<th>Process Sheet No</th>
									<th>Input Material & Quantity</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody >
								
							<?php $__currentLoopData = $data['batchcards']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>

							<td><input type="checkbox" class="check_batchcard" name="batchcard_id[]" value="<?php echo e($card['id']); ?>"></td>
                            <td><?php echo e($card['batch_no']); ?></td>
                            <td><?php echo e($card['sku_code']); ?></td>
                            <td><?php echo e($card['quantity']); ?></td>
                            <td><?php if($card['start_date']): ?> <?php echo e(date( 'd-m-Y' , strtotime($card['start_date']))); ?> <?php endif; ?></td>
                            <td><?php if($card['target_date']): ?> <?php echo e(date( 'd-m-Y' , strtotime($card['target_date']))); ?> <?php endif; ?></td>
                            <td><?php echo e($card['process_sheet_id']); ?></td>
                            <!-- <td>
							 <?php $__currentLoopData = $card['material']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($material['item_code'] != NULL): ?>
        <span><?php echo e($material['item_code']); ?></span> - 
        <span><?php echo e($material['quantity']); ?><?php echo e($material['unit_name']); ?></span><br/>
    <?php else: ?>
        Assembly
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> --> 
<td>
    
    <?php if(!empty($card['input_material'])): ?>
        <?php $__currentLoopData = $card['input_material']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $input): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($input['item1_code']): ?>
                <span><?php echo e($input['item1_code']); ?></span> - 
                <span><?php echo e($input['quantity1']); ?></span> 
				<span><?php echo e($input['unit_name']); ?></span><br/>
				<?php endif; ?>
            <?php if($input['item2_code']): ?>
                <span><?php echo e($input['item2_code']); ?></span> - 
                <span><?php echo e($input['quantity2']); ?></span> 
                <span><?php echo e($input['unit_name2']); ?></span><br/> 
            <?php endif; ?>
            <?php if($input['item3_code']): ?>
                <span><?php echo e($input['item3_code']); ?></span> - 
                <span><?php echo e($input['quantity3']); ?></span> 
                <span><?php echo e($input['unit_name3']); ?></span><br/> 
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</td>

							
							<td>
								<?php if($card['is_active']==1): ?>
								<button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
								<div class="dropdown-menu">
									<a href="<?php echo e(url('batchcard/edit?id='.$card["id"])); ?>" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
									<a href="<?php echo e(url('batchcard/delete?id='.$card["id"])); ?>" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> 
								</div>
								<?php endif; ?>
								<a class="badge badge-default" style="font-size: 13px; color:black;border:solid black;border-width:thin;margin-top:2px;" href="<?php echo e(url('batchcard/batchcard-list/'.$card['id'].'/report')); ?>" target="_blank"><i class="fas fa-file-pdf" style='color:red'></i>&nbsp;PDF</a>
								<a style="font-size: 11px; color:white;border:solid black;border-width:thin;margin-top:2px;" class="badge badge-primary inputmaterial-add" style="font-size: 13px;" href="#" data-batchId="<?php echo e($card["id"]); ?>" data-batchno="<?php echo e($card['batch_no']); ?>" data-sku="<?php echo e($card['sku_code']); ?>" data-productId="<?php echo e($card['product_id']); ?>" data-toggle="modal" data-target="#addInputMaterialModal"><i class="fas fa-plus"></i> Input Material</a>                 
							</td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</tbody>
						</table>
						<div class="box-footer clearfix">
							<?php echo e($data['batchcards']->appends(request()->input())->links()); ?>

						</div> 
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- az-content-body -->
	<div class="modal fade" id="addInputMaterialModal" role="dialog">
        <div class="modal-dialog modal-xs">
              <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="display: block;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Add BatchCard Input Material<span id="batchcard_number"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                            <form method="POST" id="commentForm"  action="<?php echo e(url('batchcard/add-input-material')); ?>" novalidate="novalidate" enctype='multipart/form-data'>
                                <?php echo e(csrf_field()); ?>

                                <div class="row">
									<table class="table table-bordered mg-b-0 sku">
									</table>
									
                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<br/>
										<h5>Input Materials</h5>
                                    	<input type="hidden" name="batch_id" id="batch_id" value="0">
										<input type="hidden" name="product_id" id="product_id" value="0">
										<table class="table table-bordered mg-b-0 input-material">
											
										</table>
                                    </div> 
								</div>
								<hr/>
								<div class="row">
                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                                class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                                role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                            Save
                                        </button>
                                    </div>
                                </div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script>
	// var dataTable = $('#example1').dataTable({
    //     "sPaginationType": "full_numbers",
    //     "ordering": false,
    // });
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
		var batch_no = $('#batch_no').val();
		var sku_code = $('#sku_code').val();
		var process_sheet = $('#process_sheet').val();
		if(!batch_no & !sku_code & !process_sheet)
		{
			e.preventDefault();
		}
	});
	
	
	$(".inputmaterial-add").on( "click", function() {
		var batch_number = $(this).data('batchno');
		$('#batchcard_number').html(' ('+batch_number+')');
		var batch_id = $(this).data('batchid');
		$('#batch_id').val(batch_id);
		var sku = $(this).data('sku');
		$('.sku').html('<tr><th>SKU CODE</th><th>'+sku +'</th></tr>');
		var product_id = $(this).data('productid');
		$('#product_id').val(product_id);
		$('.input-material').html('');
		if(product_id!=0)
		{
			$.get("<?php echo e(url('batchcard/get-InputMaterial')); ?>?product_id="+product_id+"&&batch_id="+batch_id,function(data)
			{
				//console.log(data);
					$('.input-material').html(data);
			});
		}
	});
	$(".check-all").click(function () {
     	$('.check_batchcard').not(this).prop('checked', this.checked);
    });

</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/pages/batchcard/batchcard-list.blade.php ENDPATH**/ ?>
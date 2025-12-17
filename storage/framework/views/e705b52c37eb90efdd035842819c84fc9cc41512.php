
<?php $__env->startSection('content'); ?>

<?php $SupplierQuotation = app('App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController'); ?>
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="">Final <?php if(request()->get('order_type')=='wo'): ?> Work <?php else: ?> Purchase <?php endif; ?> Order</a></span>
                <span><a>Excess Order Quantity</a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;"><?php if(request()->get('order_type')=='wo'): ?> Work <?php else: ?> Purchase <?php endif; ?> Order - Excess Order Quantity
            <button style="float: right;font-size: 14px;" onclick="document.location.href='<?php echo e(url('inventory/final-purchase/excel-export').'?'.http_build_query(array_merge(request()->all()))); ?>'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button> 
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
           
            <?php echo $__env->make('includes.purchase-details.purchase-work-order-tab', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                                
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label>RQ No:</label>
                                                            <input type="text" value="<?php echo e(request()->get('rq_no')); ?>" name="rq_no" id="rq_no" class="form-control" placeholder="RQ NO"> 
                                                            <input type="hidden" value="<?php echo e(request()->get('order_type')); ?>" id="order_type"  name="order_type">
                                                        </div><!-- form-group -->
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label><?php if(request()->get('order_type')=='wo'): ?> WO <?php else: ?> PO <?php endif; ?> No:</label>
                                                            <input type="text" value="<?php echo e(request()->get('po_no')); ?>" name="po_no" id="po_no" class="form-control" placeholder="<?php if(request()->get('order_type')=='wo'): ?> WO NO <?php else: ?> PO NO <?php endif; ?>">
                                                            
                                                        </div><!-- form-group -->
                                                        
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label for="exampleInputEmail1" style="font-size: 12px;">Supplier</label>
                                                            <input type="text" value="<?php echo e(request()->get('supplier')); ?>" name="supplier" id="supplier" class="form-control" placeholder="SUPPLIER">
                                                            
                                                        </div>
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label  style="font-size: 12px;"><?php if(request()->get('order_type')=='wo'): ?> WO <?php else: ?> PO <?php endif; ?> Date </label>
                                                            <input type="text" value="<?php echo e(request()->get('po_from')); ?>" id="po_from" class="form-control datepicker" name="po_from" placeholder="MM-YYYY">
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
                <div class="tab-pane active show " id="purchase">
                    <style>
                        tbody tr{
                            font-size:12.9px;
                        }
                    </style>
                    <div class="table-responsive">
                        <table class="table table-bordered mg-b-0" id="example1">
                            <thead>
                                <tr>
                                
                                    <th style="width:120px;">RQ NO:</th>
                                    <th><?php if(request()->get('order_type')=="wo"): ?> WO <?php else: ?> PO <?php endif; ?> No</th>
                                    <th>PO date</th>
                                    <th>Supplier</th>
                                    <th>Created Date</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data['po_data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $po_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr style="	<?php if($po_data->status == 5): ?>
                                    background: #ffc1074f;
                                    <?php endif; ?> <?php if($po_data->status == 0): ?> background: #ffc1074f;
                                    <?php endif; ?>"
                                    >
                                    <td><?php echo e($po_data->rq_no); ?></td>
                                    <td><?php echo e($po_data->po_number); ?></td>
                                    <td><?php echo e(date('d-m-Y',strtotime($po_data->po_date))); ?></td>
                                    <td><?php echo e($po_data->vendor_id); ?> - <?php echo e($po_data->vendor_name); ?></td>
                                    <td><?php echo e(date('d-m-Y',strtotime($po_data->created_at))); ?></td>
                                    <td><?php echo e($po_data->f_name); ?> <?php echo e($po_data->l_name); ?></td>
                                    <td>
                                    
                                    <button data-toggle="dropdown" style="width: 75px;" class="badge 
                                        <?php if($po_data->status==1): ?> badge-success <?php elseif($po_data->status==4): ?>  badge-warning <?php elseif($po_data->status==5): ?>  badge-warning <?php elseif($po_data->status==0): ?> badge-danger <?php endif; ?>"> 
                                        <?php if($po_data->status==1): ?> 
                                            Approved 
                                        <?php elseif($po_data->status==4): ?>  
                                            pending
                                        <?php elseif($po_data->status==5): ?>  
                                            On hold
                                        <?php elseif($po_data->status==0): ?>  
                                            Cancelled
                                        <?php endif; ?>
                                        <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i>
                                    </button>
								    <div class="dropdown-menu">
                                        <a href="<?php echo e(url('inventory/final-purchase-view/'.$po_data->id.'/excess-quantity')); ?>" class="dropdown-item" style="padding:2px 15px;"><i class="fas fa-eye"></i> View</a>
                                        <a href="<?php echo e(url('inventory/final-purchase-delete/'.$po_data->id)); ?>" class="dropdown-item"onclick="return confirm('Are you sure you want to delete this ?');"><i class="fa fa-trash"></i> Delete</a>
                                    </div>
                                    <?php if($po_data->status==1): ?>
                                        <a href="<?php echo e(url('inventory/final-purchase-view/'.$po_data->id.'/excess-quantity')); ?>"   po="<?php echo e($po_data->po_number); ?>" status="<?php echo e($po_data->status); ?>" rq="<?php echo e($po_data->rq_no); ?>" podate="<?php echo e(date('d-m-Y',strtotime($po_data->po_date))); ?>" supplier ="<?php echo e($po_data->vendor_name); ?>" value="<?php echo e($po_data->po_id); ?>" data-target="#excessOrderModal" 
                                             class="excess-order-model badge badge-warning" style="width:92px;padding:6px;margin-top:2px;background-color:#FF5733 ;color:white;">
                                            <i class="fa fa-window-close"></i> Excess Order
                                        </a>
                                    <?php endif; ?>
                                    </td>
                                </tr>
                                
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            <?php echo e($data['po_data']->appends(request()->input())->links()); ?>

                    </div> 
                
                    </div>
                </div>

            </div>
		</div>
	</div>
	<!-- az-content-body -->
    <div id="cancelModal" class="modal">
        <div class="modal-dialog modal-md" role="document">
            <form id="status-change-form" method="post" action="<?php echo e(url('inventory/final-purchase/change/status')); ?>">
                <?php echo e(csrf_field()); ?> 
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">#Cancel <?php if(request()->get('order_type')=="wo"): ?> Work Order <?php else: ?> Purchase Order <?php endif; ?> <span class="po_number"></span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputAddress2">Status *</label><br>
                            
							<input type="hidden" name="po_id" id ="po-id" class="po-id">
                            <input type="hidden" name="poc" value="poc">
                            <input type="hidden" value="<?php echo e(request()->get('order_type')); ?>" id="order_type"  name="order_type">
                            <select class="form-control" name="status" id="status">
                                <option value="0" selected>Cancel</option>
                            </select>
                        </div> 
                        <div class="form-group">
                            <label>Date *</label>
                            <input type="text" 
                                value="<?php echo e(date('d-m-Y')); ?>" class="form-control datepicker2" name="date" placeholder="Date">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Canceled By *</label><br/>
                            <style>
                                    .select2-container .select2-selection--single {
                                        height: 38px;
                                        width: 450px;
                                    }
                                    .select2-container--default .select2-selection--single .select2-selection__arrow b{
                                        margin-left: 242px;
                                        margin-top: 2px;
                                    }
                                    .select2-container--open .select2-dropdown--above{
                                        width:445px;
                                    }
                                    .select2-container--default .select2-results>.select2-results__options{
                                        width: 433px;
                                    }
                            </style>
                            <select class="form-control select2 approved_by" name="approved_by">
                                <option value="">--- select one ---</option>
                                <?php $__currentLoopData = $data['users']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user['user_id']); ?>"><?php echo e($user['f_name']); ?> <?php echo e($user['l_name']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div> 
                        <div class="form-group">
                            <label for="inputAddress">Remarks</label>
                            <textarea style="min-height: 100px;" name="remarks" type="text" class="form-control" id="remarks" placeholder="Remarks"></textarea>
                        </div> 
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button type="submit" class="btn btn-primary" id="save"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
								role="status" aria-hidden="true"></span> <i class="fas fa-save"></i> Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div><!-- modal-dialog -->
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
 function cancelQuantity(id){
   let orderQtyAccept = parseInt($(".orderQtyAccept"+id).val());
   let orderQty =  parseInt($(".orderQty"+id).val());
   $('.orderQtyReject'+id).val((isNaN(parseInt(orderQty - orderQtyAccept)) ? orderQty : parseInt(orderQty - orderQtyAccept) )  );
 }
 function acceptQuantity(id){
   let orderQtyReject = parseInt($(".orderQtyReject"+id).val());
   let orderQty =  parseInt($(".orderQty"+id).val());
   $('.orderQtyAccept'+id).val((isNaN(parseInt(orderQty - orderQtyReject)) ? orderQty : parseInt(orderQty - orderQtyReject) )  );
 }
function quantityCheck(id,type){
    if(type =='accept'){
        if( parseInt($(".orderQtyAccept"+id).val()) >  parseInt($(".orderQty"+id).val())){
            $(".orderQtyAccept"+id).val(Math.floor($(".orderQtyAccept"+id).val() /10));
        }
        if( parseInt($(".orderQtyAccept"+id).val()) < 0){
            $(".orderQtyAccept"+id).val(0);
        }
         cancelQuantity(id);
    }
    if(type =='reject'){
        if( parseInt($(".orderQtyReject"+id).val()) >  parseInt($(".orderQty"+id).val())){
            $(".orderQtyReject"+id).val(Math.floor($(".orderQtyReject"+id).val() /10));
        }
        
        if( parseInt($(".orderQtyReject"+id).val()) < 0){
            $(".orderQtyReject"+id).val(0);
        }
        acceptQuantity(id);

    }
}

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
    $(".datepicker2").datepicker({
        format: " dd-mm-yyyy",
        autoclose:true
    });
  /*  $("#status-change-form").validate({
            rules: {
                status: {
                    required: true,
                },
                date: {
                    required: true,
                },
                remarks: {
                    required: true,
                },
                approved_by:{
                    required: true,
                }
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                //form.submit();
            }
    });
*/
  });
    

  
	$('.search-btn').on( "click", function(e)  {
		var supplier = $('#supplier').val();
		var rq_no = $('#rq_no').val();
		var po_no = $('#po_no').val();
		var po_from = $('#from').val();
        var processed_from = $('#processed_from').val();
        var status = $('#status').val();
		if(!supplier & !rq_no & !po_no & !from & !processed_from & !status)
		{
			e.preventDefault();
		}
	});

    $(document).ready(function() {
        $('body').on('click', '#excess-order-model', function (event) {
            $(".binding").empty();
            event.preventDefault();
            var po = $(this).attr('po');
            $('.po-number').html(po);
            $('.po_number').html('('+po+')');
			let po_id = $(this).attr('value');
			$('#po_id').val(po_id);
            var rq = $(this).attr('rq');
            $('.rq-number').html(rq);
            var supplier = $(this).attr('supplier');
            $('.supplier').html(supplier);
            var po_date = $(this).attr('podate');
            $('.po-date').html(po_date);
          //  quantityCheck();
            $.ajax ({
                    type: 'GET',
                    url: "<?php echo e(url('getOrderItems')); ?>",
                    data: { po_id: '' + po_id + '' },
                    success : function(data) {
                        $('.binding').append(data);
                    }
                });

        });
        $('body').on('click', '#cancel-model', function (event) {
            event.preventDefault();
            var po = $(this).attr('po');
            $('.po_number').html('('+po+')');
			let po_id = $(this).attr('value');
           // alert(po_id);
			$('#po-id').val(po_id);
        });
         
    });
    
    
	
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/pages/purchase-details/final-purchase/excess-order-qty.blade.php ENDPATH**/ ?>
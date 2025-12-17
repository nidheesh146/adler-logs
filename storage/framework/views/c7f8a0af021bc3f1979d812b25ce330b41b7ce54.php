
<?php $__env->startSection('content'); ?>
<?php $fn = app('App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController'); ?>

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"><span><a href="<?php echo e(url('inventory/supplier-quotation')); ?>">SUPPLIER QUOTATION </a></span> <span>Comparison of quotation</span> </div>
			<h4 class="az-content-title" style="font-size: 20px;">Comparison of quotation <span>( <?php echo e($rq_number); ?> )</span>
            </h4>
			<div class="alert alert-success success" style="width: 100%;display:none;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fa fa-check"></i> Quotation selected successfully..
            </div>
                   
            <div class="alert alert-danger danger"  role="alert" style="width: 100%;display:none;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                Quotation selection failed
            </div>
                 
            <style>
                input[type="radio"]{
                    appearance: none;
                    border: 1px solid #d3d3d3;
                    width: 30px;
                    height: 30px;
                    content: none;
                    outline: none;
                    margin: 0;
                    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
                    background-color: #fff;
                }

                input[type="radio"]:checked {
                appearance: none;
                outline: none;
                padding: 0;
                content: none;
                border: none;
                }

                input[type="radio"]:checked::before{
                position: relative;
                color: green !important;
                content: "\00A0\2713\00A0" !important;
                border: 1px solid #d3d3d3;
                font-weight: bolder;
                font-size: 21px;
                }

               th, td {
                border-color: black;
                color:#1c273c;
                }
                thead th{
                    color:red;
                }
                
            </style>
            <?php if($supplier_data): ?>
			<div class="table-responsive" style=" border-color:black;width:1000px; overflow-x: scroll;">
				<table class="table table-bordered " id="example1" class="table1">
                <colgroup>
                <?php
                    function bgcolor(){return dechex(rand(0,10000000));}
                ?>
                    <col span="2">
                    <?php if(!empty($suppliers)): ?>
                    <?php $i=0; ?>
                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <col span="5" style="border-color:black; background-color:#<?php echo bgcolor(); ?>">
                    <?php $i++; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </colgroup>
					<thead>
						<tr>
							<th  rowspan="2" style="color:#1c273c;">Item </th>
							<!-- <th  rowspan="2" style="color:#1c273c;">Item Code</th> -->
							<th  rowspan="2" style="color:#1c273c;">Item HSN</th>
                            <?php if(!empty($suppliers)): ?>
				            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<th colspan="5" style="color:black; font-size:15px;">
                                <center><?php echo e($supplier['vendor_name']); ?></center>
                                
                                <!-- <div style="font-size:10px;text-align:center;margin-top:-10px;">(Delivery Date :<?php echo e(date('d-m-Y',strtotime($supplier['commited_delivery_date']))); ?>)</div> -->
                            </th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
						</tr>
                        <tr>
                        <?php if(!empty($suppliers)): ?>
                        <input type="hidden" name="item_count"  id="item_count" value="<?php echo e(count($supplier_data)); ?>">
				            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th></th>
                            <th width="5%" style="color:#1c273c;">Rate</th>
                            <th style="color:#1c273c;">Qty</th>
                            <th style="color:#1c273c;">Discount(%)</th>
                            <th style="color:#1c273c;">Total</th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </tr>
                        
					</thead>
					<tbody >
                    <?php if(!empty($supplier_data)): ?>
						<?php $__currentLoopData = $supplier_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                        <tr>
                            <?php $i=1;?>
                            <td ><?php echo e($item['item_name']); ?></td>
                            <!-- <td><?php echo e($item['item_code']); ?></td> -->
                            <td><?php echo e($item['hsn_code']); ?></td>
                            <?php $__currentLoopData = $item['price_data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $j=1;?>
                            <td>
                            <input type="radio" class="item-select-radio" name="<?php echo e($data['radio_name']); ?>" value="<?php echo e($data['itemId']); ?>" data-quotation="<?php echo e($rq_no); ?>"  data-supplier="<?php echo e($data['supplier_id']); ?>" <?php if($data["selected_item"]==1): ?> checked <?php endif; ?>></td>
                            <td class="supplier_rate" ><?php if($data['rate']==NULL): ?> 0 <?php else: ?> <?php echo e($data['rate']); ?> <?php echo e($data['currency_code']); ?> <?php endif; ?></td>
                            <td class="quantity" ><?php echo e($data['quantity']); ?> <?php echo e($item['unit_name']); ?></td>
                            <td class="quantity" ><?php echo e($data['discount']); ?></td>
                            <td class="total<?php echo e($i++); ?>"><?php echo e($data['total']); ?> <span class="currency"><?php echo e($data['currency_code']); ?></span></td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
						</tr>                 
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
                    <?php endif; ?>
                    <tr>
                    <td colspan="2"></td>
                    <?php if(!empty($suppliers)): ?>
				    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td colspan="4">
                            <span style="float:right">Total :</span>
                        </td>
                        <td class="grant_total"><span class="tot"></span><span class="currency_coe"><?php echo e($fn->getCurrency_code($rq_no,$supplier['id'])); ?></span></td>
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td colspan="5"><strong>Remarks:</strong><?php echo e($fn->getRemarks($rq_no,$supplier['id'])); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                    <?php endif; ?>
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
            <?php else: ?>
            <div class="row">
            <div class="alert alert-success success" style="width: 100%;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fa fa-check"></i> It is the fixed rate item, No need to Compare..
            </div>
            </div>
            <?php endif; ?>
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
<script type="text/javascript">
   var getSum = function (colNumber) {
    var sum = 0;
    var selector = '.total' + colNumber;
    
    $('#example1').find(selector).each(function (index, element) {
        sum += parseInt($(element).text());
    });  
    return sum;        
};

$('#example1').find('.tot').each(function (index, element) {
    $(this).text(  getSum(index + 1)); 
    var currency = $('#example1').find('.currency:first').text();
    //alert(currency);
    $(this).next('.currency_code').text(currency);
});
$('.item-select-radio').on('change', function() {
    let item_id = $(this).val();
    let quotation_id = $(this).data('quotation');
    let supplier = $(this).data('supplier');
    $.ajax({
           type:'POST',
           url:"<?php echo e(url('inventory/select-quotation-items')); ?>",
           data:{ "_token": "<?php echo e(csrf_token()); ?>",quotation_id:quotation_id, item_id:item_id, supplier:supplier},
           success:function(data){
            
            //location.reload();
            //   if(data == 1)
            //   { 
            //     $(".success").show();
            //     //alert('Quotation Selected successfuly');
            //   }
            //   else 
            //   {
            //     $(".danger").show();
            //     //alert('Quotation Selection failed');
            //   }
           }
    });
});

document.addEventListener('visibilitychange', e=>{
     if (document.visibilityState === 'visible') 
     {
    } 
    else 
    {
        var itemcount = $('#item_count').val();
        var numberOfChecked = $('input:radio:checked').length;
        // alert(numberOfChecked);
        // alert(itemcount);
        if(itemcount!=numberOfChecked)
        {
            if(!confirm('You did not check all items, Do you want to proceed?' )) 
            { 
                return false; 
                e.preventDefault();
            } 
        }
    }  
});
// $(".select-button").on("click", function(){
//     var quotation_id = $(this).data('quotation');
//     var supplier = $(this).data('supplier');
//     $(".danger").hide();
//     $(".success").hide();
//     //alert(supplier);
//     $.ajax({
//            type:'POST',
//            url:"<?php echo e(url('inventory/select-quotation')); ?>",
//            data:{ "_token": "<?php echo e(csrf_token()); ?>",quotation_id:quotation_id, supplier:supplier},
//            success:function(data){
//             location.reload();
//               if(data == 1)
//               {
                
//                 $(".success").show();
//                 //alert('Quotation Selected successfuly');
//               }
//               else 
//               {
//                 $(".danger").show();
//                 //alert('Quotation Selection failed');
//               }
//            }
//     });
// });

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/pages/purchase-details/supplier-quotation/comparison-quotation.blade.php ENDPATH**/ ?>
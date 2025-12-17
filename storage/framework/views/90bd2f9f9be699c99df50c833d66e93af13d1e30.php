
<?php $__env->startSection('content'); ?>

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="<?php echo e(url('inventory/get-purchase-reqisition')); ?>" style="color: #596881;">PURCHASE DETAILS</a></span> 
                <span><a href="<?php echo e(url('inventory/get-purchase-reqisition')); ?>" style="color: #596881;"><?php if(request()->get('prsr')=="sr" || request()->sr_id): ?> SERVICE REQUISITION <?php else: ?> PURCHASE REQUISITION <?php endif; ?></a></span>
                <span><a href="">
                   <?php if((request()->pr_id) AND (!request()->sr_id)): ?> 
                        Edit Purchase Requestor Details
                    <?php elseif((!request()->pr_id) AND (request()->sr_id)): ?>
                        Edit Service Requestor Details  
                    <?php elseif(request()->get('prsr')=="sr"): ?>
                        Add Service Requestor Details
                    <?php else: ?>
                        Add Purchase Requestor Details
                   <?php endif; ?>
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                    <?php if((request()->pr_id) AND (!request()->sr_id)): ?> 
                        Purchase Requestor Details
                    <?php elseif((!request()->pr_id) AND (request()->sr_id)): ?>
                         Service Requestor Details  
                    <?php elseif(request()->get('prsr')=="sr"): ?>
                         Service Requestor Details
                    <?php else: ?>
                         Purchase Requestor Details
                   <?php endif; ?>
            </h4>
            <div class="az-dashboard-nav">
                <nav class="nav">
                    <a class="nav-link  active  " href=""><?php if(request()->get('prsr')=="sr" || request()->sr_id): ?> Service <?php else: ?> Purchase <?php endif; ?> Requestor Details </a>
                     <a class="nav-link  " <?php if(request()->pr_id): ?> href="<?php echo e(url('inventory/get-purchase-reqisition-item?pr_id='.request()->pr_id)); ?>" <?php endif; ?> >  <?php if(request()->get('prsr')=="sr" || request()->sr_id): ?> Service <?php else: ?> Purchase <?php endif; ?> Requisition Details </a>
                     <a class="nav-link  " href=""> </a>
                </nav>
           
            </div>

			<div class="row">
                    
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    <?php if(Session::get('success')): ?>
                    <div class="alert alert-success " style="width: 100%;">
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
                   
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" id="commentForm" autocomplete="off" >
               

                        <?php echo e(csrf_field()); ?>  
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Basic details  
                                
                                    <?php if(!empty($data['inv_purchase_req_master'])): ?>
                                        <?php if(request()->pr_id): ?>
                                            ( PR NO : <?php echo e($data['inv_purchase_req_master']['pr_no']); ?> )
                                        <?php else: ?>
                                            ( SR NO : <?php echo e($data['inv_purchase_req_master']['pr_no']); ?> )
                                        <?php endif; ?>
                                    <?php endif; ?>
                                
                        
                                </label>
                                <div class="form-devider"></div>
                            </div>
                         </div>

                        <div class="row">


                            

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Requestor *</label>
                                <select class="form-control select2 requestor" name="Requestor">
                                    <?php $__currentLoopData = $data['users']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <option value="<?php echo e($user['user_id']); ?>"
                                     <?php if(!empty($data['inv_purchase_req_master'])): ?> 
                                       <?php if($user['user_id'] == $data['inv_purchase_req_master']['requestor_id']): ?>
                                           selected
                                       <?php endif; ?>
                                     <?php elseif(config('user')['user_id']== $user['user_id']): ?>
                                        selected
                                    <?php endif; ?>
                                     ><?php echo e($user['f_name']); ?> <?php echo e($user['l_name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div><!-- form-group -->


                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Department *</label>
                                <select class="form-control select2" name="Department">
                                    <option value="">--- select one ---</option>
                                    <?php $__currentLoopData = $data['Department']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <option value="<?php echo e($item['id']); ?>"
                                    <?php if(!empty($data['inv_purchase_req_master'])): ?>
                                       <?php if($item['id'] == $data['inv_purchase_req_master']['department']): ?>
                                           selected
                                       <?php endif; ?>
                                    <?php elseif(config('user')['department']== $item['dept_name']): ?>
                                        selected
                                     <?php endif; ?>
                                     ><?php echo e($item['dept_name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Date *</label>
                            <input type="text" 
                                value="<?php echo e((!empty($data['inv_purchase_req_master'])) ? date('d-m-Y',strtotime($data['inv_purchase_req_master']['date'])) : date('d-m-Y')); ?>"
                                class="form-control datepicker" name="Date" placeholder="Date">
                            </div><!-- form-group -->

                        </div> 
                      

              
            
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                <?php if(!empty($data['response']['purchase_requisition'][0])): ?>
                                    Update
                                <?php else: ?> 
                                     Save & Next
                                <?php endif; ?>
                                
                                </button>
                            </div>
                        </div>
                        <div class="form-devider"></div>
                    </form>

                </div>
            </div>
            





        </div>
        





	</div>
	<!-- az-content-body -->
</div>




<script src="<?= url('') ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>

<script>
  $(function(){
    'use strict'

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true
    });
  //  .datepicker('update', new Date());

    $('.datepicker').mask('99-99-9999');
              

    $("#commentForm").validate({
            rules: {
                Requestor: {
                    required: true,
                },
                Department: {
                    required: true,
                },
                Date: {
                    required: true,
                },
                
                
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });

    
  });
  $('.requestor').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
    });
    $(document).ready(function() {
        $('form').submit(function() {
            $(this).find(':submit').prop('disabled', true);
        });
    });
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/pages/purchase-details/purchase-requisition/purchase-requisition-add.blade.php ENDPATH**/ ?>
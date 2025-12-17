
<?php $__env->startSection('content'); ?>

<div class="az-content az-content-dashboard">
  <br> 
    <div class="container">
        <div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">Cancellation Proforma Invoice(CPI)</a></span> 
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">
                   
                </a></span>
            </div>
    
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Cancellation Proforma Invoice(CPI)
            </h4>
            <div class="az-dashboard-nav">
           
            </div>

            <div class="row">
                    
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
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
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $errorr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo e($errorr); ?>

                </div>
             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>    
            <form method="POST" id="commentForm" autocomplete="off" >
                <?php echo e(csrf_field()); ?> 
                <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Basic details  
                                </label>
                                <div class="form-devider"></div>
                            </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" data-select2-id="7">
                            <label>PI Number *<span class="spinner-border spinner-button spinner-border-sm"
                                    style="display:none;" role="status" aria-hidden="true"></span></label>
                            <?php if(!empty($edit['pi'])): ?>
                            <input type="hidden" name="pi_number" value="<?php echo e($edit['pi']->pi_number); ?>">

                            <?php endif; ?>
                            <select class="form-control pi_number" name="pi_number" <?php if(!empty($edit['pi'])): ?> disabled <?php endif; ?>>
                                <!-- <option value="" ></option> -->
                                <?php if(!empty($edit['pi'])): ?>
                                    <option value="<?php echo e($edit['pi']->pi_number); ?>" selected><?php echo e($edit['pi']->pi_number); ?></option>
                                <?php endif; ?>
                            </select>
                    </div>
                       
                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <label>CPI date *</label>
                            <input type="text" class="form-control datepicker" value="<?php echo e(date("d-m-Y")); ?>" name="cpi_date" placeholder="pi date" id="cpi_date">
                    <?php if(!empty($edit['pi'])): ?>
                    <input type="hidden" name="stock_location" value="<?php echo e($edit['pi']->stock_location); ?>">
                    <?php endif; ?>
                </div>
                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label>Created by: *</label>
                        <select class="form-control user_list" name="created_by">
                        <?php $__currentLoopData = $data['users']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->user_id); ?>"   <?php if(!empty($edit['pi']) && $edit['pi']->created_by == $user->user_id): ?> selected  <?php endif; ?>   ><?php echo e($user->f_name); ?> <?php echo e($user->l_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                          
                        </select>
                </div>

                <?php if(!empty($edit['items'])): ?>

                  foreach ($edit['items'] as $item) {
        
                    <input type="text" name="sku_code" value="<?php echo e($item->sku_code); ?>">
                        
                    <input type="hidden" name="discription" value="<?php echo e($item->discription); ?>">
                    <input type="hidden" name="batch_no" value="<?php echo e($item->batch_no); ?>">
                    <input type="hidden" name="quantity" value="<?php echo e($item->quantity); ?>">
                 }
                <?php endif; ?>
                               
            </div>
             <div class="data-bindings" style="width:100%;">
             </div>
            </form>
  
    </div>
               
    </div>
        
    </div>
        <!-- az-content-body -->
    </div>

    <script src="<?=url('');?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>
    <script>
        $(document).ready(function() {
            $('form').submit(function() {
                $(this).find(':submit').prop('disabled', true);
            });
        });
      $(function(){
        'use strict'

        $("#commentForm").validate({
        rules: {
            miq_number: {
                required: true,
            },
            mac_date:{
                required: true,
            },
            created_by:{
                required: true,
            }

        },
        submitHandler: function(form) {
            $('.spinner-button').show();
            form.submit();
        }
    });


    $('.user_list').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',

      });


      });

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true,
    endDate: new Date()
    });
    $('.datepicker').mask('99-99-9999');


    $('.pi_number').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          piimupiputLength: 2,
          allowClear: true,
          ajax: {
          url: "<?php echo e(url('fgs/CPI/find-pi-number-for-cpi')); ?>",
          processResults: function (data) {
            return { results: data };

          }
        }
      }).on('change', function (e) {
        $('.spinner-button').show();

        let res = $(this).select2('data')[0];
        if(res){
          $.get("<?php echo e(url('fgs/CPI/find-pi-info')); ?>?id="+res.id,function(data){
            $('.data-bindings').html(data);
            $('.spinner-button').hide();
          });
        }else{
          $('.data-bindings').html('');
          $('.spinner-button').hide();
        }
      });

      //check all
 function toggleCheckboxes(headerCheckbox) {
            $('.rowCheckbox').prop('checked', headerCheckbox.checked);
            enableTextBox();
        }

        function enableTextBox() {
            const checkedCheckboxes = $('.rowCheckbox:checked');

            // Enable/disable qty_to_cancel inputs based on the number of checkboxes checked
            $('.qty_to_cancel').each(function() {
                const $row = $(this).closest('tr');
                const $checkbox = $row.find('.rowCheckbox');

                if ($checkbox.is(':checked') || checkedCheckboxes.length === 0) {
                    $(this).prop('disabled', false).prop('required', true);

                    // Set max attribute for qty_to_cancel based on the checked checkbox
                    if (checkedCheckboxes.length > 1) {
                        $(this).attr('max', function() {
                            return $row.find('td:eq(4)').text().replace('Nos', '').trim();
                        });

                        // Copy the value from "QUANTITY" to "QUANTITY TO CANCEL"
                        const quantityValue = $row.find('td:eq(4)').text().replace('Nos', '').trim();
                        $(this).val(quantityValue);
                    } else {
                        $(this).removeAttr('max').val('').prop('required', false);
                    }
                } else {
                    $(this).val('').prop('required', false).prop('disabled', true);
                }
            });
        }

        // Add a click event listener to individual row checkboxes
        $('.rowCheckbox').on('click', function() {
            enableTextBox();
        });

        // Add a click event listener to the "Select All" checkbox
        $('#selectAll').on('click', function() {
            toggleCheckboxes(this);
        });

    // function enableTextBox(cash) 
    // {
    //     const checkbox = $(cash);
    //     if(checkbox.is(':checked')){
    //         checkbox.closest('tr').find('.qty_to_cancel').attr("disabled", false);
    //         checkbox.closest('tr').find('.qty_to_cancel').attr("required", "true");
    //     }else{
    //         checkbox.closest('tr').find('.qty_to_cancel').val('');
    //         checkbox.closest('tr').find('.qty_to_cancel').attr("required", "false");
    //         checkbox.closest('tr').find('.qty_to_cancel').attr("disabled", true);
    //     }
    // }
    
    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/pages/FGS/CPI/CPI-add.blade.php ENDPATH**/ ?>

<!DOCTYPE html>
<html lang="en">
<?php echo $__env->make('includes.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<body class="az-body az-body-sidebar">
<?php echo $__env->make('includes.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="az-content az-content-dashboard-two">
<?php echo $__env->make('includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

       <?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->make('includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
</body>
</html>
<script>
       $(function(){
         'use strict'
 
         $('.az-sidebar .with-sub').on('click', function(e){
           e.preventDefault();
           $(this).parent().toggleClass('show');
           $(this).parent().siblings().removeClass('show');
         })
 
         $(document).on('click touchstart', function(e){
           e.stopPropagation();
 
           // closing of sidebar menu when clicking outside of it
           if(!$(e.target).closest('.az-header-menu-icon').length) {
             var sidebarTarg = $(e.target).closest('.az-sidebar').length;
             if(!sidebarTarg) {
               $('body').removeClass('az-sidebar-show');
             }
           }
         });
 
 
         $('#azSidebarToggle').on('click', function(e){
           e.preventDefault();
 
           if(window.matchMedia('(min-width: 992px)').matches) {
             $('body').toggleClass('az-sidebar-hide');
           } else {
             $('body').toggleClass('az-sidebar-show');
           }
         })
 
   
 
       });
     </script><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/layouts/default.blade.php ENDPATH**/ ?>
<?php $Controller = app('App\Http\Controllers\Controller'); ?>
<?php
       $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
        //echo $Action;
       // $query  =  $_GET;
?>

<div class="card bd-0" style="margin-top:-10px;">
    <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
        <nav class="nav nav-tabs">
            <?php
              $query['order_type'] = 'po';
            ?>
            <a class="nav-link 
            <?php if(empty(request()->order_type)): ?> 
               active
            <?php endif; ?>
            <?php if(!empty(request()->order_type) &&  request()->order_type == 'po'): ?>
               active
            <?php endif; ?>
            " href="<?=url()->current();?>?<?=http_build_query($query);?>" >Purchase Order</a>
            <?php
                $query['order_type'] = 'wo';
            ?>

            <a class="nav-link 
            <?php if(!empty(request()->order_type) &&  request()->order_type == 'wo'): ?>
               active
            <?php endif; ?>
            "  href="<?=url()->current();?>?<?=http_build_query($query);?>">  Work Order </a>
        </nav>   
    </div>
  </div>
<br/><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/includes/purchase-details/purchase-work-order-tab.blade.php ENDPATH**/ ?>
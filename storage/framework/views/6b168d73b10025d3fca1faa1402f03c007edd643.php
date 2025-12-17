<?php $Controller = app('App\Http\Controllers\Controller'); ?>
<?php
       $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
        //echo $Action;
     // $query  =  $_GET;
?>

<div class="card bd-0">
    <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
        <nav class="nav nav-tabs">
            <?php
              $query['prsr'] = 'pr';
            ?>
            <a class="nav-link 
            <?php if(empty(request()->prsr)): ?> 
               active
            <?php endif; ?>
            <?php if(!empty(request()->prsr) &&  request()->prsr == 'pr'): ?>
               active
            <?php endif; ?>
            " href="<?=url()->current();?>?<?=http_build_query($query);?>" id="purchase_tab">Purchase requisition</a>
            <?php
                $query['prsr'] = 'sr';
            ?>

            <a class="nav-link 
            <?php if(!empty(request()->prsr) &&  request()->prsr == 'sr'): ?>
               active
            <?php endif; ?>
            "  href="<?=url()->current();?>?<?=http_build_query($query);?>" id="service_tab">  Service requisition </a>
        </nav>   
    </div>
  </div>
<br/><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/includes/purchase-details/pr-sr-tab.blade.php ENDPATH**/ ?>
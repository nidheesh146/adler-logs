<?php
$routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
        //echo $Action;
?>
  <div class="card bd-0">
    <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
        <nav class="nav nav-tabs">
          <a class="nav-link <?php if(in_array($Action,['StockManagement.allLocations'])): ?> <?php echo e('active'); ?> <?php endif; ?>"  href="<?php echo e(url('fgs/stock-management/all-locations')); ?>">All </a>
          <a class="nav-link <?php if(in_array($Action,['StockManagement.location1Stock'])): ?> <?php echo e('active'); ?> <?php endif; ?>"  href="<?php echo e(url('fgs/stock-management/location1')); ?>">Location1 </a>
          <a class="nav-link <?php if(in_array($Action,['StockManagement.location2Stock'])): ?> <?php echo e('active'); ?> <?php endif; ?>"  href="<?php echo e(url('fgs/stock-management/location2')); ?>">Location2</a>
          <a class="nav-link <?php if(in_array($Action,['StockManagement.location3Stock'])): ?> <?php echo e('active'); ?> <?php endif; ?>"  href="<?php echo e(url('fgs/stock-management/location3')); ?>">Location3</a>
          <a class="nav-link <?php if(in_array($Action,['StockManagement.locationSNN'])): ?> <?php echo e('active'); ?> <?php endif; ?>"  href="<?php echo e(url('fgs/stock-management/locationSNN')); ?>">SNN Mktd</a>
          <a class="nav-link <?php if(in_array($Action,['StockManagement.locationSNNTrade'])): ?> <?php echo e('active'); ?> <?php endif; ?>"  href="<?php echo e(url('fgs/stock-management/locationSNNTrade')); ?>">Trade</a>
          <a class="nav-link <?php if(in_array($Action,['StockManagement.locationSNNOEM'])): ?> <?php echo e('active'); ?> <?php endif; ?>"  href="<?php echo e(url('fgs/stock-management/locationSNNOEM')); ?>">OEM</a>
          <a class="nav-link <?php if(in_array($Action,['StockManagement.locationAHPL'])): ?> <?php echo e('active'); ?> <?php endif; ?>"  href="<?php echo e(url('fgs/stock-management/locationAHPL')); ?>">AHPL Mktd </a>
          <a class="nav-link <?php if(in_array($Action,['StockManagement.MAAStock'])): ?> <?php echo e('active'); ?> <?php endif; ?>"  href="<?php echo e(url('fgs/stock-management/Jayon')); ?>"> Jayon Mktd </a> 
          <!-- <a class="nav-link <?php if(in_array($Action,['StockManagement.quarantineStock'])): ?> <?php echo e('active'); ?> <?php endif; ?>"  href="<?php echo e(url('fgs/stock-management/quarantine')); ?>"> Quarantine</a> -->
      </nav>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/includes/fgs/stock-location-tab.blade.php ENDPATH**/ ?>
@inject('Controller', 'App\Http\Controllers\Controller')
@php
       $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
        //echo $Action;
        $query  =  $_GET;
@endphp

<div class="card bd-0" style="margin-top:-10px;">
    <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
        <nav class="nav nav-tabs">
            <?php
              $query['order_type'] = 'purchase-order';
            ?>
            <a class="nav-link 
            @if(empty(request()->order_type)) 
               active
            @endif
            @if(!empty(request()->order_type) &&  request()->order_type == 'purchase-order')
               active
            @endif
            " href="<?=url()->current();?>?<?=http_build_query($query);?>" >Purchase Order</a>
            <?php
                $query['order_type'] = 'work-order';
            ?>

            <a class="nav-link 
            @if(!empty(request()->order_type) &&  request()->order_type == 'work-order')
               active
            @endif
            "  href="<?=url()->current();?>?<?=http_build_query($query);?>">  Work Order </a>
        </nav>   
    </div>
  </div>
<br/>
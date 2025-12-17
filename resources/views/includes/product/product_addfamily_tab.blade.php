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
          <a class="nav-link @if(in_array($Action,['product.Product-add-group'])){{'active'}} @endif"  href="{{url('product/Product-add-family')}}">Product Family</a>
          <a class="nav-link @if(in_array($Action,['product.Product-add-family'])) @endif"  href="{{url('product/Product-add-group')}}">Product Group </a>
          <a class="nav-link @if(in_array($Action,['product.Product-add-brand'])) @endif"  href="{{url('product/Product-add-brand')}}">Product Brand</a>
      </nav>
    </div>
</div>

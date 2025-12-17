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
          <a class="nav-link @if(in_array($Action,['Dcbackorder.GetAllDC'])){{'active'}} @endif"  href="{{url('fgs/Dcbackorder-report')}}">All</a>
          <a class="nav-link @if(in_array($Action,['Dcbackorder.PendingDC'])){{'active'}} @endif"  href="{{url('fgs/Dc-pending-report')}}">DC</a>
          <a class="nav-link @if(in_array($Action,['Dcbackorder.PendingCDC'])){{'active'}} @endif"  href="{{url('fgs/CDC-pending-report')}}">CDC </a>
      </nav>
    </div>
</div>

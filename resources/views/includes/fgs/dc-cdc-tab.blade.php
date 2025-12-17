<?php
$routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
       // echo $Action;
?>
  <div class="card bd-0">
    <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
        <nav class="nav nav-tabs">
          <a class="nav-link @if(in_array($Action,['DCreport.DCReport'])){{'active'}} @endif"  href="{{url('fgs/DC-report')}}">DC Report</a>
          <a class="nav-link @if(in_array($Action,['DCreport.CDCReport'])){{'active'}} @endif"  href="{{url('fgs/CDC-report')}}">CDC Report</a>
      </nav>
    </div>
</div>

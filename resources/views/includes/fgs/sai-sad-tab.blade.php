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
          <a class="nav-link @if(in_array($Action,['SAI.SAIlist'])){{'active'}} @endif"  href="{{url('fgs/SAI-list')}}">SAI</a>
          <a class="nav-link @if(in_array($Action,['SAD.SADlist'])){{'active'}} @endif"  href="{{url('fgs/SAD-list')}}">SAD</a>
      </nav>
    </div>
</div>

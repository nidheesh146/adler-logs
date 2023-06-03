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
          <a class="nav-link nav-sub-item @if(in_array($Action,['GRS.pendingGRS'])){{'active'}} @endif"  href="{{url('fgs/GRS/pending-report')}}">GRS </a>
          <a class="nav-link @if(in_array($Action,['OEF.pendingOEF'])){{'active'}} @endif"  href="{{url('fgs/OEF/pending-report')}}">OEF</a>
          <a class="nav-link @if(in_array($Action,['PI.pendingPI'])){{'active'}} @endif"  href="{{url('fgs/PI/pending-report')}}">PI</a>
      </nav>
    </div>
</div>

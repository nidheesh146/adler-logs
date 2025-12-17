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
          <a class="nav-link @if(in_array($Action,['NetBkBillingr.NetBillingReportAll'])){{'active'}} @endif"  href="{{url('fgs/net-all-billing-report')}}">All</a>
          <a class="nav-link @if(in_array($Action,['NetBkBillingr.NetBookingReport'])){{'active'}} @endif"  href="{{url('fgs/net-bking-report')}}">Net booking </a>
          <a class="nav-link @if(in_array($Action,['NetBkBillingr.NetBillingReport'])){{'active'}} @endif"  href="{{url('fgs/net-bk-billing-report')}}">Net billing</a>

</nav>
    </div>
</div>

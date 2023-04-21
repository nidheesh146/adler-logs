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
          <a class="nav-link @if (in_array($Action,['StockManagement.allLocations'])) {{'active'}} @endif"  href="{{ url('fgs/stock-management/all-locations') }}">All </a>
          <a class="nav-link @if (in_array($Action,['StockManagement.location1Stock'])) {{'active'}} @endif"  href="{{ url('fgs/stock-management/location1') }}">Location1 </a>
          <a class="nav-link @if (in_array($Action,['StockManagement.location2Stock'])) {{'active'}} @endif"  href="{{ url('fgs/stock-management/location2') }}">Location2</a>
          <a class="nav-link @if (in_array($Action,['StockManagement.location3Stock'])) {{'active'}} @endif"  href="{{ url('fgs/stock-management/location3') }}">Location3</a>
          <a class="nav-link @if (in_array($Action,['StockManagement.locationSNN'])) {{'active'}} @endif"  href="{{ url('fgs/stock-management/locationSNN') }}">SNN Mktd</a>
          <a class="nav-link @if (in_array($Action,['StockManagement.locationAHPL'])) {{'active'}} @endif"  href="{{ url('fgs/stock-management/locationAHPL') }}">AHPL Mktd </a>
          <a class="nav-link @if (in_array($Action,['StockManagement.MAAStock'])) {{'active'}} @endif"  href="{{ url('fgs/stock-management/MAA') }}"> Material Allocation Area(MAA) </a>
          <a class="nav-link @if (in_array($Action,['StockManagement.quarantineStock'])) {{'active'}} @endif"  href="{{ url('fgs/stock-management/quarantine') }}"> Quarantine</a>
      </nav>
    </div>
</div>

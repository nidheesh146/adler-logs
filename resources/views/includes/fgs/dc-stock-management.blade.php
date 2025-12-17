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
          <a class="nav-link @if (in_array($Action,['DeliveryNote.dc_transfer_stock'])) {{'active'}} @endif"  href="{{ url('fgs/Delivery_challan/Challan-stock-all-location') }}">All </a>
          <a class="nav-link @if (in_array($Action,['DeliveryNote.dc_transfer_stock_consignment'])) {{'active'}} @endif"  href="{{ url('fgs/Delivery_challan/Challan-stock-consignment') }}">Consignment </a>
          <a class="nav-link @if (in_array($Action,['DeliveryNote.dc_transfer_stock_loaner'])) {{'active'}} @endif"  href="{{ url('fgs/Delivery_challan/Challan-stock-loaner') }}">Loaner</a>
          <a class="nav-link @if (in_array($Action,['DeliveryNote.replacement'])) {{'active'}} @endif"  href="{{ url('fgs/Delivery_challan/Challan-stock-replacement') }}">Replacement</a>
          <a class="nav-link @if (in_array($Action,['DeliveryNote.dc_transfer_stock_demo'])) {{'active'}} @endif"  href="{{ url('fgs/Delivery_challan/Challan-stock-demo') }}">Demo</a>
          <a class="nav-link @if (in_array($Action,['DeliveryNote.dc_transfer_stock_samples'])) {{'active'}} @endif"  href="{{ url('fgs/Delivery_challan/Challan-stock-samples') }}">Samples</a>
          <a class="nav-link @if (in_array($Action,['DeliveryNote.dc_transfer_stock_samples'])) {{'active'}} @endif"  href="{{ url('fgs/Delivery_challan/Challan-stock-scheme') }}">Scheme</a>
          <a class="nav-link @if (in_array($Action,['DeliveryNote.dc_transfer_stock_samples'])) {{'active'}} @endif"  href="{{ url('fgs/Delivery_challan/Challan-stock-satellite') }}">Satellite</a>
          <a class="nav-link @if (in_array($Action,['DeliveryNote.dc_transfer_stock_samples.MAAStock'])) {{'active'}} @endif"  href="{{ url('fgs/stock-management/MAA') }}"> Material Allocation Area(MAA) </a>

      </nav>
    </div>
</div>

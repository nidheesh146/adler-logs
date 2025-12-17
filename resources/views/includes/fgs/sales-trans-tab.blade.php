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
          <a class="nav-link @if(in_array($Action,['Fgsreport.get_sales_data'])){{'active'}} @endif"  href="{{url('fgs/fgs-sales-report')}}">All</a>
          <a class="nav-link @if(in_array($Action,['OEF.oef_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-oef-transaction-report')}}">OEF </a>
          <a class="nav-link @if(in_array($Action,['COEF.coef_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-coef-transaction-report')}}">COEF</a>
          <a class="nav-link @if(in_array($Action,['PI.pi_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-pi-transaction-report')}}">PI</a>
          <a class="nav-link @if(in_array($Action,['CPI.cpi_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-cpi-transaction-report')}}">CPI</a>
          <a class="nav-link @if(in_array($Action,['DNI.dni_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-dni-transaction-report')}}">DNI</a>
          <a class="nav-link @if(in_array($Action,['EXI.exi_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-exi-transaction-report')}}">EXI</a>
          <a class="nav-link @if(in_array($Action,['SRNcontroller.srn_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-srn-transaction-report')}}">SRN</a>


      </nav>
    </div>
</div>

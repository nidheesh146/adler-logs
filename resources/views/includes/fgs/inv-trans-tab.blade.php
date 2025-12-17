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
          <a class="nav-link @if(in_array($Action,['Fgsreport.get_inv_data'])){{'active'}} @endif"  href="{{url('fgs/fgs-inv-report')}}">All</a>
          <a class="nav-link @if(in_array($Action,['MRN.mrn_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-mrn-transaction-report')}}">MRN </a>
          <a class="nav-link @if(in_array($Action,['GRS.grs_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-grs-transaction-report')}}">GRS</a>
          <a class="nav-link @if(in_array($Action,['CGRS.cgrs_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-cgrs-transaction-report')}}">CGRS</a>
          <a class="nav-link @if(in_array($Action,['MIN.min_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-min-transaction-report')}}">MIN</a>
          <a class="nav-link @if(in_array($Action,['MIN.cmin_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-cmin-transaction-report')}}">CMIN</a>
          <a class="nav-link @if(in_array($Action,['MTQ.mtq_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-mtq-transaction-report')}}">MTQ</a>
          <a class="nav-link @if(in_array($Action,['CMTQ.cmtq_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-cmtq-transaction-report')}}">CMTQ</a>
          <a class="nav-link @if(in_array($Action,['MIScontroller.mis_transaction'])){{'active'}} @endif"  href="{{url('fgs/fgs-mis-transaction-report')}}">MIS</a>
          <a class="nav-link @if(in_array($Action,['DCcontroller.dc_transaction'])){{'active'}} @endif"  href="{{url('fgs/DC-report-inv-transaction')}}">DC</a>
          <a class="nav-link @if(in_array($Action,['CDCcontroller.cdc_transaction'])){{'active'}} @endif"  href="{{url('fgs/CDC-report-inv-transaction')}}">CDC</a>



      </nav>
    </div>
</div>

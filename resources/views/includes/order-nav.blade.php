<?php
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
?>
<div class="az-dashboard-nav">
    <nav class="nav">
        {{-- <a class="nav-link @if (in_array($Action, ['Report.print_order'])) {{'active'}} @endif " href="{{ url('report/subscribers/order') }}"> Magazine order</a> --}}
        <a class="nav-link @if (in_array($Action, ['Report.commission_view','Report.commission_view_org','Report.sale_commission_list'])) {{'active'}} @endif"
        
        @if(config('organization')['type'] == 1)
        href="{{url('commission-view')}}" 
        @else
        href="{{url('commission-view-org/'.$Controller->hashEncode(config('organization')['org_id']))}}?date={{date("m-Y")}}" 
        @endif
        
        
        
        >Commission & Sale </a>
        <a class="nav-link @if (in_array($Action, ['Subscription.list_subscription'])) {{'active'}} @endif" href="#">Log</a>
        {{-- <a class="nav-link " href="{{ url('subscribers-renew') }}">Subscription renew</a> --}}
    </nav>
    <nav class="nav">
    </nav>
</div>
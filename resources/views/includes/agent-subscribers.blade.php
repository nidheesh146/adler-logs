<?php
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
?>
    <div class="az-dashboard-nav">
        <nav class="nav">
           
            @if (in_array('agent.list', config('permission'))) 
            <a class="nav-link @if (in_array($Action, ['Agent.index'])) {{'active'}} @endif " href="{{ url('agent/agents') }}">Agents </a>
            @endif
          
            @if (in_array('agent.orderrequest.list', config('permission'))) 
            <a class="nav-link @if (in_array($Action, ['Agent.list_subscription'])) {{'active'}} @endif " href="{{url('list-agent-subscription/agents')}}">  Order request</a>
            @endif

            @if (in_array('agent.invoice.list', config('permission'))) 
            <a class="nav-link @if (in_array($Action, ['Agent.all_agent_invoice_list'])) {{'active'}} @endif " href="{{url('all-agent-invoice/agents')}}"> Invoice</a>
            @endif

        </nav>
        <nav class="nav">
        </nav>
    </div>
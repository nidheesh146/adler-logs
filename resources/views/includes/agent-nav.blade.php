<?php
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
?>




<nav class="nav az-nav-column">
    @if (in_array('agent.list', config('permission')))  
  
    <a class="nav-link @if (in_array($Action, ['Agent.agentcreate'])) {{'active'}} @endif " style="{{ !$id ? 'cursor: no-drop;' : '' }}"
        href="{{ $id ? url('agent/create/' . $org . '/' . $id) : '#' }}"><i
            class="fas fa-user-plus" style="font-size: 13px;margin: 0;"></i> {{( config('organization')['type'] == 5 ) ? 'My profile' : 'Agent' }}
    </a>

    @endif
    @if (in_array('agent.orderrequest.list', config('permission')))  
    <a class="nav-link @if (in_array($Action, ['Agent.create'])) {{'active'}} @endif" style="{{ !$id ? 'cursor: no-drop;' : '' }}"
        href="{{ $id ? url('agent-subscription/create/' . $org . '/' . $id) : '#' }}"> <i class="fas fa-paper-plane" style="font-size: 13px;margin: 0;"></i> </i>
            Order request</a>
    @endif
    @if (in_array('agent.invoice.list', config('permission')))  
    <a class="nav-link @if (in_array($Action, ['Magazine.index','Agent.agent_invoice_list'])) {{'active'}} @endif"  href="{{url('agent-invoice/' . $org . '/' . $id)}}" style="font-size: 13px;margin: 0;"><i style="
                margin: 0;font-size: 17px;" class="fas fa-file-invoice"></i>Invoice</a>
    
    @endif          
     <a class="nav-link"  href="#" ></a>
</nav>
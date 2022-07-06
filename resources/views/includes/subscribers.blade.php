<?php
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
?>
<div class="az-dashboard-nav">
    <nav class="nav">
        <a class="nav-link @if (in_array($Action, ['Subscribers.index'])) {{'active'}} @endif " href="{{ url('subscribers/subscribers') }}">Subscribers </a>
        <a class="nav-link @if (in_array($Action, ['Subscription.list_subscription'])) {{'active'}} @endif" href="{{ url('list-subscription/subscription') }}">Subscriptions</a>
        {{-- <a class="nav-link " href="{{ url('subscribers-renew') }}">Subscription renew</a> --}}
    </nav>
    <nav class="nav">
    </nav>
</div>
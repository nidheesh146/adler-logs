@inject('Controller', 'App\Http\Controllers\Controller')

@foreach (config('notification') as $notification)
    @if ($notification['module'] == 'subscription' && $notification['sub_module'] == 'insert')
        <a href="{{ url('list-subscription/subscription') }}?subscription_id=&subscriber_id=&org=&magazine=&status=7">
            <div class="media new">
                <span> <i class="fas fa-newspaper"></i>&nbsp;<strong
                        style="color:red">{{ $notification['count'] }}</strong></span>
                <div class="media-body">
                    <p> <strong>Subscription : </strong> awaiting for approval</p>
                    <span>{{ date('M d,Y H:i', strtotime($notification['created_at'])) }}</span>
                </div>
            </div>
        </a>

    @elseif($notification['module'] == 'subscription' && ( $notification['sub_module'] == 'active' ||
        $notification['sub_module'] == 'deactive'))
        <a href="{{ url('list-subscription/subscription') }}?subscription_id=&subscriber_id=&org=&magazine=&status=7">
            <div class="media new">
                <span> <i class="fas fa-newspaper"></i>&nbsp;<strong
                        style="color:red">{{ $notification['count'] }}</strong></span>
                <div class="media-body">
                    <p> <strong>Subscription : </strong> {{ $notification['message'] }}</p>
                    <span>{{ date('M d,Y H:i', strtotime($notification['created_at'])) }}</span>
                </div>
            </div>
        </a>
    @elseif($notification['module'] == 'order_request' && $notification['sub_module'] == 'insert')
        <a href="{{ url('list-agent-subscription/agents') }}?request_id=&agent_id=&magazine=&from=&status=7">
            <div class="media new">
                <span> <i class="fas fa-newspaper"></i>&nbsp;<strong
                        style="color:red">{{ $notification['count'] }}</strong></span>
                <div class="media-body">
                    <p> <strong>Order request : </strong> awaiting for approval</p>
                    <span>{{ date('M d,Y H:i', strtotime($notification['created_at'])) }}</span>
                </div>
            </div>
        </a>
    @elseif($notification['module'] == 'order_request' && ( $notification['sub_module'] == 'active' ||
        $notification['sub_module'] == 'deactive'))
        <a
            href="{{ url('agent-subscription/create/agent/' . $Controller->hashEncode($Controller->agent_suscriber_id(['org_id' => $notification['org_id']]))) }}?Subscription_ID=&magazine=&from=&status=7">
            <div class="media new">
                <span> <i class="fas fa-newspaper"></i>&nbsp;<strong
                        style="color:red">{{ $notification['count'] }}</strong></span>
                <div class="media-body">
                    <p> <strong>Order request : </strong> {{ $notification['message'] }}</p>
                    <span>{{ date('M d,Y H:i', strtotime($notification['created_at'])) }}</span>
                </div>
            </div>
        </a>
    @elseif($notification['module'] == 'invoice' && $notification['sub_module'] == 'insert')
        <a
            href="{{ url('agent-invoice/agent/' . $Controller->hashEncode($Controller->agent_suscriber_id(['org_id' => $notification['org_id']]))) }}?request_id=&agent_id=&magazine=&from=&status=7">
            <div class="media new">
                <span> <i class="fas fa-newspaper"></i>&nbsp;<strong
                        style="color:red">{{ $notification['count'] }}</strong></span>
                <div class="media-body">
                    <p> <strong>Invoice : </strong> {{ $notification['message'] }}</p>
                    <span>{{ date('M d,Y H:i', strtotime($notification['created_at'])) }}</span>
                </div>
            </div>
        </a>
    @elseif($notification['module'] == 'payment' && $notification['sub_module'] == 'insert')
        <a href="{{ url('agent-transaction') }}?rAgent=&invoice_no=&transaction_id=&from=&to=&Status=7&type=">
            <div class="media new">
                <span> <i class="fas fa-newspaper"></i>&nbsp;<strong
                        style="color:red">{{ $notification['count'] }}</strong></span>
                <div class="media-body">
                    <p> <strong>Transaction : </strong> {{ $notification['message'] }}</p>
                    <span>{{ date('M d,Y  H:i', strtotime($notification['created_at'])) }}</span>
                </div>
            </div>
        </a>
    @elseif($notification['module'] == 'payment' &&  ( $notification['sub_module'] == 'active' ||
    $notification['sub_module'] == 'deactive'))
        <a href="{{ url('agent-payment/agent/'.$Controller->hashEncode($Controller->agent_suscriber_id(['org_id' => $notification['org_id']]))) }}?Agent=&invoice_no=&transaction_id=&from=&to=&Status=7&type=">
            <div class="media new">
                <span> <i class="fas fa-newspaper"></i>&nbsp;<strong
                        style="color:red">{{ $notification['count'] }}</strong></span>
                <div class="media-body">
                    <p> <strong>Transaction : </strong> {{ $notification['message'] }}</p>
                    <span>{{ date('M d,Y  H:i', strtotime($notification['created_at'])) }}</span>
                </div>
            </div>
        </a>
    @else
        <a href="#">
            <div class="media new">
                <span> <i class="fas fa-newspaper"></i>&nbsp;<strong
                        style="color:red">{{ $notification['count'] }}</strong></span>
                <div class="media-body">
                    <p> <strong>{{ $notification['module'] }}: </strong> {{ $notification['message'] }}</p>
                    <span>{{ date('M d,Y H:i', strtotime($notification['created_at'])) }}</span>
                </div>
            </div>
        </a>
    @endif
@endforeach

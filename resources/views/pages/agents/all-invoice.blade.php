@extends('layouts.default')
@section('content')
@inject('AgentController', 'App\Http\Controllers\Web\AgentController')
@inject('Controller', 'App\Http\Controllers\Controller')
<?php
$type = [1=>'state',2=>'district',3=>'mekhala',4=>'unit',5=>'agent'];
?>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span>Agents</span>
                    <span>Invoice</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Invoice
                    <div class="right-button">
                        <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                            <i class="fa fa-download" aria-hidden="true"></i> Download <i
                                class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                        <div class="dropdown-menu">
                        <a href="{{request()->fullUrl()}}{{ (count(request()->all('')) > 0)  ? '&' : '?' }}download=excel" class="dropdown-item">Excel</a>
                
                        </div>
                    <div>
                    <div>
                        {{-- <button data-toggle="dropdown" style="float: right;" class="badge badge-pill badge-dark "><i
                                class="fas fa-plus"></i> Subscriber <i
                                class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button> --}}
                        {{-- <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('agent/create/'.$type[config('organization')['type']]) }}'" class="badge badge-pill badge-dark "><i
                                    class="fas fa-plus"></i> Agent  </button> --}}

                        {{-- <div class="dropdown-menu">
                            <a href="{{ url('subscribers/create/state') }}" class="dropdown-item">Under my
                                organization </a>
                            <a href="{{ url('subscribers/create/district') }}" class="dropdown-item"> Under
                                District</a>
                            <a href="{{ url('subscribers/create/mekhala') }}" class="dropdown-item">Under Mekhala</a>
                            <a href="{{ url('subscribers/create/unit') }}" class="dropdown-item">Under Unit</a>
                        </div> --}}
                    </div>
                </h4>

             @include('includes.agent-subscribers')

                <div class="row row-sm mg-b-20 mg-lg-b-0">
                    <div class="table-responsive" style="margin-bottom: 13px;">
                        <table class="table table-bordered mg-b-0">
                            <tbody>
                                <tr>
                                    <form>
                                    <th scope="row">
                                        <div class="row filter_search" style="margin-left: 0px;">

                                            <div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
                                                {{-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <select class="form-control select2">
                                                        <option label="Choose Organization"></option>
                                                        <option value="Firefox">State</option>
                                                        <option value="Firefox">District</option>
                                                        <option value="Firefox">Mekhala</option>
                                                        <option value="Firefox">Unit</option>
                                                        <option value="Firefox">Agent</option>
                                                    </select>
                                                </div> --}}
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label>Invoice NO:</label>
                                                    <input type="text" value="{{request()->get('invoice')}}" name="invoice" class="form-control" placeholder="INVOICE NO">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label>Subscription ID</label>
                                                    <input type="text" value="{{request()->get('subscription')}}" name="subscription" class="form-control" placeholder="SUBSCRIPTION ID">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label>Suscriber ID</label>
                                                    <input type="text" value="{{request()->get('suscriber')}}" name="suscriber" class="form-control" placeholder="SUSCRIBER ID">
                                                </div><!-- form-group -->
                                      
                                                {{-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label>Request ID</label>
                                                    <input type="email" class="form-control" placeholder="MONTH & YEAR">
                                                </div>>
                                       --}}
                                 

                                                {{-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                           
                                      

                                                <select class="form-control select2">
                                                    <option value="Firefox">All {{ucfirst($org)}}  Organizations</option>
                                                  <option value="Firefox">unit 1</option>
                                                  <option value="Firefox">unit 2</option>
                                                </select>
                                            </div><!-- form-group --> --}}

                                            </div>

                                            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12"
                                                    style="padding: 0 0 0px 6px;">
                                                    <label style="    width: 100%;">&nbsp;</label>
                                                    <button type="submit" class="badge badge-pill badge-primary"
                                                    style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                  @if(count(request()->all('')) > 1)
                                                    <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                    style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                  @endif
                                            </div>
                                        </div>
                                    </form>
                                    </th>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="table-responsive">
                    @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                    </div>
                @endif

                    <table class="table table-bordered mg-b-0">
                        <thead>
                            <tr>
                                <th>INVOICE NO</th>
                                <th>SUBSCRIPTION ID</th>
                                <th>suscriber ID</th>
                                <th>Magazine <br> & Quantity</th>
                                <th>MONTH & YEAR</th>
                                <th>ISSUED	</th>
                                <th>DUE DATE</th>
                                <th>TOTAL DUE</th>
                     
                                <th></th>

                                {{-- <th>Users</th> --}}
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($data['invoice'] as $subscription)
                                <tr>
                                    <th> <a target="_blank" href="{{url('agent-invoice-pdf/'.$Controller->hashEncode($subscription['inv_id']))}}"  > {{$subscription['invoice_id']}}</a></th>
                                    <th><a href="{{url('agent-subscription/create/agent/'.$Controller->hashEncode($subscription['subr_id']).'/'.$Controller->hashEncode($subscription['subn_id']))}}" >{{$subscription['subscription_id']}}</a></th>
                                    <td><a href=" {{ url('agent/create/agent/'.$Controller->hashEncode($subscription['subr_id'])) }}">{{$subscription['subscriber_id']}}</a></td>
                                    <td><?=$AgentController->book_num($subscription['subn_id']);?> - {{$subscription['quantity']}} </td>
                                    <td>{{date('M-Y',strtotime($subscription['invoice_month']))}}</td>
                                    <td>{{date('d-m-Y',strtotime($subscription['issued_date']))}}</td>
                                    <td>{{date('d-m-Y',strtotime($subscription['expire_date']))}}</td>
                                    <td  >
                                        <a target="_blank" href="{{url('agent-invoice-pdf/'.$Controller->hashEncode($subscription['inv_id']))}}"  > {{sprintf("%.3f",($subscription['amount'] - $subscription['commission']))}}</a>
         
                                  </td>
                                    <td>
                                        <button data-toggle="dropdown" class="badge  badge-primary">Action <i
                                            class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                    <div class="dropdown-menu">
                                    <a target="_blank" href="{{url('agent-invoice-pdf/'.$Controller->hashEncode($subscription['inv_id']))}}"  class="dropdown-item"><i class="fas fa-eye"></i> View</a>
                                    <a target="_blank"href="{{url('agent-invoice-pdf/'.$Controller->hashEncode($subscription['inv_id']))}}?download=pdf"  class="dropdown-item"><i class="fas fa-download"></i> Download</a>
                                       
                                    </div> 
                                    </td>

                                </tr>
                            @endforeach




                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $data['invoice']->links(); }}
                    </div>
                </div>
            </div>



            <div id="modaldemo2" class="modal">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
    
    
                            <div class="row  timelines">
    
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- modal-dialog -->
    
    




        </div><!-- az-content-body -->
    </div>
    </div><!-- az-content -->




    <script src="<?= url('') ?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>

    <script>
        
         $('[data-toggle="tooltip"]').tooltip();
        $('.subscription-view').click(function(){
            $.get("<?= url('') ?>/agent-subscription-view/"+$(this).attr('id'), function( data ) {
              $('.timelines').html(data);
              $('#modaldemo2').modal('show');
            });
            });
        </script>

@stop

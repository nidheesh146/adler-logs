@extends('layouts.subscriber-default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
@inject('SubscriptionController', 'App\Http\Controllers\Web\SubscriptionController')
<?php

$type = [1=>'state',2=>'district',3=>'mekhala',4=>'unit',5=>'agent'];
?>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span>Dashboard</span>
                    {{-- <span> Magazine order</span> --}}
                </div>
            <h4 class="az-content-title" style="font-size: 20px;"> Subscription history  for '{{$subscriber->subscriber_id}} - {{$subscriber->f_name}} {{$subscriber->l_name}}'
               
                </h4>

            {{-- @include('includes.order-nav') --}}
{{-- 

                <div class="row row-sm mg-b-20 mg-lg-b-0">
                  
                    <div class="table-responsive" style="margin-bottom: 13px;">
                        <table class="table table-bordered mg-b-0">
                            <tbody>
                                <tr>
                                    <form>
                                    <th scope="row">
                                        <div class="row filter_search" style="margin-left: 0px;">

                                            <div class="col-sm-10 col-md-3 col-lg-3 col-xl-3 row">
                                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                    <label>Subscription ID</label>
                                                    <input type="text" name="subscription"  value="{{request()->get('subscription')}}" class="form-control" placeholder="Subscription ID">
                                                </div><!-- form-group -->
                                                <input type="hidden" class="form-control" name="search" value="true" >
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
                                                                 
                                        </div>
                                    </form>
                                    </th>
                            </tbody>
                        </table>
                    </div>
                </div> --}}


                <div class="table-responsive">

                    <table class="table table-bordered mg-b-0">
                        
                        <thead>
                            <tr>
                                <th>Subscription ID</th>
                                <th>BOOK</th>
                                <th>From</th>
                                <th>TO</th>
                                <th>CREATED</th>
                            </tr>
                        </thead>
                        <tbody>
                               @foreach( $data['subscription']  as $subscription)
                                <tr>
                                   <th>{{$subscription->subscription_id}}</th>
                                    <th> <?=$SubscriptionController->book_id($subscription->subn_id,true);?></th>
                                    <td>{{ date('d-m-Y',strtotime($subscription->subscription_from))}}</td>
                                    <td>{{ date('d-m-Y',strtotime($subscription->subscription_to))}}</td>
                                    <td>{{ date('d-m-Y',strtotime($subscription->created_at))}}</td>
                                </tr>
                               @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{  $data['subscription']->links() }}
                    </div>
                </div>
            </div>



        </div><!-- az-content-body -->
    </div>
    </div><!-- az-content -->




                <script src="<?= url('') ?>/js/azia.js"></script>
                  <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
                  <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>



@stop

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
                    <span>Order request</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Order request
                   
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
                                    <th scope="row">
                                      <form>
                                        <div class="row filter_search" style="margin-left: 0px;">
                                            <div class="col-sm-10 col-md-7 col-lg-7 col-xl-7 row">
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label for="exampleInputEmail1">Magazine</label>
                                                    <select class="form-control attr_magazine" name="magazine">
                                                        <option value="">Choose one</option>
                                                        @foreach ($data['magazine'] as $magazine)
                                                            <option value="{{ $magazine['id'] }}" {{(request()->get('magazine') == $magazine['id']) ? 'selected' : ''}}>
                                                                {{ $magazine['name'] . '-' . $magazine['magazine_id'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label>Request ID</label>
                                                    <input type="text" class="form-control" value="{{request()->get('request_id')}}" name="request_id" placeholder="Request ID">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label>Agent ID</label>
                                                    <input type="text" class="form-control" value="{{request()->get('agent_id')}}" name="agent_id" placeholder="Agent ID">
                                                </div>

                                            </div>

                                            <div class="col-sm-2 col-md-5 col-lg-5 col-xl-5 row">
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
                                <th>AGENT ID</th>
                                <th>request ID</th>
                                <th>Magazine <br> & Quantity</th>
                                <th>from</th>
                                <th>to</th>
                                <th>CREATED</th>

                     
                                <th></th>

                                {{-- <th>Users</th> --}}
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($data['suscription'] as $subscription)
                                <tr>
                                    <th> 
                                        @if (in_array('agent.edit', config('permission')))  
                                        <a href=" {{ url('agent/create/agent/'.$Controller->hashEncode($subscription['subr_id'])) }}">{{$subscription['subscriber_id']}}</a>
                                        @else 
                                        {{$subscription['subscriber_id']}}
                                        @endif
                                    
                                    </th>
                                    <th>
                                        @if (in_array('agent.orderrequest.edit', config('permission')))  
                                        <a href="{{url('agent-subscription/create/agent/'.$Controller->hashEncode($subscription['subr_id']).'/'.$Controller->hashEncode($subscription['subn_id']))}}" >{{$subscription['subscription_id']}}</a>
                                        @else 
                                        {{$subscription['subscription_id']}}
                                        @endif
                                    </th>
                                    <td><?=$AgentController->book_id($subscription['subn_id']);?></td>
                                    <td>{{date('M-Y',strtotime($subscription['subscription_from']))}}</td>
                           
                                    <td>{{date('M-Y',strtotime($subscription['subscription_to']))}}</td>
                                    <td>{{date('d-m-Y',strtotime($subscription['created_at']))}}</td>
                                    <td>
                                        <button data-toggle="dropdown" class="badge  badge-primary">Active <i
                                            class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                    <div class="dropdown-menu">
                                    <a href="#" id="{{$Controller->hashEncode($subscription['subn_id'])}}" class="dropdown-item subscription-view"><i class="fas fa-eye"></i> View</a>
                                    @if (in_array('agent.orderrequest.edit', config('permission')))  
                                      <a href="{{url('agent-subscription/create/'.$org.'/'.$Controller->hashEncode($subscription['subr_id']).'/'.$Controller->hashEncode($subscription['subn_id']))}}" class="dropdown-item "><i class="fas fa-edit"></i> Edit</a>
                                    @endif
                                    @if (in_array('agent.orderrequest.delete', config('permission')))  
                                    <a  onclick="return confirm('Are you sure you want to delete this ?');"  href="{{url('agent-subscription-delete/'.$org.'/'.$Controller->hashEncode($subscription['subn_id']).'/'.$Controller->hashEncode($subscription['subn_id']))}}?all=true" class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a> 
                                    @endif
                                </div>


                                    </td>

                                </tr>
                            @endforeach




                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $data['suscription']->links(); }}
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

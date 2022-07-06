@extends('layouts.default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
<?php
$type = [1=>'state',2=>'district',3=>'mekhala',4=>'unit',5=>'agent'];
?>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span>Agents</span>
                    <span>All Agents</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Agents
                      
                    <div class="right-button">
                        <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                            <i class="fa fa-download" aria-hidden="true"></i> Download <i
                                class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                        <div class="dropdown-menu">
                        <a href="{{request()->fullUrl()}}{{ (count(request()->all('')) > 0)  ? '&' : '?' }}download=excel" class="dropdown-item">Excel</a>
                
                        </div>
                    <div>
                        {{-- <button data-toggle="dropdown" style="float: right;" class="badge badge-pill badge-dark "><i
                                class="fas fa-plus"></i> Subscriber <i
                                class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button> --}}
                         @if (in_array('agent.add', config('permission')))        
                        <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('agent/create/'.$type[config('organization')['type']])}}'" class="badge badge-pill badge-dark "><i
                                    class="fas fa-plus"></i> Agent  </button>
                        @endif
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

                                            <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label>Agent ID</label>
                                                    <input type="text" value="{{request()->get('agent_id')}}"  name="agent_id" class="form-control" placeholder="Agent ID">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label>Name</label>
                                                    <input type="text" name="name" value="{{request()->get('name')}}" class="form-control" placeholder="Name">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label>Email</label>
                                                    <input type="email" name="email" value="{{request()->get('email')}}"  class="form-control" placeholder="Email">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label>Phone number</label>
                                                    <input type="text" name="number" value="{{request()->get('number')}}"  class="form-control" placeholder="Phone number">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label>PIN Code</label>
                                                    <input type="text" name="pin_code" value="{{request()->get('pin_code')}}"  class="form-control" placeholder="PIN Code">
                                                </div>
                                          

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
                                <th>Agent ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>PIN code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($data['suscriber'] as $suscriber)
                                <tr>
                                    <th>
                                    @if (in_array('agent.edit', config('permission')))  
                                        <a href=" {{ url('agent/create/' . $type[$suscriber['type_id']].'/'.$Controller->hashEncode($suscriber['subr_id'])) }}" >{{$suscriber['subscriber_id']}}</a>
                                    @else
                                        {{$suscriber['subscriber_id']}}
                                    @endif
                                    </th>
                                    <th>{{$suscriber['f_name']}} {{$suscriber['l_name']}}</th>
                                    <td>{{$suscriber['email']}}</td>
                                    <td>{{$suscriber['phone']}}</td>
                                    <td>{{$suscriber['pincode']}}</td>
                                   
                                    <td>
                                        
                                        <button data-toggle="dropdown" style="width: 64px;" class="badge {{($suscriber['status'] == 1 ) ? 'badge-success' : 'badge-danger' }}"> {{($suscriber['status'] == 1 ) ? 'Active' : 'Deactive' }}  <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                        <div class="dropdown-menu">
                                            @if (in_array('agent.orderrequest.list', config('permission')))  
                                            <a href="{{ url('agent-subscription/create/'.$type[$suscriber['type_id']].'/'.$Controller->hashEncode($suscriber['subr_id']))  }}"
                                                class="dropdown-item"><i class="fas fa-paper-plane" style="font-size: 13px;margin: 0;"></i> </i>
                                                Order request
                                            </a>
                                            @endif
                                            @if (in_array('agent.edit', config('permission')))  
                                               <a href=" {{ url('agent/create/' . $type[$suscriber['type_id']].'/'.$Controller->hashEncode($suscriber['subr_id'])) }}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
                                            @endif
                                            @if (in_array('agent.delete', config('permission')))  
                                                <a href=" {{ url('agent-subscribers-action/' . $org.'/'.$Controller->hashEncode($suscriber['subr_id'])).'/action' }}"  class="dropdown-item" onclick="return confirm('Are you sure you want to {{($suscriber['status'] == 1) ? 'deactive' : 'active' }} this ?');" ><?=($suscriber['status'] == 1) ? '<i class="fas fa-times"></i> Deactive' : '<i class="fas fa-check"></i> Active' ;?></a>
                                                <a href=" {{ url('agent-subscribers-action/' . $org.'/'.$Controller->hashEncode($suscriber['subr_id'])).'/delete' }}" onclick="return confirm('Are you sure you want to delete this ?');"  class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a>
                                            @endif
                                            </div>

                                    </td>

                                </tr>
                            @endforeach




                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $data['suscriber']->links() }}
                    </div>
                </div>
            </div>



        </div><!-- az-content-body -->
    </div>
    </div><!-- az-content -->


    <!-- SMALL MODAL -->
    <div id="modaldemo2" class="modal">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                {{-- <div class="modal-header">
   
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div> --}}
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <br>
                    <div class="container">

                        <ul class="timeline">

                            <li class="timeline-inverted">
                                <div class="timeline-badge " style="color:red;"><i class="fas fa-sitemap"></i></div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title">State</h4>
                                    </div>
                                    <div class="timeline-body">
                                        <p>State name</p>
                                    </div>
                                </div>
                            </li>

                            <li class="timeline-inverted">
                                <div class="timeline-badge " style="color:blue;"><i class="fas fa-sitemap"></i></div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title">District</h4>
                                    </div>
                                    <div class="timeline-body">
                                        <p>State name</p>
                                    </div>
                                </div>
                            </li>

                            <li class="timeline-inverted">
                                <div class="timeline-badge " style="color:green;"><i class="fas fa-sitemap"></i></div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title">Agent</h4>
                                    </div>
                                    <div class="timeline-body">
                                        <p>Agent name</p>
                                    </div>
                                </div>
                            </li>

                            <li class="timeline-inverted">
                                <div class="timeline-badge " style="color:#03a9f4;"><i class="fas fa-sitemap"></i></div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title">Mekhala</h4>
                                    </div>
                                    <div class="timeline-body">
                                        <p>Mekhala name</p>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-inverted">
                                <div class="timeline-badge " style="color:#ff9800;"><i class="fas fa-sitemap"></i></div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title">Unit</h4>
                                    </div>
                                    <div class="timeline-body">
                                        <p>Unit name</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                {{-- <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-indigo">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
          </div> --}}
            </div>
        </div><!-- modal-dialog -->
    </div><!-- modal -->




                <script src="<?= url('') ?>/js/azia.js"></script>
                  <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
                  <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>



@stop

@extends('layouts.default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
{{-- @inject('Organization', 'App\Http\Controllers\Web\OrganizationController') --}}
<?php
$type = [1=>'state',2=>'district',3=>'mekhala',4=>'unit',5=>'agent'];
?>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('organization/all') }}" >Organization </a></span>
                    <span> Pending Organizations</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">  Pending Organizations

                   
                    <div>
                    <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                        <i class="fa fa-download" aria-hidden="true"></i> Download <i
                            class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item">Excel</a>
                    </div>
                </h4>
              
            @if (in_array('organization.pending.list', config('permission')) && config('organization')['type'] == 1)
              <div class="az-dashboard-nav">
                    <nav class="nav">
                      <a class="nav-link " href="{{ url('organization/all') }}">Organization</a> 
                     <a class="nav-link active" href="{{ url('org/pending') }}">Pending  @if(config('org_pending_count') > 0 ) <span class="badge badge-pill badge-danger">{{config('org_pending_count')}}</span>@endif</a>
                      </nav>
                    <nav class="nav">
                    </nav> 
                </div>
               @endif





                {{-- <div class="row row-sm mg-b-20 mg-lg-b-0">
                    <div class="table-responsive" style="margin-bottom: 13px;">
                      
                        <table class="table table-bordered mg-b-0">
                            <tbody>
                                <tr>
                                    <th scope="row">

                                        <div class="row">

                                            <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">

                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label> Organization</label>

                                                    <select class="form-control select2">
                                                        <option value="all">All Organization</option>
                                                        <option value="Firefox">District</option>
                                                        <option value="Firefox">Mekhala</option>
                                                        <option value="Firefox">Unit</option>
                                                        <option value="Firefox">Agent</option>

                                                    </select>
                                                </div>


                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label> Name</label>
                                                    <input type="text" class="form-control" placeholder="Enter name">
                                                </div>




                                                <!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label>Email</label>
                                                    <input type="email" class="form-control" placeholder="Enter Email">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label>Owner name </label>
                                                    <input type="email" class="form-control" placeholder="Owner name">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label>Phone number</label>
                                                    <input type="email" class="form-control" placeholder="Phone number">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2"
                                                style="padding: 0 0 0px 6px;">
                                                <label style="    width: 100%;">&nbsp;</label>
                                                <button type="submit" class="btn btn-primary btn-rounded"
                                                    style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                            </div>
                                        </div>

                                    </th>
                            </tbody>
                        </table>
                    </div>
                </div> --}}


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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Owner name</th>
                                <th>Phone Number</th>
                                @if (in_array('organization.pending.assign', config('permission')))
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                             @foreach ($data['organization'] as $organization)
                                <tr>
                                    <th>{{ $organization->org_name }}</th>
                                    <td>{{ $organization->org_email }}</td>
                                    <td>{{ $organization->org_owner_name }}</td>
                                    <td>{{ $organization->org_phone }}</td>
                                    @if (in_array('organization.pending.assign', config('permission')))
                                    <td>
                                    <button data-toggle="dropdown" style="width: 64px;" class="badge badge-danger"> Deactive <i
                                            class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                    <div class="dropdown-menu">
                                        <a href="{{ url('org/assign/district/'.$Controller->hashEncode($organization->org_id)) }}" class="dropdown-item"><i class="fas fa-plus"></i> Under district</a>
                                        <a href="{{ url('org/assign/mekhala/'.$Controller->hashEncode($organization->org_id)) }}" class="dropdown-item"><i class="fas fa-plus"></i> Under mekhala</a>
                                        <a href="{{ url('org/assign/unit/'.$Controller->hashEncode($organization->org_id)) }}" class="dropdown-item"><i class="fas fa-plus"></i> Under unit</a>
                                        <a href="{{ url('org/assign/agent/'.$Controller->hashEncode($organization->org_id)) }}" class="dropdown-item"><i class="fas fa-plus"></i> Under agent</a>
                                    <a href="{{url('delete-pending-organization/'.$Controller->hashEncode($organization->org_id))}}" onclick="return confirm('Are you sure you want to delete this ?')" class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>
                                    </div>
 
                                    </td>
                                    @endif
                                </tr>
                            @endforeach 
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $data['organization']->links() }}
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

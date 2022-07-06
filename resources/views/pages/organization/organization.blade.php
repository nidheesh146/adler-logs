@extends('layouts.default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
@inject('Organization', 'App\Http\Controllers\Web\OrganizationController')
<?php
$type = [1=>'state',2=>'district',3=>'mekhala',4=>'unit',5=>'agent'];
?>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span>Organization</span>
                    <span>{{ $org }} Organizations</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;"> Organizations

                   
                    <div class="right-button">
                    <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                        <i class="fa fa-download" aria-hidden="true"></i> Download <i
                            class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                    <div class="dropdown-menu">
                    <a href="{{request()->fullUrl()}}{{ (count(request()->all('')) > 0)  ? '&' : '?' }}download=excel" class="dropdown-item">Excel</a>
            
                    </div>

                    @if (in_array('organization.add', config('permission')))
                    <div class="right-button" >
                        <button data-toggle="dropdown" style="float: right;font-size: 14px;" class="badge badge-pill badge-dark ">
                            <i class="fas fa-plus"></i> Organization <i
                                class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                        <div class="dropdown-menu">
                            {{-- <a href="{{ url('create-organization/state') }}" class="dropdown-item">State</a> --}}
                            @if(config('organization')['type'] == 1)
                            <a href="{{ url('create-organization/district') }}" class="dropdown-item">District</a>
                            @endif
                            @if(config('organization')['type'] == 1 || config('organization')['type'] == 2)
                            <a href="{{ url('create-organization/mekhala') }}" class="dropdown-item">Mekhala</a>
                            @endif
                            @if(config('organization')['type'] == 1 || config('organization')['type'] == 2 || config('organization')['type'] == 3)
                            <a href="{{ url('create-organization/unit') }}" class="dropdown-item">Unit</a>
                            @endif
                            {{-- <a href="{{ url('create-organization/agent') }}" class="dropdown-item">Agent</a> --}}
                        </div>
                    </div>
                    @endif
                </h4>
              
            @if (in_array('organization.pending.list', config('permission')) && config('organization')['type'] == 1)
              {{-- <div class="az-dashboard-nav">
                    <nav class="nav">
                      <a class="nav-link active" href="{{ url('organization/all') }}">Organization</a> 
                        <a class="nav-link " href="{{ url('org/pending') }}">Pending @if(config('org_pending_count') > 0 ) <span class="badge badge-pill badge-danger">{{config('org_pending_count')}}</span>@endif</a>
                      </nav>
                    <nav class="nav">
                    </nav> 
                </div> --}}
               @endif





                <div class="row row-sm mg-b-20 mg-lg-b-0">
                    <div class="table-responsive" style="margin-bottom: 13px;">
                       
                        @if (Session::get('success'))
                            <div class="alert alert-success " style="width: 100%;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                            </div>
                        @endif

                        <table class="table table-bordered mg-b-0">
                            <tbody>
                                <tr>
                                    <th scope="row">
                                       <form>
                                        <div class="row filter_search">

                                            <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">

                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label> Organization</label>

                                                    <select class="form-control select2" name="type">
                                                        <option value="">All Organization</option>
                                                        @if(config('organization')['type'] == 1 )
                                                          <option value="2" {{(request()->get('type') == 1) ? 'selected' : ''}}>State</option>
                                                        @endif
                                                        @if(config('organization')['type'] == 1 || config('organization')['type'] == 2)
                                                          <option value="2" {{(request()->get('type') == 2) ? 'selected' : ''}}>District</option>
                                                        @endif
                                                        @if(config('organization')['type'] == 1 || config('organization')['type'] == 2 || config('organization')['type'] == 3 )
                                                          <option value="3" {{(request()->get('type') == 3) ? 'selected' : ''}}>Mekhala</option>
                                                        @endif
                                                        @if(config('organization')['type'] == 1 || config('organization')['type'] == 2 || config('organization')['type'] == 3 || config('organization')['type'] == 4)
                                                          <option value="4" {{(request()->get('type') == 4) ? 'selected' : ''}}>Unit</option>
                                                        @endif
                                                    </select>

                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label>ID</label>
                                                    <input type="text" value="{{request()->get('org_id')}}" class="form-control" name="org_id" placeholder="Organization ID">
                                                </div><!-- form-group -->

                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label> Name</label>
                                                    <input type="text" value="{{request()->get('name')}}" class="form-control" name="name" placeholder="Organization name">
                                                </div>




                                                <!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label>Email</label>
                                                    <input type="email" class="form-control" value="{{request()->get('email')}}"  name="email" placeholder=" Email">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label>Incharge</label>
                                                    <input type="text" value="{{request()->get('incharge')}}" class="form-control" name="incharge" placeholder="incharge name">
                                                </div><!-- form-group -->

                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label>Phone number</label>
                                                    <input type="text" value="{{request()->get('phone')}}" class="form-control" name="phone" placeholder="Phone number">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2"
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
                    <table class="table table-bordered mg-b-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Person in-charge</th>
                                <th>Phone Number</th>
                                <th>Action</th>
                                @if (in_array('user.list', config('permission')) || in_array('role.list', config('permission')) || in_array('module.list', config('permission')) || in_array('permission.list', config('permission')))
                                    <th>Users</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>



                            @foreach ($data['organization'] as $organization)
                                <tr>
                                    <th> 
                                        @if (in_array('organization.edit', config('permission')))
                                        <a href="{{url('edit-organization/'.$type[$organization->type_id].'/'.$Controller->hashEncode($organization->org_id))}}"
                                        >{{ $organization->org_number }}</a>
                                    @else
                                    {{ $organization->org_number }}
                                    @endif
                                    </th>
                                    <th>{{ $organization->org_name }}</th>
                                    <td>{{ $organization->org_email }}</td>
                                    <td>{{ $organization->org_owner_name }}</td>
                                    <td>{{ $organization->org_phone }}</td>
                                    <td>
                                        <button data-toggle="dropdown" style="width: 64px;" class="badge  {{($organization->status == 1 ) ? 'badge-success' : 'badge-danger' }}"> {{($organization->status == 1 ) ? 'Active' : 'Deactive' }}  <i
                                                class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                        <div class="dropdown-menu">

                                            @if (in_array('organization.edit', config('permission')))
                                                <a href="{{url('edit-organization/'.$type[$organization->type_id].'/'.$Controller->hashEncode($organization->org_id))}}"
                                                    class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
                                            @endif
                                            @if (in_array('organization.delete', config('permission')) && $organization->type_id != 1)
                                                <a onclick="return confirm('Are you sure you want to {{($organization->status == 1) ? 'deactive' : 'active' }} this ?');" 
                                                    href="{{url('action-organization/'.$org.'/'.$Controller->hashEncode($organization->org_id).'/'.$Controller->hashEncode($organization->status))}}" class="dropdown-item"><?=($organization->status == 1) ? '<i class="fas fa-times"></i> Deactive' : '<i class="fas fa-check"></i> Active' ;?></a>
                                            @endif
                                            @if (in_array('organization.delete', config('permission')) && $organization->type_id != 1)
                                                @if($Organization->deletePermission($organization->org_id,$organization->type_id))
                                                   <a onclick="return confirm('Are you sure you want to delete this ?');"  href="{{url('delete-organization/'.$org.'/'.$Controller->hashEncode($organization->org_id).'/delete')}}" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete </a>
                                                @endif
                                            @endif
                                            <a href="#" id="{{ $organization->org_id }}" class="dropdown-item orgstructure"><i class="fas fa-sitemap"></i> Commission flow</a>
                                            <!-- dropdown-menu -->
                                        </div>
                                    </td>

                                    @if (in_array('user.list', config('permission')) || in_array('role.list', config('permission')) || in_array('module.list', config('permission')) || in_array('permission.list', config('permission')))
                                     <td> <a href="{{ url('organization/users/' . $org . '/'.$Controller->hashEncode($organization->org_id)) }}"
                                                class="badge badge-dark"><i class="fas fa-users"></i> Users </a></td>
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


    <script>
     $('.orgstructure').click(function(){
    $.get("<?= url('') ?>/org-structure/"+$(this).attr('id'), function( data ) {
      $('.timeline').html(data);
      $('#modaldemo2').modal('show');
    });
    });
    </script>








@stop

@extends('layouts.default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
<?php
$type = [1=>'State',2=>'District',3=>'Mekhala',4=>'Unit',5=>'Agent'];
?>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('organization/' . $org) }}" >Organization</a></span>
                    <span>Role</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">{{$type[$permission['type_id']]}} - {{$permission['org_name']}}'s roles
                
                
                @if (in_array('role.list', config('permission')))
                @if($role_id)
                    <button style="float: right;font-size: 14px;" onclick="document.location.href='{{ url('organization/role/'.$org.'/'.$orgid) }}'" class="badge badge-pill badge-dark "><i
                        class="fas fa-plus"></i> Add role  </button>
                @endif
                @endif
                
                </h4>
                <div class="az-dashboard-nav">

                    @include('includes.user-nav')

                    <nav class="nav">
                        <a class="nav-link" href="#"></a>
                        {{-- <a class="nav-link" href="{{ url('create-organization/' . $org) }}"><i class="fa fa-plus"
                                aria-hidden="true"></i> Add user</a> --}}
                        {{-- <a class="nav-link" href="#"><i class="far fa-file-pdf"></i> Export to PDF</a> --}}
                        <!-- <a class="nav-link" href="#"><i class="far fa-envelope"></i>Send to Email</a> -->
                        <a class="nav-link" href="#"><i class="fas fa-ellipsis-h"></i></a>
                    </nav>
                </div>
                <div class="row row-sm mg-b-20 mg-lg-b-0">



                    <div class="col-lg-7 col-xl-7 mg-t-20 mg-lg-t-0">
                        <div class="card card-table-one" style="min-height: 500px;">
                          @if (Session::get('succ'))
                          <div class="alert alert-success " style="width: 100%;">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                              <i class="icon fa fa-check"></i> {{ Session::get('succ') }}
                            </div>
                            @endif
                            <h6 class="card-title">User role</h6>
                            <p class="az-content-text mg-b-20"></p>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Role Name</th>
                                            <th>Role description</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($data['role'] as $key => $role)
                                            <tr style="{{($role['created_org']==0) ? 'background: #3466ff1a;' : ''}}">
                                                <td>{{ $role['role_name'] }}</td>
                                                <td>{{ $role['role_description'] }}</td>
                                                <td> <button data-toggle="dropdown" class="badge badge-primary">Active <i
                                                            class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                                    <div class="dropdown-menu">
                                                       
                                                    @if (in_array('role.permission', config('permission')))
                                                      @if($role['created_org'] != 0)
                                                      <a href="{{ url('organization/role-permission/'.$org.'/'.$orgid.'/'.$Controller->hashEncode($role['role_id']))}}"
                                                            class="dropdown-item"><i class="fas fa-user-lock"></i> Permission</a>
                                                      @else
                                                    <a href="{{ url('organization/permission/'.$org.'/'.$orgid)}}" class="dropdown-item"><i class="fas fa-user-lock"></i> Permission</a>
                                                      @endif
                                                      @endif
                                                        @if (in_array('role.edit', config('permission')))
                                                        @if ($role['created_org'] != 0)
                                                             <a href="{{url('organization/role/'.$org.'/'.$orgid.'/'.$Controller->hashEncode($role['role_id']))}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
                                                        @endif
                                                        @endif
                                                        @if (in_array('role.delete', config('permission')))
                                                        @if ($role['created_org'] != 0)
                                                      <a href="{{url('organization/delete-role/'.$org.'/'.$orgid.'/'.$Controller->hashEncode($role['role_id']))}}" onclick="return confirm('Are you sure want to delete this item? The user cannot sign in under this role ')"  class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>
                                                        @endif
                                                        @endif
                                                        <!-- dropdown-menu -->
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div><!-- table-responsive -->
                        </div><!-- card -->
                    </div>


                    <div class="col-lg-5 col-xl-5 mg-t-20 mg-lg-t-0">
                        <div class="card card-table-one" style="min-height: 500px;">
                          @if (Session::get('success'))
                          <div class="alert alert-success " style="width: 100%;">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                              <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                            </div>
                            @endif
                          <form  method="POST"  id="commentForm">
                          <h6 class="card-title">{{ $role_id ? 'Update' : 'Create new' }} user role</h6>
                            <p class="az-content-text mg-b-20"></p>
                            {{ csrf_field() }}   
                            <div class="row">
                                <div class="form-group col-sm-12 ">
                                    <label>Role Name *</label>
                                <input type="text"  value="{{$role_id ? $data['single_role']->role_name : ''}}" name="role" class="form-control" placeholder="Enter role Name ">
                                </div><!-- form-group -->
                                <div class="form-group col-sm-12 ">
                                    <label>Description *</label>
                                    <textarea class="form-control" name="description" placeholder="Enter Description">{{$role_id ? $data['single_role']->role_description : ''}}</textarea>
                                </div><!-- form-group -->

                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded" style="float: right;"><i
                                    class="fas fa-save"></i> {{ $role_id ? 'Update' : 'Submit' }} </button>
                                </div>
                            </div>


                          </form>





                        </div><!-- card -->
                    </div>
                </div>
            </div>
        </div><!-- az-content-body -->
    </div>
    </div><!-- az-content -->


    <!-- SMALL MODAL -->
          <script src="<?= url('') ?>/js/azia.js"></script>
          <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
          <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
          <script src="<?= url('') ?>/js/jquery.validate.js"></script>
          <script src="<?= url('') ?>/js/additional-methods.js"></script>
          <script>
          $("#commentForm").validate({
            rules: {
              role:{
                  required: true,
                  minlength: 1,
                  maxlength: 20
               },
               description:{
                  required: true,
                  minlength: 1,
                   maxlength: 115
               },
              
            }

     
          });
          </script>


@stop

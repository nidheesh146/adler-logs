@extends('layouts.default')
@section('content')
<?php
$type = [1=>'State',2=>'District',3=>'Mekhala',4=>'Unit',5=>'Agent'];
?>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{url('organization/'.$org)}}" >Organization</a></span>
                    <span> Permission</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">{{$type[$permission['type_id']]}} - {{$permission['org_name']}}'s permissions</h4>
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


                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th>Permission</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach ($data['permission'] as $key => $organization)
                            
                            <tr style="background: #efefef;">
                            <th> {{ucfirst($key )}}</th>
                                <td></td>
                            </tr>

                            @foreach ($organization as $key => $organizations)
                            <tr>
                              <th></th>
                            <td> {{$organizations}}</td>
                           </tr>
                           @endforeach
                           @endforeach
                        </tbody>
                    </table>
                </div>
            </div>



        </div><!-- az-content-body -->
    </div>
    </div><!-- az-content -->




      <script src="<?= url('') ?>/js/azia.js"></script>
      <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>



@stop

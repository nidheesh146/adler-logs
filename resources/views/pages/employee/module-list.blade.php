@extends('layouts.default')
@section('content')
<?php
$type = [1=>'State',2=>'District',3=>'Mekhala',4=>'Unit',5=>'Agent'];
?>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="" >Organization</a></span>
                    <span>Module</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">permission_type_id - org's modules</h4>

                <div class="az-dashboard-nav">
                    
                    

                    <nav class="nav">
                        <a class="nav-link" href="#"></a>
                        {{-- <a class="nav-link" href="{{ url('create-organization/' . $org) }}"><i class="fa fa-plus"
                                aria-hidden="true"></i> Add user</a> --}}
                        {{-- <a class="nav-link" href="#"><i class="far fa-file-pdf"></i> Export to PDF</a> --}}
                        <!-- <a class="nav-link" href="#"><i class="far fa-envelope"></i>Send to Email</a> -->
                        <a class="nav-link" href="#"><i class="fas fa-ellipsis-h"></i></a>
                    </nav>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

                  <div class="table-responsive">
                    <table class="table table-bordered mg-b-0">
                      <thead>
                        <tr>
                        
                          <th style="width: 80%;">Module</th>
                          {{-- <th>Users</th> --}}

                        </tr>
                      </thead>
                      <tbody>
                        
                       
                        <tr>
                        <td >{{ucfirst($module['per_module'])}}</td>
                        </tr>
                        @endforeach
                    
                      </tbody>
                    </table>
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

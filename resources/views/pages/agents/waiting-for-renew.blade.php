@extends('layouts.default')
@section('content')
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span>Subscribers</span>
                    <span>All Subscribers</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Subscribers

                    <div>
                        <button data-toggle="dropdown" style="float: right; margin-left: 9px;" class="badge badge-pill badge-info ">
                            <i class="fa fa-download" aria-hidden="true"></i> Download <i
                                class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-item">Excel</a>
                
                        </div>
                        <div>
                    <button data-toggle="dropdown" style="float: right;" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Subscribers <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                    <div class="dropdown-menu">
                        <a href="{{ url('subscribers/create/state') }}" class="dropdown-item">Under my organization </a>
                        <a href="{{ url('subscribers/create/district') }}" class="dropdown-item"> Under District</a>
                        <a href="{{ url('subscribers/create/mekhala') }}" class="dropdown-item">Under Mekhala</a>
                        <a href="{{ url('subscribers/create/unit') }}" class="dropdown-item">Under Unit</a>
                    </div>
                </div>
                    
                
                </h4>

                <div class="az-dashboard-nav">
                    <nav class="nav">
                        <a class="nav-link " href="{{ url('subscribers/subscribers') }}">Subscribers</a>
                        <a class="nav-link " href="#">Subscriptions</a>
                        <a class="nav-link  active" href="{{ url('subscribers-renew') }}">Waiting for renew</a>
                    </nav>
                     <nav class="nav">
                     </nav>
                </div>


                <div class="row row-sm mg-b-20 mg-lg-b-0">


                    <div class="table-responsive" style="margin-bottom: 13px;">
                        <table class="table table-bordered mg-b-0">
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <div class="row" style="margin-left: 0px;">

                                    <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row" >
                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                            <select class="form-control select2">
                                            <option label="Choose Organization"></option>
                                            <option value="Firefox">State</option>
                                            <option value="Firefox">District</option>
                                            <option value="Firefox">Mekhala</option>
                                            <option value="Firefox">Unit</option>
                                            <option value="Firefox">Agent</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                             
                                            <input type="text" class="form-control" placeholder="Subscriber ID">
                                        </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                             
                                                <input type="text" class="form-control" placeholder="Enter name">
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                             
                                                <input type="email" class="form-control" placeholder="Enter Email">
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                           
                                                <input type="email" class="form-control" placeholder="Phone number">
                                            </div>

                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                     
                                                <input type="email" class="form-control" placeholder="Phone number">
                                            </div>
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                             
                                      

                                                <select class="form-control select2">
                                                    <option value="Firefox">All type</option>
                                                  <option value="Firefox">Agent</option>
                                                  <option value="Firefox">Subscriber</option>
                                                </select>
                                            </div><!-- form-group -->
                                        
                                            {{-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                           
                                      

                                                <select class="form-control select2">
                                                    <option value="Firefox">All {{ucfirst($org)}}  Organizations</option>
                                                  <option value="Firefox">unit 1</option>
                                                  <option value="Firefox">unit 2</option>
                                                </select>
                                            </div><!-- form-group --> --}}
                                        
                                        </div>

                                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row" >
                                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12"
                                                style="padding: 0 0 0px 6px;">
                                                {{-- <label style="    width: 100%;">&nbsp;</label> --}}
                                                <button type="submit" class="btn btn-primary btn-rounded"
                                                    style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                        </div>
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
                                <th>Phone</th>
                                <th>PIN code</th>
                                <th>Status<br>(paid/free)</th>
                                <th>type</th>
                                <th> {{$org}}</th>
                                <th>Action</th>

                                {{-- <th>Users</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>1010101</th>
                                <th>Baiju</th>
                                <td>baiju@gmail.com</td>
                                <td>9037715996</td>
                                <td>679302</td>
                                <td>free</td>
                                <td>Agents</td>
                                <th>unit 1</th>
                                <td>
                                    <button data-toggle="dropdown" class="badge  badge-primary">Active <i
                                            class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                    <div class="dropdown-menu">
                                    <a href="{{url('subscription/create/'.$org.'/1')}}" class="dropdown-item">Renew </a>
                                        <a href="#" data-toggle="modal" data-target="#modaldemo2"
                                            class="dropdown-item">Commission flow</a>

                                    <a href="{{url('subscribers/create/'.$org)}}" class="dropdown-item">Edit</a>
                                        <a href="#" class="dropdown-item">Deactive</a>
                                        <a href="#" class="dropdown-item">Delete</a>
                                        <!-- dropdown-menu -->
                                    </div>
               
                                </td>
                                {{-- <td> <a href="{{ url('organization/users/1') }}" class="badge badge-dark"><i
                                            class="fas fa-users"></i> Users </a></td> --}}

                            </tr>

                        </tbody>
                    </table>
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

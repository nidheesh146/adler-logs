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
                    <span>Reports</span>
                    <span> Magazine order</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;"> Magazine order ( {{date('M Y')}} )
                    <div>
                        <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;"
                            class="badge badge-pill badge-info ">
                            <i class="fa fa-download" aria-hidden="true"></i> Download <i
                                class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                        <div class="dropdown-menu">
                        <a target="_blank" href="{{ (strpos($request->fullUrl(),'?') === false)  ? $request->fullUrl().'?download=print' : $request->fullUrl().'&download=print' }}" class="dropdown-item"><i class="fas fa-print"></i> Print</a>
                            <a  href="{{ (strpos($request->fullUrl(),'?') === false)  ? $request->fullUrl().'?download=pdf' : $request->fullUrl().'&download=pdf' }}" class="dropdown-item"> <i class="far fa-file-pdf"></i>  PDF</a>
                        </div>
                    </div>
                    <div>
                    </div>
                </h4>

            @include('includes.order-nav')


                <div class="row row-sm mg-b-20 mg-lg-b-0">
                  
                    <div class="table-responsive" style="margin-bottom: 13px;">
                        <table class="table table-bordered mg-b-0">
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <div class="row" style="margin-left: 0px;">

                                            <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
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
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <select class="form-control attr_magazine" name="magazine">
                                                        <option value="">Choose Magazine</option>
                                                        @foreach ($data['magazine'] as $magazine)
                                                            <option value="{{ $magazine['id'] }}">
                                                                {{ $magazine['name'] . '-' . $magazine['magazine_id'] }}</option>
                                                        @endforeach
                                                    </select>
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

                                        


                                            </div>

                                            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12"
                                                    style="padding: 0 0 0px 6px;">
                                                    {{-- <label style="    width: 100%;">&nbsp;</label> --}}
                                                    <button type="submit" class="btn btn-primary btn-rounded"
                                                        style="margin-top:-2px;"><i class="fas fa-search"></i>
                                                        Search</button>&nbsp;
                                                        {{-- <button type="submit" class="btn btn-primary btn-rounded"
                                                        style="margin-top:-2px;"><i class="fas fa-search"></i>
                                                        reset</button> --}}
                                                </div>
                                            </div>
                                            Total Order : {{$data['subscriber_count'] + $data['agent_count'] + $data['author_count']}} 
                                            ( Subscriber : {{$data['subscriber_count']}} , Agents {{$data['agent_count']}} , Authors {{$data['author_count']}} )
                        
                                        </div>
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
                                <th>SUBSCRIBER ID</th>
                                <th>Subscription ID</th>
                                <th>Name</th>
                                <th>PHONE</th>
                                <th>EMAIL</th>
                                <th>pincode</th>
                                <th>Magazine</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($data['result'] as $result)
                            @php
                            $mg_id =[];
                            $book_id = explode("/", $result['book_id']);
                            $magazine_id = explode("/", $result['magazine_id']);
                            $magazine_name = explode("/", $result['magazine_name']);
                            $array_count_values = array_count_values($book_id);
                            foreach($book_id as $key => $b_id){
                              $mg_id[$b_id] = $magazine_id[$key].'-'.$magazine_name[$key].'&nbsp;('.$array_count_values[$b_id].')<br>';
                            }
                            @endphp
                                <tr>
                                    <th>{{$result['subscriber_id']}}</th>
                                    <th><?=implode("<br>",explode(",",$result['subscription_id']));?></th>
                                    <td>{{$result['f_name']}}{{$result['l_name']}}</td>
                                    <td>{{$result['phone']}}</td>
                                    <td>{{$result['email']}}</td>
                                    <td>{{$result['pincode']}}</td>
                                   
                                    <td>
                                        <?=implode(" ",$mg_id);?>
                           
                                    </td>

                                </tr>
                            @endforeach




                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $data['result']->links() }}
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

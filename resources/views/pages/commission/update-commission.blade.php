@extends('layouts.default')
@section('content')
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('commission/update') }}" >Commission</a></span>
                    <span>Update commission</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Update commission</h4>


                <div class="row row-sm mg-b-20 mg-lg-b-0">
                    <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div>
                
                    <form style="border: 1px solid rgba(28, 39, 60, 0.12);padding: 29px;"
                        class="col-sm-12 col-md-6 col-lg-6 col-xl-6" method="POST" >
                          
                    @foreach ($errors->all() as $error)
                    <div class="alert alert-danger "  role="alert" style="width: 100%;">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      {{ $error }}
                    </div>
                   @endforeach
                  
                   @if (Session::get('success'))
                   <div class="alert alert-success " style="width: 100%;">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                       <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                   </div>
                   @endif
                        {{ csrf_field() }}
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">Subscribers commission
                                (%)</label>
                            <div class="form-devider"></div>

                        </div>


                        <div class="form-group">
                            <label for="exampleInputPassword1">District </label>
                            <input type="text" class="form-control" name="subscriber_district"
                                value="{{ $data['subscriber_district'] }}">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Mekhala </label>
                            <input type="text" class="form-control" name="subscriber_mekhala"
                                value="{{ $data['subscriber_mekhala'] }}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Unit </label>
                            <input type="text" class="form-control" name="subscriber_unit"
                                value="{{ $data['subscriber_unit'] }}">
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">Agents commission (%)</label>
                            <div class="form-devider"></div>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">District</label>
                            <input type="text" class="form-control" name="agent_district"
                                value="{{ $data['agent_district'] }}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Agent </label>
                            <input type="text" class="form-control" name="agent_agent" value="{{ $data['agent_agent'] }}">
                        </div>

                        <button type="submit" class="btn btn-primary  btn-rounded " style="float: right;"><i
                                class="fas fa-save"></i> Update</button>
                    </form>

                </div>



            </div><!-- az-content-body -->
        </div>
    </div><!-- az-content -->


    <script src="<?= url('') ?>/js/azia.js"></script>
      <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script>
        $(function () {
          'use strict'



        });
      </script>

@stop

@extends('layouts.default')
@section('content')
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('magazine') }}" >Magazine</a></span>
                    <span>Edit magazine</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Edit magazine</h4>


                <div class="row row-sm mg-b-20 mg-lg-b-0">

                    <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div>
                    <form style="border: 1px solid rgba(28, 39, 60, 0.12);padding: 29px;"
                      method="post" id="commentForm"  class="col-sm-12 col-md-6 col-lg-6 col-xl-6" >
                      {{ csrf_field() }}  
                      @if(Session::get('success'))
                      <div class="alert alert-success " style="width: 100%;">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          <i class="icon fa fa-check"></i> {{Session::get('success')}}
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="exampleInputEmail1">Magazine *</label>
                        <input type="text" value="{{$data['magazine']['name']}}" class="form-control" name="name"
                                placeholder="Enter magazine name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Price *</label>
                            <input type="text" value="{{$data['magazine']['price']}}" class="form-control" name="price"  placeholder="Price">
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>

                </div>



            </div><!-- az-content-body -->
        </div>
    </div><!-- az-content -->


    <script src="<?= url('') ?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="<?= url('') ?>/js/jquery.validate.js"></script>
      <script src="<?= url('') ?>/js/additional-methods.js"></script>
      <script>
        $(function () {
          'use strict'

          $("#commentForm").validate({
                rules: {
                  name:{
                      required: true,
                   },
                   price:{
                      required: true,
                      number: true,
                   },
                }
              });


        });
      </script>

@stop

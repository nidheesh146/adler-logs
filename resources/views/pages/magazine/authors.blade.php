@extends('layouts.default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
@inject('MagazineController', 'App\Http\Controllers\Web\MagazineController')
<div class="az-content az-content-dashboard">
    <div class="container">
      <div class="az-content-body">
      <div class="az-content-breadcrumb">
      <span>Magazine</span>
            <span>Authors</span>
          </div>
          <h4 class="az-content-title" style="font-size: 20px;">Authors
          
            @if (in_array('authors.add',config('permission'))) 
          <a href="{{url('magazine/add-authors')}}" style="float: right;font-size: 14px;" class="badge badge-pill badge-dark "><i
              class="fas fa-plus"></i> Add author </a>
              @endif
          
          </h4>

          @include('includes.magazine-nav')
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

                                      <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                        <label>Author ID</label>
                                        <input type="text" name="author_id" value="{{request()->get('author_id')}}" class="form-control" placeholder="Enter name">
                                    </div>

                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                            <label> Name</label>
                                            <input type="text" name="name" value="{{request()->get('name')}}" class="form-control" placeholder="Enter name">
                                        </div>

                                        <!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                            <label>Email</label>
                                            <input type="email" name="email" value="{{request()->get('email')}}" class="form-control" placeholder="Enter Email">
                                        </div><!-- form-group -->
                                      
                                        <div class="form-group col-sm-12 ccol-md-3 col-lg-3 col-xl-3">
                                            <label>Phone number</label>
                                            <input type="text" name="phone" value="{{request()->get('phone')}}" class="form-control" placeholder="Phone number">
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2"
                                        style="padding: 0 0 0px 6px ;">
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

         
<div class="row row-sm mg-b-20 mg-lg-b-0">
      <div class="table-responsive">
            <table class="table table-bordered mg-b-0">
              <thead>
                <tr>
                  <th>Author ID</th>
                  <th>Name</th>
                  <th>NO: of journals published</th>
                  <th>Phone</th>
                  <th>Email</th>
                                
                @if (in_array('authors.edit',config('permission')) || in_array('authors.delete',config('permission')) ) 
                  <th>Action</th>
                @endif
                </tr>
              </thead>
           

              <tbody>
                @foreach ( $data['authors']  as $magazine)
                <tr>
                <td>{{$magazine['author_id']}}</td>
                  <th scope="row">
                  @if(in_array('authors.edit',config('permission')))
                     <a href="{{url('magazine/add-authors/'.$Controller->hashEncode($magazine['id']))}}" >{{$magazine['f_name']}} {{$magazine['l_name']}}</a>
                  @else
                     {{$magazine['f_name']}} {{$magazine['l_name']}}
                  @endif
                  </th>
                  <td>{{$MagazineController->journals_published($magazine['id'])}}</td>
                  <td>{{$magazine['mobile']}}</td>
                  <td>{{$magazine['email']}}</td>
                @if (in_array('authors.edit',config('permission')) || in_array('authors.delete',config('permission')) ) 
                  <td>
                    <button data-toggle="dropdown" style="width: 64px;" class="badge  {{($magazine['status'] == 1 ) ? 'badge-success' : 'badge-danger' }}"> {{($magazine['status'] == 1 ) ? 'Active' : 'Deactive' }}  <i
                      class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
              <div class="dropdown-menu">
             @if(in_array('authors.edit',config('permission')))
                <a href="{{url('magazine/add-authors/'.$Controller->hashEncode($magazine['id']))}}"  class="dropdown-item orgstructure"><i class="fas fa-edit"></i> Edit</a>
                @endif
                @if(in_array('authors.delete',config('permission')))
                <a href="{{url('magazine/authors/action/'.$Controller->hashEncode($magazine['id']).'/action')}}" onclick="return confirm('Are you sure you want to {{($magazine['status'] == 1) ? 'deactive' : 'active' }} this ?');"  class="dropdown-item orgstructure">   <?=($magazine['status'] == 1) ? '<i class="fas fa-times"></i> Deactive' : '<i class="fas fa-check"></i> Active' ;?></a>
                <a href="{{url('magazine/authors/action/'.$Controller->hashEncode($magazine['id']).'/delete')}}"onclick="return confirm('Are you sure you want to delete this ?');"  class="dropdown-item orgstructure"><i class="fas fa-trash-alt"></i> Delete</a>
                @endif
              </div>
                  </td>
               @endif



                </tr>
                @endforeach
              </tbody>

            

    

            </table>
            <div class="box-footer clearfix">
              {{ $data['authors']->links() }}
              </div>
          </div>
</div>





      </div><!-- az-content-body -->
    </div>
  </div><!-- az-content -->
  <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?=url('');?>/js/azia.js"></script>


@stop
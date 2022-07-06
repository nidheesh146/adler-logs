@extends('layouts.default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
@inject('MagazineController', 'App\Http\Controllers\Web\MagazineController')
<?php
$type = [1=>'State',2=>'District',3=>'Mekhala',4=>'Unit',5=>'Agent'];
?>
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
    color: black;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #f30b0b;
    margin: -6px 0px 0px -8px;
    font-size: 16px;
}
    </style>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('magazine/edition') }}" >Edition</a></span>
                    <span><a href="{{ url('magazine/add-edition/'.$edition) }}" >Edit EDITION</a></span>
                    <span>articles</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Article
                    @if (in_array('edition.add',config('permission'))) 
                    @if ($id)
                        <button style="float: right;font-size: 14px;" onclick="document.location.href='{{ url('magazine/article/'.$edition) }}'"
                            class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Add Article   </button>
                    @endif
                    @endif
                </h4>
               
                <div class="row row-sm mg-b-20 mg-lg-b-0">
                    @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                      </div>
                      @endif

                      @foreach ($errors->all() as $error)
                      <div class="alert alert-danger " role="alert" style="width: 100%;">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          {{ $error }}
                      </div>
                     @endforeach
                    <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                        <div class="pd-20 bg-gray-200"
                            style="border: 1px solid rgba(28, 39, 60, 0.12);background-color: #ffffff;">
                            <nav class="nav az-nav-column">
                                @if (in_array('edition.add',config('permission')) || in_array('edition.edit',config('permission'))) 
                                <a class="nav-link "  href="{{ url('magazine/add-edition/'.$edition) }}">
                                     <i class="fas fa-book" style="font-size: 13px;margin: 0;"></i>Edition
                                </a>
                                @endif
                                @if (in_array('edition.article',config('permission'))) 
                                <a class="nav-link active"   href="{{ url('magazine/article/'.$edition) }}">
                                    <i class="fas fa-book-reader"  style="font-size: 13px;margin: 0;"></i>
                                    Articles</a>
                                @endif
                                <a class="nav-link" data-toggle="tab" href="#"></a>
                            </nav>
                        </div><!-- pd-10 -->
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="card card-table-one" style="min-height: 500px;">
                         
                      
                            <h6 class="card-title">Articles</h6>
                            <p class="az-content-text mg-b-20"></p>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>name</th>
                                            <th>Author</th>
                                            <th>pages</th>
                                            <th></th>


                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($data['articles']  as $key => $articles)
                                            <tr>
                                            <td>{{$articles['article_name']}}</td>
                                            <td>{{$MagazineController->get_authors_name($articles['articles_id'])}}</td>
                                                <td>{{$articles['pages']}}</td>
                                                <td> 
                                                    <button data-toggle="dropdown" style="width: 64px;" class="badge badge-success">Action<i
                                                        class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                                <div class="dropdown-menu">
                                                <a href="{{url('magazine/article/'.$edition.'/'.$Controller->hashEncode($articles['articles_id']))}}"  class="dropdown-item "><i class="fas fa-edit"></i> Edit</a>
                                                <a href="{{url('magazine/article-delete/'.$edition.'/'.$Controller->hashEncode($articles['articles_id']))}}"onclick="return confirm('Are you sure you want to delete this ?');"  class="dropdown-item "><i class="fas fa-trash-alt"></i> Delete</a>

                                                </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div><!-- table-responsive -->
                        </div><!-- card -->
                    </div>


                    <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                        <div class="card card-table-one" style="min-height: 500px;">



                          <form  method="POST"  id="commentForm">
                          <h6 class="card-title"> {{$id ? 'Edit' : 'Add'}} article</h6>
                            <p class="az-content-text mg-b-20"></p>
                            {{ csrf_field() }}   
                            <div class="row">


                                
                                <div class="form-group col-sm-12 ">
                                    <label>Article name *</label>
                                <input type="text" class="form-control" name="article_name" value="{{$id ? $data['single_articles']['article_name'] : ''}}" placeholder="Enter article name"> 
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 ">
                                <label for="exampleInputEmail1">Authors *</label>
                                <select class="form-control attr_authors" name="authors[]" multiple>
                                    <option value="">Choose one</option>
                                    @foreach ($data['authors'] as $authors)
                                        <option value="{{ $authors['id'] }}"
                                        @if ($id && in_array($authors['id'],$data['get_authors'])) {{'selected'}} @endif>
                                            {{ $authors['author_id'] .'-'. $authors['f_name'] . ' ' . $authors['l_name'] }}</option>
                                    @endforeach
                                </select>
                                </div>

                                <div class="form-group col-sm-12 ">
                                    <label>Number of pages *</label>
                                    <input type="number" value="{{$id ? $data['single_articles']['pages'] : ''}}" class="form-control" name="pages" placeholder="Number of pages"> 
                                </div><!-- form-group -->

                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded" style="float: right;">
                                        <span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span> <i
                                    class="fas fa-save"></i> {{$id ? 'Update' : 'Submit'}}  </button>
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


          <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= url('') ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/jquery-ui/ui/widgets/datepicker.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/spectrum-colorpicker/spectrum.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js"></script>
<script src="<?= url('') ?>/lib/jquery-simple-datetimepicker/jquery.simple-dtpicker.js"></script>
<script src="<?= url('') ?>/lib/pickerjs/picker.min.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
          <script>

            $('.attr_authors').select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search',
            });
          $("#commentForm").validate({
            rules: {
                'authors[]':{
                  required: true,
               },
               pages:{
                  required: true,
                  number:true   
               },
               article_name:{
                  required: true,
               }
            },
            submitHandler: function(form) {
                    $('.spinner-button').show();
                    form.submit();
                }
          });
          </script>


@stop

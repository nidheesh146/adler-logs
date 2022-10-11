@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb"> <span>Supplier Master </span>
             
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">
                Supplier Master
                    <div class="right-button">

                        <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                            <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                        <div class="dropdown-menu">
                        <a href="" class="dropdown-item">Excel</a>
                
                        </div> -->
                        
                        <div>
                            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/suppliers-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Supplier</button> 
                        </div>
                    </div>
                </h4>



                <br>
                <div class="tab-content">
                    <div class="row row-sm mg-b-20 mg-lg-b-0">
                        <div class="table-responsive" style="margin-bottom: 13px;">
                            <table class="table table-bordered mg-b-0">
                                <tbody>
                                    <tr>

                                        <form autocomplete="off">
                                        <th scope="row">
                                            <div class="row filter_search" style="margin-left: 0px;">
                                                <div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">

                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label for="exampleInputEmail1" style="font-size: 12px;">SUPPLIER ID
                                                        </label>
                                                        <input type="text" value="{{request()->get('supplier_id')}}" name="supplier_id" class="form-control"
                                                            placeholder=" SUPPLIER ID ">
                                                    </div><!-- form-group -->
                                                  

                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label for="exampleInputEmail1" style="font-size: 12px;">SUPPLIER NAME
                                                            </label>
                                                        <input type="text" value="{{request()->get('supplier_name')}}" name="supplier_name" id="item_code"
                                                            class="form-control" placeholder="SUPPLIER NAME">

                                                    </div><!-- form-group -->
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label for="exampleInputEmail1"
                                                            style="font-size: 12px;">CONTACT PERSON</label>
                                                        <input type="text" value="{{request()->get('contact_persion')}}"  name="contact_persion" 
                                                            class="form-control" placeholder="CONTACT PERSON">

                                                    </div>
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label style="font-size: 12px;">TYPE</label>
                                                        <select name="type" id="status" class="form-control">
                                                            <option value=""> --Select One-- </option>
                                                            <option value="indirect" @if(request()->get('type') == 'indirect') selected @endif> Indirect </option>
                                                            <option value="direct" @if(request()->get('type') == 'direct') selected @endif > Direct</option>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12"
                                                        style="padding: 0 0 0px 6px;">
                                                        <label style="width: 100%;">&nbsp;</label>
                                                        <button type="submit"
                                                            class="badge badge-pill badge-primary search-btn"
                                                            style="margin-top:-2px;"><i class="fas fa-search"></i>
                                                            Search</button>
                                                            @if(count(request()->all('')) > 2)
															<a href="{{url()->current()}}" class="badge badge-pill badge-warning"
															style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
														@endif
                                                    </div>
                                                </div>
                                            </div>
                                        </th>
                                    </form>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                    </div>
                    @endif
                    <div class="tab-pane active  show" id="purchase">
                        <div class="table-responsive">
                            <table class="table table-bordered mg-b-0" id="example1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Supplier name</th>
                                        <th>Contact person</th>
                                        <th>Contact Number</th>
                                        <th>Email</th>
                                        <th>Type</th>
                                        <th>remarks</th>

                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $data['suppliers']  as $item)
                                    <tr>
                                        <td>{{$item->vendor_id}}</td>
                                        <td>{{$item->vendor_name}}</td>
                                        <td>{{$item->contact_person}}</td>
                                        <td>{{(!empty(json_decode($item->contact_number)[0])) ? implode(", ",json_decode($item->contact_number)):""}}</td>
                                        <td>{{(!empty(json_decode($item->email)[0])) ? implode(", ",json_decode($item->email)):""}}</td>
                                        <td>{{$item->supplier_type}}</td>
                                        <td>{{$item->remarks}}</td>
                                        <td> <button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                            <div class="dropdown-menu"> 
                                            <a href="{{url('inventory/suppliers-add/'.$item->id)}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
                                            <a href="{{url("inventory/suppliers-delete/".$item->id)}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> 
                                      
                                            </div>
                                        
                                        </td>
                           
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="box-footer clearfix">
                                {{ $data['suppliers']->appends(request()->input())->links() }}
                            </div>


                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>


    
    <script src="<?=url('');?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
    





@stop

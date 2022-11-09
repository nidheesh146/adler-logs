@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">ROW MATERIAL</a></span> 
                <span><a href="" style="color: #596881;">
                @if($edit) Edit @else ADD @endif ROW MATERIAL 
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            @if($edit) Edit @else Add @endif Row Material 
			</h4>
            @if(Session::get('error'))
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{Session::get('error')}}
                </div>
            
            @endif
            @if (Session::get('success'))
                <div class="alert alert-success " style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                </div>
            @endif 
            <div class="form-devider"></div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    <form method="POST" id="commentForm" novalidate="novalidate">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Item Code *</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['item_code'] : ''}}" class="form-control" name="item_code" id="item_code"  placeholder="Item Code">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Item Name</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['item_name'] : ''}}" class="form-control" name="item_name" id="item_name"  placeholder="Item Name">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Item Short Name</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['item_short_name'] : ' '}}" class="form-control" name="item_short_name" id="item_short_name"  placeholder="Item Short Name">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Item Type1 *</label>
                                <select class="form-control" name="item_type1" id="item_type1">
                                    <option value="">--select one--</option>
                                    @foreach($data['type1'] as $type)
                                    <option value="{{$type->id}}" @if($type->id == $edit['item_type_id'])
                                           selected @endif>{{$type->type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Item Type2 *</label>
                                <select class="form-control" name="item_type2" id="item_type2">
                                    <option value="">--select one--</option>
                                    @foreach($data['type2'] as $type)
                                    <option value="{{$type->id}}" @if($type->id == $edit['item_type_id_2'])
                                           selected @endif >{{$type->type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Issue Unit *</label>
                                <select class="form-control" name="issue_unit" id="issue_unit">
                                    <option value="">--select one--</option>
                                    @foreach($data['units'] as $unit)
                                    <option value="{{$unit->id}}" @if($unit->id == $edit['issue_unit_id'])
                                           selected @endif>{{$unit->unit_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Receipt Unit* </label>
                                <select class="form-control" name="receipt_unit" id="receipt_unit">
                                    <option value="">--select one--</option>
                                    @foreach($data['units'] as $unit)
                                    <option value="{{$unit->id}}" @if($unit->id == $edit['receipt_unit_id'])
                                           selected @endif >{{$unit->unit_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Stock Keeping Unit *</label>
                                <select class="form-control" name="stock_keeping_unit" id="stock_keeping_unit">
                                    <option value="">--select one--</option>
                                    @foreach($data['units'] as $unit)
                                    <option value="{{$unit->id}}" @if($unit->id == $edit['stock_keeping_unit_id'])
                                           selected @endif>{{$unit->unit_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Stock Type</label>
                                <select class="form-control" name="stock_type" id="stock_type">
                                    <option value="0" @if($edit['stock_type'])==0) selected @endif>0</option>
                                    <option value="1" @if($edit['stock_type'])==1) selected @endif>1</option>
                                </select>
                            </div> 
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Conv Fact in RU</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['conv_fact_in_ru'] : ' '}}" class="form-control" name="conv_fact_ru" id="conv_fact_ru"  placeholder="Conv Fact in RU">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Conv Fact in IU</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['conv_fact_in_iu'] : ' '}}" class="form-control" name="conv_fact_iu" id="conv_fact_iu"  placeholder="Conv Fact in IU">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Reorder Level</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['reorder_level'] : ' '}}" class="form-control" name="reorder_level" id="reorder_level"  placeholder="Reorder Level">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Min Stock Qty</label>
                                <input type="text"   value="{{(!empty($edit)) ? $edit['min_stock'] : ' '}}" class="form-control" name="min_stock_qty" id="min_stock_qty"  placeholder="Min Stock Qty">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Max Stock Qty</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['max_stock'] : ' '}}"  class="form-control" name="max_stock_qty" id="max_stock_qty"  placeholder="Max Stock Qty">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Over Stock Level</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['over_stock'] : ' '}}" class="form-control" name="over_stock_level" id="over_stock_level"  placeholder="Over Stock Level">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Item Description *</label>
                                <textarea value="" class="form-control" name="item_description" id="item_description"
                                            placeholder="Item Description" >{{(!empty($edit)) ? $edit['discription'] : ' '}}</textarea>
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Item Short Description</label>
                                <textarea value="" class="form-control" name="item_short_description" id="item_description"
                                            placeholder="Item Short Description" >{{(!empty($edit)) ? $edit['short_description'] : ' '}}</textarea>
                            </div>
                           
    
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Item Origin</label>
                                <select class="form-control" name="item_origin" id="item_origin">
                                    <option value="">--Select one--</option>
                                    <option value="1" @if($edit['item_origin'])==2) selected @endif>origin1</option>
                                    <option value="2" @if($edit['item_origin'])==2) selected @endif>origin2</option>
                                </select>
                            </div> 
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Brand</label>
                                <select class="form-control" name="brand" id="brand">
                                    <option value="">--Select one--</option>
                                    <option value="1" @if($edit['brand_id'])==1) selected @endif>brand1</option>
                                    <option value="2" @if($edit['brand_id'])==2) selected @endif>brand2</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Adler Purchase Rate</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['company_purchase_rate'] : ' '}}"  class="form-control" name="adler_purchase_rate" id="adler_purchase_rate"  placeholder="Adler Purchase Rate">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Purchase Rate</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['purchase_rate'] : ' '}}" class="form-control" name="purchase_rate" id="purchase_rate"  placeholder="Purchase Rate">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Sales Rate</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['sales_rate'] : ' '}}" class="form-control" name="sales_rate" id="sales_rate"  placeholder="Sales Rate">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label> Adsp1</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['item_short_name'] : ' '}}" class="form-control" name="adsp1" id="adsp1"  placeholder="Adsp1">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label> Adsp2</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['ad_sp2'] : ' '}}" class="form-control" name="adsp2" id="adsp2"  placeholder="Adsp2">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Path</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['path'] : ' '}}" class="form-control" name="path" id="path" placeholder="Path">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Hierarchy Path</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['hierarchy_path'] : ' '}}" class="form-control" name="hierarchy_path" id="hierarchy_path" placeholder="Hierarchy Path">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Unit Weight</label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['unit_weight_kgs'] : ' '}}" class="form-control" name="unit_weight" id="unit_weight"  placeholder="Unit Weight">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Revision Record</label>
                                <input type="text" value="{{(!empty($edit)) ? $edit['revision_record'] : ' '}}"  class="form-control" name="revision_record" id="revision_record"  placeholder=">Revision Record">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Expiry Control Required</label>
                                <select class="form-control" name="expiry_control_required" id="expiry_control_required">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Method Expiry Control </label>
                                <input type="text"  value="{{(!empty($edit)) ? $edit['method_of_expiry'] : ' '}}" class="form-control" name="method_expiry_control" id="method_expiry_control" placeholder="Method Expiry Control">
                            </div>
                                  
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Checker</label>
                                <select class="form-control checked" name="checker" id="checker">
                                @foreach ($data['users'] as $item)
                                             <option value="{{$item['user_id']}}"
                                             @if(!empty($edit) && $edit['checker_id'] == $item['user_id']) selected 
                                             @elseif(config('user')['user_id']== $item['user_id'])
                                                selected
                                            @endif
                                             >{{$item['f_name']}} {{$item['l_name']}}</option>
                                            @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Approved</label>
                                <select class="form-control approved" name="approved">
                                @foreach ($data['users'] as $item)
                                             <option value="{{$item['user_id']}}"
                                             @if(!empty($edit) && $edit['approver_id'] == $item['user_id']) selected 
                                             @elseif(config('user')['user_id']== $item['user_id'])
                                                selected
                                            @endif
                                             >{{$item['f_name']}} {{$item['l_name']}}</option>
                                            @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                        class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                        role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    @if($edit)
                                        Update
                                    @else
                                        Save
                                    @endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>			
        </div>
        
	</div>
	<!-- az-content-body -->
</div>




<script src="<?= url('') ?>/js/azia.js"></script>

<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>

<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>

<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>

<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script>
    $(function(){
        $("#commentForm").validate({
            rules: {
                item_code: {
                    required: true,
                },
                item_type1: {
                    required: true,
                },
                item_type2: {
                    required: true,
                },
                item_description: {
                    required: true,
                },
                issue_unit: {
                    required: true,
                },
                receipt_unit: {
                    required: true,
                },
                stock_keeping_unit: {
                    required: true,
                },  
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });
        $('.checked').select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search',
        });
        $('.approved').select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search',
        });	
    
    });						
							
</script>
@stop
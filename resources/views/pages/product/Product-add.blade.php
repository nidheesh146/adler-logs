@extends('layouts.default')
@section('content')
@php 
use App\Http\Controllers\Web\ProductController;
$obj_product=new ProductController();
@endphp
<style>
    .autosize {
        resize: none;
        overflow: hidden;
        min-height: 220px;
    }
</style>
<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">
            <div class="az-content-breadcrumb">
                <span><a href="{{url('inventory/suppliers-list')}}" style="color: #596881;">Product </a></span>
                <span><a href=""> Poduct Add</a></span>
            </div>
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;"> Product Add</h4>
            @foreach ($errors->all() as $errorr)
            <div class="alert alert-danger " role="alert" style="width: 100%;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ $errorr }}
            </div>
            @endforeach
            @if (Session::get('success'))
            <div class="alert alert-success " style="width: 100%;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fa fa-check"></i> {{ Session::get('success') }}
            </div>
            @endif
            @if (Session::get('error'))
            <div class="alert alert-danger " style="width: 100%;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fa fa-check"></i> {{ Session::get('error') }}
            </div>
            @endif
            <div class="row">
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">

                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" id="commentForm" autocomplete="off" novalidate="novalidate" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        <div class="form-devider"></div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Product SKU Code1 *</label>

                                <input type="text" name="sku_code" id="sku_code" class="form-control" value="{{(!empty($data)) ? $data['sku_code']: ""}}" placeholder="Product SKU Code">

                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Product SKU Name </label>

                                <input type="text" name="sku_name" id="sku_name" class="form-control" value="{{(!empty($data)) ? $data['sku_name']: ""}}" placeholder="Product SKU Name">

                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Description</label>
                                <textarea type="text" name="discription" id="discription" class="form-control" placeholder="Description"><?php echo (!empty($data)) ? $data['discription'] : ""; ?> 
                                </textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Short Name </label>

                                <input type="text" name="short_name" id="short_name" class="form-control" value="{{(!empty($data)) ? $data['short_name']: ""}}" placeholder="Short Name">

                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Ad Sp1 </label>

                                <input type="text" name="ad_sp1" id="ad_sp1" class="form-control" value="{{(!empty($data)) ? $data['ad_sp1']: ""}}" placeholder="Ad Sp1">

                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Given Reason</label>
                                <textarea type="text" name="given_reason" id="given_reason" class="form-control" placeholder="Given Reason"><?php echo (!empty($data)) ? $data['given_reason'] : ""; ?> 
                                </textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Minimum Stock</label>
                                <input type="text" name="minimum_stock" class="form-control" value="{{(!empty($data)) ? $data['minimum_stock']: ""}}" placeholder="Minimum Stock">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Expiry</label>
                                <input type="text" name="expiry" class="form-control" value="{{(!empty($data)) ? $data['expiry']: ""}}" placeholder="Expiry">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Sterilization Type</label>
                                <select name="sterilization_type" id="sterilization_type" class="form-control">
                                    <option value="">-- Select one ---</option>
                                    <option value="EO">EO</option>
                                    <option value="R">R</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is reusable</label><br>

                                <input type="radio" id="is_reusable" name="is_reusable" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_reusable" name="is_reusable" value="No">
                                <label for="No">No</label><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is Instruction</label>
                                <br>
                                <input type="radio" id="is_instruction" name="is_instruction" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_instruction" name="is_instruction" value="No">
                                <label for="No">No</label><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is Sterile</label>
                                <br>
                                <input type="radio" id="is_sterile" name="is_sterile" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_sterile" name="is_sterile" value="No">
                                <label for="No">No</label><br>
                            </div>

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Ad Sp2</label>
                                <input type="text" name="ad_sp2" class="form-control" value="{{(!empty($data)) ? $data['ad_sp2']: ""}}" placeholder="Ad Sp2">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>MRP</label>
                                <input type="text" name="mrp" class="form-control" value="{{(!empty($data)) ? $data['mrp']: ""}}" placeholder="MRP">
                            </div>

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Drawing Image</label>
                                <input type="text" name="minimum_stock" class="form-control" value="{{(!empty($data)) ? $data['minimum_stock']: ""}}" placeholder="">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Family </label>
                               
                                <select name="product_family_id" id="product_family_id" class="form-control" >
                                    <option value="">-- Select one ---</option>
                                    @foreach ($family as $item)
                                    <option value="{{$item->id}}" @if(!empty($data) && $data['product_family_id'] == $item->id) selected @endif>{{$item->family_name}}</option>

                                    <option value="{{$item->id}}" >{{$item->family_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label> Group </label>
                                
                                 <select name="product_group_id" id="product_group_id" class="form-control" >
                                    <option value="">-- Select one ---</option>
                                 @foreach ($group as $item)
                                 <option value="{{$item->id}}" @if(!empty($data) && $data['product_group_id'] == $item->id) selected @endif>{{$item->group_name}}</option>
                                @endforeach
                                </select> 
                                
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label> Brand </label>
                                @if(!empty($data['brand_details_id']))
                                <input type="text" name="brand_details_id" class="form-control" value="{{(!empty($data)) ? $obj_product->get_product_brand($data['brand_details_id']): ""}}" placeholder="">
                                @else
                                 <select name="brand_details_id" id="brand_details_id" class="form-control" >
                                    <option value="">-- Select one ---</option>
                                 @foreach ($brand as $item)
                                        <option value="{{$item->id}}">{{$item->brand_name}}</option>
                                @endforeach
                            </select>   
                                @endif
                            </div>
                            <!-- <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Product Group Id</label>
                                <input type="text" name="minimum_stock" class="form-control"  value="{{(!empty($data)) ? $data['minimum_stock']: ""}}" placeholder="Minimum Stock"> 
                            </div>  -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Snn Description</label>
                                <textarea type="text" name="snn_description" id="snn_description" class="form-control" placeholder="Snn Description"><?php echo (!empty($data)) ? $data['snn_discription'] : ""; ?>
                                </textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>CE Logo</label>
                                <input type="text" name="ce_logo" class="form-control" value="{{(!empty($data)) ? $data['ce_logo']: ""}}" placeholder="CE Logo">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Classification Mdd</label>
                                <input type="text" name="minimum_stock" class="form-control" value="{{(!empty($data)) ? $data['minimum_stock']: ""}}" placeholder="Minimum Stock">
                            </div>

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Drug Licence Number</label>
                                <input type="text" name="drug_license_number" class="form-control" value="{{(!empty($data)) ? $data['drug_license_number']: ""}}" placeholder="Drug Licence Number">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Hierarchy Path</label>
                                <input type="text" name="hierarchy_path" class="form-control" value="{{(!empty($data)) ? $data['hierarchy_path']: ""}}" placeholder="Hierarchy Path">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>HSN Code </label>
                                <input type="text" name="hsn_code" id="hsn_code" class="form-control" value="{{(!empty($data)) ? $data['hsn_code']: ""}}" placeholder="HSN Code">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Instruction for use</label>
                                <input type="text" name="instruction_for_use" class="form-control" value="{{(!empty($data)) ? $data['instruction_for_use']: ""}}" placeholder="Instruction for use">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is ce marked</label><br>
                                <input type="radio" id="is_ce_marked" name="is_ce_marked" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_ce_marked" name="is_ce_marked" value="No">
                                <label for="No">No</label><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is control sample applicable</label><br>
                                <input type="radio" id="is_control_sample_applicable" name="is_control_sample_applicable" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_control_sample_applicable" name="is_control_sample_applicable" value="No">
                                <label for="No">No</label><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is Doc Applicability</label><br>
                                <input type="radio" id="is_doc_applicability" name="is_doc_applicability" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_doc_applicability" name="is_doc_applicability" value="No">
                                <label for="No">No</label><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is Do Not Resuse Logo</label><br>
                                <input type="radio" id="is_donot_reuse_logo" name="is_donot_reuse_logo" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_donot_reuse_lo" name="is_donot_reuse_lo" value="No">
                                <label for="No">No</label><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is Do Not Reuse Symbol</label><br>
                                <input type="radio" id="is_donot_reuse_symbol" name="is_donot_reuse_symbol" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_donot_reuse_symbol" name="is_donot_reuse_symbol" value="No">
                                <label for="No">No</label><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is Ear Log Address</label><br>
                                <input type="radio" id="is_ear_log_address" name="is_ear_log_address" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_ear_log_address" name="is_ear_log_address" value="No">
                                <label for="No">No</label><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is Instructon for Re Use Symbol </label><br>
                                <input type="radio" id="is_instruction_for_reuse_symbol" name="is_instruction_for_reuse_symbol" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_instruction_for_reuse_symbol" name="is_instruction_for_reuse_symbol" value="No">
                                <label for="No">No</label><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is Non Sterile Logo</label><br>
                                <input type="radio" id="is_non_sterile_logo" name="is_non_sterile_logo" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_non_sterile_logo" name="is_non_sterile_logo" value="No">
                                <label for="No">No</label><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is Read Instruction Logo</label><br>
                                <input type="radio" id="is_read_instruction_logo" name="is_read_instruction_logo" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_read_instruction_logo" name="is_read_instruction_logo" value="No">
                                <label for="No">No</label><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is Sterile Expiry Date</label><br>
                                <input type="radio" id="is_doc_applicability" name="is_doc_applicability" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_doc_applicability" name="is_doc_applicability" value="No">
                                <label for="No">No</label><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is Temperature Logo</label><br>
                                <input type="radio" id="is_doc_applicability" name="is_doc_applicability" value="Yes">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_doc_applicability" name="is_doc_applicability" value="No">
                                <label for="No">No</label><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Item Type</label>
                                <select name="item_type" id="item_type" class="form-control">
                                    <option value="">-- Select one ---</option>
                                    <option value="FINISHED GOODS" @if(!empty($data)) && $data['item_type'] == "FINISHED GOODS") selected @endif >FINISHED GOODS</option>
                                    <option value="PSI" @if(!empty($data) && $data['item_type'] == "PSI") selected @endif>PSI</option>
                                    <option value="SEMI FINISHED GOODS" @if(!empty($data) && $data['item_type'] == "SEMI FINISHED GOODS") selected @endif>SEMI FINISHED GOODS</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Label Format Number</label>
                                <input type="text" name="label_format_number" class="form-control" value="{{(!empty($data)) ? $data['label_format_number']: ""}}" placeholder="Label Format Number">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Maximum Stock</label>
                                <input type="text" name="maximum_stock" class="form-control" value="{{(!empty($data)) ? $data['maximum_stock']: ""}}" placeholder="Maximum Stock">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Minimum Stock Set Date</label>
                                <input type="date" name="min_stock_set_date" class="form-control" value="{{(!empty($data)) ? $data['min_stock_set_date']: ""}}" placeholder="Minimum Stock Set Date">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Notified Body Number</label>
                                <input type="text" name="notified_body_number" class="form-control" value="{{(!empty($data)) ? $data['notified_body_number']: ""}}" placeholder="Notified Body Number">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Over Stock Level</label>
                                <input type="text" name="over_stock_level" class="form-control" value="{{(!empty($data)) ? $data['over_stock_level']: ""}}" placeholder="Over Stock Level">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Quantity Per Pack</label>
                                <input type="text" name="quantity_per_pack" class="form-control" value="{{(!empty($data)) ? $data['quantity_per_pack']: ""}}" placeholder="Quantity Per Pack">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Record File No</label>
                                <input type="text" name="record_file_no" class="form-control" value="{{(!empty($data)) ? $data['record_file_no']: ""}}" placeholder="Record File No">
                            </div>

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Revision Record</label>
                                <input type="text" name="revision_record" class="form-control" value="{{(!empty($data)) ? $data['revision_record']: ""}}" placeholder="Revision Record">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Technical file</label>
                                <input type="text" name="technical_file" class="form-control" value="{{(!empty($data)) ? $data['technical_file']: ""}}" placeholder="Technical file">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Unit Weight KG </label>
                                <input type="text" name="unit_weight_kg" class="form-control" value="{{(!empty($data)) ? $data['unit_weight_kg']: ""}}" placeholder="Unit Weight KG">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Groups </label>
                                <input type="text" name="groups" class="form-control" value="{{(!empty($data)) ? $data['groups']: ""}}" placeholder="Groups">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Family Name</label>
                                <input type="text" name="family" class="form-control" value="{{(!empty($data)) ? $data['family']: ""}}" placeholder="Family">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Brand Name</label>
                                <input type="text" name="brand" class="form-control" value="{{(!empty($data)) ? $data['brand']: ""}}" placeholder="Brand">
                            </div>
                            <!-- <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Product Type Id</label>
                                <input type="text" name="mrp" class="form-control"  value="{{(!empty($data)) ? $data['mrp']: ""}}" placeholder="MRP"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Product Group1 Id</label>
                                <input type="text" name="mrp" class="form-control"  value="{{(!empty($data)) ? $data['mrp']: ""}}" placeholder="MRP"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Product OEM Id</label>
                                <input type="text" name="mrp" class="form-control"  value="{{(!empty($data)) ? $data['mrp']: ""}}" placeholder="MRP"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Product Category Id </label>
                                <input type="text" name="mrp" class="form-control"  value="{{(!empty($data)) ? $data['mrp']: ""}}" placeholder="MRP"> 
                            </div> -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Process Sheet No</label>
                                <input type="text" name="process_sheet_no" class="form-control" value="{{(!empty($data)) ? $data['process_sheet_no']: ""}}" placeholder="Process Sheet No">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Image Upload</label>
                                <input type="file" name="pimage" id="pimage" style="width:60%;max-width: 535px; border: 1px solid #1b273d;" />
                            </div>
                            {{--<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>PDF Upload</label>
                                <input type="file" name="pdf" id="pdf" accept="pdf" style="width:60%;max-width: 535px; border: 1px solid #1b273d;" />
                            </div>--}}
                            
                           {{-- <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Process Sheet Upload</label>
                                <input type="file" name="pimage"  style="width:60%;max-width: 535px; border: 1px solid #1b273d;" />
                            </div>--}}
                        </div>
                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Is active</label><br>
                                <input type="radio" id="is_active" name="is_active" value="1">
                                <label for="Yes">Yes</label>&nbsp &nbsp &nbsp
                                <input type="radio" id="is_active" name="is_active" value="0">
                                <label for="No">No</label><br>
                            </div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Save
                                </button>
                            </div>
                        </div>
                        <div class="form-devider"></div>
                    </form>

                </div>
            </div>

        </div>
    </div>
    <!-- az-content-body -->
</div>
<script src="<?= url('') ?>/js/azia.js"></script>

<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"> </script>

<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>

<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>

<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script>
    $('#product').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 4,
        allowClear: true,
        ajax: {
            url: "{{ url('fgs/productsearch') }}",
            processResults: function(data) {
                return {
                    results: data
                };
            }
        }
    }).on('change', function(e) {
        $('.spinner-button').show();
        let res = $(this).select2('data')[0];
        if (res) {
            $('#description').text(res.discription)
            $('#product_group').val(res.group_name)
            $('#hsn_code').val(res.hsn_code)
        }
    });
    $("#commentForm").validate({
        rules: {
            product: {
                required: true,
            },
            purchase_price: {
                required: true,
            },
            sales_price: {
                required: true,
            },
            transfer_price: {
                required: true,
            },
            pimage:{
                extension: "jpeg|png|jpg",
            },
            pdf:{
                extension: "pdf",
            }

        },
        submitHandler: function(form) {
            form.submit();
        }
    });
</script>






@stop
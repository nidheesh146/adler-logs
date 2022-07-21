@php
       $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
        //echo $Action;
@endphp


  <div class="az-sidebar">
    <div class="az-sidebar-header">
      <a href="{{url('')}}" class="az-logo" style="
      text-transform: uppercase;color: #1d263d;"><img class="wd-45 ht-40 mg-l-10 bd bd-gray-500 rounded-10"
        src="<?=url('');?>/img/alder_logo.png" >&nbsp;ADLER</a>
    </div><!-- az-sidebar-header -->
    
    <div class="az-sidebar-loggedin">
      <div class="az-img-user online"><img src="<?=url('');?>/img/profile.png" alt=""></div>
      <div class="media-body">
        <h6>Aziana Pechon</h6>
        <span>Premium Member</span>
      </div><!-- media-body -->
    </div><!-- az-sidebar-loggedin -->
    <div class="az-sidebar-body">
      <ul class="nav">
        <li class="nav-label">Main Menu</li>
        <li class="nav-item @if (in_array($Action,['Inventory.get_purchase_reqisition','Inventory.add_purchase_reqisition','Inventory.edit_purchase_reqisition',
        'Inventory.get_purchase_reqisition_item','Inventory.edit_purchase_reqisition_item',
        'Inventory.add_purchase_reqisition_item','Approval.getList','Quotation.getQuotation', 'SupplierQuotation.viewSupplierQuotationItems', 'SupplierQuotation.getSupplierQuotationEditItem', 'SupplierQuotation.comparisonOfQuotation','SupplierQuotation.getSupplierQuotation'])) {{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="fas fa-shopping-cart" style="font-size: 17px;"></i>Purchase Details</a>
          <ul class="nav-sub">
            {{-- <li class="nav-sub-item"><a href="#" class="nav-sub-link">Order</a></li> --}}
            <li class="nav-sub-item @if(in_array($Action,['Inventory.get_purchase_reqisition','Inventory.add_purchase_reqisition','Inventory.edit_purchase_reqisition',
            'Inventory.get_purchase_reqisition_item','Inventory.edit_purchase_reqisition_item',
            'Inventory.add_purchase_reqisition_item'])){{'active'}} @endif"><a href="{{url('inventory/get-purchase-reqisition')}}"  class="nav-sub-link">Requisition</a></li>
            <li class="nav-sub-item @if(in_array($Action,['Approval.getList'])){{'active'}} @endif"><a href="{{url('inventory/purchase-reqisition/approval')}}"  class="nav-sub-link">Requisition Approval</a></li>
          
            <li class="nav-sub-item  @if(in_array($Action,['Quotation.getQuotation'])){{'active'}} @endif "><a href="{{url('inventory/quotation')}}" class="nav-sub-link">Request for Quotation</a></li>
            <!-- <li class="nav-sub-item"><a href="{{url('inventory/quotation')}}"  class="nav-sub-link">Purchase Reqisition</a></li> -->
            <li class="nav-sub-item @if(in_array($Action,['SupplierQuotation.getSupplierQuotation', 'SupplierQuotation.viewSupplierQuotationItems', 'SupplierQuotation.getSupplierQuotationEditItem','SupplierQuotation.comparisonOfQuotation'])){{'active'}} @endif"><a href="{{url('inventory/supplier-quotation')}}" class="nav-sub-link">Supplier Quotation</a></li>
            {{-- <li class="nav-sub-item @if(in_array($Action,['SupplierQuotation.comparisonOfQuotation'])){{'active'}} @endif"><a href="{{url('inventory/supplier-quotation')}}" class="nav-sub-link">Comparison of Quotation</a></li> --}}

          </ul>
        </li><!-- nav-item -->
        
        <li class="nav-item @if(in_array($Action,['BatchCard.getBatchcardUpload'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-tabs-outline"></i>Batch Card</a>
          <ul class="nav-sub">
            <li class="nav-sub-item @if(in_array($Action,['BatchCard.getBatchcardUpload'])){{'active'}} @endif ">
            <a href="{{url('batchcard/batchcard-upload')}}"  class="nav-sub-link">Batch Card Upload</a>
            </li>
          </ul>
        </li>



      </ul><!-- nav -->
    </div><!-- az-sidebar-body -->
  </div><!-- az-sidebar -->

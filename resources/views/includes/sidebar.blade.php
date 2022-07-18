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
            <li class="nav-sub-item @if(in_array($Action,['SupplierQuotation.getSupplierQuotation', 'SupplierQuotation.viewSupplierQuotationItems', 'SupplierQuotation.getSupplierQuotationEditItem'])){{'active'}} @endif"><a href="{{url('inventory/supplier-quotation')}}" class="nav-sub-link">Supplier Quotation</a></li>
            {{-- <li class="nav-sub-item @if(in_array($Action,['SupplierQuotation.comparisonOfQuotation'])){{'active'}} @endif"><a href="{{url('inventory/supplier-quotation')}}" class="nav-sub-link">Comparison of Quotation</a></li> --}}
            
            


          </ul>
        </li><!-- nav-item -->
        



        <li class="nav-item">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-edit"></i>Forms</a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="form-elements.html" class="nav-sub-link">Form Elements</a></li>
            <li class="nav-sub-item"><a href="form-layouts.html" class="nav-sub-link">Form Layouts</a></li>
            <li class="nav-sub-item"><a href="form-validation.html" class="nav-sub-link">Form Validation</a></li>
            <li class="nav-sub-item"><a href="form-wizards.html" class="nav-sub-link">Form Wizards</a></li>
            <li class="nav-sub-item"><a href="form-editor.html" class="nav-sub-link">WYSIWYG Editor</a></li>
          </ul>
        </li><!-- nav-item -->
        <li class="nav-item">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-chart-bar-outline"></i>Charts</a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="chart-morris.html" class="nav-sub-link">Morris Charts</a></li>
            <li class="nav-sub-item"><a href="chart-flot.html" class="nav-sub-link">Flot Charts</a></li>
            <li class="nav-sub-item"><a href="chart-chartjs.html" class="nav-sub-link">ChartJS</a></li>
            <li class="nav-sub-item"><a href="chart-sparkline.html" class="nav-sub-link">Sparkline</a></li>
            <li class="nav-sub-item"><a href="chart-peity.html" class="nav-sub-link">Peity</a></li>
          </ul>
        </li><!-- nav-item -->
        <li class="nav-item">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-map"></i>Maps</a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="map-google.html" class="nav-sub-link">Google Maps</a></li>
            <li class="nav-sub-item"><a href="map-leaflet.html" class="nav-sub-link">Leaflet</a></li>
            <li class="nav-sub-item"><a href="map-vector.html" class="nav-sub-link">Vector Maps</a></li>
          </ul>
        </li><!-- nav-item -->
        <li class="nav-item">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-tabs-outline"></i>Tables</a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="table-basic.html" class="nav-sub-link">Basic Tables</a></li>
            <li class="nav-sub-item"><a href="table-data.html" class="nav-sub-link">Data Tables</a></li>
          </ul>
        </li><!-- nav-item -->
        <li class="nav-item">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-archive"></i>Utilities</a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="util-background.html" class="nav-sub-link">Background</a></li>
            <li class="nav-sub-item"><a href="util-border.html" class="nav-sub-link">Border</a></li>
            <li class="nav-sub-item"><a href="util-display.html" class="nav-sub-link">Display</a></li>
            <li class="nav-sub-item"><a href="util-flex.html" class="nav-sub-link">Flex</a></li>
            <li class="nav-sub-item"><a href="util-height.html" class="nav-sub-link">Height</a></li>
            <li class="nav-sub-item"><a href="util-margin.html" class="nav-sub-link">Margin</a></li>
            <li class="nav-sub-item"><a href="util-padding.html" class="nav-sub-link">Padding</a></li>
            <li class="nav-sub-item"><a href="util-position.html" class="nav-sub-link">Position</a></li>
            <li class="nav-sub-item"><a href="util-typography.html" class="nav-sub-link">Typography</a></li>
            <li class="nav-sub-item"><a href="util-width.html" class="nav-sub-link">Width</a></li>
            <li class="nav-sub-item"><a href="util-extras.html" class="nav-sub-link">Extras</a></li>
          </ul>
        </li><!-- nav-item -->
      </ul><!-- nav -->
    </div><!-- az-sidebar-body -->
  </div><!-- az-sidebar -->

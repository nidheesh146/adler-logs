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
      <div class="az-img-user online">
        @if(config('user')['profile_img'])
        <img src="<?=url('');?>/Employee_Image/{{ config('user')['profile_img']}}" alt="">
        @else
        <img src="<?=url('');?>/img/profile.png" alt="">
        @endif
      </div>
      <div class="media-body">
        <h6>{{config('user')['f_name']}} {{config('user')['l_name']}}</h6>
        <span>Premium Member</span>
      </div><!-- media-body -->
    </div><!-- az-sidebar-loggedin -->
    <div class="az-sidebar-body">
      <ul class="nav">
        <li class="nav-label">Main Menu</li>
        <li class="nav-item @if (in_array($Action,['Inventory.get_purchase_reqisition','Inventory.add_purchase_reqisition','Inventory.edit_purchase_reqisition',
        'Inventory.get_purchase_reqisition_item','Inventory.edit_purchase_reqisition_item',
        'Inventory.add_purchase_reqisition_item','Approval.getList','Quotation.getQuotation', 
        'SupplierQuotation.viewSupplierQuotationItems', 'SupplierQuotation.getSupplierQuotationEditItem',
        'SupplierQuotation.comparisonOfQuotation','SupplierQuotation.getSupplierQuotation','Purchase.getFinalPurchase',
        'Purchase.addFinalPurchase','Purchase.Edit_PO_item','Quotation.getQuotation','Supplier.list_supplier','Purchase.purchaseOderCancellation',
        'Supplier.add_supplier','Purchase.viewFinalPurchase','Purchase.add1FinalPurchase','Purchase.editFinalPurchase','Purchase.purchaseOderApproval','Quotation.directPurchase','Purchase.viewFinalPurchaseExcess','Purchase.getExcessQty'])) {{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="fas fa-shopping-cart" style="font-size: 17px;"></i>Purchase Details</a>
          <ul class="nav-sub">
            <li class="nav-sub-item @if(in_array($Action,['Inventory.get_purchase_reqisition','Inventory.add_purchase_reqisition','Inventory.edit_purchase_reqisition',
            'Inventory.get_purchase_reqisition_item','Inventory.edit_purchase_reqisition_item',
            'Inventory.add_purchase_reqisition_item'])){{'active'}} @endif"><a href="{{url('inventory/get-purchase-reqisition')}}"  class="nav-sub-link">Requisition</a></li>
            <li class="nav-sub-item @if(in_array($Action,['Approval.getList'])){{'active'}} @endif"><a href="{{url('inventory/purchase-reqisition/approval')}}"  class="nav-sub-link">Requisition Approval</a></li>
          
            <li class="nav-sub-item  @if(in_array($Action,['Quotation.getQuotation'])){{'active'}} @endif "><a href="{{url('inventory/quotation')}}" class="nav-sub-link">Request for Quotation</a></li>
            <li class="nav-sub-item  @if(in_array($Action,['Quotation.directPurchase'])){{'active'}} @endif "><a href="{{url('inventory/direct/purchase')}}" class="nav-sub-link">Fixed Rate-Purchase/Work Order</a></li>
            <li class="nav-sub-item @if(in_array($Action,['SupplierQuotation.getSupplierQuotation', 'SupplierQuotation.viewSupplierQuotationItems', 'SupplierQuotation.getSupplierQuotationEditItem','SupplierQuotation.comparisonOfQuotation'])){{'active'}} @endif"><a href="{{url('inventory/supplier-quotation')}}" class="nav-sub-link">Supplier Quotation</a></li>
            {{-- <li class="nav-sub-item @if(in_array($Action,['SupplierQuotation.comparisonOfQuotation'])){{'active'}} @endif"><a href="{{url('inventory/supplier-quotation')}}" class="nav-sub-link">Comparison of Quotation</a></li> --}}
            <li class="nav-sub-item  @if(in_array($Action,['Purchase.getFinalPurchase','Purchase.addFinalPurchase','Purchase.Edit_PO_item','Purchase.viewFinalPurchase','Purchase.add1FinalPurchase','Purchase.editFinalPurchase'])){{'active'}} @endif "><a href="{{url('inventory/final-purchase')}}" class="nav-sub-link">Purchase/Work Order</a></li>
            <li class="nav-sub-item  @if(in_array($Action,['Purchase.purchaseOderApproval','Purchase.viewFinalPurchase'])){{'active'}} @endif "><a href="{{url('inventory/final-purchase/approval')}}" class="nav-sub-link">Order Approval</a></li>
            <li class="nav-sub-item  @if(in_array($Action,['Purchase.purchaseOderCancellation','Purchase.viewFinalPurchase'])){{'active'}} @endif "><a href="{{url('inventory/final-purchase/cancellation')}}" class="nav-sub-link">Order Cancellation</a></li>
            <li class="nav-sub-item  @if(in_array($Action,['Purchase.viewFinalPurchaseExcess','Purchase.getExcessQty'])){{'active'}} @endif "><a href="{{url('inventory/final-purchase/excess-quantity')}}" class="nav-sub-link">Excess Order Quatity </a></li>
            <li class="nav-sub-item  @if(in_array($Action,['Supplier.list_supplier','Supplier.add_supplier'])){{'active'}} @endif "><a href="{{url('inventory/suppliers-list')}}" class="nav-sub-link">Supplier Master</a></li>
          </ul>
        </li><!-- nav-item -->
        

        <li class="nav-item @if(in_array($Action,['Purchase.supplierInvoice','Purchase.supplierInvoiceAdd','Purchase.supplierInvoiceItemEdit','LotAllocation.addLotAllocation','LotAllocation.lotAllocation','MIQ.MIQlist','MIQ.MIQAdd','MIQ.MIQAddItemInfo','MAC.MACAddItemInfo','MAC.MAClist','MAC.MACAdd',
        'MRD.RMRNAddItemInfo','MRR.addMRR',
        'MRD.MRDlist','MRD.MRDAdd','MRD.MRDAddItemInfo','MRD.RMRNlist','MRD.RMRNAdd','Stock.StockToProduction','Stock.StockToProductionAdd','MAC.WOAAdd','MRD.WORAdd','Stock.StockFromProduction','Stock.StockFromProductionAdd','Stock.StockTransfer','Stock.StockTransferAdd'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub">
            <i class="fas fa-boxes" style="font-size:19px"></i>Inventory</a>
            
          <ul class="nav-sub">
          <li class="nav-sub-item  @if(in_array($Action,['Purchase.supplierInvoice','Purchase.supplierInvoiceAdd','Purchase.supplierInvoiceItemEdit'])){{'active'}} @endif "><a href="{{url('inventory/supplier-invoice')}}" class="nav-sub-link">Supplier Invoice</a></li>
            <li class="nav-sub-item  @if(in_array($Action,['LotAllocation.addLotAllocation','LotAllocation.lotAllocation'])){{'active'}} @endif "><a href="{{url('inventory/lot-allocation-list')}}" class="nav-sub-link">LOT Number Allocation</a></li>
            <li class="nav-sub-item  @if(in_array($Action,['MIQ.MIQlist','MIQ.MIQAdd','MIQ.MIQAddItemInfo','MIQ.MIQAddItemInfo'])){{'active'}} @endif "><a href="{{url('inventory/MIQ')}}" class="nav-sub-link">MIQ</a></li> 
            <li class="nav-sub-item  @if(in_array($Action,['MAC.MAClist','MAC.MACAdd','MAC.MACAddItemInfo','MAC.WOAAdd'])){{'active'}} @endif "><a href="{{url('inventory/MAC')}}" class="nav-sub-link">MAC/WOA</a></li> 
            <li class="nav-sub-item  @if(in_array($Action,['MRD.MRDlist','MRD.MRDAdd','MRD.MRDAddItemInfo','MRD.WORAdd'])){{'active'}} @endif "><a href="{{url('inventory/MRD')}}" class="nav-sub-link">MRD/WOR</a></li>
            <li class="nav-sub-item  @if(in_array($Action,['MRD.RMRNlist','MRD.RMRNAdd','MRD.RMRNAddItemInfo'])){{'active'}} @endif "><a href="{{url('inventory/RMRN')}}" class="nav-sub-link">RMRN</a></li>  
            <li class="nav-sub-item  @if(in_array($Action,['MRR.addMRR','MRD.MRDAdd','MRD.MRDAddItemInfo'])){{'active'}} @endif "><a href="{{url('inventory/receipt-report')}}" class="nav-sub-link">MRR/SRR</a></li>  
            <li class="nav-sub-item  @if(in_array($Action,['Stock.StockToProduction','Stock.StockToProductionAdd'])){{'active'}} @endif "><a href="{{url('inventory/Stock/ToProduction')}}" class="nav-sub-link">Stock Issue To Production</a></li> 
            <li class="nav-sub-item  @if(in_array($Action,['Stock.StockFromProduction','Stock.StockFromProductionAdd'])){{'active'}} @endif "><a href="{{url('inventory/Stock/FromProduction')}}" class="nav-sub-link">Stock Return From Production</a></li>
            <li class="nav-sub-item  @if(in_array($Action,['Stock.StockTransfer','Stock.StockTransferAdd'])){{'active'}} @endif "><a href="{{url('inventory/Stock/transfer')}}" class="nav-sub-link">Stock Transfer Order</a></li>  
            
          </ul>
        </li> 
        
        <li class="nav-item @if(in_array($Action,['BatchCard.getBatchcardUpload', 'BatchCard.BatchcardAdd'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-tabs-outline"></i>Batch Card</a>
          <ul class="nav-sub">
            <li class="nav-sub-item @if(in_array($Action,['BatchCard.getBatchcardUpload'])){{'active'}} @endif ">
            <a href="{{url('batchcard/batchcard-upload')}}"  class="nav-sub-link">Batch Card Upload</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['BatchCard.BatchcardAdd'])){{'active'}} @endif ">
            <a href="{{url('batchcard/batchcard-add')}}"  class="nav-sub-link">Batch Card Add</a>
            </li>
          </ul>
        </li>

        <li class="nav-item @if(in_array($Action,['Label.sterilizationProductLabel', 'Label.nonSterileProductLabel' ,'Label.instrumentLabel','Label.patientLabel', 'Label.mrpLabel', 'Label.generateInstrumentLabel', 'Label.generateMRPLabel','Label.generateNonSterileProductLabel','Label.generateSterilizationProductLabel','Label.generatePatientLabel','Label.printingReport'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="fas fa-address-card" style="font-size:20px;"></i>Label Card</a>
          <ul class="nav-sub">
            <li class="nav-sub-item @if(in_array($Action,['Label.instrumentLabel', 'Label.generateInstrumentLabel'])){{'active'}} @endif ">
              <a href="{{url('label/instrument-label')}}"  class="nav-sub-link">Instrument Label</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['Label.mrpLabel', 'Label.generateMRPLabel'])){{'active'}} @endif ">
              <a href="{{url('label/mrp-label')}}"  class="nav-sub-link">MRP Label</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['Label.nonSterileProductLabel', 'Label.generateNonSterileProductLabel'])){{'active'}} @endif ">
              <a href="{{url('label/non-sterile-product-label')}}"  class="nav-sub-link">Non-Sterile Label</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['Label.sterilizationProductLabel','Label.generateSterilizationProductLabel'])){{'active'}} @endif ">
              <a href="{{url('label/sterilization-label')}}"  class="nav-sub-link">Sterilization Label</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['Label.patientLabel', 'Label.generatePatientLabel'])){{'active'}} @endif ">
              <a href="{{url('label/patient-label')}}"  class="nav-sub-link">Patient Label</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['Label.printingReport'])){{'active'}} @endif ">
              <a href="{{url('label/printing-report')}}"  class="nav-sub-link">Label Printing Report</a>
            </li>
          </ul>
        </li>
        <li class="nav-item @if(in_array($Action,['RowMaterial.materialList','RowMaterial.materialAdd','RowMaterial.materialEdit','RowMaterial.fixedRateList','RowMaterial.getfixedRateUpload'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="fas fa-swatchbook" style="font-size: 19px"></i>Row Material</a>
          <ul class="nav-sub">
            <li class="nav-sub-item @if(in_array($Action,['RowMaterial.materialList','RowMaterial.materialAdd','RowMaterial.materialEdit'])){{'active'}} @endif ">
            <a href="{{url('row-material/list')}}"  class="nav-sub-link">Row Materials</a>
            </li>
            <!-- <li class="nav-sub-item @if(in_array($Action,['BatchCard.BatchcardAdd'])){{'active'}} @endif ">
            <a href="{{url('batchcard/batchcard-add')}}"  class="nav-sub-link">Row Material Upload</a>
            </li> -->
            <li class="nav-sub-item @if(in_array($Action,['RowMaterial.fixedRateList'])){{'active'}} @endif ">
            <a href="{{url('row-material/fixed-rate')}}"  class="nav-sub-link">Fixed Rate Row Material</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['RowMaterial.getfixedRateUpload'])){{'active'}} @endif ">
            <a href="{{url('row-material/fixed-rate/upload')}}"  class="nav-sub-link">Fixed Rate Material Upload</a>
            </li>
          </ul>
        </li>

        <li class="nav-item @if(in_array($Action,['Product.productList','Product.productFileUpload'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="fab fa-product-hunt" style="font-size: 19px"></i>Product</a>
          <ul class="nav-sub">
            <li class="nav-sub-item @if(in_array($Action,['Product.productList'])){{'active'}} @endif ">
            <a href="{{url('product/list')}}"  class="nav-sub-link">Products</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['Product.productFileUpload'])){{'active'}} @endif ">
            <a href="{{url('product/file/upload')}}"  class="nav-sub-link">Product Upload</a>
            </li> 
          </ul>
        </li>

        <li class="nav-item @if(in_array($Action,['Employee.employeeList','Employee.employeeAdd','Employee.employeeEdit'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="fas fa-user-alt" style="font-size: 19px"></i>Employee</a>
          <ul class="nav-sub">
            <li class="nav-sub-item @if(in_array($Action,['Employee.employeeList','Employee.employeeAdd','Employee.employeeEdit'])){{'active'}} @endif ">
            <a href="{{url('employee/list')}}"  class="nav-sub-link">Employee</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['BatchCard.BatchcardAdd'])){{'active'}} @endif ">
            <a href="{{url('batchcard/batchcard-add')}}"  class="nav-sub-link">Permissions</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['BatchCard.BatchcardAdd'])){{'active'}} @endif ">
            <a href="{{url('module/list')}}"  class="nav-sub-link">Modules</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['BatchCard.BatchcardAdd'])){{'active'}} @endif ">
            <a href="{{url('batchcard/batchcard-add')}}"  class="nav-sub-link">Role</a>
            </li>
          </ul>
        </li>


      </ul><!-- nav -->
    </div><!-- az-sidebar-body -->
  </div><!-- az-sidebar -->

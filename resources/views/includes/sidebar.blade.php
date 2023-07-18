@php
       $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
       // echo $Action;
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
        'Purchase.addFinalPurchase','Purchase.Edit_PO_item','Quotation.getQuotation','Supplier.list_supplier','Purchase.purchaseOderCancellation','Purchase.pendingPurchaseRealisation',
        'Supplier.add_supplier','Purchase.viewFinalPurchase','Purchase.add1FinalPurchase','Purchase.editFinalPurchase','Purchase.purchaseOderApproval','Quotation.directPurchase','Purchase.viewFinalPurchaseExcess','Purchase.getExcessQty'])) {{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="fas fa-shopping-cart" style="font-size: 17px;"></i>Purchase Details</a>
          <ul class="nav-sub">
            @if (in_array('purchase_details.requisition_list',config('permission')) || in_array('purchase_details.requisition_add',config('permission')) || in_array('purchase_details.requisition_edit',config('permission')) || in_array('purchase_details.requisition_delete',config('permission')) ||
            in_array('purchase_details.requisition_item_list',config('permission')) || in_array('purchase_details.requisition_item_add',config('permission')) || in_array('purchase_details.requisition_item_edit',config('permission')) ) 
            <li class="nav-sub-item @if(in_array($Action,['Inventory.get_purchase_reqisition','Inventory.add_purchase_reqisition','Inventory.edit_purchase_reqisition',
            'Inventory.get_purchase_reqisition_item','Inventory.edit_purchase_reqisition_item',
            'Inventory.add_purchase_reqisition_item'])){{'active'}} @endif"><a href="{{url('inventory/get-purchase-reqisition')}}"  class="nav-sub-link">Requisition</a></li>
            @endif
            @if (in_array('purchase_details.requisition_approval',config('permission'))) 
            <li class="nav-sub-item @if(in_array($Action,['Approval.getList'])){{'active'}} @endif"><a href="{{url('inventory/purchase-reqisition/approval')}}"  class="nav-sub-link">Requisition Approval</a></li>
            @endif
            @if (in_array('purchase_details.request_for_quotation',config('permission'))) 
            <li class="nav-sub-item  @if(in_array($Action,['Quotation.getQuotation'])){{'active'}} @endif "><a href="{{url('inventory/quotation')}}" class="nav-sub-link">Request for Quotation</a></li>
            @endif
            @if (in_array('purchase_details.fixed_rate',config('permission'))) 
            <li class="nav-sub-item  @if(in_array($Action,['Quotation.directPurchase'])){{'active'}} @endif "><a href="{{url('inventory/direct/purchase')}}" class="nav-sub-link">Fixed Rate-Purchase/Work Order</a></li>
            @endif
            @if (in_array('supplier_qotation.list',config('permission')) || in_array('supplier_qotation.comparison',config('permission')) || in_array('supplier_qotation.item_edit',config('permission'))) 
            <li class="nav-sub-item @if(in_array($Action,['SupplierQuotation.getSupplierQuotation', 'SupplierQuotation.viewSupplierQuotationItems', 'SupplierQuotation.getSupplierQuotationEditItem','SupplierQuotation.comparisonOfQuotation'])){{'active'}} @endif"><a href="{{url('inventory/supplier-quotation')}}" class="nav-sub-link">Supplier Quotation</a></li>
            @endif
            @if (in_array('order.list',config('permission')) || in_array('order.creation',config('permission')) || in_array('order.edit',config('permission')) || in_array('order.delete',config('permission')) || in_array('order.change_status',config('permission')) ||  in_array('order.view',config('permission')))
            <li class="nav-sub-item  @if(in_array($Action,['Purchase.getFinalPurchase','Purchase.addFinalPurchase','Purchase.Edit_PO_item','Purchase.viewFinalPurchase','Purchase.add1FinalPurchase','Purchase.editFinalPurchase'])){{'active'}} @endif "><a href="{{url('inventory/final-purchase')}}" class="nav-sub-link">Purchase/Work Order</a></li>
            @endif
            @if (in_array('order.approval',config('permission')))
            <li class="nav-sub-item  @if(in_array($Action,['Purchase.purchaseOderApproval','Purchase.viewFinalPurchase'])){{'active'}} @endif "><a href="{{url('inventory/final-purchase/approval')}}" class="nav-sub-link">Order Approval</a></li>
            @endif
            @if (in_array('order.cancellation',config('permission')) || in_array('order.partial_cancellation',config('permission')))
            <li class="nav-sub-item  @if(in_array($Action,['Purchase.purchaseOderCancellation','Purchase.viewFinalPurchase'])){{'active'}} @endif "><a href="{{url('inventory/final-purchase/cancellation')}}" class="nav-sub-link">Order Cancellation</a></li>
            @endif
            @if (in_array('order.excess_order_qty',config('permission')))
            <li class="nav-sub-item  @if(in_array($Action,['Purchase.viewFinalPurchaseExcess','Purchase.getExcessQty'])){{'active'}} @endif "><a href="{{url('inventory/final-purchase/excess-quantity')}}" class="nav-sub-link">Excess Order Quatity </a></li>
            @endif
            @if(in_array('supplier.list',config('permission')) || in_array('supplier.add',config('permission')) || in_array('supplier.edit',config('permission')) || in_array('supplier.delete',config('permission')))
            <li class="nav-sub-item  @if(in_array($Action,['Supplier.list_supplier','Supplier.add_supplier'])){{'active'}} @endif "><a href="{{url('inventory/suppliers-list')}}" class="nav-sub-link">Supplier Master</a></li>
            @endif
            <li class="nav-sub-item  @if(in_array($Action,['Purchase.pendingPurchaseRealisation'])){{'active'}} @endif "><a href="{{url('inventory/pending-purchase-realisation')}}" class="nav-sub-link">R02-Pending Purchase Realisation</a></li>
          </ul>
        </li><!-- nav-item -->
        

        <li class="nav-item @if(in_array($Action,['Purchase.supplierInvoice','Purchase.supplierInvoiceAdd','Purchase.supplierInvoiceItemEdit','LotAllocation.addLotAllocation','LotAllocation.lotAllocation','MIQ.MIQlist','MIQ.MIQAdd','MIQ.MIQAddItemInfo','MAC.MACAddItemInfo','MAC.MAClist','MAC.MACAdd','MRR.receiptReport',
        'MRD.RMRNAddItemInfo','MRR.addMRR','Stock.viewItems','Inventoryreport.get_data','Stock.stockReport',
        'MRD.MRDlist','MRD.MRDAdd','MRD.MRDAddItemInfo','MRD.RMRNlist','MRD.RMRNAdd','Stock.StockToProduction','Stock.StockToProductionAdd','MAC.WOAAdd','MRD.WORAdd','Stock.StockFromProduction','Stock.StockFromProductionAdd','Stock.StockTransfer','Stock.StockTransferAdd','Stock.DirectSIP','Stock.IndirectSIP','Stock.SIPview','MIQ.LiveQuarantineReport'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub">
            <i class="fas fa-boxes" style="font-size:19px"></i>Inventory</a>
            
          <ul class="nav-sub">
          <li class="nav-sub-item  @if(in_array($Action,['Purchase.supplierInvoice','Purchase.supplierInvoiceAdd','Purchase.supplierInvoiceItemEdit'])){{'active'}} @endif "><a href="{{url('inventory/supplier-invoice')}}" class="nav-sub-link">Supplier Invoice</a></li>
            <li class="nav-sub-item  @if(in_array($Action,['LotAllocation.addLotAllocation','LotAllocation.lotAllocation'])){{'active'}} @endif "><a href="{{url('inventory/lot-allocation-list')}}" class="nav-sub-link">LOT Number Allocation</a></li>
            <li class="nav-sub-item  @if(in_array($Action,['MIQ.MIQlist','MIQ.MIQAdd','MIQ.MIQAddItemInfo','MIQ.MIQAddItemInfo'])){{'active'}} @endif "><a href="{{url('inventory/MIQ')}}" class="nav-sub-link">MIQ</a></li> 
              <li class="nav-sub-item  @if(in_array($Action,['MIQ.LiveQuarantineReport'])){{'active'}} @endif "><a href="{{url('inventory/MIQ/QuarantineReport')}}" class="nav-sub-link"> Quarantine Report</a></li> 
            <li class="nav-sub-item  @if(in_array($Action,['MAC.MAClist','MAC.MACAdd','MAC.MACAddItemInfo','MAC.WOAAdd'])){{'active'}} @endif "><a href="{{url('inventory/MAC')}}" class="nav-sub-link">MAC/WOA</a></li> 
            <li class="nav-sub-item  @if(in_array($Action,['MRD.MRDlist','MRD.MRDAdd','MRD.MRDAddItemInfo','MRD.WORAdd'])){{'active'}} @endif "><a href="{{url('inventory/MRD')}}" class="nav-sub-link">MRD/WOR</a></li>
            <li class="nav-sub-item  @if(in_array($Action,['MRD.RMRNlist','MRD.RMRNAdd','MRD.RMRNAddItemInfo'])){{'active'}} @endif "><a href="{{url('inventory/RMRN')}}" class="nav-sub-link">RMRN</a></li>  
            <li class="nav-sub-item  @if(in_array($Action,['MRR.addMRR','MRR.receiptReport'])){{'active'}} @endif "><a href="{{url('inventory/receipt-report')}}" class="nav-sub-link">MRR/SRR</a></li>  
            <li class="nav-sub-item  @if(in_array($Action,['Stock.stockReport'])){{'active'}} @endif"><a href="{{url('inventory/stock-report')}}"  class="nav-sub-link">Stock Report</a></li>
            <li class="nav-sub-item  @if(in_array($Action,['Stock.StockToProduction','Stock.StockToProductionAdd','Stock.DirectSIP','Stock.IndirectSIP','Stock.SIPview'])){{'active'}} @endif "><a href="{{url('inventory/Stock/ToProduction')}}" class="nav-sub-link">Stock Issue To Production</a></li> 
            <li class="nav-sub-item  @if(in_array($Action,['Stock.StockFromProduction','Stock.StockFromProductionAdd'])){{'active'}} @endif "><a href="{{url('inventory/Stock/FromProduction')}}" class="nav-sub-link">Stock Return From Production</a></li>
            <li class="nav-sub-item  @if(in_array($Action,['Stock.StockTransfer','Stock.StockTransferAdd','Stock.viewItems'])){{'active'}} @endif "><a href="{{url('inventory/Stock/transfer')}}" class="nav-sub-link">Stock Transfer Order</a></li>  
            <li class="nav-sub-item @if(in_array($Action,['Inventoryreport.get_data'])){{'active'}} @endif "><a href="{{url('inventory/inventory-trans-report')}}" class="nav-sub-link">Inventory Transaction Report</a></li>  

          </ul>
        </li>
        
        <li class="nav-item @if(in_array($Action,['CustomerSupplier.customerSupplierList','CustomerSupplier.addCustomerSupplier','Price.priceList','Price.priceAdd','MRN.MRNList','MRN.MRNList','MRN.MRNAdd','MRN.MRNitemlist','OEF.pendingOEF',
        'MRN.MRNitemAdd','MIN.MINList','MIN.MINAdd','MIN.MINitemlist','MIN.MINitemAdd','CMIN.CMINList','CMIN.CMINAdd','CMIN.CMINitemlist','CMIN.CMINitemAdd','OEF.OEFList','OEF.OEFAdd','OEF.OEFitemlist','OEF.OEFitemAdd','COEF.COEFList','COEF.COEFAdd','COEF.COEFitemlist','COEF.COEFitemAdd','GRS.GRSList','GRS.GRSAdd','GRS.GRSitemlist',
        'PI.PIAdd','PI.PIList','PI.PIitemlist','CPI.CPIList','CPI.CPIAdd','CPI.CPIItemList','DNI.DNIList','DNI.DNIAdd','DNI.DNIitemlist','EXI.EXIList','EXI.EXIAdd','EXI.EXIitemlist','StockManagement.location1Stock','StockManagement.location2Stock','StockManagement.MAAStock','StockManagement.quarantineStock','MTQ.MTQAdd','StockManagement.productionStockAdd',
        'StockManagement.productionStockList','CMIN.CMINList','CMIN.CMINAdd','CMIN.CMINItemList','CMIN.CMINitemAdd','PI.pendingPI','GRS.pendingGRS','PI.mergedPIList','BackorderReport.get_data','GRS.GRSitemAdd',
        'COEF.COEFList','COEF.COEFAdd','COEF.COEFItemList','COEF.COEFitemAdd','CGRS.CGRSList','CGRS.CGRSAdd','CGRS.CGRSItemList','ProductMaster.productList','MTQ.MTQitemlist','MTQ.MTQList','MTQ.MTQitemAdd','MIS.MISList','MIS.MISAdd','MIS.MISitemlist','SRN.SRNAdd','SRN.SRNlist','SRN.SRNitemlist' ,
        'CMTQ.CMTQitemlist','CMTQ.CMTQList','CMTQ.CMTQAdd','CMTQ.CMTQitemAdd',
        'StockManagement.allLocations','StockManagement.location3Stock','StockManagement.locationSNN','StockManagement.locationAHPL','PI.pendingPI','Fgsreport.get_data','StockManagement.batchTraceReport','DNI.netBillingReport'])){{'active show'}} @endif ">

          <a href="#" class="nav-link with-sub"><i class="fas fa-address-card" style="font-size:20px;"></i>Finished Goods</a>
          <ul class="nav-sub">
            <li class="nav-sub-item @if(in_array($Action,['CustomerSupplier.customerSupplierList','CustomerSupplier.addCustomerSupplier'])){{'active'}} @endif ">
              <a href="{{url('fgs/customer-supplier')}}"  class="nav-sub-link">Customer-Supplier</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['Price.priceList','Price.priceAdd'])){{'active'}} @endif ">
              <a href="{{url('fgs/price-master/list')}}"  class="nav-sub-link">Price Master</a>
            </li>

            <li class="nav-sub-item @if(in_array($Action,['ProductMaster.productAdd','ProductMaster.productList'])){{'active'}} @endif ">
            <a href="{{url('fgs/product-master/list')}}"  class="nav-sub-link">FGS Item Master</a>
            </li>
            {{--
            <li class="nav-sub-item @if(in_array($Action,['StockManagement.productionStockAdd','StockManagement.productionStockList'])){{'active'}} @endif ">
              <a href="{{url('fgs/production-stock/list')}}"  class="nav-sub-link">Production Stock</a>
            </li>
            --}}
            <li class="nav-sub-item @if(in_array($Action,['StockManagement.location1Stock','StockManagement.location2Stock','StockManagement.MAAStock','StockManagement.quarantineStock','StockManagement.allLocations',
            'StockManagement.location3Stock','StockManagement.locationSNN','StockManagement.locationAHPL'])){{'active'}} @endif ">
              <a href="{{url('fgs/stock-management/all-locations')}}"  class="nav-sub-link">Stock Management</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['BackorderReport.get_data','GRS.pendingGRS','PI.pendingPI','OEF.pendingOEF'])){{'active'}} @endif ">
              <a href="{{url('fgs/back-ordr-report')}}"  class="nav-sub-link">Back order Report</a>
            </li>
            <li class="nav-sub-item  @if(in_array($Action,['Fgsreport.get_data'])){{'active'}} @endif">
              <a href="{{url('fgs/fgs-report')}}"  class="nav-sub-link">FGS Report</a>
            </li>
            <li class="nav-sub-item  @if(in_array($Action,['StockManagement.batchTraceReport'])){{'active'}} @endif">
              <a href="{{url('fgs/batch-trace-report')}}"  class="nav-sub-link">Batch Trace Report</a>
            </li>
            <li class="nav-sub-item  @if(in_array($Action,['DNI.netBillingReport'])){{'active'}} @endif">
              <a href="{{url('fgs/net-billing-report')}}"  class="nav-sub-link">Net Billing Report</a>
            </li>
            <li class="nav-item @if(in_array($Action,['MRN.MRNList','MRN.MRNList','MRN.MRNAdd','MRN.MRNitemlist','MTQ.MTQitemlist','MTQ.MTQList','MTQ.MTQitemAdd','GRS.GRSitemAdd',
              'MRN.MRNitemAdd','MIN.MINList','MIN.MINAdd','MIN.MINitemlist','MIN.MINitemAdd','GRS.GRSList','GRS.GRSAdd','GRS.GRSitemlist','CMIN.CMINList','CMIN.CMINAdd','CMIN.CMINItemList',
              'CMIN.CMINitemAdd','MTQ.MTQAdd','CGRS.CGRSList','CGRS.CGRSAdd','CGRS.CGRSItemList','MIS.MISList','MIS.MISAdd','MIS.MISitemlist','CMTQ.CMTQitemlist','CMTQ.CMTQList','CMTQ.CMTQAdd','CMTQ.CMTQitemAdd'])){{'active show'}} @endif">
              <a href="#" class="nav-link with-sub">Inventory</a>
              <ul class="nav-sub">
                <li class="nav-sub-item @if(in_array($Action,['MRN.MRNList','MRN.MRNitemlist','MRN.MRNAdd','MRN.MRNitemAdd'])){{'active'}} @endif ">
                  <a href="{{url('fgs/MRN-list')}}"  class="nav-sub-link">MRN</a>
                </li>
                <li class="nav-sub-item @if(in_array($Action,['MIN.MINList','MIN.MINAdd','MIN.MINitemlist','MIN.MINitemAdd'])){{'active'}} @endif ">
                  <a href="{{url('fgs/MIN-list')}}"  class="nav-sub-link">MIN</a>
                </li>
                <li class="nav-sub-item @if(in_array($Action,['CMIN.CMINList','CMIN.CMINAdd','CMIN.CMINItemList','CMIN.CMINitemAdd'])){{'active'}} @endif ">
                  <a href="{{url('fgs/CMIN/CMIN-list')}}"  class="nav-sub-link">CMIN</a>
                </li>
                <li class="nav-sub-item @if(in_array($Action,['GRS.GRSList','GRS.GRSAdd','GRS.GRSitemlist','GRS.GRSitemAdd'])){{'active'}} @endif ">
                  <a href="{{url('fgs/GRS-list')}}"  class="nav-sub-link">GRS</a>
                </li>
                <!-- <li class="nav-sub-item @if(in_array($Action,['GRS.pendingGRS'])){{'active'}} @endif ">
                  <a href="{{url('fgs/GRS/pending-report')}}"  class="nav-sub-link">GRS - Back Order Report</a>
                </li> -->
                 <li class="nav-sub-item @if(in_array($Action,['CGRS.CGRSList','CGRS.CGRSAdd','CGRS.CGRSItemList'])){{'active'}} @endif ">
                  <a href="{{url('fgs/CGRS/CGRS-list')}}"  class="nav-sub-link">CGRS</a>
                </li>
                <li class="nav-sub-item @if(in_array($Action,['MTQ.MTQitemlist','MTQ.MTQList','MTQ.MTQAdd','MTQ.MTQitemAdd'])){{'active'}} @endif ">
                  <a href="{{url('fgs/MTQ-list')}}"  class="nav-sub-link">MTQ</a>
                </li>
                <li class="nav-sub-item @if(in_array($Action,['CMTQ.CMTQitemlist','CMTQ.CMTQList','CMTQ.CMTQAdd','CMTQ.CMTQitemAdd'])){{'active'}} @endif ">
                  <a href="{{url('fgs/CMTQ-list')}}"  class="nav-sub-link">CMTQ</a>
                </li>
                <li class="nav-sub-item @if(in_array($Action,['MIS.MISList','MIS.MISAdd','MIS.MISitemlist'])){{'active'}} @endif ">
                  <a href="{{url('fgs/MIS-list')}}"  class="nav-sub-link">MIS</a>
                </li>
              </ul>
            </li>
            <li class="nav-item @if(in_array($Action,['OEF.OEFList','OEF.OEFAdd','OEF.OEFitemlist','OEF.OEFitemAdd','COEF.COEFList','COEF.COEFAdd','COEF.COEFitemlist','COEF.COEFitemAdd','PI.PIAdd','PI.PIList','DNI.DNIList','DNI.DNIAdd','DNI.DNIitemlist','PI.mergedPIList',
            'EXI.EXIList','EXI.EXIAdd','EXI.EXIitemlist','PI.PIList','PI.PIAdd','PI.PIitemlist','COEF.COEFList','COEF.COEFAdd','COEF.COEFItemList','COEF.COEFitemAdd','CPI.CPIList','CPI.CPIAdd','CPI.CPIItemList','SRN.SRNAdd','SRN.SRNlist','SRN.SRNitemlist'])){{'active show'}} @endif">
              <a href="#" class="nav-link with-sub">Sales</a>
              <ul class="nav-sub">
                <li class="nav-sub-item @if(in_array($Action,['OEF.OEFList','OEF.OEFAdd','OEF.OEFitemlist','OEF.OEFitemAdd'])){{'active'}} @endif ">
                  <a href="{{url('fgs/OEF-list')}}"  class="nav-sub-link">OEF</a>
                </li>
                <!-- <li class="nav-sub-item @if(in_array($Action,['OEF.pendingOEF'])){{'active'}} @endif ">
                  <a href="{{url('fgs/OEF/pending-report')}}"  class="nav-sub-link">OEF - Back Order Report</a>
                </li> -->
                 <li class="nav-sub-item @if(in_array($Action,['COEF.COEFList','COEF.COEFAdd','COEF.COEFItemList','COEF.COEFitemAdd'])){{'active'}} @endif ">
                  <a href="{{url('fgs/COEF/COEF-list')}}"  class="nav-sub-link">COEF</a>
                </li> 
                <li class="nav-sub-item @if(in_array($Action,['PI.PIList','PI.PIAdd','PI.PIitemlist'])){{'active'}} @endif ">
                  <a href="{{url('fgs/PI-list')}}"  class="nav-sub-link">PI</a>
                </li>
                <li class="nav-sub-item @if(in_array($Action,['PI.mergedPIList'])){{'active'}} @endif ">
                  <a href="{{url('fgs/merged-PI-list')}}"  class="nav-sub-link">Merged PI List</a>
                </li>
                 <li class="nav-sub-item @if(in_array($Action,['CPI.CPIList','CPI.CPIAdd','CPI.CPIItemList'])){{'active'}} @endif ">
                  <a href="{{url('fgs/CPI/CPI-list')}}"  class="nav-sub-link">CPI</a>
                </li>
                <li class="nav-sub-item @if(in_array($Action,['DNI.DNIList','DNI.DNIAdd','DNI.DNIitemlist'])){{'active'}} @endif ">
                  <a href="{{url('fgs/DNI-list')}}"  class="nav-sub-link">DNI</a>
                </li>
                <li class="nav-sub-item @if(in_array($Action,['EXI.EXIList','EXI.EXIAdd','EXI.EXIitemlist'])){{'active'}} @endif ">
                  <a href="{{url('fgs/EXI-list')}}"  class="nav-sub-link">EXI</a>
                </li>
                <li class="nav-sub-item @if(in_array($Action,['SRN.SRNAdd','SRN.SRNlist','SRN.SRNitemlist'])){{'active'}} @endif ">
                  <a href="{{url('fgs/SRN-list')}}"  class="nav-sub-link">SRN</a>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        
        <li class="nav-item @if(in_array($Action,['BatchCard.getBatchcardUpload', 'BatchCard.BatchcardAdd','BatchCard.BatchcardList','BatchCard.requestList'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-tabs-outline"></i>Batch Card</a>
          <ul class="nav-sub">
            <li class="nav-sub-item @if(in_array($Action,['BatchCard.BatchcardList'])){{'active'}} @endif ">
            <a href="{{url('batchcard/batchcard-list')}}"  class="nav-sub-link">Batch Card List</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['BatchCard.getBatchcardUpload'])){{'active'}} @endif ">
            <a href="{{url('batchcard/batchcard-upload')}}"  class="nav-sub-link">Batch Card Upload</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['BatchCard.BatchcardAdd'])){{'active'}} @endif ">
            <a href="{{url('batchcard/batchcard-add')}}"  class="nav-sub-link">Batch Card Add</a>
            </li>
            <!-- <li class="nav-sub-item @if(in_array($Action,['BatchCard.requestList'])){{'active'}} @endif ">
            <a href="{{url('batchcard/request-list')}}"  class="nav-sub-link">Quantity Updation Requests </a>
            </li> -->
          </ul>
        </li>
        

        <li class="nav-item @if(in_array($Action,['Label.sterilizationProductLabel', 'Label.nonSterileProductLabel' ,'Label.instrumentLabel','Label.patientLabel', 'Label.mrpLabel', 
        'Label.generateInstrumentLabel', 'Label.generateMRPLabel','Label.generateNonSterileProductLabel','Label.generateSterilizationProductLabel','Label.generatePatientLabel',
        'Label.printingReport','Label.adhlMRPLabel','Label.generateADHLMRPLabel','Label.ahplMRPLabel','Label.generateAHPLMRPLabel','Label.snnMRPLabel','Label.generateSNNMRPLabel'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="fas fa-address-card" style="font-size:20px;"></i>Label Card</a>
          <ul class="nav-sub">
            <li class="nav-sub-item @if(in_array($Action,['Label.instrumentLabel', 'Label.generateInstrumentLabel'])){{'active'}} @endif ">
              <a href="{{url('label/instrument-label')}}"  class="nav-sub-link">Instrument Label</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['Label.mrpLabel', 'Label.generateMRPLabel'])){{'active'}} @endif ">
              <a href="{{url('label/mrp-label')}}"  class="nav-sub-link">MRP Label</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['Label.ahplMRPLabel', 'Label.generateAHPLMRPLabel'])){{'active'}} @endif ">
              <a href="{{url('label/ahpl-mrp-label')}}"  class="nav-sub-link">AHPL MRP Label</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['Label.snnMRPLabel', 'Label.generateADHLMRPLabel'])){{'active'}} @endif ">
              <a href="{{url('label/snn-mrp-label')}}"  class="nav-sub-link">SNN MRP Label</a>
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
        <li class="nav-item @if(in_array($Action,['RowMaterial.materialList','RowMaterial.materialAdd','RowMaterial.materialEdit','RowMaterial.fixedRateList','RowMaterial.getfixedRateUpload',
        'RowMaterial.materialUpload'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="fas fa-swatchbook" style="font-size: 19px"></i>Raw Material</a>
          <ul class="nav-sub">
            <li class="nav-sub-item @if(in_array($Action,['RowMaterial.materialList','RowMaterial.materialAdd','RowMaterial.materialEdit'])){{'active'}} @endif ">
            <a href="{{url('row-material/list')}}"  class="nav-sub-link">Raw Materials</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['RowMaterial.materialUpload'])){{'active'}} @endif ">
            <a href="{{url('row-material/upload')}}"  class="nav-sub-link">Raw Material Upload</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['RowMaterial.fixedRateList'])){{'active'}} @endif ">
            <a href="{{url('row-material/fixed-rate')}}"  class="nav-sub-link">Fixed Rate Raw Material</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['RowMaterial.getfixedRateUpload'])){{'active'}} @endif ">
            <a href="{{url('row-material/fixed-rate/upload')}}"  class="nav-sub-link">Fixed Rate Material Upload</a>
            </li>
          </ul>
        </li>

        <li class="nav-item @if(in_array($Action,['Product.productList','Product.productFileUpload','Product.addInputMaterial','Product.getProductUpload','Product.locationList'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="fab fa-product-hunt" style="font-size: 19px"></i>Product</a>
          <ul class="nav-sub">
            <li class="nav-sub-item @if(in_array($Action,['Product.productList','Product.addInputMaterial'])){{'active'}} @endif ">
            <a href="{{url('product/list')}}"  class="nav-sub-link">Products</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['Product.getProductUpload'])){{'active'}} @endif ">
            <a href="{{url('product/file/upload')}}"  class="nav-sub-link">Product Upload</a>
            </li> 
            <li class="nav-sub-item @if(in_array($Action,['Product.locationList'])){{'active'}} @endif ">
            <a href="{{url('product/location')}}"  class="nav-sub-link">Product Location</a>
            </li> 
          </ul>
        </li>

        <li class="nav-item @if(in_array($Action,['Employee.employeeList','Employee.employeeAdd','Employee.employeeEdit'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="fas fa-user-alt" style="font-size: 19px"></i>Employee</a>
          <ul class="nav-sub">
            <li class="nav-sub-item @if(in_array($Action,['Employee.employeeList','Employee.employeeAdd','Employee.employeeEdit'])){{'active'}} @endif ">
            <a href="{{url('employee/list')}}"  class="nav-sub-link">Employee</a>
            </li>
            
          </ul>
        </li>
        <li class="nav-item @if(in_array($Action,['RolePermission.roleList','RolePermission.moduleList','RolePermission.permissionList','RolePermission.rolePermission'])){{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="fa fa-cog" style="font-size: 19px"></i>Settings</a>
          <ul class="nav-sub">
          <li class="nav-sub-item @if(in_array($Action,['RolePermission.roleList','RolePermission.rolePermission'])){{'active'}} @endif ">
            <a href="{{url('inventory/inventory-gst')}}"  class="nav-sub-link">GST</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['RolePermission.roleList','RolePermission.rolePermission'])){{'active'}} @endif ">
            <a href="{{url('settings/role')}}"  class="nav-sub-link">Role</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['RolePermission.moduleList'])){{'active'}} @endif ">
            <a href="{{url('settings/module')}}"  class="nav-sub-link">Module</a>
            </li>
            <li class="nav-sub-item @if(in_array($Action,['RolePermission.permissionList'])){{'active'}} @endif ">
            <a href="{{url('settings/permission')}}"  class="nav-sub-link">Permissions</a>
            </li>
          </ul>
        </li>


      </ul><!-- nav -->
    </div><!-- az-sidebar-body -->
  </div><!-- az-sidebar -->

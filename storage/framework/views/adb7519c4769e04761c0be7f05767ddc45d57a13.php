<?php
$routeArray = app('request')->route()->getAction();
$controllerAction = class_basename($routeArray['controller']);
list($controller, $action) = explode('@', $controllerAction);
$Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
//echo $Action;
?>
<div class="az-sidebar">
  <div class="az-sidebar-header">
    <a href="<?php echo e(url('')); ?>" class="az-logo" style="
      text-transform: uppercase;color: #1d263d;"><img class="wd-45 ht-40 mg-l-10 bd bd-gray-500 rounded-10" src="<?= url(''); ?>/img/alder_logo.png">&nbsp;ADLER</a>
  </div><!-- az-sidebar-header -->

  <div class="az-sidebar-loggedin">
    <div class="az-img-user online">
      <?php if(config('user')['profile_img']): ?>
      <img src="<?= url(''); ?>/Employee_Image/<?php echo e(config('user')['profile_img']); ?>" alt="">
      <?php else: ?>
      <img src="<?= url(''); ?>/img/profile.png" alt="">
      <?php endif; ?>
    </div>
    <div class="media-body">
      <h6><?php echo e(config('user')['f_name']); ?> <?php echo e(config('user')['l_name']); ?></h6>
      <span>Premium Member</span>
    </div><!-- media-body -->
  </div><!-- az-sidebar-loggedin -->
  <div class="az-sidebar-body">
    <ul class="nav">
      <li class="nav-label">Main Menu</li>
      <?php if(in_array('purchase_details.view',config('permission'))): ?>
      <li class="nav-item <?php if(in_array($Action,['Inventory.get_purchase_reqisition','Inventory.add_purchase_reqisition','Inventory.edit_purchase_reqisition',
        'Inventory.get_purchase_reqisition_item','Inventory.edit_purchase_reqisition_item',
        'Inventory.add_purchase_reqisition_item','Approval.getList','Quotation.getQuotation', 
        'SupplierQuotation.viewSupplierQuotationItems', 'SupplierQuotation.getSupplierQuotationEditItem',
        'SupplierQuotation.comparisonOfQuotation','SupplierQuotation.getSupplierQuotation','Purchase.getFinalPurchase',
        'Purchase.addFinalPurchase','Purchase.Edit_PO_item','Quotation.getQuotation','Supplier.list_supplier','Purchase.purchaseOderCancellation','Purchase.pendingPurchaseRealisation',
        'Supplier.add_supplier','Purchase.viewFinalPurchase','Purchase.add1FinalPurchase','Purchase.editFinalPurchase','Purchase.purchaseOderApproval','Quotation.directPurchase','Purchase.viewFinalPurchaseExcess','Purchase.getExcessQty'])): ?> <?php echo e('active show'); ?> <?php endif; ?> ">
        <a href="#" class="nav-link with-sub"><i class="fas fa-shopping-cart" style="font-size: 17px;"></i>Purchase Details</a>
        <ul class="nav-sub">
          <?php if(in_array('purchase_details.requisition_list',config('permission')) || in_array('purchase_details.requisition_add',config('permission')) || in_array('purchase_details.requisition_edit',config('permission')) || in_array('purchase_details.requisition_delete',config('permission')) ||
          in_array('purchase_details.requisition_item_list',config('permission')) || in_array('purchase_details.requisition_item_add',config('permission')) || in_array('purchase_details.requisition_item_edit',config('permission')) ): ?>
          <li class="nav-sub-item <?php if(in_array($Action,['Inventory.get_purchase_reqisition','Inventory.add_purchase_reqisition','Inventory.edit_purchase_reqisition',
            'Inventory.get_purchase_reqisition_item','Inventory.edit_purchase_reqisition_item',
            'Inventory.add_purchase_reqisition_item'])): ?><?php echo e('active'); ?> <?php endif; ?>"><a href="<?php echo e(url('inventory/get-purchase-reqisition')); ?>" class="nav-sub-link">Requisition</a></li>
          <?php endif; ?>
          <?php if(in_array('purchase_details.requisition_approval',config('permission'))): ?>
          <li class="nav-sub-item <?php if(in_array($Action,['Approval.getList'])): ?><?php echo e('active'); ?> <?php endif; ?>"><a href="<?php echo e(url('inventory/purchase-reqisition/approval')); ?>" class="nav-sub-link">Requisition Approval</a></li>
          <?php endif; ?>
          <?php if(in_array('purchase_details.request_for_quotation',config('permission'))): ?>
          <li class="nav-sub-item  <?php if(in_array($Action,['Quotation.getQuotation'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/quotation')); ?>" class="nav-sub-link">Request for Quotation</a></li>
          <?php endif; ?>
          <?php if(in_array('purchase_details.fixed_rate',config('permission'))): ?>
          <li class="nav-sub-item  <?php if(in_array($Action,['Quotation.directPurchase'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/direct/purchase')); ?>" class="nav-sub-link">Fixed Rate-Purchase/Work Order</a></li>
          <?php endif; ?>
          <?php if(in_array('supplier_qotation.list',config('permission')) || in_array('supplier_qotation.comparison',config('permission')) || in_array('supplier_qotation.item_edit',config('permission'))): ?>
          <li class="nav-sub-item <?php if(in_array($Action,['SupplierQuotation.getSupplierQuotation', 'SupplierQuotation.viewSupplierQuotationItems', 'SupplierQuotation.getSupplierQuotationEditItem','SupplierQuotation.comparisonOfQuotation'])): ?><?php echo e('active'); ?> <?php endif; ?>"><a href="<?php echo e(url('inventory/supplier-quotation')); ?>" class="nav-sub-link">Supplier Quotation</a></li>
          <?php endif; ?>
          <?php if(in_array('order.list',config('permission')) || in_array('order.creation',config('permission')) || in_array('order.edit',config('permission')) || in_array('order.delete',config('permission')) || in_array('order.change_status',config('permission')) || in_array('order.view',config('permission'))): ?>
          <li class="nav-sub-item  <?php if(in_array($Action,['Purchase.getFinalPurchase','Purchase.addFinalPurchase','Purchase.Edit_PO_item','Purchase.viewFinalPurchase','Purchase.add1FinalPurchase','Purchase.editFinalPurchase'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/final-purchase')); ?>" class="nav-sub-link">Purchase/Work Order</a></li>
          <?php endif; ?>
          <?php if(in_array('order.approval',config('permission'))): ?>
          <li class="nav-sub-item  <?php if(in_array($Action,['Purchase.purchaseOderApproval','Purchase.viewFinalPurchase'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/final-purchase/approval')); ?>" class="nav-sub-link">Order Approval</a></li>
          <?php endif; ?>
          <?php if(in_array('order.cancellation',config('permission')) || in_array('order.partial_cancellation',config('permission'))): ?>
          <li class="nav-sub-item  <?php if(in_array($Action,['Purchase.purchaseOderCancellation','Purchase.viewFinalPurchase'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/final-purchase/cancellation')); ?>" class="nav-sub-link">Order Cancellation</a></li>
          <?php endif; ?>
          <?php if(in_array('order.excess_order_qty',config('permission'))): ?>
          <li class="nav-sub-item  <?php if(in_array($Action,['Purchase.viewFinalPurchaseExcess','Purchase.getExcessQty'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/final-purchase/excess-quantity')); ?>" class="nav-sub-link">Excess Order Quatity </a></li>
          <?php endif; ?>
          <?php if(in_array('supplier.list',config('permission')) || in_array('supplier.add',config('permission')) || in_array('supplier.edit',config('permission')) || in_array('supplier.delete',config('permission'))): ?>
          <li class="nav-sub-item  <?php if(in_array($Action,['Supplier.list_supplier','Supplier.add_supplier'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/suppliers-list')); ?>" class="nav-sub-link">Supplier Master</a></li>
          <?php endif; ?>
          <li class="nav-sub-item  <?php if(in_array($Action,['Purchase.pendingPurchaseRealisation'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/pending-purchase-realisation')); ?>" class="nav-sub-link">R02-Pending Purchase Realisation</a></li>
        </ul>
      </li><!-- nav-item -->
<?php endif; ?>
      <?php if(in_array('inventory.view',config('permission'))): ?>
      <li class="nav-item <?php if(in_array($Action,['Purchase.supplierInvoice','Purchase.supplierInvoiceAdd','Purchase.supplierInvoiceItemEdit','LotAllocation.addLotAllocation','LotAllocation.lotAllocation','MIQ.MIQlist','MIQ.MIQAdd','MIQ.MIQAddItemInfo','MAC.MACAddItemInfo','MAC.MAClist','MAC.MACAdd','MRR.receiptReport',
        'MRD.RMRNAddItemInfo','MRR.addMRR','Stock.viewItems','Inventoryreport.get_data','Stock.stockReport','Stock.transactionSlip','Stock.transactionSlipAdd','Purchase.SplitInvoiceItem','Stock.PackingSIP','Stock.underProcessSIP',
        'MRD.MRDlist','MRD.MRDAdd','MRD.MRDAddItemInfo','MRD.RMRNlist','MRD.RMRNAdd','Stock.StockToProduction','Stock.StockToProductionAdd','MAC.WOAAdd','MRD.WORAdd','Stock.resetBatchInputMaterial',
        'Stock.StockFromProduction','Stock.StockFromProductionAdd','Stock.StockTransfer','Stock.StockTransferAdd','Stock.DirectSIP','Stock.IndirectSIP','Stock.SIPview','MIQ.LiveQuarantineReport','FGSTransfer.fgsTransfer','FGSTransfer.fgsTransferList','FGSTransfer.fgsTransferAdd','Stock.Add_stock_location'])): ?><?php echo e('active show'); ?> <?php endif; ?> ">
        <a href="#" class="nav-link with-sub">
          <i class="fas fa-boxes" style="font-size:19px"></i>Inventory</a>

        <ul class="nav-sub">
          <li class="nav-sub-item  <?php if(in_array($Action,['Purchase.supplierInvoice','Purchase.supplierInvoiceAdd','Purchase.supplierInvoiceItemEdit','Purchase.SplitInvoiceItem'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/supplier-invoice')); ?>" class="nav-sub-link">Supplier Invoice</a></li>
          <li class="nav-sub-item  <?php if(in_array($Action,['LotAllocation.addLotAllocation','LotAllocation.lotAllocation'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/lot-allocation-list')); ?>" class="nav-sub-link">LOT Number Allocation</a></li>
          <li class="nav-sub-item  <?php if(in_array($Action,['MIQ.MIQlist','MIQ.MIQAdd','MIQ.MIQAddItemInfo','MIQ.MIQAddItemInfo'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/MIQ')); ?>" class="nav-sub-link">MIQ</a></li>
          <li class="nav-sub-item  <?php if(in_array($Action,['MIQ.LiveQuarantineReport'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/MIQ/QuarantineReport')); ?>" class="nav-sub-link"> Quarantine Report</a></li>
          <li class="nav-sub-item  <?php if(in_array($Action,['MAC.MAClist','MAC.MACAdd','MAC.MACAddItemInfo','MAC.WOAAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/MAC')); ?>" class="nav-sub-link">MAC/WOA</a></li>
          <li class="nav-sub-item  <?php if(in_array($Action,['MRD.MRDlist','MRD.MRDAdd','MRD.MRDAddItemInfo','MRD.WORAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/MRD')); ?>" class="nav-sub-link">MRD/WOR</a></li>
          <li class="nav-sub-item  <?php if(in_array($Action,['MRD.RMRNlist','MRD.RMRNAdd','MRD.RMRNAddItemInfo'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/RMRN')); ?>" class="nav-sub-link">RMRN</a></li>
          <li class="nav-sub-item  <?php if(in_array($Action,['MRR.addMRR','MRR.receiptReport'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/receipt-report')); ?>" class="nav-sub-link">MRR/SRR</a></li>
          <!-- <li class="nav-sub-item  <?php if(in_array($Action,['FGSTransfer.fgsTransfer','FGSTransfer.fgsTransferList','FGSTransfer.fgsTransferAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/fgs-transfer-list')); ?>" class="nav-sub-link">FGS Transffer</a></li> -->
          <li class="nav-sub-item  <?php if(in_array($Action,['Stock.stockReport','Stock.transactionSlipAdd'])): ?><?php echo e('active'); ?> <?php endif; ?>"><a href="<?php echo e(url('inventory/stock-report')); ?>" class="nav-sub-link">Stock Report</a></li>
          <li class="nav-sub-item  <?php if(in_array($Action,['Stock.transactionSlip'])): ?><?php echo e('active'); ?> <?php endif; ?>"><a href="<?php echo e(url('inventory/transaction-slip')); ?>" class="nav-sub-link">Transaction Slip</a></li>
          <li class="nav-sub-item  <?php if(in_array($Action,['Stock.resetBatchInputMaterial'])): ?><?php echo e('active'); ?> <?php endif; ?>"><a href="<?php echo e(url('inventory/reset-batchcard-material')); ?>" class="nav-sub-link">Reset Batchcard Material </a></li>
          <li class="nav-sub-item  <?php if(in_array($Action,['Stock.StockToProduction','Stock.StockToProductionAdd','Stock.DirectSIP','Stock.IndirectSIP','Stock.SIPview', 'Stock.PackingSIP','Stock.underProcessSIP'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/Stock/ToProduction')); ?>" class="nav-sub-link">Stock Issue To Production</a></li>
          <li class="nav-sub-item  <?php if(in_array($Action,['Stock.StockFromProduction','Stock.StockFromProductionAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/Stock/FromProduction')); ?>" class="nav-sub-link">Stock Return From Production</a></li>
          <li class="nav-sub-item  <?php if(in_array($Action,['Stock.StockTransfer','Stock.StockTransferAdd','Stock.viewItems'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/Stock/transfer')); ?>" class="nav-sub-link">Stock Transfer Order</a></li>
          <li class="nav-sub-item <?php if(in_array($Action,['Inventoryreport.get_data'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/inventory-trans-report')); ?>" class="nav-sub-link">Inventory Transaction Report</a></li>

        </ul>
      </li>
      <?php endif; ?>
      <?php if(in_array('finished_goods.view',config('permission'))): ?>
      <li class="nav-item <?php if(in_array($Action,['CustomerSupplier.customerSupplierList','CustomerSupplier.addCustomerSupplier','Price.priceList','Price.priceAdd','MRN.MRNList','MRN.MRNList','MRN.MRNAdd','MRN.MRN_edit','MRN.MRNitemlist','OEF.pendingOEF',
        'MRN.MRNitemAdd','MIN.MINList','MIN.MINAdd','MIN.MINitemlist','MIN.MINitemAdd','CMIN.CMINList','CMIN.CMINAdd','CMIN.CMINitemlist','CMIN.CMINitemAdd','OEF.OEFList','OEF.OEFAdd','OEF.OEFitemlist','OEF.OEFitemAdd','COEF.COEFList','COEF.COEFAdd','COEF.COEFitemlist','COEF.COEFitemAdd','GRS.GRSList','GRS.GRSAdd','GRS.GRSitemlist',
        'PI.PIAdd','PI.PIList','PI.PIitemlist','CPI.CPIList','CPI.CPIAdd','CPI.CPIItemList','DNI.DNIList','DNI.DNIAdd','DNI.DNIitemlist','EXI.EXIList','EXI.EXIAdd','EXI.EXIitemlist','StockManagement.location1Stock','StockManagement.location2Stock','StockManagement.MAAStock','StockManagement.quarantineStock','MTQ.MTQAdd','StockManagement.productionStockAdd',
        'StockManagement.productionStockList','CMIN.CMINList','CMIN.CMINAdd','CMIN.CMINItemList','CMIN.CMINitemAdd','PI.pendingPI','GRS.pendingGRS','PI.mergedPIList','PI.mergeMutiplePI','BackorderReport.get_data','GRS.GRSitemAdd',
        'COEF.COEFList','COEF.COEFAdd','COEF.COEFItemList','COEF.COEFitemAdd','CGRS.CGRSList','CGRS.CGRSAdd','CGRS.CGRSItemList','ProductMaster.productList','MTQ.MTQitemlist','MTQ.MTQList','MTQ.MTQitemAdd','MIS.MISList','MIS.MISAdd','MIS.MISitemlist','SRN.SRNAdd','SRN.SRNlist','SRN.SRNitemlist' ,
        'CMTQ.CMTQitemlist','CMTQ.CMTQList','CMTQ.CMTQAdd','CMTQ.CMTQitemAdd','StockManagement.locationSNNTrade','GRS.GRSItemEdit','GRS.GRSEdit','PI.PIEdit','OEF.edit_oef_item','DCreport.DCReport','DCreport.CDCReport','SRN.SRNManualAdd',
        'StockManagement.allLocations','StockManagement.location3Stock','StockManagement.locationSNN','StockManagement.locationAHPL','PI.pendingPI','Fgsreport.get_sales_data','Fgsreport.get_inv_data','StockManagement.batchTraceReport','DNI.netBillingReport','DeliveryNote.ChallanList',
        'DeliveryNote.ChallanAdd','DeliveryNote.ChallanItemAdd','DeliveryNote.Challanitemlist','MIN.MINitemedit','MRN.edit_mrn','MRN.edit_mrn_item','CDC.CDCList','CDC.CDCAdd','CDC.CDCItemList','CDC.CDCpdf','SAI.SAIlist','SAI.SAIAdd','SAI.SAIItemList','SAD.SADlist','SAD.SADAdd','SAD.SADlist','SAD.SADItemList',
        'Dcbackorder.GetAllDC','Dcbackorder.PendingDC','Dcbackorder.PendingCDC','Price.priceUpload','ProductMaster.product_upload','NetBkBillingr.NetBillingReportAll','DeliveryNote.dc_transfer_stock','DeliveryNote.dc_transfer_stock_consignment','DeliveryNote.dc_transfer_stock_loaner','DeliveryNote.dc_transfer_stock_replacement','DeliveryNote.dc_transfer_stock_demo','DeliveryNote.dc_transfer_stock_samples',
        'SatelliteStock.locationList','SRNcontroller.srn_transaction','EXI.exi_transaction','DNI.dni_transaction','CPI.cpi_transaction',
        'OEF.oef_transaction','COEF.coef_transaction','Fgsreport.get_inv_data','MIScontroller.mis_transaction','CMTQ.cmtq_transaction','MTQ.mtq_transaction','MIN.cmin_transaction','MIN.min_transaction',
      'CGRS.cgrs_transaction','GRS.grs_transaction','MRN.mrn_transaction'])): ?><?php echo e('active show'); ?> <?php endif; ?> ">
        <a href="#" class="nav-link with-sub"><i class="fas fa-address-card" style="font-size:20px;"></i>Finished Goods</a>
        <ul class="nav-sub">
          <li class="nav-sub-item <?php if(in_array($Action,['CustomerSupplier.customerSupplierList','CustomerSupplier.addCustomerSupplier'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/customer-supplier')); ?>" class="nav-sub-link">FGS Customer-Supplier</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['Price.priceList','Price.priceAdd','Price.priceUpload'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/price-master/list')); ?>" class="nav-sub-link">FGS Price Master</a>
          </li>

          <li class="nav-sub-item <?php if(in_array($Action,['ProductMaster.productAdd','ProductMaster.productList','ProductMaster.product_upload'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/product-master/list')); ?>" class="nav-sub-link">FGS Item Master</a>
          </li>
          
      <li class="nav-sub-item <?php if(in_array($Action,['StockManagement.location1Stock','StockManagement.location2Stock','StockManagement.MAAStock','StockManagement.quarantineStock','StockManagement.allLocations',
            'StockManagement.location3Stock','StockManagement.locationSNN','StockManagement.locationAHPL','StockManagement.locationSNNTrade'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
        <a href="<?php echo e(url('fgs/stock-management/all-locations')); ?>" class="nav-sub-link">FGS On shelf Stock</a>
      </li>
      <li class="nav-sub-item <?php if(in_array($Action,['BackorderReport.get_data','GRS.pendingGRS','PI.pendingPI','OEF.pendingOEF'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
        <a href="<?php echo e(url('fgs/back-ordr-report')); ?>" class="nav-sub-link">FGS Back order Report</a>
      </li>
      
      <li class="nav-sub-item  <?php if(in_array($Action,['Fgsreport.get_sales_data','SRNcontroller.srn_transaction','EXI.exi_transaction','DNI.dni_transaction','CPI.cpi_transaction',
      'OEF.oef_transaction','COEF.coef_transaction'])): ?><?php echo e('active'); ?> <?php endif; ?>">
        <a href="<?php echo e(url('fgs/fgs-sales-report')); ?>" class="nav-sub-link">FGS Sales Transaction Report</a>
      </li>
      <li class="nav-sub-item  <?php if(in_array($Action,['Fgsreport.get_inv_data','MIScontroller.mis_transaction','CMTQ.cmtq_transaction','MTQ.mtq_transaction','MIN.cmin_transaction','MIN.min_transaction',
      'CGRS.cgrs_transaction','GRS.grs_transaction','MRN.mrn_transaction'])): ?><?php echo e('active'); ?> <?php endif; ?>">
        <a href="<?php echo e(url('fgs/fgs-inv-report')); ?>" class="nav-sub-link">FGS Inv Transaction Report</a>
      </li>
      <li class="nav-sub-item  <?php if(in_array($Action,['StockManagement.batchTraceReport'])): ?><?php echo e('active'); ?> <?php endif; ?>">
        <a href="<?php echo e(url('fgs/batch-trace-report')); ?>" class="nav-sub-link">FGS Batch Trace Report</a>
      </li>
      <li class="nav-sub-item  <?php if(in_array($Action,['DNI.netBillingReport'])): ?><?php echo e('active'); ?> <?php endif; ?>">
        <a href="<?php echo e(url('fgs/net-billing-report')); ?>" class="nav-sub-link">FGS Net Billing Report</a>
      </li>
      <li class="nav-sub-item <?php if(in_array($Action,['DeliveryNote.dc_transfer_stock','DeliveryNote.dc_transfer_stock_consignment','DeliveryNote.dc_transfer_stock_loaner','DeliveryNote.dc_transfer_stock_replacement','DeliveryNote.dc_transfer_stock_demo','DeliveryNote.dc_transfer_stock_samples'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/Delivery_challan/Challan-stock-all-location')); ?>" class="nav-sub-link">FGS OFF Shelf Stock</a>
      </li>
      <li class="nav-sub-item  <?php if(in_array($Action,['DCreport.DCReport','DCreport.CDCReport'])): ?><?php echo e('active'); ?> <?php endif; ?>">
        <a href="<?php echo e(url('fgs/DC-report')); ?>" class="nav-sub-link">FGS DC-CDC Backorder Report</a>
      </li>
      <li class="nav-sub-item <?php if(in_array($Action,['Dcbackorder.GetAllDC','Dcbackorder.PendingDC','Dcbackorder.PendingCDC'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
        <a href="<?php echo e(url('fgs/Dc-pending-report')); ?>" class="nav-sub-link">DC Back order Report</a>
      </li>
      <li class="nav-sub-item  <?php if(in_array($Action,['NetBkBillingr.NetBillingReportAll'])): ?><?php echo e('active'); ?> <?php endif; ?>">
        <a href="<?php echo e(url('fgs/net-all-billing-report')); ?>" class="nav-sub-link">FGS Net Booking-Billing Report</a>
      </li>
      <li class="nav-item <?php if(in_array($Action,['MRN.MRNList','MRN.MRNList','MRN.MRNAdd','MRN.MRNitemlist','MTQ.MTQitemlist','MTQ.MTQList','MTQ.MTQitemAdd','GRS.GRSitemAdd','GRS.GRSItemEdit','GRS.GRSEdit',
              'MRN.MRNitemAdd','MRN.MRN_edit','MIN.MINList','MIN.MINAdd','MIN.MINitemlist','MIN.MINitemAdd','GRS.GRSList','GRS.GRSAdd','GRS.GRSitemlist','CMIN.CMINList','CMIN.CMINAdd','CMIN.CMINItemList',
              'CMIN.CMINitemAdd','MTQ.MTQAdd','CGRS.CGRSList','CGRS.CGRSAdd','CGRS.CGRSItemList','MIS.MISList','MIS.MISAdd','MIS.MISitemlist','CMTQ.CMTQitemlist','CMTQ.CMTQList','CMTQ.CMTQAdd','CMTQ.CMTQitemAdd','DeliveryNote.ChallanList',
              'DeliveryNote.ChallanAdd','DeliveryNote.ChallanItemAdd','DeliveryNote.Challanitemlist','MIN.MINitemedit','MRN.edit_mrn','MRN.edit_mrn_item','CDC.CDCList','CDC.CDCAdd','CDC.CDCItemList','CDC.CDCpdf',
              'SAI.SAIlist','SAI.SAIAdd','SAI.SAIItemList','SAD.SADlist','SAD.SADAdd','SAD.SADlist','SAD.SADItemList'])): ?><?php echo e('active show'); ?> <?php endif; ?>">
        <a href="#" class="nav-link with-sub">Inventory</a>
        <ul class="nav-sub">
          <li class="nav-sub-item <?php if(in_array($Action,['MRN.MRNList','MRN.MRNitemlist','MRN.MRNAdd','MRN.MRNitemAdd','MRN.MRN_edit','MRN.edit_mrn','MRN.edit_mrn_item'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/MRN-list')); ?>" class="nav-sub-link">MRN</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['MIN.MINList','MIN.MINAdd','MIN.MINitemlist','MIN.MINitemAdd','MIN.MINitemedit'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/MIN-list')); ?>" class="nav-sub-link">MIN</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['CMIN.CMINList','CMIN.CMINAdd','CMIN.CMINItemList','CMIN.CMINitemAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/CMIN/CMIN-list')); ?>" class="nav-sub-link">CMIN</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['GRS.GRSList','GRS.GRSAdd','GRS.GRSitemlist','GRS.GRSitemAdd','GRS.GRSItemEdit','GRS.GRSEdit'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/GRS-list')); ?>" class="nav-sub-link">GRS</a>
          </li>
          <!-- <li class="nav-sub-item <?php if(in_array($Action,['GRS.pendingGRS'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
                  <a href="<?php echo e(url('fgs/GRS/pending-report')); ?>"  class="nav-sub-link">GRS - Back Order Report</a>
                </li> -->
          <li class="nav-sub-item <?php if(in_array($Action,['CGRS.CGRSList','CGRS.CGRSAdd','CGRS.CGRSItemList'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/CGRS/CGRS-list')); ?>" class="nav-sub-link">CGRS</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['MTQ.MTQitemlist','MTQ.MTQList','MTQ.MTQAdd','MTQ.MTQitemAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/MTQ-list')); ?>" class="nav-sub-link">MTQ</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['CMTQ.CMTQitemlist','CMTQ.CMTQList','CMTQ.CMTQAdd','CMTQ.CMTQitemAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/CMTQ-list')); ?>" class="nav-sub-link">CMTQ</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['MIS.MISList','MIS.MISAdd','MIS.MISitemlist'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/MIS-list')); ?>" class="nav-sub-link">MIS</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['DeliveryNote.ChallanList','DeliveryNote.ChallanAdd','DeliveryNote.ChallanItemAdd','DeliveryNote.Challanitemlist'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/Delivery_challan/Challan-list')); ?>" class="nav-sub-link">Delivery Note</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['CDC.CDCList','CDC.CDCAdd','CDC.CDCItemList','CDC.CDCpdf'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/CDC/CDC-list')); ?>" class="nav-sub-link">CDC</a>
          </li>
          <li class="nav-sub-item  <?php if(in_array($Action,['SAI.SAIlist','SAI.SAIAdd','SAI.SAIItemList'])): ?><?php echo e('active'); ?> <?php endif; ?>">
            <a href="<?php echo e(url('fgs/SAI-list')); ?>" class="nav-sub-link">SAI</a>
          </li>
          <li class="nav-sub-item  <?php if(in_array($Action,['SAD.SADlist','SAD.SADAdd','SAD.SADlist','SAD.SADItemList'])): ?><?php echo e('active'); ?> <?php endif; ?>">
            <a href="<?php echo e(url('fgs/SAD-list')); ?>" class="nav-sub-link">SAD</a>
          </li>
        </ul>
      </li>
      <li class="nav-item <?php if(in_array($Action,['OEF.OEFList','OEF.OEFAdd','OEF.OEFitemlist','OEF.OEFitemAdd','COEF.COEFList','COEF.COEFAdd','COEF.COEFitemlist','COEF.COEFitemAdd','PI.PIAdd','PI.PIList','DNI.DNIList','DNI.DNIAdd','DNI.DNIitemlist','PI.mergedPIList','PI.mergeMutiplePI','PI.PIEdit',
            'EXI.EXIList','EXI.EXIAdd','EXI.EXIitemlist','PI.PIList','PI.PIAdd','PI.PIitemlist','COEF.COEFList','COEF.COEFAdd','COEF.COEFItemList','COEF.COEFitemAdd','CPI.CPIList','CPI.CPIAdd','CPI.CPIItemList','SRN.SRNAdd','SRN.SRNlist','SRN.SRNitemlist','OEF.edit_oef_item','SRN.SRNManualAdd'])): ?><?php echo e('active show'); ?> <?php endif; ?>">
        <a href="#" class="nav-link with-sub">Sales</a>
        <ul class="nav-sub">
          <li class="nav-sub-item <?php if(in_array($Action,['OEF.OEFList','OEF.OEFAdd','OEF.OEFitemlist','OEF.OEFitemAdd','OEF.edit_oef_item'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/OEF-list')); ?>" class="nav-sub-link">OEF</a>
          </li>
          <!-- <li class="nav-sub-item <?php if(in_array($Action,['OEF.pendingOEF'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
                  <a href="<?php echo e(url('fgs/OEF/pending-report')); ?>"  class="nav-sub-link">OEF - Back Order Report</a>
                </li> -->
          <li class="nav-sub-item <?php if(in_array($Action,['COEF.COEFList','COEF.COEFAdd','COEF.COEFItemList','COEF.COEFitemAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/COEF/COEF-list')); ?>" class="nav-sub-link">COEF</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['PI.PIList','PI.PIAdd','PI.PIitemlist','PI.PIEdit'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/PI-list')); ?>" class="nav-sub-link">PI</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['PI.mergedPIList','PI.mergeMutiplePI'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/merged-PI-list')); ?>" class="nav-sub-link">Merged PI List</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['CPI.CPIList','CPI.CPIAdd','CPI.CPIItemList'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/CPI/CPI-list')); ?>" class="nav-sub-link">CPI</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['DNI.DNIList','DNI.DNIAdd','DNI.DNIitemlist'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/DNI-list')); ?>" class="nav-sub-link">DNI</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['EXI.EXIList','EXI.EXIAdd','EXI.EXIitemlist'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/EXI-list')); ?>" class="nav-sub-link">EXI</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['SRN.SRNAdd','SRN.SRNlist','SRN.SRNitemlist','SRN.SRNManualAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/SRN-list')); ?>" class="nav-sub-link">SRN</a>
          </li>
        </ul>
      </li>
      <li class="nav-item <?php if(in_array($Action,['SatelliteStock.locationList'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
        <a href="#" class="nav-link with-sub">Satellite Stock</a>
        <ul class="nav-sub">
          <li class="nav-sub-item <?php if(in_array($Action,['SatelliteStock.locationList'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('fgs/satellite-stock/location')); ?>" class="nav-sub-link">Satellite Stock Location</a>
          </li>
          <li class="nav-sub-item <?php if(in_array($Action,['Stock.Add_stock_location'])): ?><?php echo e('active'); ?> <?php endif; ?> "><a href="<?php echo e(url('inventory/stock-location-Add')); ?>" class="nav-sub-link">Stock Location</a></li>

          
        </ul>
      </li>

    </ul>
    </li>
    <?php endif; ?>
    <?php if(in_array('batchcard.view',config('permission'))): ?>
    <li class="nav-item <?php if(in_array($Action,['BatchCard.getBatchcardUpload', 'BatchCard.BatchcardAdd','BatchCard.BatchcardList','BatchCard.requestList','BatchCard.batch_item_search'])): ?><?php echo e('active show'); ?> <?php endif; ?> ">
      <a href="#" class="nav-link with-sub"><i class="typcn typcn-tabs-outline"></i>Batch Card</a>
      <ul class="nav-sub">
        <li class="nav-sub-item <?php if(in_array($Action,['BatchCard.BatchcardList'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('batchcard/batchcard-list')); ?>" class="nav-sub-link">Batch Card List</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['BatchCard.getBatchcardUpload'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('batchcard/batchcard-upload')); ?>" class="nav-sub-link">Batch Card Upload</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['BatchCard.BatchcardAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('batchcard/batchcard-add')); ?>" class="nav-sub-link">Batch Card Add</a>
        </li>
        <!-- <li class="nav-sub-item <?php if(in_array($Action,['BatchCard.requestList'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('batchcard/trackItemcode')); ?>"  class="nav-sub-link">Requistion-Batchcard </a>
        </li> -->
        <li class="nav-sub-item <?php if(in_array($Action,['BatchCard.batch_item_search'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
            <a href="<?php echo e(url('batchcard/batch-item-search')); ?>"  class="nav-sub-link">Batch-Item Report</a>
        </li>
      </ul>
    </li>
<?php endif; ?>
    <?php if(in_array('label_card.view',config('permission'))): ?>
    <li class="nav-item <?php if(in_array($Action,['Label.sterilizationProductLabel', 'Label.nonSterileProductLabel' ,'Label.instrumentLabel','Label.patientLabel', 'Label.mrpLabel', 
        'Label.generateInstrumentLabel', 'Label.generateMRPLabel','Label.generateNonSterileProductLabel','Label.generateSterilizationProductLabel','Label.generatePatientLabel',
        'Label.printingReport','Label.adhlMRPLabel','Label.generateADHLMRPLabel','Label.ahplMRPLabel','Label.generateAHPLMRPLabel','Label.snnMRPLabel','Label.generateSNNMRPLabel',
        'Label.docAdlerMRPLabel','Label.mailingLabel','Label.jayonMRPLabel','Label.nonSterileProductLabel2','Label.generateNonSterileProductLabel2','Label.docSNNMRPLabel',
        'Label.docWiseComparison'])): ?><?php echo e('active show'); ?> <?php endif; ?> ">
      <a href="#" class="nav-link with-sub"><i class="fas fa-address-card" style="font-size:20px;"></i>Label Card</a>
      <ul class="nav-sub">
        <li class="nav-sub-item <?php if(in_array($Action,['Label.instrumentLabel', 'Label.generateInstrumentLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/instrument-label')); ?>" class="nav-sub-link">Instrument Label</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Label.mrpLabel', 'Label.generateMRPLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/mrp-label')); ?>" class="nav-sub-link">MRP Label</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Label.ahplMRPLabel', 'Label.generateAHPLMRPLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/ahpl-mrp-label')); ?>" class="nav-sub-link">AHPL MRP Label</a>
        </li>
        
        <li class="nav-sub-item <?php if(in_array($Action,['Label.snnMRPLabel', 'Label.generateADHLMRPLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/snn-mrp-label')); ?>" class="nav-sub-link">SNN MRP Label</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Label.jayonMRPLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/Jayon-mrp-label')); ?>" class="nav-sub-link">Jayon MRP Label</a>
        </li>
       
        <li class="nav-sub-item <?php if(in_array($Action,['Label.docWiseComparison'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/doc-item-comparison')); ?>" class="nav-sub-link">DOC. Wise Item Comparison</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Label.docAdlerMRPLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/doc-adler-mrp-label')); ?>" class="nav-sub-link">DOC. Wise Adler MRP Label</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Label.docSNNMRPLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/doc-snn-mrp-label')); ?>" class="nav-sub-link">DOC. Wise SNN MRP Label</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Label.mailingLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/mailing-label')); ?>" class="nav-sub-link">Mailing Label</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Label.nonSterileProductLabel', 'Label.generateNonSterileProductLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/non-sterile-product-label')); ?>" class="nav-sub-link">Non-Sterile Label</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Label.nonSterileProductLabel2', 'Label.generateNonSterileProductLabel2'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/non-sterile-product-label2')); ?>" class="nav-sub-link">Non-Sterile Label 2</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Label.sterilizationProductLabel','Label.generateSterilizationProductLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/sterilization-label')); ?>" class="nav-sub-link">Sterilization Label</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['SampleLabel.SterilizationProductLABLE2','SampleLabel.GenerateSterilizationProductLABLE2'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('samplelabel/sterilization-label-2')); ?>" class="nav-sub-link">Sterilization label 2</a>
        </li>
        <!-- <li class="nav-sub-item <?php if(in_array($Action,['Label.sterilizationProductLabel2','Label.generateSterilizationProductLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/sterilization-label2')); ?>" class="nav-sub-link">Sterilization Label2</a>
        </li> -->
        <!-- <li class="nav-sub-item <?php if(in_array($Action,['Label.sterilizationProductLabel2','Label.generateSterilizationProductLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/new-sterile')); ?>" class="nav-sub-link">new-sterile</a>
        </li> -->
        <!-- <li class="nav-sub-item <?php if(in_array($Action,['Label.sterilizationProductLabel2','Label.generateSterilizationProductLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/aneurysm-clip-sterile-packaging')); ?>" class="nav-sub-link">Aneursym Clip Sterile</a>
        </li> -->
        <!-- <li class="nav-sub-item <?php if(in_array($Action,['Label.sterilizationProductLabel2','Label.generateSterilizationProductLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/new-non-sterile-label')); ?>" class="nav-sub-link">New Non Sterile</a>
        </li> -->
        <li class="nav-sub-item <?php if(in_array($Action,['Label.patientLabel', 'Label.generatePatientLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/patient-label')); ?>" class="nav-sub-link">Patient Label</a>
        </li>
        <!-- <li class="nav-sub-item <?php if(in_array($Action,['Label.newpatientLabel', 'Label.generatenewPatientLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/new-patient-label')); ?>" class="nav-sub-link">New Patient Label</a>
        </li> -->
        
        <li class="nav-sub-item <?php if(in_array($Action,['Label.printingReport'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/printing-report')); ?>" class="nav-sub-link">Label Printing Report</a>
        </li>
      </ul>
    </li>
    <?php endif; ?>
    <?php if(in_array('raw_material.view',config('permission'))): ?>
    <li class="nav-item <?php if(in_array($Action,['RowMaterial.materialList','RowMaterial.materialAdd','RowMaterial.materialEdit','RowMaterial.fixedRateList','RowMaterial.getfixedRateUpload',
        'RowMaterial.materialUpload','Inventorygst.Add_itemtype'])): ?><?php echo e('active show'); ?> <?php endif; ?> ">
      <a href="#" class="nav-link with-sub"><i class="fas fa-swatchbook" style="font-size: 19px"></i>Raw Material</a>
      <ul class="nav-sub">
        <li class="nav-sub-item <?php if(in_array($Action,['RowMaterial.materialList','RowMaterial.materialAdd','RowMaterial.materialEdit'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('row-material/list')); ?>" class="nav-sub-link">Raw Materials</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['RowMaterial.materialUpload'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('row-material/upload')); ?>" class="nav-sub-link">Raw Material Upload</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['RowMaterial.fixedRateList'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('row-material/fixed-rate')); ?>" class="nav-sub-link">Fixed Rate Raw Material</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['RowMaterial.getfixedRateUpload'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('row-material/fixed-rate/upload')); ?>" class="nav-sub-link">Fixed Rate Material Upload</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Inventorygst.Add_itemtype'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('inventory/inventory-itemtype-add')); ?>" class="nav-sub-link">Inv item type</a>
        </li>
      </ul>
    </li>
    <?php endif; ?>
    <?php if(in_array('product.view',config('permission'))): ?>
    <li class="nav-item <?php if(in_array($Action,['Product.productList','Product.productFileUpload','Product.addInputMaterial','Product.getProductUpload','Product.locationList','Product.upload_product_inputmaterial'])): ?><?php echo e('active show'); ?> <?php endif; ?> ">
      <a href="#" class="nav-link with-sub"><i class="fab fa-product-hunt" style="font-size: 19px"></i>Product</a>
      <ul class="nav-sub">
        <li class="nav-sub-item <?php if(in_array($Action,['Product.productList','Product.addInputMaterial'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('product/list')); ?>" class="nav-sub-link">Products</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Product.getProductUpload'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('product/file/upload')); ?>" class="nav-sub-link">Product Upload</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Product.locationList'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('product/location')); ?>" class="nav-sub-link">Product Location</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Product.Product-addgroup'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('product/Product-add-group')); ?>" class="nav-sub-link">Product Group Add</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Product.upload_product_inputmaterial'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('product/input-material-upload')); ?>" class="nav-sub-link">Product input material(PRD-29)</a>
        </li>
      </ul>
    </li>
    <?php endif; ?>
    <?php if(in_array('employee.view',config('permission'))): ?>
    <li class="nav-item <?php if(in_array($Action,['Employee.employeeList','Employee.employeeAdd','Employee.employeeEdit'])): ?><?php echo e('active show'); ?> <?php endif; ?> ">
      <a href="#" class="nav-link with-sub"><i class="fas fa-user-alt" style="font-size: 19px"></i>Employee</a>
      <ul class="nav-sub">
        <li class="nav-sub-item <?php if(in_array($Action,['Employee.employeeList','Employee.employeeAdd','Employee.employeeEdit'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('employee/list')); ?>" class="nav-sub-link">Employee</a>
        </li>

      </ul>
    </li>
    <?php endif; ?>
    <?php if(in_array('settings.view',config('permission'))): ?>
    <li class="nav-item <?php if(in_array($Action,['RolePermission.roleList','RolePermission.moduleList','RolePermission.permissionList','RolePermission.rolePermission','Config.get_config_list','Config.get_configpage','Config.add_configsetting'])): ?><?php echo e('active show'); ?> <?php endif; ?> ">
      <a href="#" class="nav-link with-sub"><i class="fa fa-cog" style="font-size: 19px"></i>Settings</a>
      <ul class="nav-sub">
        <li class="nav-sub-item <?php if(in_array($Action,['RolePermission.roleList','RolePermission.rolePermission'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('inventory/inventory-gst')); ?>" class="nav-sub-link">GST</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['RolePermission.roleList','RolePermission.rolePermission'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('settings/role')); ?>" class="nav-sub-link">Role</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['RolePermission.moduleList'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('settings/module')); ?>" class="nav-sub-link">Module</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['RolePermission.permissionList'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('settings/permission')); ?>" class="nav-sub-link">Permissions</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Config.get_config_list','Config.get_configpage','Config.add_configsetting'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('settings/configlist')); ?>" class="nav-sub-link">Config List</a>
        </li>
      </ul>
    </li>
    <?php endif; ?>
    <?php if(in_array('accounts.view',config('permission'))): ?>
    <li class="nav-item <?php if(in_array($Action,['AccountsPayment.PaymentAdd','AccountsReceipt.ReceiptAdd'])): ?><?php echo e('active show'); ?> <?php endif; ?> ">
      <a href="#" class="nav-link with-sub"><i class="fab fa-product-hunt" style="font-size: 19px"></i>Accounts</a>
      <ul class="nav-sub">
        <li class="nav-sub-item <?php if(in_array($Action,['AccountsPayment.PaymentAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('accounts/payment-add')); ?>" class="nav-sub-link">Payment</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['AccountsReceipt.ReceiptAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('accounts/receipt-add')); ?>" class="nav-sub-link">Receipt</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['AccountsJournal.JournalAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('accounts/Journal-add')); ?>" class="nav-sub-link">Journal</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['AccountsContra.ContraAdd'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('accounts/Contra-add')); ?>" class="nav-sub-link">Contra</a>
        </li>
      </ul>
    </li>
    <?php endif; ?>
    <?php if(in_array('sample_label_card.view',config('permission'))): ?>
    <li class="nav-item <?php if(in_array($Action,['SampleLabel.newaneurysm','SampleLabel.NewNonsterile','SampleLabel.Newsterile','SampleLabel.NewpatientLabel','SampleLabel.InstrumentLabel','SampleLabel.NonSterileProductLabel','SampleLabel.Patient30Label','SampleLabel.NewNonsterilization','SampleLabel.SterilizationProductLABLE2'])): ?><?php echo e('active show'); ?> <?php endif; ?> ">
      <a href="#" class="nav-link with-sub"><i class="fas fa-address-card" style="font-size:20px;"></i>Sample Label Card</a>
      <ul class="nav-sub">

        <li class="nav-sub-item <?php if(in_array($Action,['SampleLabel.Newsterile','SampleLabel.NewsterileGenrate'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('samplelabel/new-sterile-label')); ?>" class="nav-sub-link">New Sterile</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['SampleLabel.newaneurysm','SampleLabel.newaneurysmgenrate'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('samplelabel/aneurysm-label')); ?>" class="nav-sub-link">Aneursym Clip Sterile</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['SampleLabel.NewNonsterile','SampleLabel.NewNonsterileGenrate'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('samplelabel/new-non-sterile')); ?>" class="nav-sub-link">New Non Sterile</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['SampleLabel.NewNonsterilization','SampleLabel.NewNonsterilizationGenrate'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('samplelabel/new-non-sterilization-label')); ?>" class="nav-sub-link">Non-Sterile-label 2</a>
        </li>
        <!-- <li class="nav-sub-item <?php if(in_array($Action,['Label.nonSterileProductLabel2', 'Label.generateNonSterileProductLabel2'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('label/non-sterile-product-label2')); ?>" class="nav-sub-link">Non-Sterile Label 2</a>
        </li> -->
        <li class="nav-sub-item <?php if(in_array($Action,['SampleLabel.NewpatientLabel', 'SampleLabel.NewgeneratePatientLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('samplelabel/new-patient')); ?>" class="nav-sub-link">New Patient Label</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['SampleLabel.InstrumentLabel','SampleLabel.GenerateInstrumentLabel'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('samplelabel/new-instrument')); ?>" class="nav-sub-link">New Instrument</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['SampleLabel.NonSterileProductLabel','SampleLabel.GenerateNonSterile'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('samplelabel/flip-non-sterile')); ?>" class="nav-sub-link">Flip Non Sterile</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['SampleLabel.Patient30Label','SampleLabel.GeneratePatient30Label'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('samplelabel/patient-30-label')); ?>" class="nav-sub-link">Patient 30</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['SampleLabel.SterilizationProductLABLE2','SampleLabel.GenerateSterilizationProductLABLE2'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('samplelabel/sterilization-label-2')); ?>" class="nav-sub-link">Sterilization label 2</a>
        </li>

      </ul>
    </li>
    <?php endif; ?>
    <?php if(in_array('quality.list',config('permission'))  || in_array('quality.check',config('permission'))): ?>
    <li class="nav-item <?php if(in_array($Action,['Quality.qualitylist'])): ?><?php echo e('active show'); ?> <?php endif; ?> ">
      <a href="#" class="nav-link with-sub"><i class="fas fa-check-circle" style="font-size: 19px"></i>Quality</a>
      <ul class="nav-sub">
        <li class="nav-sub-item <?php if(in_array($Action,['Quality.qualitylist'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('quality/qualitylist')); ?>" class="nav-sub-link">Quality List</a>
        </li>
        <li class="nav-sub-item <?php if(in_array($Action,['Quality.inspectedqualitylist'])): ?><?php echo e('active'); ?> <?php endif; ?> ">
          <a href="<?php echo e(url('quality/inspected-quality-list')); ?>" class="nav-sub-link">Inspected Quality List</a>
        </li>
      </ul>
      
    </li>
<?php endif; ?>
    </ul><!-- nav -->
  </div><!-- az-sidebar-body -->
</div><!-- az-sidebar --><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/includes/sidebar.blade.php ENDPATH**/ ?>
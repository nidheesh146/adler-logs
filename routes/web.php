<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'App\Http\Controllers\Web'], function() {
    Route::get('/', 'UserController@login');
    Route::post('/', 'UserController@login');
    Route::get('logout', 'UserController@logout');

    Route::get('user-add', 'WebapiController@insert_user');
    Route::get('user-dept', 'WebapiController@insert_dept');
    


});

Route::group(['namespace' => 'App\Http\Controllers\Web\PurchaseDetails'], function() {
    Route::get('request-for-quotation/{q_id}/{s_id}', 'QuotationController@request_quotation');
});

Route::group(['namespace' => 'App\Http\Controllers\Web\PurchaseDetails','middleware'=>['RolePermission']], function() {
    // purchase requisition master
    Route::get('inventory/get-purchase-reqisition', 'InventoryController@get_purchase_reqisition')->name('search-requisition');
    Route::get('inventory/add-purchase-reqisition', 'InventoryController@add_purchase_reqisition');
    Route::post('inventory/add-purchase-reqisition', 'InventoryController@add_purchase_reqisition');
    Route::get('inventory/edit-purchase-reqisition', 'InventoryController@edit_purchase_reqisition');
    Route::post('inventory/edit-purchase-reqisition', 'InventoryController@edit_purchase_reqisition');
    Route::get('inventory/delete-purchase-reqisition', 'InventoryController@delete_purchase_reqisition');

    
    // purchase requisition item
    Route::get('inventory/get-purchase-reqisition-item', 'InventoryController@get_purchase_reqisition_item');
    Route::get('inventory/add-purchase-reqisition-item', 'InventoryController@add_purchase_reqisition_item');
    Route::post('inventory/add-purchase-reqisition-item', 'InventoryController@add_purchase_reqisition_item');
    Route::get('inventory/edit-purchase-reqisition-item', 'InventoryController@edit_purchase_reqisition_item');
    Route::post('inventory/edit-purchase-reqisition-item', 'InventoryController@edit_purchase_reqisition_item');
    Route::get('inventory/delete-purchase-reqisition-item', 'InventoryController@delete_purchase_reqisition_item');

  
    Route::get('inventory/get-description', 'InventoryController@get_description');
    Route::get('inventory/get-single-item', 'InventoryController@getSingleItem');
    Route::get('getSGSTandCGST','InventoryController@getSGSTandCGST');
    Route::get('inventory/purchase-reqisition-item/excel-export', 'InventoryController@requisitionItemExport');

    // service requisition master
    Route::get('inventory/edit-service-reqisition', 'InventoryController@edit_purchase_reqisition');
    Route::post('inventory/edit-service-reqisition', 'InventoryController@edit_purchase_reqisition');
    Route::get('inventory/delete-service-reqisition', 'InventoryController@delete_service_reqisition');
    // service requisition item
    Route::get('inventory/get-service-reqisition-item', 'InventoryController@get_purchase_reqisition_item');
    // Route::get('inventory/add-service-reqisition-item', 'InventoryController@add_purchase_reqisition_item');
    // Route::post('inventory/add-service-reqisition-item', 'InventoryController@add_purchase_reqisition_item');
    
    // Quotation Master
    Route::get('inventory/quotation/{id?}', 'QuotationController@getQuotation');
    // Route::get('inventory/suppliersearch', 'QuotationController@suppliersearch');
    Route::post('inventory/add/quotation','QuotationController@postQuotation');
    Route::get('inventory/quotation/items','QuotationController@getItems');
    Route::get('inventory/direct/purchase','QuotationController@directPurchase');
    Route::post('inventory/add/fixed-item-quotation','QuotationController@directPurchaseQuotation');
    
    

    // Quotation item
    Route::get('inventory/quotation-item', 'QuotationController@getQuotationItem');
    Route::get('inventory/add/quotation-item','QuotationController@addQuotationItem');
    Route::post('inventory/add/quotation-item','QuotationController@postQuotationItem');
    Route::get('inventory/edit/quotation-item', 'QuotationController@editQuotationItem');
    Route::post('inventory/edit/quotation-item', 'QuotationController@editQuotationItem');
    Route::post('inventory/delete/quotation-item', 'QuotationController@deleteQuotationItem');

    Route::get('inventory/itemcodesearch/{itemcode?}', 'InventoryController@itemcodesearch');
    Route::get('inventory/suppliersearch', 'InventoryController@suppliersearch');
    Route::get('inventory/purchase-reqisition/approval', 'ApprovalController@getList');
    Route::post('inventory/purchase-reqisition/approval', 'ApprovalController@Approve');
    Route::get('inventory/all-requisition-item/excel-export', 'ApprovalController@AllrequisitionItemExport');
    

    Route::get('inventory/supplier-quotation', 'SupplierQuotationController@getSupplierQuotation');
    Route::post('inventory/supplierQuotationUpdate/{rq_no}/{supp_id}', 'SupplierQuotationController@supplierQuotationUpdate');
    Route::get('inventory/view-supplier-quotation-items/{rq_no}/{supp_id}','SupplierQuotationController@viewSupplierQuotationItems');
    Route::get('inventory/edit-supplier-quotation-item/{rq_no}/{supp_id}/{item_id}', 'SupplierQuotationController@getSupplierQuotationEditItem');
    Route::post('inventory/edit-supplier-quotation-item/{rq_no}/{supp_id}/{item_id}', 'SupplierQuotationController@getSupplierQuotationEditItem');
    Route::get('inventory/supplier-quotation/excel-export', 'SupplierQuotationController@supplierQuotationExport');
    // comparison of quotation   
    Route::get('inventory/comparison-quotation/{rq_no}', 'SupplierQuotationController@comparisonOfQuotation');
    Route::post('inventory/select-quotation', 'SupplierQuotationController@selectQuotation');
    Route::post('inventory/select-quotation-items', 'SupplierQuotationController@selectQuotationItems'); 
    
    //final purchase
    // Route::get('inventory/final-purchase-add/{id?}', 'PurchaseController@addFinalPurchase');
    // Route::post('inventory/final-purchase-add/{id?}', 'PurchaseController@addFinalPurchase');
    Route::get('inventory/final-purchase', 'PurchaseController@getFinalPurchase');
    Route::get('inventory/final-purchase-add', 'PurchaseController@add1FinalPurchase');
    Route::post('inventory/final-purchase-insert', 'PurchaseController@insertFinalPurchase');
    Route::get('inventory/final-purchase-edit/{id?}', 'PurchaseController@editFinalPurchase');
    Route::post('inventory/final-purchase-edit/{id?}', 'PurchaseController@editFinalPurchase');


    Route::get('inventory/final-purchase-delete/{id?}', 'PurchaseController@deleteFinalPurchase');
    Route::get('inventory/find-rq-number', 'PurchaseController@find_rq_number');
    Route::get('inventory/final-purchase-item-edit/{id}', 'PurchaseController@Edit_PO_item');
    Route::post('inventory/final-purchase-item-edit/{id}', 'PurchaseController@Edit_PO_item');
    Route::get('inventory/final-purchase/pdf/{id}', 'PurchaseController@generateFinalPurchasePdf');
    Route::get('inventory/final-purchase/export/all', 'PurchaseController@exportFinalPurchaseAll');
    Route::get('inventory/final-purchase/export/open', 'PurchaseController@exportFinalPurchaseOpen');
    Route::get('inventory/final-purchase/excel-export', 'PurchaseController@exportFinalPurchase');

    Route::post('inventory/final-purchase/change/status', 'PurchaseController@changeStatus');
    Route::get('inventory/final-purchase-view/{id}', 'PurchaseController@viewFinalPurchase');
    Route::get('inventory/final-purchase/cancellation', 'PurchaseController@purchaseOderCancellation');
    Route::get('inventory/final-purchase/approval', 'PurchaseController@purchaseOderApproval');
    Route::post('inventory/final-purchase/approval', 'PurchaseController@Approve');
    Route::get('getOrderItems','PurchaseController@getOrderItems');
    Route::post('inventory/final-purchase/partial-cancellation', 'PurchaseController@partialCancellation');
    Route::get('inventory/final-purchase/excess-quantity', 'PurchaseController@getExcessQty');
    Route::get('inventory/final-purchase-view/{id}/excess-quantity', 'PurchaseController@viewFinalPurchaseExcess');
    Route::post('inventory/final-purchase/excess-qty-order','PurchaseController@excessPurchaseOrder');
    Route::get('inventory/pending-purchase-realisation','PurchaseController@pendingPurchaseRealisation');
    Route::get('inventory/pending-purchase-realisation/excel-export','PurchaseController@pendingPurchaseRealisationExport');
    Route::get('inventory/getTermsandConditions','PurchaseController@getTermsandConditions');
    Route::post('inventory/final-purchase/change/terms-condition','PurchaseController@changeTerms');
    //supplier-invoice
    Route::get('inventory/supplier-invoice', 'PurchaseController@supplierInvoice');
    Route::get('inventory/supplier-invoice-add', 'PurchaseController@supplierInvoiceAdd');
    Route::post('inventory/supplier-invoice-add', 'PurchaseController@supplierInvoiceAdd');
    Route::post('inventory/supplier-invoice-edit', 'PurchaseController@supplierInvoiceEdit1');
    Route::get('inventory/find-po-number', 'PurchaseController@find_po_number');
    Route::get('inventory/supplier-invoice-delete/{id}', 'PurchaseController@supplier_invoice_delete');
    Route::get('inventory/supplier-invoice-item-edit/{master}/{id}', 'PurchaseController@supplierInvoiceItemEdit');
    Route::post('inventory/supplier-invoice-item-edit/{master}/{id}', 'PurchaseController@supplierInvoiceItemEdit');
    Route::get('inventory/getPurchaseOrderItem','PurchaseController@getPurchaseOrderItem');
    Route::get('inventory/getInvoiceData','PurchaseController@getInvoiceData');
    Route::post('inventory/partial-supplier-invoice','PurchaseController@PartialSupplierInvoice');
    Route::get('inventory/supplier-invoice/excel-export', 'PurchaseController@supplierInvoiceExport');
    //lot allocation
    Route::get('inventory/lot-allocation-list', 'LotAllocationController@lotAllocation');
    Route::get('inventory/lot-allocation-add', 'LotAllocationController@addLotAllocation');
    Route::post('inventory/lot-allocation-add', 'LotAllocationController@addLotAllocation');
    Route::post('inventory/lot-allocation-edit', 'LotAllocationController@addLotAllocation');
    Route::get('inventory/get-single-invoice-item/{itemId}','LotAllocationController@getInvoiceItem');
    Route::get('inventory/get-single-lot-allocation/{lot_allocation_id}','LotAllocationController@getsingleLot');
    Route::get('inventory/lot-allocation/pdf/{id}', 'LotAllocationController@generatePdf');
    Route::get('inventory/lot-allocation/excel-export','LotAllocationController@lotAllocationExport');
   // MIQ
    Route::get('inventory/MIQ', 'MIQController@MIQlist');
    Route::get('inventory/MIQ-add/{id?}', 'MIQController@MIQAdd');
    Route::post('inventory/MIQ-add/{id?}', 'MIQController@MIQAdd');
    Route::get('inventory/MIQ/{id}/item', 'MIQController@MIQAddItemInfo');
    Route::post('inventory/MIQ/{id}/item', 'MIQController@MIQAddItemInfo');
    Route::get('inventory/find-invoice-number','MIQController@findInvoiceNumber');
    Route::get('inventory/MIQ-delete/{id}', 'MIQController@miq_delete');
    Route::get('inventory/MIQ/excel-export','MIQController@MIQExport');
    Route::get('inventory/MIQ/quarantine-excel-export','MIQController@MIQQuarantineExport');
    Route::get('inventory/MIQ/QuarantineReport','MIQController@LiveQuarantineReport');
    // MAC
    Route::get('inventory/MAC', 'MACController@MAClist');
    Route::get('inventory/MAC-add/{id?}', 'MACController@MACAdd');
    Route::post('inventory/MAC-add/{id?}', 'MACController@MACAdd'); 
    Route::get('inventory/MAC/{id}/item', 'MACController@MACAddItemInfo');
    Route::post('inventory/MAC/{id}/item', 'MACController@MACAddItemInfo');
    //Route::get('inventory/find-miq-no', 'MACController@findMiqNumber');
    //Route::get('inventory/find-miq-info', 'MACController@find_miq_info');
    Route::get('inventory/MAC-delete/{id}', 'MACController@mac_delete');
    Route::get('inventory/MAC/find-invoice-number-for-mac','MACController@findInvoiceNumberForMAC');
    Route::get('inventory/MAC/find-invoice-info', 'MACController@invoiceInfo');
    Route::get('inventory/MAC/find-invoice-number-for-woa','MACController@findInvoiceNumberForWOA');

    Route::get('inventory/WOA-add/{id?}', 'MACController@WOAAdd');
    Route::post('inventory/WOA-add/{id?}', 'MACController@WOAAdd');
    Route::get('inventory/WOA/{id}/pdf', 'MACController@WOApdf');
    Route::get('inventory/MAC/excel-export','MACController@MACExport');
   
    // MRD
    Route::get('inventory/MRD', 'MRDController@MRDlist');
    Route::get('inventory/MRD-add/{id?}', 'MRDController@MRDAdd');
    Route::post('inventory/MRD-add/{id?}', 'MRDController@MRDAdd');
    Route::get('inventory/MRD/{id}/item', 'MRDController@MRDAddItemInfo');
    Route::post('inventory/MRD/{id}/item', 'MRDController@MRDAddItemInfo');
    //Route::get('inventory/find-miq-for_mrd', 'MRDController@findMiqNumberForMRD');
    Route::get('inventory/MRD/find-invoice-number-for-mrd','MRDController@findInvoiceNumberForMRD');
    Route::get('inventory/MRD/find-invoice-info', 'MRDController@invoiceInfo');
    Route::get('inventory/MRD/find-invoice-number-for-wor','MRDController@findInvoiceNumberForWOR');
    Route::get('inventory/MRD-delete/{id}', 'MRDController@mrd_delete');
    Route::get('inventory/MRD/excel-export','MRDController@MRDExport');

    Route::get('inventory/WOR-add/{id?}', 'MRDController@WORAdd');
    Route::post('inventory/WOR-add/{id?}', 'MRDController@WORAdd');

    //RMRN
    Route::get('inventory/RMRN', 'MRDController@RMRNlist');
    Route::get('inventory/RMRN-add/{id?}', 'MRDController@RMRNAdd');
    Route::post('inventory/RMRN-add/{id?}', 'MRDController@RMRNAdd');
    Route::get('inventory/RMRN/{id}/item', 'MRDController@RMRNAddItemInfo');
    Route::post('inventory/RMRN/{id}/item', 'MRDController@RMRNAddItemInfo');
    Route::get('inventory/RMRN-delete/{id}', 'MRDController@RMRNDelete');
    Route::get('inventory/RMRN/pdf/{id}', 'MRDController@RMRNpdf');
    Route::get('inventory/RMRN/excel-export','MRDController@RMRNExport');
    Route::get('inventory/find-mrd', 'MRDController@find_mrd');
    Route::get('inventory/find-mrd-info', 'MRDController@find_mrd_info');
    
    //MRR/SRR
    Route::get('inventory/receipt-report', 'MRRController@receiptReport');
    Route::get('inventory/MRR-add/{id?}', 'MRRController@addMRR');
    Route::post('inventory/MRR-add/{id?}', 'MRRController@addMRR');
    Route::get('inventory/find-mac-for-mrr', 'MRRController@find_mac_for_mrr');
    Route::get('inventory/find-mac-info', 'MRRController@find_mac_info');
    Route::get('inventory/find-woa-for-mrr', 'MRRController@find_woa_for_mrr');
    Route::get('inventory/find-woa-info', 'MRRController@find_woa_info');
    Route::get('inventory/MRR-delete/{id}', 'MRRController@mrr_delete');
    Route::get('inventory/receipt-report/{id}/report', 'MRRController@receiptReportPDF');
    Route::get('getPO_for_merged_si_item','MRRController@getPO_for_merged_si_item');
     Route::get('inventory/MRR/excel-export','MRRController@MRRExport');

    Route::get('inventory/find-invoice-for-mrr','MRRController@find_invoice_for_mrr');
    Route::get('inventory/find-invoice-for-srr','MRRController@find_invoice_for_srr');
    Route::get('inventory/MRR/find-invoice-info','MRRController@find_invoice_info');
    //Stock To Production
   
    Route::post('inventory/stock/issueToProduction', 'StockController@issueToProduction');
    Route::get('inventory/Stock/ToProduction/delete/{id}', 'StockController@StockToProductionDelete');
    Route::post('inventory/stock-ToProduction-edit', 'StockController@StockToProductionEdit');
    Route::get('getSingleSIP', 'StockController@getSingleSIP');

    Route::get('inventory/Stock/ToProduction', 'StockController@StockToProduction');
    Route::get('inventory/Stock/ToProduction-add', 'StockController@StockToProductionAdd');
    Route::get('inventory/stock/find-batchcard','StockController@findBatchCard');
    Route::get('inventory/stock/fetchBatchCard-info','StockController@fetchBatchCard_info');
    Route::get('inventory/stock/fetchBatchCard-items','StockController@fetchBatchCard_items');
    Route::get('inventory/stock/fetchLotcard','StockController@fetchLotcard');
    Route::get('inventory/stock/fetchPrimaryBatchCard-info','StockController@fetchPrimaryBatchCard_info');
    Route::get('inventory/Stock/ToProduction/Direct','StockController@DirectSIP');
    Route::post('inventory/Stock/ToProduction/Direct','StockController@addDirectSIP');
    Route::get('inventory/Stock/ToProduction/Indirect','StockController@IndirectSIP');
    Route::post('inventory/Stock/ToProduction/Indirect','StockController@addIndirectSIP');
    Route::get('inventory/Stock/ToProduction/excel-export','StockController@StockToProductionExport');


    // Route::get('inventory/stock/item-mac-info','StockController@itemMacDetails');
    Route::get('inventory/stock/item-stock-info','StockController@itemStockDetails');
    Route::post('inventory/stock/quantity-updation-request','StockController@quantityUpdationRequest');
    Route::get('inventory/indirect/itemcodesearch/{itemcode?}','StockController@Indirectitemcodesearch');
    Route::get('inventory/direct/itemcodesearch/{itemcode?}','StockController@Directitemcodesearch');

    Route::get('inventory/stock/fetchBatchCards','StockController@fetchBatchCards');
     //Stock From Production
    Route::get('inventory/Stock/FromProduction', 'StockController@StockFromProduction');
    Route::get('inventory/Stock/FromProduction-add', 'StockController@StockFromProductionAdd');
    Route::post('inventory/stock/return-FromProductionAdd', 'StockController@returnFromProductionAdd');
    Route::get('inventory/Stock/FromProduction/delete/{id}', 'StockController@StockFromProductionDelete');
    Route::post('inventory/stock-FromProduction-edit', 'StockController@StockFromProductionEdit');
    Route::get('getSingleSIR', 'StockController@getSingleSIR');
    Route::get('inventory/Stock/ToProduction/view/{id}','StockController@SIPview');
    //Route::get('inventory/stock/fetchSIPinfoDirect','StockController@fetchSIPinfoDirect');
    Route::get('inventory/stock/fetchDirectItemLotCards','StockController@fetchDirectItemLotCards');
    Route::get('inventory/stock/lotcardInfo','StockController@lotcardInfo');
    
    Route::get('inventory/stock/fetchSIPinfoIndirect','StockController@fetchSIPinfoIndirect');

      //Stock transfer
      Route::get('inventory/Stock/transfer', 'StockController@StockTransfer');
      Route::get('inventory/Stock/transfer-add', 'StockController@StockTransferAdd');
      Route::post('inventory/stock/transfer-order', 'StockController@transferOrder');
      Route::get('inventory/stock/item_qty_in_mac_not_equal_zero','StockController@item_qty_in_mac_not_equal_zero');
      Route::get('inventory/stock/fetchSIPlist_for_sto','StockController@fetchSIPlist_for_sto');
      Route::get('inventory/Stock/transfer/items/{sto_id}','StockController@viewItems');
      Route::get('getSingleSTO', 'StockController@getSingleSTO');

    // suppliers
    // Route::get('inventory/terms-and-conditions-list','TermsconditionsController@list_terms_conditions');
    // Route::get('inventory/terms-and-conditions-add/{id?}','TermsconditionsController@add_terms_conditions');
    // Route::post('inventory/terms-and-conditions-add/{id?}','TermsconditionsController@add_terms_conditions');
    // Route::get('inventory/terms-and-conditions-get/{id}','TermsconditionsController@get_terms_conditions');
    
    // suppliers
    Route::get('inventory/suppliers-list','SupplierController@list_supplier');
    Route::get('inventory/suppliers-add/{id?}','SupplierController@add_supplier');
    Route::post('inventory/suppliers-add/{id?}','SupplierController@add_supplier');
    Route::get('inventory/suppliers-delete/{id}','SupplierController@delete_suppliers');

    Route::get('inventory/inventory-gst','InventorygstController@get_data');
    Route::post('inventory/inventory-gst_add','InventorygstController@add_gst_details');
    Route::get('inventory/inventory-trans-report','InventoryreportController@get_data');



});



Route::group(['namespace' => 'App\Http\Controllers\Web','middleware'=>['RolePermission']], function() {
  
    //DashBoard
    Route::get('dashboard','DashboardController@index');
    Route::get('profile','ProfileController@profile');
    Route::post('updateprofile','ProfileController@updateProfile');
   //Batchcard
    Route::get('batchcard/batchcard-list', 'BatchCardController@BatchcardList');
    Route::get('batchcard/batchcard-upload', 'BatchCardController@getBatchcardUpload');
    Route::post('batchcard/batchcard-upload', 'BatchCardController@batchcardUpload');
    Route::get('batchcard/batchcard-add', 'BatchCardController@BatchcardAdd');
    Route::post('batchcard/batchcard-add', 'BatchCardController@BatchcardAdd');
    Route::get('batchcard/productsearch', 'BatchCardController@productsearch');
    Route::post('batchcard/assemble-batchcard-add','BatchCardController@assemblebatchcardAdd');
    Route::get('batchcard/product/find-input-materials','BatchCardController@findInputMaterials');
    Route::get('batchcard/request-list','BatchCardController@requestList');
    Route::get('batchcard/quantity-update/approve','BatchCardController@approveRequest');
    Route::get('batchcard/quantity-update/reject','BatchCardController@rejectRequest');
    Route::get('batchcard/batchcard-list/{batch_id}/report','BatchCardController@BatchCardpdf');
    //Label card
    Route::get('label/adhl-mrp-label','LabelController@adhlMRPLabel');
    Route::post('label/adhl-mrp-label','LabelController@generateADHLMRPLabel');
    Route::get('label/mrp-label','LabelController@mrpLabel');
    Route::post('label/mrp-label','LabelController@generateMRPLabel');
    Route::get('label/getBatchcard/{sku_code}', 'LabelController@getBatchcard');
    Route::get('label/instrument-label','LabelController@instrumentLabel');
    Route::post('label/instrument-label','LabelController@generateInstrumentLabel');
    Route::get('label/non-sterile-product-label', 'LabelController@nonSterileProductLabel');
    Route::post('label/non-sterile-product-label', 'LabelController@generateNonSterileProductLabel');
    Route::get('label/sterilization-label','LabelController@sterilizationProductLabel');
    Route::post('label/sterilization-label','LabelController@generateSterilizationProductLabel');
    Route::get('label/patient-label','LabelController@patientLabel');
    Route::post('label/patient-label','LabelController@generatePatientLabel');
    //Route::get('label/print/patient-label', ['as' => 'patient-label', 'uses' => 'LabelController@patient']);
    Route::get('label/batchcardSearch','LabelController@batchcardSearch');
    Route::get('label/batchcardData/{batch_no_id}','LabelController@batchcardData');
    Route::get('label/printing-report','LabelController@printingReport');
    Route::post('label/insert-printing-data','LabelController@insertPrintingData');
    Route::get('label/exportPrinting-report', 'LabelController@exportPrintingReport')->name('ExportPrintingData');

    // Row material
    Route::get('row-material/list','RowMaterialController@materialList');
    Route::get('row-material/upload','RowMaterialController@materialUpload');
    Route::post('row-material/upload','RowMaterialController@materialPostUpload');
    Route::get('row-material/add','RowMaterialController@materialAdd');
    Route::post('row-material/add','RowMaterialController@materialAdd');
    Route::get('row-material/edit','RowMaterialController@materialEdit');
    Route::post('row-material/edit','RowMaterialController@materialEdit');
    Route::get('row-material/delete', 'RowMaterialController@materialDelete');

    //fixed rate row material
    Route::get('row-material/fixed-rate','RowMaterialController@fixedRateList');
    Route::get('row-material/fixed-rate/add','RowMaterialController@fixedRateAdd');
    Route::post('row-material/fixed-rate/add','RowMaterialController@fixedRateAdd');
    Route::get('row-material/fixed-rate/edit','RowMaterialController@fixedRateEdit');
    Route::post('row-material/fixed-rate/edit','RowMaterialController@fixedRateEdit');
    Route::get('row-material/fixed-rate/delete', 'RowMaterialController@fixedRateDelete');
    Route::get('row-material/fixed-rate/upload','RowMaterialController@getfixedRateUpload');
    Route::post('row-material/fixed-item-upload','RowMaterialController@fixedRateItemUpload');

    //Product
    Route::get('product/list','ProductController@productList');
    Route::get('product/add-input-material','ProductController@addInputMaterial');
    Route::post('product/add-input-material','ProductController@addInputMaterial');
    Route::get('product/delete-input-material','ProductController@deleteInputMaterial');
    Route::get('product/file/upload','ProductController@getProductUpload');
    Route::post('product/product-upload','ProductController@productFileUpload');
    Route::get('product/alternative-input-material','ProductController@alternativeInputMaterial');
    Route::post('product/alternative-input-material/add','ProductController@alternativeInputMaterialAdd');
    Route::get('product/location/{id?}', 'ProductController@locationList');
    Route::post('product/location/{id?}', 'ProductController@locationList');
    Route::get('product/Product-add/{id?}','ProductController@productAdd');
    Route::post('product/Product-add/{id?}','ProductController@productAdd');

});

Route::group(['namespace' => 'App\Http\Controllers\Web\FGS','middleware'=>['RolePermission']], function() 
{
    //Customer -supplier master 
    Route::get('fgs/customer-supplier','CustomerSupplierController@customerSupplierList');
    Route::get('fgs/customer-supplier/add/{id?}','CustomerSupplierController@addCustomerSupplier');
    Route::post('fgs/customer-supplier/add/{id?}','CustomerSupplierController@addCustomerSupplier');
    Route::get('fgs/customersearch','CustomerSupplierController@customersearch');
    Route::get('fgs/domestic_customersearch','CustomerSupplierController@domesticCustomer');
    Route::get('fgs/export_customersearch','CustomerSupplierController@exportCustomer');
    Route::get('fgs/customer-supplier/excel-export','CustomerSupplierController@CustomerSupplierExport');
    //Price master
    Route::get('fgs/price-master/list','PriceController@priceList');
    Route::get('fgs/price-master/add/{id?}','PriceController@priceAdd');
    Route::post('fgs/price-master/add/{id?}','PriceController@priceAdd');
    Route::get('fgs/productsearch','PriceController@productsearch');
    Route::get('fgs/price-master/excel-export','PriceController@PriceMasterExport');
    // Product master
    Route::get('fgs/product-master/list','ProductMasterController@productList');

    Route::get('fgs/product-master/add/{id?}','ProductMasterController@productAdd');
    Route::post('fgs/product-master/add/{id?}','ProductMasterController@productAdd');

    Route::get('fgs/product-master/excel-export','ProductMasterController@ProductExport');

    //Production stock
    Route::get('fgs/production-stock/list','StockManagementController@productionStockList');
    Route::get('fgs/production-stock/Add','StockManagementController@productionStockAdd');
    Route::post('fgs/production-stock/Add','StockManagementController@productionStockAdd');
    
    //MRN
    Route::get('fgs/MRN-list','MRNController@MRNList');
    Route::get('fgs/MRN-add','MRNController@MRNAdd');
    Route::post('fgs/MRN-add','MRNController@MRNAdd');
    Route::get('fgs/MRN/item-list/{mrn_id}','MRNController@MRNitemlist');
    //Route::get('fgs/productsearch','MRNController@productsearch');
    Route::get('fgs/fetchProductBatchCards','MRNController@fetchProductBatchCards');
    Route::get('fgs/MRN/add-item/{mrn_id}','MRNController@MRNitemAdd');
    Route::post('fgs/MRN/add-item/{mrn_id}','MRNController@MRNitemAdd');
    Route::get('fgs/MRN/pdf/{mrn_id}','MRNController@MRNpdf');
    //MIN
    Route::get('fgs/MIN-list','MINController@MINList');
    Route::get('fgs/MIN-add','MINController@MINAdd');
    Route::post('fgs/MIN-add','MINController@MINAdd');
    Route::get('fgs/MIN/item-list/{min_id}','MINController@MINitemlist');
    Route::get('fgs/MIN/add-item/{min_id}','MINController@MINitemAdd');
    Route::post('fgs/MIN/add-item/{min_id}','MINController@MINitemAdd');
    Route::get('fgs/MIN/pdf/{min_id}','MINController@MINpdf');
    Route::get('fgs-stock/fetchproduct','MINController@fetchFGSStockProduct');
    Route::get('fgs/fetchProductBatchCardsFromFGSStock','MINController@fetchBatchCardsFromFGSStock');
    //CMIN
    Route::get('fgs/CMIN/CMIN-list','CMINController@CMINList');
    Route::get('fgs/CMIN-add','CMINController@CMINAdd');
    Route::post('fgs/CMIN-add','CMINController@CMINAdd');
    Route::get('fgs/CMIN/find-min-number-for-cmin','CMINController@findMinNumberForCMIN');
    Route::get('fgs/CMIN/find-min-info', 'CMINController@minInfo');
    Route::get('fgs/CMIN/items-list/{cmin_id}','CMINController@CMINItemList');
    Route::get('fgs/CMIN/pdf/{cmin_id}','CMINController@CMINpdf');

     //MTQ
     Route::get('fgs/MTQ-list','MTQController@MTQList');
     Route::get('fgs/MTQ-add','MTQController@MTQAdd');
     Route::post('fgs/MTQ-add','MTQController@MTQAdd');
     Route::get('fgs/MTQ/item-list/{mtq_id}','MTQController@MTQitemlist');
     Route::get('fgs/MTQ/add-item/{mtq_id}','MTQController@MTQitemAdd');
     Route::post('fgs/MTQ/add-item/{mtq_id}','MTQController@MTQitemAdd');
     Route::get('fgs/fetchProductBatchCardsforMTQ','MTQController@fetchProductBatchCardsforMTQ');
     Route::get('fgs/MTQ/pdf/{mtq_id}','MTQController@MTQpdf');
     //CMTQ
      Route::get('fgs/CMTQ-list','CMTQController@CMTQList');
     Route::get('fgs/CMTQ/CMTQ-add','CMTQController@CMTQAdd');
     Route::post('fgs/CMTQ/CMTQ-add','CMTQController@CMTQAdd');
     Route::get('fgs/CMTQ/item-list/{cmtq_id}','CMTQController@CMTQitemlist');
     Route::get('fgs/CMTQ/find-mtq-number-for-cmtq','CMTQController@findMTQNumberForCMTQ');
     Route::get('fgs/CMTQ/find-mtq-info', 'CMTQController@mtqInfo');
     Route::get('fgs/CMTQ/pdf/{cmtq_id}','CMTQController@CMTQpdf');

     //MIS
     Route::get('fgs/MIS-list','MISController@MISList');
     Route::get('fgs/MIS-add','MISController@MISAdd');
     Route::post('fgs/MIS-add','MISController@MISAdd');
     Route::get('fgs/MIS/find-mtq-number-for-mis','MISController@findMTQNumberForMIS');
     Route::get('fgs/MIS/find-mtq-info','MISController@findMTQInfo');
     Route::get('fgs/MIS/item-list/{mis_id}','MISController@MISitemlist');
     Route::get('fgs/MIS/add-item/{mis_id}','MISController@MISitemAdd');
     Route::post('fgs/MIS/add-item/{mis_id}','MISController@MISitemAdd');
      Route::get('fgs/MIS/pdf/{mis_id}','MISController@MISpdf');

    //OEF
    Route::get('fgs/OEF-list','OEFController@OEFList');
    Route::get('fgs/OEF-add','OEFController@OEFAdd');
    Route::post('fgs/OEF-add','OEFController@OEFAdd');
    Route::get('fgs/OEF/item-list/{oef_id}','OEFController@OEFitemlist');
    Route::get('fgs/OEF/add-item/{oef_id}','OEFController@OEFitemAdd');
    Route::post('fgs/OEF/add-item/{oef_id}','OEFController@OEFitemAdd');
    Route::get('fgs/OEFproductsearch/{oef_id}','OEFController@OEFproductsearch');
    Route::get('fgs/OEF/pdf/{oef_id}','OEFController@OEFpdf');
    Route::get('fgs/OEF/pending-report','OEFController@pendingOEF');
    Route::get('fgs/OEF/pending-OEF-export','OEFController@pendingOEFExport');
    Route::get('fgs/OEF/ackpdf/{oef_id}','OEFController@OEFackpdf');

     //COEF
    Route::get('fgs/COEF/COEF-list','COEFController@COEFList');
    Route::get('fgs/COEF-add','COEFController@COEFAdd');
    Route::post('fgs/COEF-add','COEFController@COEFAdd');
    Route::get('fgs/COEF/find-oef-number-for-coef','COEFController@findOefNumberForCOEF');
    Route::get('fgs/COEF/find-oef-info', 'COEFController@oefInfo');
    Route::get('fgs/COEF/item-list/{coef_id}','COEFController@COEFItemList');
    Route::get('fgs/COEF/pdf/{coef_id}','COEFController@COEFpdf');
    //GRS
    Route::get('fgs/GRS-list','GRSController@GRSList');
    Route::get('fgs/GRS-add','GRSController@GRSAdd');
    Route::post('fgs/GRS-add','GRSController@GRSAdd');
    Route::get('fgs/GRS/item-list/{grs_id}','GRSController@GRSitemlist');
    Route::get('fgs/GRS/{grs_id}/add-item/{oef_item_id}','GRSController@GRSitemAdd');
    Route::post('fgs/GRS/{grs_id}/add-item/{oef_item_id}','GRSController@GRSitemAdd');
    // Route::get('fgs/GRS/add-item/{grs_id}','GRSController@GRSitemAdd');
    // Route::post('fgs/GRS/add-item/{grs_id}','GRSController@GRSitemAdd');
    Route::get('fgs/GRS/find-oef-number-for-grs','GRSController@findOEFforGRS');
    Route::get('fgs/GRS/find-oef-info','GRSController@findOEFInfo');
    Route::get('fgs/GRS/pdf/{grs_id}','GRSController@GRSpdf');
    Route::get('fgs/GRS/pending-report','GRSController@pendingGRS');
    Route::get('fgs/GRS/pending-GRS-export','GRSController@pendingGRSExport');
    //CGRS
    Route::get('fgs/CGRS/CGRS-list','CGRSController@CGRSList');
    Route::get('fgs/CGRS-add','CGRSController@CGRSAdd');
    Route::post('fgs/CGRS-add','CGRSController@CGRSAdd');
    Route::get('fgs/CGRS/find-grs-number-for-cgrs','CGRSController@findGrsNumberForCGRS');
    Route::get('fgs/CGRS/find-grs-info', 'CGRSController@grsInfo');
    Route::get('fgs/CGRS/items-list/{cgrs_id}','CGRSController@CGRSItemList');
    Route::get('fgs/CGRS/pdf/{cgrs_id}','CGRSController@CGRSpdf');
    //PI
    Route::get('fgs/PI-list','PIController@PIList');
    Route::get('fgs/PI-add','PIController@PIAdd');
    Route::post('fgs/PI-add','PIController@PIAdd');
    Route::get('fgs/PI/item-list/{pi_id}','PIController@PIitemlist');
    Route::get('fgs/PI/fetchGRS','PIController@fetchGRS');
    Route::get('fgs/PI/pdf/{pi_id}','PIController@PIpdf');
    Route::get('fgs/PI/payment-pdf/{pi_id}','PIController@PIPaymentpdf');
    Route::get('fgs/PI/pending-report','PIController@pendingPI');
    Route::get('fgs/PI/pending-PI-export','PIController@pendingPIExport');
    Route::get('fgs/merged-PI-list','PIController@mergedPIList');
    Route::get('fgs/merge-multiple-PI','PIController@mergeMutiplePI');
    Route::post('fgs/merge-pi','PIController@mergePIInsert');
    Route::get('fgs/PI/merged-payment-pdf/{mpi_id}','PIController@MergedPIPaymentpdf');
    //CPI
     Route::get('fgs/CPI/CPI-list','CPIController@CPIList');
    Route::get('fgs/CPI/CPI-add','CPIController@CPIAdd');
    Route::post('fgs/CPI/CPI-add','CPIController@CPIAdd');
    Route::get('fgs/CPI/find-pi-number-for-cpi','CPIController@findPiNumberForCPI');
    Route::get('fgs/CPI/find-pi-info', 'CPIController@piInfo');
    Route::get('fgs/CPI/item-list/{cpi_id}','CPIController@CPIItemList');
    Route::get('fgs/CPI/pdf/{cpi_id}','CPIController@CPIpdf'); 
    Route::get('fgs/PI/back-ordr-report','BackorderReportController@get_data');

    
    //DNI
    Route::get('fgs/DNI-list','DNIController@DNIList');
    Route::get('fgs/DNI-add','DNIController@DNIAdd');
    Route::post('fgs/DNI-add','DNIController@DNIAdd');
    Route::get('fgs/DNI/item-list/{dni_id}','DNIController@DNIitemlist');
    Route::get('fgs/DNI/fetchPI','DNIController@fetchPI');
    Route::get('fgs/DNI/pdf/{grs_id}','DNIController@DNIpdf');

     //EXI
     Route::get('fgs/EXI-list','EXIController@EXIList');
     Route::get('fgs/EXI-add','EXIController@EXIAdd');
     Route::post('fgs/EXI-add','EXIController@EXIAdd');
     Route::get('fgs/EXI/item-list/{exi_id}','EXIController@EXIitemlist');
     Route::get('fgs/EXI/fetchPI','EXIController@fetchPI');
     Route::get('fgs/EXI/pdf/{grs_id}','EXIController@EXIpdf'); 

     //stock-management
     Route::get('fgs/stock-management/all-locations','StockManagementController@allLocations');
     Route::get('fgs/stock-management/location1','StockManagementController@location1Stock');
     Route::get('fgs/stock-management/location2','StockManagementController@location2Stock');
     Route::get('fgs/stock-management/location3','StockManagementController@location3Stock');
     Route::get('fgs/stock-management/locationSNN','StockManagementController@locationSNN');
     Route::get('fgs/stock-management/locationAHPL','StockManagementController@locationAHPL');
     Route::get('fgs/stock-management/MAA','StockManagementController@MAAStock');
     Route::get('fgs/stock-management/quarantine','StockManagementController@quarantineStock');
     Route::get('fgs/stock-report/all','StockManagementController@AlllocationExport');
     Route::get('fgs/stock-report/location1','StockManagementController@location1Export');
     Route::get('fgs/stock-report/location2','StockManagementController@location2Export');
     Route::get('fgs/stock-report/location3','StockManagementController@location3Export');
     Route::get('fgs/stock-report/MAA','StockManagementController@MAAExport');
     Route::get('fgs/stock-report/SNN','StockManagementController@SNNExport');
     Route::get('fgs/stock-report/AHPL','StockManagementController@AHPLExport');

    //  //MTQ
    //  Route::get('fgs/MTQ-list','MTQController@MTQList');
    //  Route::get('fgs/MTQ-add','MTQController@MTQAdd');
    //  Route::get('fgs/MTQ-item-list','MTQController@MTQitemlist');
     //MTQ
     Route::get('fgs/MTQ-list','MTQController@MTQList');
     Route::get('fgs/MTQ/add','MTQController@MTQAdd');
     Route::post('fgs/MTQ/add','MTQController@MTQAdd');
     Route::get('fgs/MTQ/item-list/{mtq_id}','MTQController@MTQitemlist');
     Route::get('fgs/MTQ/add-item/{mtq_id}','MTQController@MTQitemAdd');
     Route::post('fgs/MTQ/add-item/{mtq_id}','MTQController@MTQitemAdd');
     Route::get('fgs/fetchProductBatchCardsforMTQ','MTQController@fetchProductBatchCardsforMTQ');

     //MIS
     Route::get('fgs/MIS-list','MISController@MISList');
     Route::get('fgs/MIS-add','MISController@MISAdd');
     Route::post('fgs/MIS-add','MISController@MISAdd');
     Route::get('fgs/MIS/find-mtq-number-for-mis','MISController@findMTQNumberForMIS');
     Route::get('fgs/MIS/find-mtq-info','MISController@findMTQInfo');
     Route::get('fgs/MIS/item-list/{mis_id}','MISController@MISitemlist');
     Route::get('fgs/MIS/add-item/{mis_id}','MISController@MISitemAdd');
     Route::post('fgs/MIS/add-item/{mis_id}','MISController@MISitemAdd');

     // SRN
     Route::get('fgs/SRN-list','SRNController@SRNlist');
     Route::get('fgs/SRN-add','SRNController@SRNAdd');
     Route::post('fgs/SRN-add','SRNController@SRNAdd');
     Route::get('fgs/SRN/find-dni-number-for-srn','SRNController@findDNINumberForSRN');
     Route::get('fgs/SRN/find-dni-info','SRNController@findDNIInfo');
     Route::get('fgs/SRN/item-list/{srn_id}','SRNController@SRNitemlist');
     Route::get('fgs/SRN/pdf/{srn_id}','SRNController@SRNpdf');

     Route::get('fgs/fgs-report','FgsreportController@get_data');
    // Route::post('fgs/fgs-report-search','FgsreportController@get_result');
     Route::get('fgs/fgs-export','FgsreportController@fgsExport');


});

Route::group(['namespace' => 'App\Http\Controllers\Web\Settings','middleware'=>['RolePermission']], function() {
    Route::get('employee/list', 'EmployeeController@employeeList');
    Route::get('employee/add', 'EmployeeController@employeeAdd');
    Route::post('employee/add', 'EmployeeController@employeeAdd');
    Route::get('employee/edit/{id}', 'EmployeeController@employeeEdit');
    Route::post('employee/edit/{id}', 'EmployeeController@employeeEdit');
    Route::get('employee/delete/{id}', 'EmployeeController@employeeDelete');

    //Role 
    Route::get('settings/role', 'RolePermissionController@roleList');
    Route::post('settings/role', 'RolePermissionController@roleList');
    Route::get('settings/role/{role_id}', 'RolePermissionController@roleList');
    Route::post('settings/role/{role_id}', 'RolePermissionController@roleList');
    Route::get('settings/delete-role/{role_id}', 'RolePermissionController@deleteRole');
    Route::get('settings/module', 'RolePermissionController@moduleList');
    Route::get('settings/permission', 'RolePermissionController@permissionList');
    Route::get('settings/role-permission/{role_id}', 'RolePermissionController@rolePermission');
    Route::post('settings/role-permission/{role_id}', 'RolePermissionController@rolePermission');

});
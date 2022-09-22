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
    Route::get('getSGSTandCGST','InventoryController@getSGSTandCGST');
    // service requisition master
    Route::get('inventory/edit-service-reqisition', 'InventoryController@edit_purchase_reqisition');
    Route::post('inventory/edit-service-reqisition', 'InventoryController@edit_purchase_reqisition');
    Route::get('inventory/delete-service-reqisition', 'InventoryController@delete_service_reqisition');
    // service requisition item
    Route::get('inventory/get-service-reqisition-item', 'InventoryController@get_purchase_reqisition_item');
    // Route::get('inventory/add-service-reqisition-item', 'InventoryController@add_purchase_reqisition_item');
    // Route::post('inventory/add-service-reqisition-item', 'InventoryController@add_purchase_reqisition_item');
    
    // Quotation Master
    Route::get('inventory/quotation', 'QuotationController@getQuotation');
    // Route::get('inventory/suppliersearch', 'QuotationController@suppliersearch');
    Route::post('inventory/add/quotation','QuotationController@postQuotation');
    

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

    Route::get('inventory/supplier-quotation', 'SupplierQuotationController@getSupplierQuotation');
    Route::post('inventory/supplierQuotationUpdate/{rq_no}/{supp_id}', 'SupplierQuotationController@supplierQuotationUpdate');
    Route::get('inventory/view-supplier-quotation-items/{rq_no}/{supp_id}','SupplierQuotationController@viewSupplierQuotationItems');
    Route::get('inventory/edit-supplier-quotation-item/{rq_no}/{supp_id}/{item_id}', 'SupplierQuotationController@getSupplierQuotationEditItem');
    Route::post('inventory/edit-supplier-quotation-item/{rq_no}/{supp_id}/{item_id}', 'SupplierQuotationController@getSupplierQuotationEditItem');
    // comparison of quotation   
    Route::get('inventory/comparison-quotation/{rq_no}', 'SupplierQuotationController@comparisonOfQuotation');
    Route::post('inventory/select-quotation', 'SupplierQuotationController@selectQuotation');

    //final purchase
    Route::get('inventory/final-purchase', 'PurchaseController@getFinalPurchase');
    Route::get('inventory/final-purchase-add/{id?}', 'PurchaseController@addFinalPurchase');
    Route::post('inventory/final-purchase-add/{id?}', 'PurchaseController@addFinalPurchase');
    Route::get('inventory/final-purchase-delete/{id?}', 'PurchaseController@deleteFinalPurchase');
    Route::get('inventory/find-rq-number', 'PurchaseController@find_rq_number');
    Route::get('inventory/final-purchase-item-edit/{id}', 'PurchaseController@Edit_PO_item');
    Route::post('inventory/final-purchase-item-edit/{id}', 'PurchaseController@Edit_PO_item');
    Route::get('inventory/final-purchase/pdf/{id}', 'PurchaseController@generateFinalPurchasePdf');
    Route::get('inventory/final-purchase/export/all', 'PurchaseController@exportFinalPurchaseAll');
    Route::get('inventory/final-purchase/export/open', 'PurchaseController@exportFinalPurchaseOpen');

    //supplier-invoice
    Route::get('inventory/supplier-invoice', 'PurchaseController@supplierInvoice');
    Route::get('inventory/supplier-invoice-add/{id?}', 'PurchaseController@supplierInvoiceAdd');
    Route::post('inventory/supplier-invoice-add/{id?}', 'PurchaseController@supplierInvoiceAdd');
    Route::get('inventory/find-po-number', 'PurchaseController@find_po_number');
    Route::get('inventory/supplier-invoice-delete/{id}', 'PurchaseController@supplier_invoice_delete');
    Route::get('inventory/supplier-invoice-item-edit/{master}/{id}', 'PurchaseController@supplierInvoiceItemEdit');
    Route::post('inventory/supplier-invoice-item-edit/{master}/{id}', 'PurchaseController@supplierInvoiceItemEdit');
   
    //lot allocation
    Route::get('inventory/lot-allocation-list', 'LotAllocationController@lotAllocation');
    Route::get('inventory/lot-allocation-add', 'LotAllocationController@addLotAllocation');
    Route::post('inventory/lot-allocation-add', 'LotAllocationController@addLotAllocation');
    Route::post('inventory/lot-allocation-edit', 'LotAllocationController@addLotAllocation');
    Route::get('inventory/get-single-invoice-item/{itemId}','LotAllocationController@getInvoiceItem');
    Route::get('inventory/get-single-lot-allocation/{lot_allocation_id}','LotAllocationController@getsingleLot');

    //MIQ
    Route::get('inventory/MIQ', 'MIQController@MIQlist');
    Route::get('inventory/MIQ-add', 'MIQController@MIQAdd');
    Route::get('inventory/MIQ/{id}/item', 'MIQController@MIQAddItemInfo');
});

// Route::group(['namespace' => 'App\Http\Controllers\Web\ServiceRequisition','middleware'=>['RolePermission']], function() {
//     // Service requisition 
//     Route::get('inventory/get-service-reqisition', 'ServiceController@get_service_reqisition');

//     // service requisition item
//     Route::get('inventory/get-service-reqisition-item', 'ServiceController@get_service_reqisition_item');
//     Route::get('inventory/add-service-reqisition-item', 'ServiceController@add_service_reqisition_item');
// });

Route::group(['namespace' => 'App\Http\Controllers\Web','middleware'=>['RolePermission']], function() {
  
   //Batchcard
    Route::get('batchcard/batchcard-upload', 'BatchCardController@getBatchcardUpload');
    Route::post('batchcard/batchcard-upload', 'BatchCardController@batchcardUpload');
    Route::get('batchcard/batchcard-add', 'BatchCardController@BatchcardAdd');
    Route::post('batchcard/batchcard-add', 'BatchCardController@BatchcardAdd');
    Route::get('batchcard/productsearch', 'BatchCardController@productsearch');

    //Label card
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
});

Route::group(['namespace' => 'App\Http\Controllers\Web\Employee','middleware'=>['RolePermission']], function() {
    Route::get('employee/list', 'EmployeeController@employeeList');
    Route::get('employee/add', 'EmployeeController@employeeAdd');
    Route::post('employee/add', 'EmployeeController@employeeAdd');
    Route::get('employee/edit/{id}', 'EmployeeController@employeeEdit');
    Route::post('employee/edit/{id}', 'EmployeeController@employeeEdit');
    Route::get('employee/delete/{id}', 'EmployeeController@employeeDelete');

    //Module 
    Route::get('module/list', 'ModuleController@moduleList');
    Route::get('module/add', 'ModuleController@moduleAdd');

});
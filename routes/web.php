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
});

Route::group(['namespace' => 'App\Http\Controllers\Web','middleware'=>['Permission']], function() {
    Route::get('inventory/get-purchase-reqisition', 'InventoryController@get_purchase_reqisition');
   
    Route::get('inventory/add-purchase-reqisition', 'InventoryController@add_purchase_reqisition');
    Route::post('inventory/add-purchase-reqisition', 'InventoryController@add_purchase_reqisition');

    Route::get('inventory/edit-purchase-reqisition', 'InventoryController@edit_purchase_reqisition');
    Route::post('inventory/edit-purchase-reqisition', 'InventoryController@edit_purchase_reqisition');

    Route::get('inventory/delete-purchase-reqisition', 'InventoryController@delete_purchase_reqisition');

    // Quotation Master
    Route::get('inventory/quotation', 'QuotationController@getQuotation');
    Route::get('inventory/suppliersearch', 'QuotationController@suppliersearch');
    Route::post('inventory/add/quotation','QuotationController@postQuotation');
    

    // Quotation item
    Route::get('inventory/quotation-item', 'QuotationController@getQuotationItem');
    Route::get('inventory/add/quotation-item','QuotationController@addQuotationItem');
    Route::post('inventory/add/quotation-item','QuotationController@postQuotationItem');
    Route::get('inventory/edit/quotation-item', 'QuotationController@editQuotationItem');
    Route::post('inventory/edit/quotation-item', 'QuotationController@editQuotationItem');
    Route::post('inventory/delete/quotation-item', 'QuotationController@deleteQuotationItem');


    Route::get('inventory/get-purchase-reqisition-item', 'InventoryController@get_purchase_reqisition_item');
 
    Route::get('inventory/add-purchase-reqisition-item', 'InventoryController@add_purchase_reqisition_item');
    Route::post('inventory/add-purchase-reqisition-item', 'InventoryController@add_purchase_reqisition_item');

 
    Route::get('inventory/edit-purchase-reqisition-item', 'InventoryController@edit_purchase_reqisition_item');
    Route::post('inventory/edit-purchase-reqisition-item', 'InventoryController@edit_purchase_reqisition_item');

    Route::get('inventory/delete-purchase-reqisition-item', 'InventoryController@delete_purchase_reqisition_item');
    
    Route::get('inventory/itemcodesearch/{itemcode?}', 'InventoryController@itemcodesearch');

    Route::get('inventory/suppliersearch', 'InventoryController@suppliersearch');

    
    Route::get('inventory/purchase-reqisition/approval', 'ApprovalController@getList');
    Route::post('inventory/purchase-reqisition/approval', 'ApprovalController@Approve');

    Route::get('inventory/supplier-quotation', 'SupplierQuotationController@getSupplierQuotation');
    Route::post('inventory/supplierQuotationUpdate/{rq_no}/{supp_id}', 'SupplierQuotationController@supplierQuotationUpdate');
    Route::get('inventory/view-supplier-quotation-items/{rq_no}/{supp_id}','SupplierQuotationController@viewSupplierQuotationItems');
    Route::get('inventory/edit-supplier-quotation-item/{rq_no}/{supp_id}/{item_id}', 'SupplierQuotationController@getSupplierQuotationEditItem');
    Route::post('inventory/edit-supplier-quotation-item/{rq_no}/{supp_id}/{item_id}', 'SupplierQuotationController@getSupplierQuotationEditItem');
       
    Route::get('inventory/comparison-quotation/{rq_no}', 'SupplierQuotationController@comparisonOfQuotation');
    
   //Batchcard
    Route::get('batchcard/batchcard-upload', 'BatchCardController@getBatchcardUpload');
    Route::post('batchcard/batchcard-upload', 'BatchCardController@batchcardUpload');
});